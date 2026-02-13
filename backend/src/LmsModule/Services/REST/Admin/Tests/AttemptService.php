<?php

namespace Psk\LmsModule\Services\REST\Admin\Tests;

use Ox3a\Common\Service\AuthService;
use Ox3a\Form\Factory\ElementFactory;
use Ox3a\Form\Model\CollectionModel;
use Ox3a\Service\DbService;
use Psk\LmsModule\Forms\Dynamic\Fields\TextField;
use Psk\LmsModule\Forms\Dynamic\TestForm;
use Psk\LmsModule\Forms\Requests\Tests\AddAttemptFormModel;
use Psk\LmsModule\Helpers\ConflictResult;
use Psk\LmsModule\Models\Questions\AnswerModel;
use Psk\LmsModule\Models\Questions\QuestionModel;
use Psk\LmsModule\Models\Tests\AttemptModel;
use Psk\LmsModule\Models\Tests\ResultModel;
use Psk\LmsModule\Repositories\Questions\AnswerRepository;
use Psk\LmsModule\Repositories\Tests\AttemptRepository;
use Psk\LmsModule\Repositories\Tests\QuestionOfTestRepository;
use Psk\LmsModule\Repositories\Tests\ResultRepository;
use Psk\LmsModule\Repositories\Tests\TestRepository;
use Psk\RestModule\RestServiceInterface;
use Psk\RestModule\Results\AbstractResult;
use Psk\RestModule\Results\NotFoundResult;
use Psk\RestModule\Results\SuccessResult;
use Psk\RestModule\Results\ValidationErrorsResult;
use Zend\Db\Sql\Select;
use Zend\Filter\Boolean;
use Zend\Validator\IsCountable;

/**
 * @internal
 */
final class AttemptService implements RestServiceInterface
{
    /** @var AddAttemptFormModel|null */
    private $addForm;

    /** @var TestRepository  */
    private $testRepository;

    /** @var AnswerRepository */
    private $answerRepository;

    /** @var DbService */
    private $dbService;

    public function __construct(
        AuthService              $authService,
        TestRepository           $testRepository,
        AnswerRepository         $answerRepository,
        ResultRepository         $resultRepository,
        AttemptRepository        $attemptRepository,
        QuestionOfTestRepository $questionOfTestRepository,
        DbService                $dbService
    )
    {
        $this->authService              = $authService;
        $this->testRepository           = $testRepository;
        $this->answerRepository         = $answerRepository;
        $this->resultRepository         = $resultRepository;
        $this->attemptRepository        = $attemptRepository;
        $this->questionOfTestRepository = $questionOfTestRepository;
        $this->dbService                = $dbService;
    }

    /**
     * @param array<string,mixed> $params
     * @return NotFoundResult|SuccessResult
     * @throws \ReflectionException
     */
    public function find($params): AbstractResult
    {
        if (isset($params['test-id'])) {
            $attempts = $this->attemptRepository->findByTestId((int) $params['test-id']);

            return new SuccessResult($attempts ?: []);
        }

        return new NotFoundResult();
    }

    /**
     * @param positive-int $id
     * @return NotFoundResult|SuccessResult
     * @throws \ReflectionException
     */
    public function get($id): AbstractResult
    {
        if ($attempt = $this->attemptRepository->findById((int) $id)) {
            return new SuccessResult($attempt);
        }

        return new NotFoundResult();
    }

