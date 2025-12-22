<?php

declare(strict_types=1);

namespace Psk\LmsModule\Repositories\Questions;

use Ox3a\Common\Service\ShareServiceInterface;
use Psk\LmsModule\Models\Questions\AnswerModel;
use Psk\LmsModule\Repositories\Db\Questions\AnswerModel\AnswerConditions;
use Psk\LmsModule\Repositories\Db\Questions\AnswerModel\AnswerMapper;

/**
 * @internal
 */
final class AnswerRepository implements ShareServiceInterface
{
    /** @var AnswerMapper */
    private $answerMapper;

    public function __construct(AnswerMapper $answerMapper)
    {
        $this->answerMapper = $answerMapper;
    }

    /**
     * @param positive-int $id
     * @return AnswerModel|null
     */
    public function findById(int $id): ?AnswerModel
    {
        $conditions = new AnswerConditions();
        $conditions->getId()->equal($id);

        $list = $this->answerMapper->findBy($conditions);

        return $list ? $list[0] : null;
    }

    /**
     * @param positive-int $questionId
     * @return AnswerModel[]
     */
    public function findByQuestionId(int $questionId): array
    {
        $conditions = new AnswerConditions();
        $conditions->getQuestionId()->equal($questionId);

        return $this->answerMapper->findBy($conditions);
    }

    /**
     * @param AnswerModel $answer
     * @param int $questionId
     * @param string $textAnswer
     * @param bool $isCorrect
     * @return void
     * @throws \ReflectionException
     */
    public function setAndSave(AnswerModel $answer, int $questionId, string $textAnswer, bool $isCorrect): void
    {
        $answer->setQuestionId($questionId);
        $answer->setAnswer($textAnswer);
        $answer->setIsCorrect($isCorrect);

        $this->answerMapper->save($answer);
    }

    /**
     * @param positive-int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->answerMapper->delete($id);
    }
}
