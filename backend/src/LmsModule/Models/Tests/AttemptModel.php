<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Tests;

use Ox3a\Annotation\Mapping;

/**
 * @Mapping\Table("lms_tests_attempts", alias="a")
 * @Mapping\Join("users", alias="u", on="a.user_id=u.id")
 */
final class AttemptModel implements \JsonSerializable
{
    public const NO_QUESTIONS_ADDED = 'В тест не добавлен ни один вопрос.';
    public const ATTEMPTS_EXHAUSTED = 'Попытки закончились.';
    public const DELETE_SUCCESS = 'Попытка успешно удалена!';

    /**
     * @Mapping\Id()
     * @Mapping\Column("id", table="a", type="int")
     * @var positive-int
     */
    private $id;

    /**
     * @Mapping\Column("user_id", table="a", type="int")
     * @var positive-int
     */
    private $userId;

    /**
     * @Mapping\Column("ima", table="u", type="string")
     * @Mapping\Viewonly()
     * @var string
     */
    private $firstName;

    /**
     * @Mapping\Column("fam", table="u", type="string")
     * @Mapping\Viewonly()
     * @var string
     */
    private $lastName;

    /**
     * @Mapping\Column("otc", table="u", type="string")
     * @Mapping\Viewonly()
     * @var string|null
     */
    private $middleName;

    /**
     * @Mapping\Column("test_id", table="a", type="int")
     * @var positive-int
     */
    private $testId;

    /**
     * @Mapping\Column("start_at", table="a", type="DateTime")
     * @var \DateTimeImmutable
     */
    private $startAt;

    /**
     * @Mapping\Column("end_at", table="a", type="DateTime")
     * @var \DateTimeImmutable|null
     */
    private $endAt;

    /**
     * @Mapping\Column("is_finished", table="a", type="bool")
     *  @var bool
     */
    private $isFinished;

    /**
     * @Mapping\Column("grade", table="a", type="float")
     * @var float
     */
    private $grade;

    /** @var TestModel */
    private $test;

    /** @var ResultModel[] */
    private $results = [];

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
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param positive-int $userId
     * @return $this
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return positive-int
     */
    public function getTestId(): int
    {
        return $this->testId;
    }

    /**
     * @param positive-int $testId
     * @return $this
     */
    public function setTestId(int $testId): self
    {
        $this->testId = $testId;
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
     * @param \DateTimeImmutable $startAt
     * @return $this
     */
    public function setStartAt(\DateTimeImmutable $startAt): self
    {
        $this->startAt = $startAt;
        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    /**
     * @param \DateTimeImmutable|null $endAt
     * @return $this
     */
    public function setEndAt(?\DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    /**
     * @param bool $isFinished
     * @return $this
     */
    public function setIsFinished(bool $isFinished): self
    {
        $this->isFinished = $isFinished;
        return $this;
    }

    /**
     * @return float
     */
    public function getGrade(): float
    {
        return $this->grade;
    }

    /**
     * @param float $grade
     * @return $this
     */
    public function setGrade(float $grade): self
    {
        $this->grade = $grade;
        return $this;
    }

    /**
     * @return TestModel
     */
    public function getTest(): TestModel
    {
        return $this->test;
    }

    /**
     * @return ResultModel[]
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /** @return array<string,mixed */
    private function getUser(): array
    {
        return [
            'id'         => $this->userId,
            'firstName'  => $this->firstName,
            'lastName'   => $this->lastName,
            'middleName' => $this->middleName,
        ];
    }

    /** @return array<string,mixed */
    public function jsonSerialize(): array
    {
        return [
            'id'         => $this->id,
            'user'       => $this->getUser(),
            'testId'     => $this->testId,
            'startAt'    => $this->startAt,
            'endAt'      => $this->endAt,
            'grade'      => $this->grade,
            'isFinished' => $this->isFinished,
            'test'       => $this->test,
            'results'    => $this->results,
        ];
    }
}