    /**
     * @param array<string,mixed> $data
     * @return AbstractResult
     * @throws \ReflectionException
     */
    public function create($data): AbstractResult
    {
        $form = $this->getAddForm($data);

        if (!$form->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        $userId = (int) $this->authService->getIdentity();
        $testId = $form->getDataModel()->testId;

        $test            = $this->testRepository->findById($testId);
        $questionsOfTest = $this->questionOfTestRepository->findByTestId($testId);
        $attempts        = $this->attemptRepository->findByTestId($testId);

        if (!count($questionsOfTest)) {
            return new ConflictResult(AttemptModel::NO_QUESTIONS_ADDED);
        }

        $attemptCount = $test->getAttemptCount();
        if ($attemptCount && count($attempts) >= $attemptCount) {
            return new ConflictResult(AttemptModel::ATTEMPTS_EXHAUSTED);
        }

        $attempt = $this->attemptRepository->create($userId, $testId);

        if ($test->isRandomQuestion()) {
            shuffle($questionsOfTest);
        }

        $questionCount = min($test->getQuestionCount(), count($questionsOfTest));
        for ($i = 0; $i < $questionCount; $i++) {
            $result = new ResultModel();
            $result->setAttemptId($attempt->getId());
            $result->setQuestionId($questionsOfTest[$i]->getQuestionId());

            $this->resultRepository->save($result);
        }

        return new SuccessResult($attempt);
    }

    /**
     * @param positive-int $id
     * @param array<string,mixed> $data
     * @return NotFoundResult|SuccessResult|ValidationErrorsResult
     * @throws \ReflectionException
     */
    public function update($id, $data): AbstractResult
    {
        if (!$attempt = $this->attemptRepository->findById((int) $id)) {
            return new NotFoundResult();
        }

        $form = $this->getUpdateForm($data, $attempt);

        if (!$form->setData($data)->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        return new SuccessResult($attempt);
    }

    /**
     * @param positive-int $id
     * @return NotFoundResult|SuccessResult
     * @throws \ReflectionException
     */
    public function delete($id): AbstractResult
    {
        if ($attempt = $this->attemptRepository->findById($id)) {
            $this->attemptRepository->delete($attempt->getId());

            return new SuccessResult(AttemptModel::DELETE_SUCCESS);
        }

        return new NotFoundResult();
    }

    /**
     * @param array<string,mixed> $data
     * @return AddAttemptFormModel
     */
    private function getAddForm(array $data): AddAttemptFormModel
    {
        $selectForTestInCourseCheck = new Select(['t' => 'lms_tests']);
        $selectForTestInCourseCheck
            ->join(
                ['c' => 'lms_courses'],
                't.course_id = c.id',
                []
            )
            ->where
            ->equalTo('t.id', $data['testId'])
            ->equalTo('t.course_id', $data['courseId']);

        $options = [
            'db' => $this->dbService,
            'testId' => $selectForTestInCourseCheck,
        ];

        if (!$this->addForm) {
            $this->addForm = new AddAttemptFormModel(null, $options);
        }

        return $this->addForm->setData($data);
    }

    /**
     * @param array<string,mixed> $data
     * @param AttemptModel $attempt
     * @return TestForm
     */
    private function getUpdateForm(array $data, AttemptModel $attempt): TestForm
    {
        $test = $attempt->getTest();
        $results = $attempt->getResults();

        $questionCount = $test->getQuestionCount();
        $countOfPage = $test->isDisplayAllQuestions() ? $questionCount : 1;

        $form = new TestForm();

        $form->setValidators([
            new IsCountable([
                'count' => $countOfPage,
            ])
        ]);

        for ($i = 0; $i < $countOfPage; $i++) {
            $answers = $this->answerRepository->findByQuestionId($results[$i]->getQuestionId());

            switch ($results[$i]->getQuestionType()) {
                case QuestionModel::TRUE_OR_FALSE:
                    $form>add([
                        'name' =>  $results[$i]->getQuestion(),
                        'options' => [
                            'label' => 'Утверждение верно',
                            'options' => [
                                1 => "Да",
                                0 => "Нет",
                            ],
                            'required' => $results[$i]->isRequired(),
                            'continueIfEmpty' => true,
                            'escapeAttr' => true,
                        ],
                        'filters' => [
                            new \Zend\Filter\Boolean(Boolean::TYPE_PHP ^ Boolean::TYPE_NULL, false),
                        ],
                        'type' => \Ox3a\Form\Model\RadioGroupModel::class,
                    ]);
                    break;
                case QuestionModel::ORDERING:
                case QuestionModel::SHORT_RESPONSE:
                    $form->add(TextField::get('text', 'Ответ', $results[$i]->isRequired()));
                    break;
                case QuestionModel::MULTIPLE_CHOICE:
                    $form->add([
                        'name' => "Выбор одного ответа",
                        'options' => [
                            'label' =>  $results[$i]->getQuestion(),
                            'options' => array_column($answers, 'answer', 'id'),
                            'escapeAttr' => true,
                        ],
                        'type' => \Ox3a\Form\Model\RadioGroupModel::class,
                    ]);
                    break;
                case QuestionModel::MULTIPLE_RESPONSE:
                    $form->add([
                        'name' => "Выбор нескольких ответов",
                        'options' => [
                            'label' =>  $results[$i]->getQuestion(),
                            'options' => array_column($answers, 'answer', 'id'),
                            'escapeAttr' => true,
                        ],
                        'type' => \Ox3a\Form\Model\MultiCheckboxModel::class,
                    ]);
                    break;
                case QuestionModel::ACCORDANCE:
                    $collection = ElementFactory::factory([
                        'type' => CollectionModel::class,
                        'name' => 'accordance' . $i,
                        'label' => $results[$i]->getQuestion(),
                    ]);

                    $leftSide = [];
                    $rightSide = [];

                    foreach ($answers as $answer) {
                        [$left, $right] = explode(AnswerModel::SEPARATOR, $answer->getAnswer(), 2) + ['empty', 'empty'];

                        if ($left !== null) {
                            $leftSide[] = trim($left);
                        }
                        if ($right !== null) {
                            $rightSide[] = trim($right);
                        }
                    }

                    shuffle($leftSide);
                    shuffle($rightSide);

                    $selectOptions = array_combine($rightSide, $rightSide);

                    foreach ($leftSide as $index => $label) {
                        $collection->add([
                            'name' => $index,
                            'options' => [
                                'label' => $label,
                                'options' => $selectOptions,
                                'escapeAttr' => true,
                            ],
                            'type' => \Ox3a\Form\Model\SelectModel::class,
                        ]);
                    }
                    break;
            }
        }

        return $form->setData($data);
    }
}
