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
        $form = $this->getForm();

        if (!$form->setData($data)->isValid()) {
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
     */
    public function update($id, $data): AbstractResult
    {
        $id = (int) $id;

        if (!$category = $this->categoryRepository->findById($id)) {
            return new NotFoundResult();
        }

        $form = $this->getForm();

        if (!$form->setData($data)->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        $category->setTitle($form->getDataModel()->title);

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
     * @return QuestionCategoryFormModel
     */
    private function getForm(): QuestionCategoryFormModel
    {
        return $this->form ?: ($this->form = new QuestionCategoryFormModel());
    }
}
