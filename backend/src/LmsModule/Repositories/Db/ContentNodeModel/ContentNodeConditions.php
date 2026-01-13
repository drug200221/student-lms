<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\ContentNodeModel;

use Ox3a\Core\ConditionsBuilder\Conditions;

/**
 * Class ContentNodeConditions
 * @package Psk\LmsModule\Repositories\Db\ContentNodeModel
 */
class ContentNodeConditions
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
     *          parentId?: Conditions\IntCondition,
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
     * @var array{ContentNodeConditions, string}[]
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
     * @param ContentNodeConditions $conditions
     * @param string $mode
     * @return $this
     */
    public function addConditions(ContentNodeConditions $conditions, $mode = 'AND')
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
