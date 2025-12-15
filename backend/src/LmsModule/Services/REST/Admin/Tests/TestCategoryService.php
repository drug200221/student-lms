<?php

declare(strict_types=1);

namespace Psk\LmsModule\Services\REST\Admin\Tests;

use Ox3a\Service\DbService;
use Psk\LmsModule\Forms\Requests\Tests\TestCategoryFormModel;
use Psk\LmsModule\Models\Tests\TestCategoryModel;
use Psk\LmsModule\Repositories\Tests\TestCategoryRepository;
use Psk\RestModule\RestServiceInterface;
use Psk\RestModule\Results\AbstractResult;
use Psk\RestModule\Results\InternalServerErrorResult;
use Psk\RestModule\Results\NotFoundResult;
use Psk\RestModule\Results\SuccessResult;
use Psk\RestModule\Results\ValidationErrorsResult;

/**
 * @internal
 */
final class TestCategoryService implements RestServiceInterface
{
    /** @var TestCategoryFormModel|null */
    private $form;

    /** @var TestCategoryRepository */
    private $categoryRepository;

    /** @var DbService */
    private $dbService;

    public function __construct(
        TestCategoryRepository $categoryRepository,
        DbService              $dbService
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
            $courseId = (int)$params['course-id'];

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
        $testCategory = $this->categoryRepository->findById((int) $id);

        return $testCategory ? new SuccessResult($testCategory) : new NotFoundResult();
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

        $category = new TestCategoryModel();

        $category
            ->setCourseId($request->courseId)
            ->setTitle($request->title);

        $this->categoryRepository->save($category);

        return new SuccessResult($category);
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

        $form = $this->getForm($data);

        if (!$form->isValid()) {
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

        $this->dbService->query("UPDATE lms_tests SET category_id = NULL WHERE category_id = ?", [$id]);

        $this->categoryRepository->delete($category->getId());

        return new SuccessResult(TestCategoryModel::DELETE_SUCCESS);
    }

    /**
     * @param array<string,mixed> $data
     * @return TestCategoryFormModel
     */
    private function getForm(array $data): TestCategoryFormModel
    {
        $this->form ?: ($this->form = new TestCategoryFormModel(null, ['db' => $this->dbService]));

        return $this->form->setData($data);
    }
}
