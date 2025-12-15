<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Tests;

use Ox3a\Annotation\Mapping;

/**
 * @internal
 * @Mapping\Table("lms_tests", alias="t")
 */
final class TestModel implements \JsonSerializable
{
    public const DELETE_SUCCESS = 'Тест успешно удален!';

    /**
     * @Mapping\Id()
     * @Mapping\Column("id", table="t", type="int")
     * @var positive-int
     */
    private $id;

    /**
     * @Mapping\Column("course_id", table="t", type="int")
     * @var positive-int
     */
    private $courseId;

    /**
     * @Mapping\Column("category_id", table="t", type="int")
     * @var positive-int|null
     */
    private $categoryId;

    /**
     * @Mapping\Column("title", table="t", type="string")
     * @var string
     */
    private $title;

    /**
     * @Mapping\Column("description", table="t", type="string")
     * @var string|null
     */
    private $description;

    /**
     * @Mapping\Column("is_display_all_questions", table="t", type="bool")
     * @var bool
     */
    private $isDisplayAllQuestions;

    /**
     * @Mapping\Column("is_visible_result", table="t", type="bool")
     * @var bool
     */
    private $isVisibleResult;

    /**
     * @Mapping\Column("is_random_questions", table="t", type="bool")
     * @var bool
     */
    private $isRandomQuestion;

    /**
     * @Mapping\Column("attempt_count", table="t", type="int")
     * @var non-negative-int
     */
    private $attemptCount;

    /**
     * @Mapping\Column("question_count", table="t", type="int")
     * @var non-negative-int
     */
    private $questionCount;

    /**
     * @Mapping\Column("time_limit", table="t", type="int")
     * @var non-negative-int
     */
    private $timeLimit;

    /**
     * Открытие теста
     * @Mapping\Column("start_at", table="t", type="DateTime")
     * @var \DateTimeImmutable
     */
    private $startAt;

    /**
     * Закрытие теста
     * @Mapping\Column("end_at", table="t", type="DateTime")
     * @var \DateTimeImmutable|null
     */
    private $endAt;

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
    public function getCourseId(): int
    {
        return $this->courseId;
    }

    /**
     * @param positive-int $courseId
     * @return $this
     */
    public function setCourseId(int $courseId): self
    {
        $this->courseId = $courseId;
        return $this;
    }

    /**
     * @return positive-int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     * @param positive-int|null $categoryId
     * @return $this
     */
    public function setCategoryId(?int $categoryId): self
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisplayAllQuestions(): bool
    {
        return $this->isDisplayAllQuestions;
    }

    /**
     * @param bool $isDisplayAllQuestions
     * @return $this
     */
    public function setIsDisplayAllQuestions(bool $isDisplayAllQuestions): self
    {
        $this->isDisplayAllQuestions = $isDisplayAllQuestions;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVisibleResult(): bool
    {
        return $this->isVisibleResult;
    }

    /**
     * @param bool $isVisibleResult
     * @return $this
     */
    public function setIsVisibleResult(bool $isVisibleResult): self
    {
        $this->isVisibleResult = $isVisibleResult;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRandomQuestion(): bool
    {
        return $this->isRandomQuestion;
    }

    /**
     * @param bool $isRandomQuestion
     * @return $this
     */
    public function setIsRandomQuestion(bool $isRandomQuestion): self
    {
        $this->isRandomQuestion = $isRandomQuestion;
        return $this;
    }

    /**
     * @return positive-int
     */
    public function getAttemptCount(): int
    {
        return $this->attemptCount;
    }

    /**
     * @param non-negative-int|null $attemptCount
     * @return $this
     */
    public function setAttemptCount(?int $attemptCount): self
    {
        $this->attemptCount = $attemptCount ?: 0;
        return $this;
    }

    /**
     * @return non-negative-int
     */
    public function getQuestionCount(): int
    {
        return $this->questionCount;
    }

    /**
     * @param non-negative-int|null $questionCount
     * @return $this
     */
    public function setQuestionCount(?int $questionCount): self
    {
        $this->questionCount = $questionCount ?: 0;
        return $this;
    }

    /**
     * @return non-negative-int
     */
    public function getTimeLimit(): int
    {
        return $this->timeLimit;
    }

    /**
     * @param non-negative-int|null $timeLimit
     * @return $this
     */
    public function setTimeLimit(?int $timeLimit): self
    {
        $this->timeLimit = $timeLimit ?: 0;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartAt(): \DateTimeImmutable
    {
        return $this->startAt;
    }

    /**
     * @param string $startAt
     * @return $this
     */
    public function setStartAt(string $startAt): self
    {
        $this->startAt = new \DateTimeImmutable($startAt);
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEndAt(): \DateTimeImmutable
    {
        return $this->endAt;
    }

    /**
     * @param string $endAt
     * @return $this
     */
    public function setEndAt(?string $endAt): self
    {
        if ($endAt) {
            $this->endAt = new \DateTimeImmutable($endAt);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        $nowDate = new \DateTimeImmutable();

        if ($nowDate < $this->startAt) {
            return "Еще не начался";
        }

        if ($nowDate > $this->endAt) {
            return "Закончен";
        }

        if ($nowDate > $this->startAt && $nowDate < $this->endAt) {
            return "Проводится";
        }

        return "Неизвестный статус";
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id'                    => $this->id,
            'courseId'              => $this->courseId,
            'categoryId'            => $this->categoryId,
            'title'                 => $this->title,
            'description'           => $this->description,
            'isDisplayAllQuestions' => $this->isDisplayAllQuestions,
            'isVisibleResult'       => $this->isVisibleResult,
            'isRandomQuestion'      => $this->isRandomQuestion,
            'attemptCount'          => $this->attemptCount,
            'questionCount'         => $this->questionCount,
            'timeLimit'             => $this->timeLimit,
            'startAt'               => $this->startAt,
            'endAt'                 => $this->endAt,
            'status'                => $this->getStatus(),
        ];
    }
}
