<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\Questions\QuestionModel;

use Ox3a\Core\ConditionsBuilder\Conditions;

/**
 * Class QuestionConditions
 * @package Psk\LmsModule\Repositories\Db\Questions\QuestionModel
 */
class QuestionConditions
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
     *          categoryId?: Conditions\IntCondition,
     *          question?: Conditions\StringCondition,
     *          type?: Conditions\IntCondition,
     *      }
     */
    private $conditions = [];

    /**
     * Список дополнительных условий
     * @var array{QuestionConditions, string}[]
     */
    private $extraConditions = [];

    /**
     * @return Conditions\IntCondition
     */
    public function getId()
    {
        if (!isset($this->conditions['id'])) {
            $this->conditions["id"] = new Conditions\IntCondition("q.id");
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
            $this->conditions["courseId"] = new Conditions\IntCondition("q.course_id");
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
     * @return Conditions\IntCondition
     */
    public function getCategoryId()
    {
        if (!isset($this->conditions['categoryId'])) {
            $this->conditions["categoryId"] = new Conditions\IntCondition("q.category_id");
        }
        return $this->conditions['categoryId'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByCategoryId($direction = 'asc')
    {
        if ($direction) {
            $this->order["categoryId"] = $direction;
        } else {
            unset($this->order["categoryId"]);
        }
        return $this;
    }

    /**
     * @return Conditions\StringCondition
     */
    public function getQuestion()
    {
        if (!isset($this->conditions['question'])) {
            $this->conditions["question"] = new Conditions\StringCondition("q.question");
        }
        return $this->conditions['question'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByQuestion($direction = 'asc')
    {
        if ($direction) {
            $this->order["question"] = $direction;
        } else {
            unset($this->order["question"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getType()
    {
        if (!isset($this->conditions['type'])) {
            $this->conditions["type"] = new Conditions\IntCondition("q.type");
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
     * @param QuestionConditions $conditions
     * @param string $mode
     * @return $this
     */
    public function addConditions(QuestionConditions $conditions, $mode = 'AND')
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
