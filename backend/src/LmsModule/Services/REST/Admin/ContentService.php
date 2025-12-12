<?php

declare(strict_types=1);

namespace Psk\LmsModule\Services\REST\Admin;

use Ox3a\Service\DbService;
use Psk\LmsModule\Forms\Requests\ContentFormModel;
use Psk\LmsModule\Helpers\ConflictResult;
use Psk\LmsModule\Models\ContentModel;
use Psk\LmsModule\Repositories\ContentRepository;
use Psk\RestModule\RestServiceInterface;
use Psk\RestModule\Results\AbstractResult;
use Psk\RestModule\Results\NotFoundResult;
use Psk\RestModule\Results\SuccessResult;
use Psk\RestModule\Results\ValidationErrorsResult;
use Zend\Db\Sql\Select;

/**
 * @internal
 */
final class ContentService implements RestServiceInterface
{
    /** @var ContentFormModel|null */
    private $contentForm;

    /** @var ContentRepository */
    private $contentRepository;

    /** @var DbService */
    private $dbService;

    public function __construct(
        ContentRepository $contentRepository,
        DbService         $dbService
    ) {
        $this->contentRepository = $contentRepository;
        $this->dbService         = $dbService;
    }

    /**
     * @param array<string,mixed> $params
     * @return SuccessResult|void
     */
    public function find($params): AbstractResult
    {
        if (isset($params['course-id'])) {
            $courseId = $this->dbService->fetchOne('SELECT id FROM lms_courses WHERE id = ?', $params['course-id']);

            if ($courseId) {
                return new SuccessResult($this->contentRepository->findByCourseId((int)$courseId));
            }
        }

        return new NotFoundResult();
    }

    /**
     * @param positive-int $id
     * @return NotFoundResult|SuccessResult
     */
    public function get($id): AbstractResult
    {
        $id = (int)$id;
        $content = $this->contentRepository->findById($id);

        return $content ? new SuccessResult($content) : new NotFoundResult();
    }

    /**
     * @param array<string,mixed> $data
     * @return AbstractResult
     * @throws \ReflectionException
     */
    public function create($data): AbstractResult
    {
        $form = $this->getForm($data);

        if (!$form->setData($data)->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        $request = $form->getDataModel();

        $content = new ContentModel();

        $content
            ->setTitle($request->title)
            ->setContent($request->content)
            ->setCourseId($request->courseId)
            ->setParentId($request->parentId)
            ->setCreatedAt(new \DateTimeImmutable());

        $this->contentRepository->save($content);

        $this->updateTreeBorders($request->courseId);

        return new SuccessResult($content);
    }

    /**
     * @param positive-int $id
     * @param array<string,mixed> $data
     * @return NotFoundResult|SuccessResult|ValidationErrorsResult
     */
    public function update($id, $data): AbstractResult
    {
        $id = (int)$id;

        if (!$content = $this->contentRepository->findById($id)) {
            return new NotFoundResult();
        }

        $form = $this->getForm($data);

        if (!$form->setData($data)->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        $request = $form->getDataModel();

        $content
            ->setTitle($request->title)
            ->setContent($request->content)
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setRevision($content->getRevision() + 1);

        if ($request->parentId) {
            $content->setParentId($request->parentId);
        }

        return new SuccessResult($content);
    }

    /**
     * @param positive-int $id
     * @return AbstractResult
     */
    public function delete($id): AbstractResult
    {
        $id = (int)$id;

        if (!$content = $this->contentRepository->findById($id)) {
            return new NotFoundResult();
        }

        if ($this->contentRepository->issetChildren($content)) {
            return new ConflictResult(ContentModel::CHILDREN_EXISTS_ERROR);
        }

        $this->contentRepository->delete($content);

        return new SuccessResult(ContentModel::DELETE_SUCCESS);
    }

    /**
     * @throws \ReflectionException
     */
    private function updateTreeBorders(int $courseId): void
    {
        $contents = $this->contentRepository->findByCourseId($courseId);
        $count = 1;

        $this->rebuildNestedSet($contents, 0, 1, $count);
    }

    /**
     * @param ContentModel[] $contents
     * @param non-negative-int $parentId
     * @param positive-int $level
     * @param positive-int $counter
     * @return void
     * @throws \ReflectionException
     */
    private function rebuildNestedSet(array &$contents, int $parentId, int $level, int &$counter): void
    {
        foreach ($contents as &$content) {
            if ($content->getParentId() === $parentId) {
                $content
                    ->setTreeLeft($counter++)
                    ->setTreeLevel($level);

                $this->rebuildNestedSet($contents, $content->getId(), $level + 1, $counter);

                $content->setTreeRight($counter++);

                $this->contentRepository->save($content);
            }
        }
    }

    /**
     * @param array<string, mixed> $data
     * @return ContentFormModel
     */
    private function getForm(array $data): ContentFormModel
    {
        $select1 = (new Select())
            ->from('lms_contents')
            ->columns(['parentId' => 'id'])
            ->where([
                'course_id' => $data['courseId'],
                'id' => $data['parentId']
            ])
            ->where(new \Zend\Db\Sql\Predicate\Expression(
                'EXISTS (SELECT 1 FROM lms_courses WHERE id = ?)',
                [$data['courseId']]
            ));

        $select2 = (new Select())
            ->columns(['parentId' => new \Zend\Db\Sql\Expression('0')])
            ->where(new \Zend\Db\Sql\Predicate\Expression(
                'EXISTS (SELECT 1 FROM lms_courses WHERE id = ?) AND ? = 0',
                [$data['courseId'], $data['parentId']]
            ));

        $unionSelect = $select1->combine($select2, Select::COMBINE_UNION, Select::QUANTIFIER_ALL);

        return $this->contentForm ?: ($this->contentForm = new ContentFormModel(null, [
            'db' => $this->dbService,
            'selectExists' => $unionSelect,
        ]));
    }
}
