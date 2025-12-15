<?php

namespace Psk\LmsModule\Services\REST\Admin\Tests;

use Ox3a\Service\DbService;
use Psk\LmsModule\Forms\Requests\Tests\TestFormModel;
use Psk\LmsModule\Models\Requests\Tests\TestRequestModel;
use Psk\LmsModule\Models\Tests\TestModel;
use Psk\LmsModule\Repositories\Tests\TestRepository;
use Psk\RestModule\RestServiceInterface;
use Psk\RestModule\Results\AbstractResult;
use Psk\RestModule\Results\NotFoundResult;
use Psk\RestModule\Results\SuccessResult;
use Psk\RestModule\Results\ValidationErrorsResult;

/**
 * @internal
 */
final class TestService implements RestServiceInterface
{
    /** @var TestFormModel|null */
    private $form;

    /** @var TestRepository  */
    private $testRepository;

    /** @var DbService */
    private $dbService;

    public function __construct(
        TestRepository $testRepository,
        DbService      $dbService
    )
    {
        $this->testRepository = $testRepository;
        $this->dbService      = $dbService;
    }

    /**
     * @param array<string,mixed> $params
     * @return NotFoundResult|SuccessResult
     */
    public function find($params): AbstractResult
    {
        if (isset($params['course-id'])) {
            $courseId = (int) $params['course-id'];

            return new SuccessResult($this->testRepository->findByCourseId($courseId) ?? []);
        }

        return new NotFoundResult();
    }

    /**
     * @param positive-int $id
     * @return NotFoundResult|SuccessResult
     */
    public function get($id): AbstractResult
    {
        if ($test = $this->testRepository->findById((int) $id)) {
            return new SuccessResult($test);
        }

        return new NotFoundResult();
    }

    /**
     * @param array<string,mixed> $data
     * @return SuccessResult|ValidationErrorsResult
     * @throws \ReflectionException
     */
    public function create($data): AbstractResult
    {
        $form = $this->getForm($data);

        if (!$form->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        $request = $form->getDataModel();

        $test = new TestModel();

        $this->updateByRequest($test, $request);

        return new SuccessResult($test);
    }

    /**
     * @param positive-int $id
     * @param array<string,mixed> $data
     * @return NotFoundResult|SuccessResult|ValidationErrorsResult
     * @throws \ReflectionException
     */
    public function update($id, $data): AbstractResult
    {
        if (!$test = $this->testRepository->findById((int) $id)) {
            return new NotFoundResult();
        }

        $form = $this->getForm($data);

        if (!$form->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        $this->updateByRequest($test, $form->getDataModel());

        return new SuccessResult($test);
    }

    /**
     * @param positive-int $id
     * @return NotFoundResult|SuccessResult
     */
    public function delete($id): AbstractResult
    {
        $id = (int) $id;

        if ($this->testRepository->findById($id)) {
            $this->testRepository->delete($id);

            return new SuccessResult(TestModel::DELETE_SUCCESS);
        }

        return new NotFoundResult();
    }

    /**
     * @param TestModel $test
     * @param TestRequestModel $request
     * @return void
     * @throws \ReflectionException
     */
    private function updateByRequest(
        TestModel          $test,
        TestRequestModel   $request
    ): void
    {
        $test
            ->setCourseId($request->courseId)
            ->setCategoryId($request->categoryId)
            ->setTitle($request->title)
            ->setDescription($request->description)
            ->setIsDisplayAllQuestions($request->isDisplayAllQuestions)
            ->setIsVisibleResult($request->isVisibleResult)
            ->setIsRandomQuestion($request->isRandomQuestion)
            ->setAttemptCount($request->attemptCount)
            ->setQuestionCount($request->questionCount)
            ->setTimeLimit($request->timeLimit)
            ->setStartAt($request->startAt)
            ->setEndAt($request->endAt);

        $this->testRepository->save($test);
    }

    /**
     * @param array<string,mixed> $data
     * @return TestFormModel
     */
    private function getForm(array $data): TestFormModel
    {
        $categories = $this->dbService->fetchOne("SELECT id FROM lms_tests_categories WHERE course_id =?", [$data['courseId']]);

        $this->form ?: ($this->form = new TestFormModel(null, [
            'db' => $this->dbService,
            'categories' => $categories,
        ]));

        return $this->form->setData($data);
    }
}
