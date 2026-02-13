<?php

declare(strict_types=1);

namespace Psk\LmsModule\Repositories\Tests;

use Ox3a\Common\Service\ShareServiceInterface;
use Psk\LmsModule\Models\Questions\AnswerModel;
use Psk\LmsModule\Models\Questions\QuestionModel;
use Psk\LmsModule\Models\Tests\AttemptModel;
use Psk\LmsModule\Models\Tests\ResultModel;
use Psk\LmsModule\Repositories\Db\Tests\ResultModel\ResultConditions;
use Psk\LmsModule\Repositories\Db\Tests\ResultModel\ResultMapper;

/**
 * @internal
 */
final class ResultRepository implements ShareServiceInterface
{
    /** @var ResultMapper */
    private $resultMapper;

    public function __construct(ResultMapper $resultMapper)
    {
        $this->resultMapper = $resultMapper;
    }

    /**
     * @param positive-int $id
     * @return ResultModel|null
     */
    public function findById(int $id): ?ResultModel
    {
        $conditions = new ResultConditions();
        $conditions->getId()->equal($id);

        $list = $this->resultMapper->findBy($conditions);

        return $list ? $list[0] : null;
    }

    /**
     * @param positive-int[] $attemptIds
     * @return array
     */
    public function findByAttemptIds(array $attemptIds): array
    {
        if (empty($attemptIds)) {
            return [];
        }

        $conditions = new ResultConditions();
        $conditions->getId()->in($attemptIds);
        return $this->resultMapper->findBy($conditions);
    }

    /**
     * @param positive-int $attemptId
     * @return ResultModel[]|null
     */
    public function findByAttemptId(int $attemptId): array
    {
        $conditions = new ResultConditions();
        $conditions->getAttemptId()->equal($attemptId);

        return $this->resultMapper->findBy($conditions);
    }

    /**
     * @param positive-int $questionId
     * @return ResultModel[]|null
     */
    public function findByQuestionId(int $questionId): array
    {
        $conditions = new ResultConditions();
        $conditions->getQuestionId()->equal($questionId);

        return $this->resultMapper->findBy($conditions);
    }

    /**
     * @param positive-int $attemptId
     * @param positive-int $questionId
     * @return ResultModel[]|null
     */
    public function findByAttemptIdAndQuestionId(int $attemptId, int $questionId): array
    {
        $conditions = new ResultConditions();
        $conditions->getAttemptId()->equal($attemptId);
        $conditions->getQuestionId()->equal($questionId);

        return $this->resultMapper->findBy($conditions);
    }

    /**
     * @param ResultModel $result
     * @return void
     * @throws \ReflectionException
     */
    public function save(ResultModel $result): void
    {
        $this->resultMapper->save($result);
    }

    /**
     * @param ResultModel $result
     * @return void
     */
    public function delete(ResultModel $result): void
    {
        $this->resultMapper->delete($result->getId());
    }

    /**
     * @param AttemptModel $attempt
     * @param QuestionModel $question
     * @param AnswerModel[] $answers
     * @return array
     */
    public function balanceAnswers(AttemptModel $attempt, QuestionModel $question, array $answers): array
    {
        $resultQuestion = $this->findByAttemptIdAndQuestionId($attempt->getId(), $question->getId());

        $difference = abs(count($resultQuestion) - count($answers));

        for ($i = $difference; $i > 0; $i--) {
            if (count($resultQuestion) > count($answers)) {
                $this->delete($resultQuestion[$i]);
                unset($resultQuestion[$i]);
            } else if (count($resultQuestion) < count($answers)) {
                $rQ = new ResultModel();
                $rQ->setAttemptId($attempt->getId());
                $rQ->setQuestionId($question->getId());

                $resultQuestion[] = $rQ;
            }
        }

        return array_values($resultQuestion);
    }

//    /**
//     * @param QuestionModel[] $questions
//     * @param ResultModel $result
//     * @return array
//     */
//    public function calculatePoints(array $questions, ResultModel $result)
//    {
//        $allPoints = 0;
//        $correctPoints = 0;
//        foreach ($questions as $question) {
//            $allPoints += $question->getPoint();
//
//            $allCorrectAnswers = 0;
//            $countCorrectAnswers = 0;
//            $countIncorrectAnswers = 0;
//            $isCoincided = false;
//            foreach ($question->getAnswers() as $answer) {
//                $resultQuestions = $this->findByResultIdAndQuestionId($result->getId(), $question->getId());
//
//                foreach ($resultQuestions as $rQ) {
//                    if ($rQ->getAnswerId()) {
//                        if ($rQ->getAnswerId() == $answer->getId()) {
//                            $answer->isCorrect() ? $countCorrectAnswers++ : $countIncorrectAnswers++;
//                        }
//                    } else {
//                        if ($question->getTypeId() == 2) {
//                            $answerText = str_ireplace([' ', ',', '.', '-', '\'', '"'], '', mb_strtolower($rQ->getAnswerText()));
//                            $answerTitle = str_ireplace([' ', ',', '.', '-', '\'', '"'], '', mb_strtolower($answer->getTitle()));
//
//                            if ($answerText == $answerTitle && !$isCoincided) {
//                                $countCorrectAnswers++;
//                                $isCoincided = true;
//                            }
//                        } else if ($question->getTypeId() == 5) {
//                            $rQ->getAnswerText() == $answer->getTitle() ?: $countCorrectAnswers++;
//                        }
//                    }
//                }
//                $answer->isCorrect() ?: $allCorrectAnswers++;
//            }
//
//            $resultAnswer = $countCorrectAnswers - $countIncorrectAnswers;
//            if ($allCorrectAnswers != 0 && $resultAnswer > 0) {
//                $correctPoints += $question->getPoint() * ($allCorrectAnswers / ($countCorrectAnswers - $countIncorrectAnswers));
//            }
//        }
//
//        return array($allPoints, $correctPoints);
//    }
}
