<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models;

use Ox3a\Annotation\Mapping;

/**
 * @internal
 * @Mapping\Table("lms_contents", alias="cnts")
 */
final class ContentModel implements \JsonSerializable
{
    public const CHILDREN_EXISTS_ERROR = 'Нельзя удалить страницу с дочерними ресурсами.';
    public const DELETE_SUCCESS = 'Контент успешно удален!';

    /**
     * @Mapping\Id()
     * @Mapping\Column("id", table="cnts", type="int")
     * @var positive-int
     */
    private $id;

    /**
     * @Mapping\Column("course_id", table="cnts", type="int")
     * @var positive-int
     */
    private $courseId;

    /**
     * @Mapping\Column("title", table="cnts", type="string")
     * @var non-empty-string
     */
    private $title;

    /**
     * @Mapping\Column("content", table="cnts", type="string")
     * @var string|null
     */
    private $content;

    /**
     * @Mapping\Column("path", table="cnts", type="string")
     * @var string|null
     */
    private $path;

    /**
     * @Mapping\Column("parent_id", table="cnts", type="int")
     * @var non-negative-int
     */
    private $parentId;

    /**
     * @Mapping\Column("created_at", table="cnts", type="DateTime")
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @Mapping\Column("updated_at", table="cnts", type="DateTime")
     * @var \DateTimeImmutable|null
     */
    private $updatedAt;

    /**
     * @Mapping\Column("revision", table="cnts", type="int")
     * @var non-negative-int
     */
    private $revision = 0;

    /**
     * !!! Возможно будет удалено !!!
     * @Mapping\Column("type", table="cnts", type="int")
     * @var int<1|2|3>
     */
    private $type = 1;

    /**
     * @Mapping\Column("tree_level", table="cnts", type="int")
     * @var non-negative-int
     */
    private $treeLevel = 0;

    /**
     * @Mapping\Column("tree_left", table="cnts", type="int")
     * @var non-negative-int
     */
    private $treeLeft = 0;

    /**
     * @Mapping\Column("tree_right", table="cnts", type="int")
     * @var non-negative-int
     */
    private $treeRight = 0;

    /**
     * @Mapping\Column("tree_order", table="cnts", type="int")
     * @var non-negative-int
     */
    private $treeOrder = 0;

    /**
     * @var self[]
     */
    private $children = [];

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
     * @return self
     */
    public function setCourseId(int $courseId): self
    {
        $this->courseId = $courseId;
        return $this;
    }

    /**
     * @return non-empty-string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param non-empty-string $title
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
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     * @return self
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     * @return self
     */
    public function setPath(?string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return non-negative-int
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * @param non-negative-int $parentId
     * @return self
     */
    public function setParentId(int $parentId): self
    {
        $this->parentId = $parentId;
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
    public function setCreatedAt(\DateTimeImmutable $createdAt): ContentModel
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeImmutable $updatedAt
     * @return self
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return non-negative-int
     */
    public function getRevision(): int
    {
        return $this->revision;
    }

    /**
     * @param non-negative-int $revision
     * @return self
     */
    public function setRevision(int $revision): self
    {
        $this->revision = $revision;
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
     * @return non-negative-int
     */
    public function getTreeLevel(): int
    {
        return $this->treeLevel;
    }

    /**
     * @param non-negative-int $treeLevel
     * @return self
     */
    public function setTreeLevel(int $treeLevel): self
    {
        $this->treeLevel = $treeLevel;
        return $this;
    }

    /**
     * @return non-negative-int
     */
    public function getTreeLeft(): int
    {
        return $this->treeLeft;
    }

    /**
     * @param non-negative-int $treeLeft
     * @return self
     */
    public function setTreeLeft(int $treeLeft): self
    {
        $this->treeLeft = $treeLeft;
        return $this;
    }

    /**
     * @return non-negative-int
     */
    public function getTreeRight(): int
    {
        return $this->treeRight;
    }

    /**
     * @param non-negative-int $treeRight
     * @return self
     */
    public function setTreeRight(int $treeRight): self
    {
        $this->treeRight = $treeRight;
        return $this;
    }

    /**
     * @return non-negative-int
     */
    public function getTreeOrder(): int
    {
        return $this->treeOrder;
    }

    /**
     * @param non-negative-int $treeOrder
     * @return self
     */
    public function setTreeOrder(int $treeOrder): self
    {
        $this->treeOrder = $treeOrder;
        return $this;
    }

    /**
     * @return self[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param self $content
     * @return void
     */
    public function addChild(self $content): void
    {
        $this->children[] = $content;
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id'           => $this->id,
            'courseId'     => $this->courseId,
            'title'        => $this->title,
            'content'      => $this->content,
            'path'         => $this->path,
            'parentId'     => $this->parentId,
            'createdAt'    => $this->createdAt,
            'updatedAt'    => $this->updatedAt,
            'revision'     => $this->revision,
            'type'         => $this->type,
            'treeLevel'    => $this->treeLevel,
            'treeLeft'     => $this->treeLeft,
            'treeRight'    => $this->treeRight,
            'treeOrder'    => $this->treeOrder,
            'children'     => $this->children,
        ];
    }
}
