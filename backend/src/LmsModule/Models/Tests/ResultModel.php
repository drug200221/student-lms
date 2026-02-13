<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Tests;

use Ox3a\Annotation\Mapping;

/**
 * @Mapping\Table("lms_tests_results", alias="r")
 * @Mapping\Join("lms_tests_attempts", alias="a", on="r.attempt_id=a.id")
 * @Mapping\Join("lms_questions", alias="q", on="r.question_id=q.id")
 * @Mapping\Join("lms_tests_questions_of_tests", alias="qt", on="a.test_id=qt.test_id AND q.id=qt.question_id")
 */
final class ResultModel implements \JsonSerializable
{
    /**
     * @Mapping\Id()
     * @Mapping\Column("id", table="r", type="int")
     * @var positive-int
     */
    private $id;

    /**
     * @Mapping\Column("attempt_id", table="r", type="int")
     * @var positive-int
     */
    private $attemptId;

    /**
     * @Mapping\Column("question_id", table="r", type="int")
     * @var positive-int
     */
    private $questionId;

    /**
     * @Mapping\Column("question", table="q", type="int")
     * @Mapping\Viewonly()
     * @var positive-int
     */
    private $question;

    /**
     * @Mapping\Column("type", table="q", type="int")
     * @Mapping\Viewonly()
     * @var positive-int
     */
    private $questionType;

    /**
     * @Mapping\Column("point", table="qt", type="int")
     * @Mapping\Viewonly()
     * @var positive-int
     */
    private $point;

    /**
     * @Mapping\Column("is_required", table="qt", type="bool")
     * @Mapping\Viewonly()
     * @var bool
     */
    private $isRequired;

    /**
     * @Mapping\Column("answer_id", table="r", type="int")
     * @var positive-int
     */
    private $answerId;

    /**
     * @Mapping\Column("answer_text", table="r", type="string")
     * @var string|null
     */
    private $answerText;

    /**
     * @return positive-int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return positive-int
     */
    public function getAttemptId(): int
    {
        return $this->attemptId;
    }

    /**
     * @param positive-int $attemptId
     * @return $this
     */
    public function setAttemptId(int $attemptId): self
    {
        $this->attemptId = $attemptId;
        return $this;
    }

    /**
     * @return positive-int
     */
    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    /**
     * @param int $questionId
     * @return $this
     */
    public function setQuestionId(int $questionId): self
    {
        $this->questionId = $questionId;
        return $this;
    }

    public function getQuestion(): int
    {
        return $this->question;
    }

    /**
     * @return int<1,6>
     */
    public function getQuestionType(): int
    {
        return $this->questionType;
    }

    /**
     * @return positive-int
     */
    public function getPoint(): int
    {
        return $this->point;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * @return positive-int
     */
    public function getAnswerId(): int
    {
        return $this->answerId;
    }

    /**
     * @param positive-int $answerId
     * @return $this
     */
    public function setAnswerId(int $answerId): self
    {
        $this->answerId = $answerId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAnswerText(): ?string
    {
        return $this->answerText;
    }

    /**
     * @param string|null $answerText
     * @return $this
     */
    public function setAnswerText(?string $answerText): self
    {
        $this->answerText = $answerText;
        return $this;
    }

    /** @return array<string,mixed> */
    public function jsonSerialize(): array
    {
        return [
            'id'         => $this->id,
            'attemptId'  => $this->attemptId,
            'question'   => [
                'id'    => $this->questionId,
                'type'  => $this->questionType,
                'title' => $this->question,
            ],
            'answerId'   => $this->answerId,
            'answerText' => $this->answerText,
        ];
    }
}
