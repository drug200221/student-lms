<?php

declare(strict_types = 1);

namespace Psk\LmsModule\Services\REST\Admin\Tests;

use Ox3a\Service\DbService;
use Psk\LmsModule\Forms\Requests\Tests\QuestionOfTestFormModel;
use Psk\LmsModule\Helpers\ConflictResult;
use Psk\LmsModule\Models\Questions\QuestionModel;
use Psk\LmsModule\Models\Tests\QuestionOfTestModel;
use Psk\LmsModule\Repositories\Questions\QuestionRepository;
use Psk\LmsModule\Repositories\Tests\QuestionOfTestRepository;
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
final class QuestionOfTestService implements RestServiceInterface
{
    /** @var QuestionOfTestFormModel|null */
    private $form;

    /** @var QuestionRepository */
    private $questionRepository;

    /** @var QuestionOfTestRepository */
    private $questionOfTestRepository;

    /** @var DbService */
    private $dbService;

    public function __construct
    (
        QuestionRepository       $questionRepository,
        QuestionOfTestRepository $questionOfTestRepository,
        DbService                $dbService
    )
    {
        $this->questionRepository       = $questionRepository;
        $this->questionOfTestRepository = $questionOfTestRepository;
        $this->dbService                = $dbService;
    }

    /**
     * @param $params
     * @return mixed
     * @throws \ReflectionException
     */
    public function find($params): AbstractResult
    {
        if (isset($params['test-id'])) {
            $questions = $this->questionOfTestRepository->findByTestId((int)$params['test-id']);

            if ($questions) {
                return new SuccessResult($questions);
            }
        }

        return new NotFoundResult();
    }

    /**
     * @param positive-int $id
     * @return AbstractResult
     * @throws \ReflectionException
     */
    public function get($id): AbstractResult
    {
        $id = (int)$id;

        if ($question = $this->questionOfTestRepository->findById($id)) {
            return new SuccessResult($question);
        }

        return new NotFoundResult();
    }

    /**
     * @param $data
     * @return SuccessResult|ValidationErrorsResult
     * @throws \ReflectionException
     */
    public function create($data): AbstractResult
    {
        $form = $this->getForm($data);

        if (!$form->setData($data)->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        $request = $form->getDataModel();

        $questionOfTest = (new QuestionOfTestModel())
            ->setTestId($request->testId)
            ->setQuestionId($request->questionId)
            ->setPoint($request->point)
            ->setIsRequired($request->isRequired);

        $this->questionOfTestRepository->save($questionOfTest);

        return new SuccessResult($questionOfTest);
    }

    /**
     * @param $id
     * @param $data
     * @return InternalServerErrorResult
     */
    public function update($id, $data): AbstractResult
    {
        return new InternalServerErrorResult("Not implemented yet.");
    }

    /**
     * @param positive-int $id
     * @return NotFoundResult|SuccessResult|ValidationErrorsResult
     * @throws \ReflectionException
     */
    public function delete($id): AbstractResult
    {
        if ($questionOfTest = $this->questionOfTestRepository->findById((int) $id)) {
            if ($this->dbService->query('SELECT id FROM lms_tests_results WHERE question_id = ? LIMIT 1', $questionOfTest->getId())) {
                return new ConflictResult(QuestionOfTestModel::EXIST_IN_ATTEMPT);
            }

            $this->questionOfTestRepository->delete($questionOfTest);

            return new SuccessResult(QuestionOfTestModel::DELETE_SUCCESS);
        }

        return new NotFoundResult();
    }

    /**
     * @param array<string,mixed> $data
     * @return QuestionOfTestFormModel
     */
    public function getForm(array $data): QuestionOfTestFormModel
    {
        return $this->form ?: ($this->form = new QuestionOfTestFormModel(null, $this->getFormOptions($data)));
    }

    /**
     * @param array<string,mixed> $data
     * @return array<string,QuestionModel[]>
     */
    private function getFormOptions(array $data): array
    {
        $courseId = (int) $data['courseId'];
        $testId   = (int) $data['testId'];

        $questions = ['' => '-Выберите вопрос-'];
        foreach ($this->questionRepository->findNotInTestByCourseIdAndTestId($courseId, $testId) as $question) {
            $questions[$question->getId()] = $question->getQuestion();
        }

        return [
            'db'                => $this->dbService,
            'questions'         => $questions,
            'questionOfTestIds' => $this->getSelectForTestOfQuestion($data),
            'testId'            => $this->getSelectForTestInCourseCheck($data),
            'questionId'        => $this->getTestQuestionsSelect($data),
        ];
    }

    /**
     * Получить Select для проверки вопросов теста
     * @param array<string,mixed> $data
     * @return Select
     */
    private function getSelectForTestOfQuestion(array $data): Select
    {
        $sQuestionOfTest = new Select('lms_tests_questions_of_tests');
        $sQuestionOfTest->where
            ->equalTo('question_id', $data['questionId'])
            ->equalTo('test_id', $data['testId']);

        return $sQuestionOfTest;
    }

    /**
     * Получить Select для проверки наличия теста в курсе
     * @param array<string,mixed> $data
     * @return Select
     */
    private function getSelectForTestInCourseCheck(array $data): Select
    {
        $select = new Select(['t' => 'lms_tests']);
        $select
            ->join(
                ['c' => 'lms_courses'],
                't.course_id = c.id',
                []
            )
            ->where
            ->equalTo('t.id', $data['testId'])
            ->equalTo('t.course_id', $data['courseId']);

        return $select;
    }

    /**
     * Получить Select для проверки наличия вопросов курса в тесте
     * @param array<string,mixed> $data
     * @return Select
     */
    private function getTestQuestionsSelect(array $data): Select
    {
        $select = $this->getSelectForTestInCourseCheck($data);

        $select->join(
            ['q' => 'lms_tests_questions'],
            't.course_id = q.course_id',
            ['id']
        );

        $select->where
            ->equalTo('q.id', $data['questionId']);

        return $select;
    }
}
