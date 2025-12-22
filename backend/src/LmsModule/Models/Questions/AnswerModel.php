<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Questions;

use Ox3a\Annotation\Mapping;

/**
 * @internal
 * @Mapping\Table("lms_tests_answers", alias="a")
 */
final class AnswerModel implements \JsonSerializable
{
    public const EMPTY_CORRECT = 'Верный ответ не может быть пустым.';
    public const SEPARATOR = '|__SEP_ANSWER__|';

    /**
     * @Mapping\Id()
     * @Mapping\Column("id", table="a", type="int")
     * @var positive-int
     */
    private $id;

    /**
     * @Mapping\Column("question_id", table="a", type="int")
     * @var positive-int
     */
    private $questionId;

    /**
     * @Mapping\Column("answer", table="a", type="string")
     * @var non-empty-string
     */
    private $answer;

    /**
     * @Mapping\Column("is_correct", table="a", type="bool")
     * @var bool
     */
    private $isCorrect;

    /**
     * @return positive-int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return positive-int
     */
    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    /**
     * @param positive-int $questionId
     * @return $this
     */
    public function setQuestionId(int $questionId): self
    {
        $this->questionId = $questionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * @param string $answer
     * @return $this
     */
    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsCorrect(): bool
    {
        return $this->isCorrect;
    }

    /**
     * @param bool $isCorrect
     * @return $this
     */
    public function setIsCorrect(bool $isCorrect): self
    {
        $this->isCorrect = $isCorrect;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'     => $this->id,
            'answer' => $this->answer,
        ];
    }
}
