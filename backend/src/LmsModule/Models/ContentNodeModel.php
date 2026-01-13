<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models;

use Ox3a\Annotation\Mapping;

/**
 * @internal
 * @Mapping\Viewonly()
 * @Mapping\Table("lms_contents", alias="cnts")
 */
final class ContentNodeModel implements \JsonSerializable
{
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
     * @Mapping\Column("parent_id", table="cnts", type="int")
     * @var non-negative-int
     */
    private $parentId;

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
     * @return non-empty-string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return non-negative-int
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * @return int<1|2|3>
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return non-negative-int
     */
    public function getTreeLevel(): int
    {
        return $this->treeLevel;
    }

    /**
     * @return non-negative-int
     */
    public function getTreeLeft(): int
    {
        return $this->treeLeft;
    }

    /**
     * @return non-negative-int
     */
    public function getTreeRight(): int
    {
        return $this->treeRight;
    }

    /**
     * @return non-negative-int
     */
    public function getTreeOrder(): int
    {
        return $this->treeOrder;
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
            'id'        => $this->id,
            'courseId'  => $this->courseId,
            'title'     => $this->title,
            'parentId'  => $this->parentId,
            'type'      => $this->type,
            'treeLevel' => $this->treeLevel,
            'treeLeft'  => $this->treeLeft,
            'treeRight' => $this->treeRight,
            'treeOrder' => $this->treeOrder,
            'children'  => $this->children,
        ];
    }
}
