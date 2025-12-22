<?php

declare(strict_types=1);

namespace Psk\LmsModule\Repositories\Questions;

use Ox3a\Common\Service\ShareServiceInterface;
use Ox3a\Service\DbService;
use Psk\LmsModule\Models\Questions\AnswerModel;
use Psk\LmsModule\Models\Questions\QuestionModel;
use Psk\LmsModule\Models\Requests\Questions\Types\AbstractQuestionTypeRequestModel;
use Psk\LmsModule\Repositories\Db\Questions\QuestionModel\QuestionConditions;
use Psk\LmsModule\Repositories\Db\Questions\QuestionModel\QuestionHydrator;
use Psk\LmsModule\Repositories\Db\Questions\QuestionModel\QuestionMapper;

/**
 * @internal
 */
final class QuestionRepository implements ShareServiceInterface
{
    /** @var QuestionMapper */
    private $questionMapper;

    /** @var AnswerRepository */
    private $answerRepository;

    /** @var DbService */
    private $dbService;

    public function __construct(
        QuestionMapper   $questionMapper,
        AnswerRepository $answerRepository,
        DbService        $dbService
    )
    {
        $this->questionMapper   = $questionMapper;
        $this->answerRepository = $answerRepository;
        $this->dbService        = $dbService;
    }

    /**
     * @param positive-int $id
     * @param positive-int|null $type
     * @return QuestionModel|null
     * @throws \ReflectionException
     */
    public function findById(int $id, int $type = null): ?QuestionModel
    {
        $condition = new QuestionConditions();

        $condition->getId()->equal($id);

        if ($type) {
            $condition->getType()->equal($type);
        }

        $list = $this->findBy($condition);

        return $list ? $list[0] : null;
    }

    /**
     * Найти вопросы не добавленные в тест
     * @param positive-int $courseId
     * @return QuestionModel[]
     */
    public function findNotInTestByCourseIdAndTestId(int $courseId, int $testId): array
    {
        $rows = $this->dbService->query(
            "SELECT tq.* FROM lms_tests_questions tq
            LEFT JOIN lms_tests_questions_of_tests tqt
                ON tq.id = tqt.question_id AND tqt.test_id = ?
            WHERE tq.course_id = ? AND tqt.question_id IS NULL", [$testId, $courseId]);

        $questions = [];
        foreach ($rows as $row) {
            $question = new QuestionModel();
            (new QuestionHydrator())->hydrate($question,
                [
                    'id'         => $row['id'],
                    'courseId'   => $row['course_id'],
                    'categoryId' => $row['category_id'],
                    'title'      => $row['title'],
                    'type'       => $row['type'],
                ]);

            $questions[] = $question;
        }

        return $questions;
    }

    /**
     * @param positive-int $courseId
     * @return QuestionModel[]
     * @throws \ReflectionException
     */
    public function findByCourseId(int $courseId): array
    {
        $condition = new QuestionConditions();

        $condition->getCourseId()->equal($courseId);

        return $this->findBy($condition);
    }

    /**
     * @param positive-int $categoryId
     * @return QuestionModel[]
     * @throws \ReflectionException
     */
    public function findByCategoryId(int $categoryId): array
    {
        $condition = new QuestionConditions();

        $condition->getCategoryId()->equal($categoryId);

        return $this->findBy($condition);
    }

    /**
     * @param QuestionConditions $conditions
     * @return QuestionModel[]
     * @throws \ReflectionException
     */
    private function findBy(QuestionConditions $conditions): array
    {
        $questions = $this->questionMapper->findBy($conditions);

        $hydrator = new QuestionHydrator();

        foreach ($questions as $question) {
            $answers = $this->answerRepository->findByQuestionId($question->getId());
            $hydrator->hydrateProperty($question, 'answers', $answers);
        }

        return $questions;
    }

    /**
     * @param QuestionModel $question
     * @param AbstractQuestionTypeRequestModel $request
     * @return void
     * @throws \ReflectionException
     */
    public function setAndSave(QuestionModel $question, AbstractQuestionTypeRequestModel $request): void
    {
        $question->setCourseId($request->courseId);
        $question->setCategoryId($request->categoryId);
        $question->setQuestion($request->question);

        if ($request->type) {
            $question->setType($request->type);
        }

        $this->questionMapper->save($question);
    }

    /**
     * @param positive-int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->dbService->query("DELETE FROM lms_tests_answers WHERE question_id = ?", [$id]);

        $this->questionMapper->delete($id);
    }

    /**
     * @param QuestionModel $question
     * @param int $newAnswerCount
     * @return AnswerModel[]
     */
    public function balanceAnswers(QuestionModel $question, int $newAnswerCount): array
    {
        if (!$answers = $question->getAnswers()) {
            $answers = [];
            for ($i = 0; $i < $newAnswerCount; $i++) {
                $answers[] = new AnswerModel();
            }
        } else {
            $oldAnswerCount = count($answers);
            $difference = $newAnswerCount - $oldAnswerCount;

            if ($difference > 0) {
                for ($i = 0; $i < $difference; $i++) {
                    $answers[] = new AnswerModel();
                }
            } elseif ($difference < 0) {
                $answers = array_slice($answers, 0, $newAnswerCount);
            }
        }

        return $answers;
    }
}
