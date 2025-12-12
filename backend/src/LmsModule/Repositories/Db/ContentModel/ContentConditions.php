<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\ContentModel;

use Ox3a\Core\ConditionsBuilder\Conditions;

/**
 * Class ContentConditions
 * @package Psk\LmsModule\Repositories\Db\ContentModel
 */
class ContentConditions
{
    /**
     * Порядок сортировки
     * @var array<non-empty-string, non-empty-string>
     */
    private $order = [];

    /**
     * Ограничение
     * @var int|null
     */
    private $limit;

    /**
     * Пропуск
     * @var int
     */
    private $offset = 0;

    /**
     * Список условий по полям
     * @var array{
     *          id?: Conditions\IntCondition,
     *          courseId?: Conditions\IntCondition,
     *          title?: Conditions\StringCondition,
     *          content?: Conditions\StringCondition,
     *          path?: Conditions\StringCondition,
     *          parentId?: Conditions\IntCondition,
     *          createdAt?: Conditions\DateTimeCondition,
     *          updatedAt?: Conditions\DateTimeCondition,
     *          revision?: Conditions\IntCondition,
     *          type?: Conditions\IntCondition,
     *          treeLevel?: Conditions\IntCondition,
     *          treeLeft?: Conditions\IntCondition,
     *          treeRight?: Conditions\IntCondition,
     *          treeOrder?: Conditions\IntCondition,
     *      }
     */
    private $conditions = [];

    /**
     * Список дополнительных условий
     * @var array{ContentConditions, string}[]
     */
    private $extraConditions = [];

