<?php

declare(strict_types=1);

namespace Psk\LmsModule\Repositories\Tests;

use Ox3a\Common\Service\ShareServiceInterface;
use Psk\LmsModule\Models\Tests\QuestionOfTestModel;
use Psk\LmsModule\Repositories\Db\Tests\QuestionOfTestModel\QuestionOfTestConditions;
use Psk\LmsModule\Repositories\Db\Tests\QuestionOfTestModel\QuestionOfTestHydrator;
use Psk\LmsModule\Repositories\Db\Tests\QuestionOfTestModel\QuestionOfTestMapper;
use Psk\LmsModule\Repositories\Questions\AnswerRepository;

final class QuestionOfTestRepository implements ShareServiceInterface
{
    /** @var QuestionOfTestMapper */
    private $questionOfTestMapper;

    /** @var AnswerRepository */
    private $answerRepository;
    public function __construct(
        QuestionOfTestMapper $questionOfTestMapper,
        AnswerRepository     $answerRepository
    )
    {
        $this->questionOfTestMapper = $questionOfTestMapper;
        $this->answerRepository     = $answerRepository;
    }

    /**
     * Получить все вопросы теста
     * @param positive-int $testId
     * @return QuestionOfTestModel[]
     * @throws \ReflectionException
     */
    public function findByTestId(int $testId): array
    {
        $conditions = new QuestionOfTestConditions();
        $conditions->getTestId()->equal($testId);
        $conditions->orderByQuestion();

        return $this->findBy($conditions);
    }

    /**
     * @param positive-int $questionId
     * @return QuestionOfTestModel[]
     * @throws \ReflectionException
     */
    public function findByQuestionId(int $questionId): array
    {
        $conditions = new QuestionOfTestConditions();
        $conditions->getQuestionId()->equal($questionId);

        return $this->findBy($conditions);
    }

    /**
     * @param positive-int $testId
     * @param positive-int $questionId
     * @return QuestionOfTestModel[]
     * @throws \ReflectionException
     */
    public function findByTestIdAndQuestionId(int $testId, int $questionId): array
    {
        $conditions = new QuestionOfTestConditions();
        $conditions->getTestId()->equal($testId);
        $conditions->getQuestionId()->equal($questionId);

        return $this->findBy($conditions);
    }

    /**
     * @param positive-int $id
     * @return QuestionOfTestModel|null
     * @throws \ReflectionException
     */
    public function findById(int $id): ?QuestionOfTestModel
    {
        $conditions = new QuestionOfTestConditions();
        $conditions->getId()->equal($id);

        $list = $this->findBy($conditions);

        return $list ? $list[0] : null;
    }

    /**
     * @param QuestionOfTestModel $questionOfTest
     * @return void
     * @throws \ReflectionException
     */
    public function save(QuestionOfTestModel $questionOfTest): void
    {
        $this->questionOfTestMapper->save($questionOfTest);
    }

    /**
     * @param QuestionOfTestModel $questionOfTest
     * @return void
     */
    public function delete(QuestionOfTestModel $questionOfTest): void
    {
        $this->questionOfTestMapper->delete($questionOfTest->getId());
    }

    /**
     * @param QuestionOfTestConditions $conditions
     * @return QuestionOfTestModel[]
     * @throws \ReflectionException
     */
    private function findBy(QuestionOfTestConditions $conditions): array
    {
        foreach($questionsOfTest = $this->questionOfTestMapper->findBy($conditions) as $questionOfTest) {
            $this->fillAnswers($questionOfTest);
        }

        return $questionsOfTest;
    }

    /**
     * @throws \ReflectionException
     */
    private function fillAnswers(QuestionOfTestModel $questionOfTest): void
    {
        $answers = $this->answerRepository->findByQuestionId($questionOfTest->getId());

        (new QuestionOfTestHydrator())->hydrateProperty($questionOfTest, 'answers', $answers);
    }
}
