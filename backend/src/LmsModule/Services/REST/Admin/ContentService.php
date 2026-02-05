<?php

declare(strict_types=1);

namespace Psk\LmsModule\Services\REST\Admin;

use Ox3a\Form\Validator\DigitsValidator;
use Ox3a\Service\DbService;
use Psk\LmsModule\Forms\Requests\ContentFormModel;
use Psk\LmsModule\Helpers\ConflictResult;
use Psk\LmsModule\Models\ContentDetailModel;
use Psk\LmsModule\Repositories\ContentDetailRepository;
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

    /** @var ContentDetailRepository */
    private $contentRepository;

    /** @var DbService */
    private $dbService;

    public function __construct(
        ContentDetailRepository $contentRepository,
        DbService               $dbService
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

        $content = new ContentDetailModel();

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
     * @throws \ReflectionException
     */
    public function update($id, $data): AbstractResult
    {
        $id = (int)$id;

        if (!$content = $this->contentRepository->findById($id)) {
            return new NotFoundResult();
        }

        if (!isset($data['parentId'])) {
            $data['parentId'] = $content->getParentId();
        }

        $form = $this->getForm($data);

        if ($content->getParentId() === $data['parentId'])
        {
            $form->getElement('parentId')->setAttribute('required', false);
            $form->getElement('parentId')->setValidators([new DigitsValidator()]);
        }

        if (!$form->setData($data)->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        $request = $form->getDataModel();

        $content
            ->setTitle($request->title)
            ->setContent($request->content)
            ->setParentId($request->parentId)
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setRevision($content->getRevision() + 1);

        $this->contentRepository->save($content);

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
            return new ConflictResult(ContentDetailModel::CHILDREN_EXISTS_ERROR);
        }

        $this->contentRepository->delete($content);

        return new SuccessResult(ContentDetailModel::DELETE_SUCCESS);
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
     * @param ContentDetailModel[] $contents
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
        // Для истории почему такой запрос: Zend\Db\Validator проверяет по первому переданному параметру в условие
        // !!! Важно в первом условии значение первого параметра всегда должно быть значением поля формы к которому привязан валидатор

        $select = (new Select())
            ->from(['lc' => 'lms_contents'])
            ->columns(['parentId' => new \Zend\Db\Sql\Expression(
                $data['parentId'] == 0 ? '0' : 'lc.id'
            )])
            ->where([
                new \Zend\Db\Sql\Predicate\PredicateSet([
                    new \Zend\Db\Sql\Predicate\Operator('lc.id', '=', $data['parentId']),
                    new \Zend\Db\Sql\Predicate\Expression('? = 0', $data['parentId'])
                ], \Zend\Db\Sql\Predicate\PredicateSet::OP_OR),

                'lc.course_id' => $data['courseId'],

                new \Zend\Db\Sql\Predicate\Expression(
                    'EXISTS (SELECT 1 FROM lms_courses WHERE id = ?)',
                    [$data['courseId']]
                )
            ])
            ->limit(1);
        return $this->contentForm ?: ($this->contentForm = new ContentFormModel(null, [
            'db' => $this->dbService,
            'selectExists' => $select,
        ]));
    }
}
