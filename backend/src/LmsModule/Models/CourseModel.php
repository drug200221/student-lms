<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models;

use Ox3a\Annotation\Mapping;

/**
 * @internal
 * @Mapping\Table("lms_courses", alias="crs")
 */
final class CourseModel implements \JsonSerializable
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
     * @Mapping\Column("created_at", table="crs", type="DateTime")
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @Mapping\Column("fill_progress", table="crs", type="int")
     * @var int<0,100>
     */
    private $fillProgress = 0;

    /** @var ContentModel[] */
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
     * @return self
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
     * @return self
     */
    public function setDescription(?string $description): self
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
     * @return self
     */
    public function setBaseId(int $baseId): self
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
     * @return self
     */
    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     * @return self
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
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
     * @return self
     */
    public function setFillProgress(int $fillProgress): self
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
            'createdAt'    => $this->createdAt,
            'fillProgress' => $this->fillProgress,
            'contents'     => $this->contents,
        ];
    }
}
