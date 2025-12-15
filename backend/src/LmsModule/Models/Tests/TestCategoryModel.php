<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Tests;

use Ox3a\Annotation\Mapping;

/**
 * @internal
 * @Mapping\Table("lms_tests_categories", alias="c")
 */
final class TestCategoryModel implements \JsonSerializable
{
    public const DELETE_SUCCESS = 'Категория успешно удалена!';

    /**
     * @Mapping\Id()
     * @Mapping\Column("id", table="c", type="int")
     * @var positive-int
     */
    private $id;

    /**
     * @Mapping\Column("course_id", table="c", type="int")
     * @var positive-int
     */
    private $courseId;

    /**
     * @Mapping\Column("title", table="c", type="string")
     * @var non-empty-string
     */
    private $title;
    
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
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id'       => $this->id,
            'courseId' => $this->courseId,
            'title'    => $this->title,
        ];
    }
}