    /**
     * @return Conditions\IntCondition
     */
    public function getId()
    {
        if (!isset($this->conditions['id'])) {
            $this->conditions["id"] = new Conditions\IntCondition("cnts.id");
        }
        return $this->conditions['id'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderById($direction = 'asc')
    {
        if ($direction) {
            $this->order["id"] = $direction;
        } else {
            unset($this->order["id"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getCourseId()
    {
        if (!isset($this->conditions['courseId'])) {
            $this->conditions["courseId"] = new Conditions\IntCondition("cnts.course_id");
        }
        return $this->conditions['courseId'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByCourseId($direction = 'asc')
    {
        if ($direction) {
            $this->order["courseId"] = $direction;
        } else {
            unset($this->order["courseId"]);
        }
        return $this;
    }

    /**
     * @return Conditions\StringCondition
     */
    public function getTitle()
    {
        if (!isset($this->conditions['title'])) {
            $this->conditions["title"] = new Conditions\StringCondition("cnts.title");
        }
        return $this->conditions['title'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByTitle($direction = 'asc')
    {
        if ($direction) {
            $this->order["title"] = $direction;
        } else {
            unset($this->order["title"]);
        }
        return $this;
    }

    /**
     * @return Conditions\StringCondition
     */
    public function getContent()
    {
        if (!isset($this->conditions['content'])) {
            $this->conditions["content"] = new Conditions\StringCondition("cnts.content");
        }
        return $this->conditions['content'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByContent($direction = 'asc')
    {
        if ($direction) {
            $this->order["content"] = $direction;
        } else {
            unset($this->order["content"]);
        }
        return $this;
    }

    /**
     * @return Conditions\StringCondition
     */
    public function getPath()
    {
        if (!isset($this->conditions['path'])) {
            $this->conditions["path"] = new Conditions\StringCondition("cnts.path");
        }
        return $this->conditions['path'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByPath($direction = 'asc')
    {
        if ($direction) {
            $this->order["path"] = $direction;
        } else {
            unset($this->order["path"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getParentId()
    {
        if (!isset($this->conditions['parentId'])) {
            $this->conditions["parentId"] = new Conditions\IntCondition("cnts.parent_id");
        }
        return $this->conditions['parentId'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByParentId($direction = 'asc')
    {
        if ($direction) {
            $this->order["parentId"] = $direction;
        } else {
            unset($this->order["parentId"]);
        }
        return $this;
    }

    /**
     * @return Conditions\DateTimeCondition
     */
    public function getCreatedAt()
    {
        if (!isset($this->conditions['createdAt'])) {
            $this->conditions["createdAt"] = new Conditions\DateTimeCondition("cnts.created_at");
        }
        return $this->conditions['createdAt'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByCreatedAt($direction = 'asc')
    {
        if ($direction) {
            $this->order["createdAt"] = $direction;
        } else {
            unset($this->order["createdAt"]);
        }
        return $this;
    }

    /**
     * @return Conditions\DateTimeCondition
     */
    public function getUpdatedAt()
    {
        if (!isset($this->conditions['updatedAt'])) {
            $this->conditions["updatedAt"] = new Conditions\DateTimeCondition("cnts.updated_at");
        }
        return $this->conditions['updatedAt'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByUpdatedAt($direction = 'asc')
    {
        if ($direction) {
            $this->order["updatedAt"] = $direction;
        } else {
            unset($this->order["updatedAt"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getRevision()
    {
        if (!isset($this->conditions['revision'])) {
            $this->conditions["revision"] = new Conditions\IntCondition("cnts.revision");
        }
        return $this->conditions['revision'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByRevision($direction = 'asc')
    {
        if ($direction) {
            $this->order["revision"] = $direction;
        } else {
            unset($this->order["revision"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getType()
    {
        if (!isset($this->conditions['type'])) {
            $this->conditions["type"] = new Conditions\IntCondition("cnts.type");
        }
        return $this->conditions['type'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByType($direction = 'asc')
    {
        if ($direction) {
            $this->order["type"] = $direction;
        } else {
            unset($this->order["type"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getTreeLevel()
    {
        if (!isset($this->conditions['treeLevel'])) {
            $this->conditions["treeLevel"] = new Conditions\IntCondition("cnts.tree_level");
        }
        return $this->conditions['treeLevel'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByTreeLevel($direction = 'asc')
    {
        if ($direction) {
            $this->order["treeLevel"] = $direction;
        } else {
            unset($this->order["treeLevel"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getTreeLeft()
    {
        if (!isset($this->conditions['treeLeft'])) {
            $this->conditions["treeLeft"] = new Conditions\IntCondition("cnts.tree_left");
        }
        return $this->conditions['treeLeft'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByTreeLeft($direction = 'asc')
    {
        if ($direction) {
            $this->order["treeLeft"] = $direction;
        } else {
            unset($this->order["treeLeft"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getTreeRight()
    {
        if (!isset($this->conditions['treeRight'])) {
            $this->conditions["treeRight"] = new Conditions\IntCondition("cnts.tree_right");
        }
        return $this->conditions['treeRight'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByTreeRight($direction = 'asc')
    {
        if ($direction) {
            $this->order["treeRight"] = $direction;
        } else {
            unset($this->order["treeRight"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getTreeOrder()
    {
        if (!isset($this->conditions['treeOrder'])) {
            $this->conditions["treeOrder"] = new Conditions\IntCondition("cnts.tree_order");
        }
        return $this->conditions['treeOrder'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByTreeOrder($direction = 'asc')
    {
        if ($direction) {
            $this->order["treeOrder"] = $direction;
        } else {
            unset($this->order["treeOrder"]);
        }
        return $this;
    }

    /**
     * Очистить сортировку
     * @return $this
     */
    public function clearOrder()
    {
        $this->order = [];
        return $this;
    }

    /**
     * Получить сортировку
     * @return array<non-empty-string, non-empty-string>
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Добавить дополнительные условия
     * @param ContentConditions $conditions
     * @param string $mode
     * @return $this
     */
    public function addConditions(ContentConditions $conditions, $mode = 'AND')
    {
        $this->extraConditions[] = [$conditions, $mode];
        return $this;
    }

    /**
     * Получить дерево условий
     * @return list<array{mixed, string}>
     */
    public function getConditions()
    {
        $conditions = [];

        foreach ($this->conditions as $condition) {
            if (($condition = $condition->getCondition())) {
                $conditions[] = [$condition, "AND"];
            }
        }

        foreach ($this->extraConditions as $extraConditions) {
            list($extraConditions, $mode) = $extraConditions;
            if (($extraConditions = $extraConditions->getConditions())) {
                $conditions[] = [$extraConditions, $mode];
            }
        }

        return $conditions;
    }

    /**
     * Получить limit
     * @param int|null $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Получить limit
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Получить offset
     * @param int $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Получить offset
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }
}
