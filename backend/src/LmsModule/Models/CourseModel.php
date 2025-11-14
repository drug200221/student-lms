<?php

namespace Psk\LmsModule\Models;

use Ox3a\Annotation\Mapping;

/**
 * @Mapping\Table("lms_courses", alias="crs")
 */
class CourseModel implements \JsonSerializable
{
    /**
     * @Mapping\Id()
     * @Mapping\Column("id", table="crs", type="int")
     * @var positive-int
     */
    private $id;

    /**
     * @Mapping\Column("title", table="crs", type="string")
     * @var string
     */
    private $title;

    /**
     * @Mapping\Column("description", table="crs", type="string")
     * @var string|null
     */
    private $description;

    /**
     * @Mapping\Column("base_id", table="crs", type="int")
     * @var positive-int
     */
    private $baseId;

    public const STUD_TYPE = 1;
    public const CDPO_TYPE = 2;
    public const AUTO_TYPE = 3;

    /**
     * @Mapping\Column("type", table="crs", type="int")
     * @var int<1|2|3>
     */
    private $type;

    /**
     * @Mapping\Column("fill_progress", table="crs", type="int")
     * @var int<0,100>
     */
    private $fillProgress;

    private $contents = [];

    /**
     * @return positive-int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return CourseModel
     */
    public function setTitle(string $title): CourseModel
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
     * @return CourseModel
     */
    public function setDescription(?string $description): CourseModel
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return positive-int
     */
    public function getBaseId(): int
    {
        return $this->baseId;
    }

    /**
     * @param positive-int $baseId
     * @return CourseModel
     */
    public function setBaseId(int $baseId): CourseModel
    {
        $this->baseId = $baseId;
        return $this;
    }

    /**
     * @return int<1|2|3>
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int<1|2|3> $type
     * @return CourseModel
     */
    public function setType(int $type): CourseModel
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int<0,100>
     */
    public function getFillProgress(): int
    {
        return $this->fillProgress;
    }

    /**
     * @param int<0,100> $fillProgress
     * @return CourseModel
     */
    public function setFillProgress(int $fillProgress): CourseModel
    {
        $this->fillProgress = $fillProgress;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'baseId'       => $this->baseId,
            'type'         => $this->type,
            'fillProgress' => $this->fillProgress,
            'contents'     => $this->contents,
        ];
    }
}
