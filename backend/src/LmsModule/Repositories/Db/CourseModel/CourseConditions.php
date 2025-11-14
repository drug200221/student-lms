<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\CourseModel;

use Ox3a\Core\ConditionsBuilder\Conditions;

/**
 * Class CourseConditions
 * @package Psk\LmsModule\Repositories\Db\CourseModel
 */
class CourseConditions
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
     *          title?: Conditions\StringCondition,
     *          description?: Conditions\StringCondition,
     *          baseId?: Conditions\IntCondition,
     *          type?: Conditions\IntCondition,
     *          fillProgress?: Conditions\IntCondition,
     *      }
     */
    private $conditions = [];

    /**
     * Список дополнительных условий
     * @var array{CourseConditions, string}[]
     */
    private $extraConditions = [];

    /**
     * @return Conditions\IntCondition
     */
    public function getId()
    {
        if (!isset($this->conditions['id'])) {
            $this->conditions["id"] = new Conditions\IntCondition("lms_courses.id");
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
     * @return Conditions\StringCondition
     */
    public function getTitle()
    {
        if (!isset($this->conditions['title'])) {
            $this->conditions["title"] = new Conditions\StringCondition("lms_courses.title");
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
    public function getDescription()
    {
        if (!isset($this->conditions['description'])) {
            $this->conditions["description"] = new Conditions\StringCondition("lms_courses.description");
        }
        return $this->conditions['description'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByDescription($direction = 'asc')
    {
        if ($direction) {
            $this->order["description"] = $direction;
        } else {
            unset($this->order["description"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getBaseId()
    {
        if (!isset($this->conditions['baseId'])) {
            $this->conditions["baseId"] = new Conditions\IntCondition("lms_courses.base_id");
        }
        return $this->conditions['baseId'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByBaseId($direction = 'asc')
    {
        if ($direction) {
            $this->order["baseId"] = $direction;
        } else {
            unset($this->order["baseId"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getType()
    {
        if (!isset($this->conditions['type'])) {
            $this->conditions["type"] = new Conditions\IntCondition("lms_courses.type");
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
    public function getFillProgress()
    {
        if (!isset($this->conditions['fillProgress'])) {
            $this->conditions["fillProgress"] = new Conditions\IntCondition("lms_courses.fill_progress");
        }
        return $this->conditions['fillProgress'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByFillProgress($direction = 'asc')
    {
        if ($direction) {
            $this->order["fillProgress"] = $direction;
        } else {
            unset($this->order["fillProgress"]);
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
     * @param CourseConditions $conditions
     * @param string $mode
     * @return $this
     */
    public function addConditions(CourseConditions $conditions, $mode = 'AND')
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
