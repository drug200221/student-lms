<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Tests;

use Ox3a\Annotation\Mapping;
use Psk\LmsModule\Models\Questions\AnswerModel;

/**
 * @Mapping\Table("lms_tests_questions_of_tests", alias="qt")
 * @Mapping\Join("lms_tests_questions", alias="q", on="qt.question_id=q.id")
 * @Mapping\LeftJoin("lms_tests_questions_categories", alias="tc", on="tc.id=q.category_id")
 */
final class QuestionOfTestModel implements \JsonSerializable
{
    public const EXIST_IN_ATTEMPT = 'Вопрос задействован в попытке.';
    public const DELETE_SUCCESS = 'Вопрос успешно удален из теста!';

    /**
     * Id вопроса теста
     * @Mapping\Id()
     * @Mapping\Column("id", table="qt", type="int")
     * @var positive-int
     */
    private $id;

    /**
     * @Mapping\Column("course_id", table="q", type="int")
     * @Mapping\Viewonly()
     * @var positive-int
     */
    private $courseId;

    /**
     * @Mapping\Column("test_id", table="qt", type="int")
     * @var positive-int
     */
    private $testId;

    /**
     * @Mapping\Column("question_id", table="qt", type="int")
     * @var positive-int
     */
    private $questionId;

    /**
     * Балл
     * @Mapping\Column("point", table="qt", type="int")
     * @var int<1,10>
     */
    private $point;

    /**
     * Обязателен ответ
     * @Mapping\Column("is_required", table="qt", type="bool")
     * @var bool
     */
    private $isRequired;

    /**
     * Название вопроса
     * @Mapping\Column("question", table="q", type="string")
     * @Mapping\Viewonly()
     * @var string
     */
    private $question;

    /**
     * @Mapping\Column("type", table="q", type="int")
     * @Mapping\Viewonly()
     * @var positive-int
     */
    private $type;

    /**
     * @Mapping\Column("title", table="tc", type="string")
     * @Mapping\Viewonly()
     * @var string|null
     */
    private $category;

    /**
     * @var AnswerModel[]
     */
    private $answers = [];

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
     * @return int<1,10>
     */
    public function getPoint(): int
    {
        return $this->point;
    }

    /**
     * @param int<1,10> $point
     * @return $this
     */
    public function setPoint(int $point): self
    {
        $this->point = $point;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * @param bool $isRequired
     * @return $this
     */
    public function setIsRequired(bool $isRequired): self
    {
        $this->isRequired = $isRequired;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * @return positive-int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @return AnswerModel[]
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id'            => $this->id,
            'testId'        => $this->testId,
            'courseId'      => $this->courseId,
            'questionId'    => $this->questionId,
            'category'      => $this->category,
            'question'      => $this->question,
            'answers'       => $this->answers,
            'type'          => $this->type,
            'point'         => $this->point,
        ];
    }
}
