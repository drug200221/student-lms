<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Questions;

use Ox3a\Annotation\Mapping;

/**
 * @internal
 * @Mapping\Table("lms_tests_questions", alias="q")
 */
final class QuestionModel implements \JsonSerializable
{
    public const INCORRECT_TYPE = 'Неверный тип вопроса.';
    public const CANNOT_BE_DELETED = 'Вопрос нельзя удалить. На него уже ответили';
    public const DELETE_SUCCESS = 'Вопрос успешно удален!';

    /**
     * @Mapping\Id()
     * @Mapping\Column("id", table="q", type="int")
     * @var positive-int
     */
    private $id;

    /**
     * @Mapping\Column("course_id", table="q", type="int")
     * @var positive-int
     */
    private $courseId;

    /**
     * @Mapping\Column("category_id", table="q", type="int")
     * @var positive-int|null
     */
    private $categoryId;

    public const TRUE_OR_FALSE = 1;
    public const SHORT_RESPONSE = 2;
    public const MULTIPLE_CHOICE = 3;
    public const MULTIPLE_RESPONSE = 4;
    public const ACCORDANCE = 5;
    public const ORDERING = 6;

    /**
     * @Mapping\Column("question", table="q", type="string")
     * @var non-empty-string
     */
    private $question;

    /**
     * @Mapping\Column("type", table="q", type="int")
     * @var int<1|2|3|4|5|6>
     */
    private $type;

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
     * @param int|null $categoryId
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
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * @param string $question
     * @return $this
     */
    public function setQuestion(string $question): self
    {
        $this->question = $question;
        return $this;
    }

    /**
     * @return positive-int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return $this
     */
    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    public static function getTypes(): array
    {
        return [
            self::TRUE_OR_FALSE     => 'trueOrFalse',
            self::SHORT_RESPONSE    => 'shortResponse',
            self::MULTIPLE_CHOICE   => 'multipleChoice',
            self::MULTIPLE_RESPONSE => 'multipleResponse',
            self::ACCORDANCE        => 'accordance',
            self::ORDERING          => 'ordering',
        ];
    }

    /**
     * @return AnswerModel[]
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'         => $this->id,
            'courseId'   => $this->courseId,
            'categoryId' => $this->categoryId,
            'title'      => $this->question,
            'type'       => $this->type,
            'answers'    => $this->answers,
        ];
    }
}
