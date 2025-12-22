<?php

declare(strict_types=1);

namespace Psk\LmsModule\Services\REST\Admin\Questions;

use Ox3a\Service\DbService;
use Psk\LmsModule\Forms\Requests\Questions\QuestionCategoryFormModel;
use Psk\LmsModule\Models\Questions\QuestionCategoryModel;
use Psk\LmsModule\Repositories\Questions\QuestionCategoryRepository;
use Psk\RestModule\RestServiceInterface;
use Psk\RestModule\Results\AbstractResult;
use Psk\RestModule\Results\InternalServerErrorResult;
use Psk\RestModule\Results\NotFoundResult;
use Psk\RestModule\Results\SuccessResult;
use Psk\RestModule\Results\ValidationErrorsResult;
use Zend\Db\Sql\Select;

/**
 * @internal
 */
final class QuestionCategoryService implements RestServiceInterface
{
    /** @var QuestionCategoryFormModel|null */
    private $form;

    /** @var QuestionCategoryRepository */
    private $categoryRepository;

    /** @var DbService */
    private $dbService;

    public function __construct(
        QuestionCategoryRepository $categoryRepository,
        DbService                  $dbService
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->dbService          = $dbService;
    }

    /**
     * @param array<string,mixed> $params
     * @return InternalServerErrorResult
     */
    public function find($params): AbstractResult
    {
        if (isset($params['course-id'])) {
            $courseId = (int) $params['course-id'];

            return new SuccessResult($this->categoryRepository->findByCourseId($courseId) ?? []);
        }

        return new NotFoundResult();
    }

    /**
     * @param positive-int $id
     * @return InternalServerErrorResult
     */
    public function get($id): AbstractResult
    {
        return new InternalServerErrorResult("Not implemented yet.");
    }

    /**
     * @param array<string,mixed> $data
     * @return AbstractResult
     * @throws \ReflectionException
     */
    public function create($data): AbstractResult
    {
        $form = $this->getForm($data);

        if (!$form->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        $request = $form->getDataModel();

        $questionCategory = new QuestionCategoryModel();

        $questionCategory
            ->setCourseId($request->courseId)
            ->setTitle($request->title);

        $this->categoryRepository->save($questionCategory);

        return new SuccessResult($questionCategory);
    }

    /**
     * @param positive-int $id
     * @param array<string,mixed> $data
     * @return AbstractResult
     * @throws \ReflectionException
     */
    public function update($id, $data): AbstractResult
    {
        $id = (int) $id;

        if (!$category = $this->categoryRepository->findById($id)) {
            return new NotFoundResult();
        }

        $form = $this->getForm($data, $id);

        if (!$form->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        $category->setTitle($form->getDataModel()->title);

        $this->categoryRepository->save($category);

        return new SuccessResult($category);
    }

    /**
     * @param positive-int $id
     * @return AbstractResult
     */
    public function delete($id): AbstractResult
    {
        $id = (int)$id;

        if (!$category = $this->categoryRepository->findById($id)) {
            return new NotFoundResult();
        }

        $this->dbService->query("UPDATE lms_tests_questions SET category_id = NULL WHERE category_id = ?", [$id]);

        $this->categoryRepository->delete($category->getId());

        return new SuccessResult(QuestionCategoryModel::DELETE_SUCCESS);
    }

    /**
     *
     * @param array<string,mixed> $data
     * @param positive-int $id
     * @return QuestionCategoryFormModel
     */
    private function getForm(array $data, int $id = 0): QuestionCategoryFormModel
    {
        $select = new Select();

        $select->columns(['course_id'])
            ->from('lms_tests_questions_categories')
            ->where
            ->equalTo('course_id', $data['courseId'])
            ->equalTo('title', $data['title']);

        if ($id) {
            $select->where->equalTo('id', $id);
        }

        $this->form ?: ($this->form = new QuestionCategoryFormModel(null, [
            'db' => $this->dbService,
            'courseId' => $select,
        ]));

        return $this->form->setData($data);
    }
}
