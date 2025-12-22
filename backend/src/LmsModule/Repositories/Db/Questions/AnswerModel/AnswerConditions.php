<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\Questions\AnswerModel;

use Ox3a\Core\ConditionsBuilder\Conditions;

/**
 * Class AnswerConditions
 * @package Psk\LmsModule\Repositories\Db\Questions\AnswerModel
 */
class AnswerConditions
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
     *          questionId?: Conditions\IntCondition,
     *          answer?: Conditions\StringCondition,
     *          isCorrect?: Conditions\BoolCondition,
     *      }
     */
    private $conditions = [];

    /**
     * Список дополнительных условий
     * @var array{AnswerConditions, string}[]
     */
    private $extraConditions = [];

    /**
     * @return Conditions\IntCondition
     */
    public function getId()
    {
        if (!isset($this->conditions['id'])) {
            $this->conditions["id"] = new Conditions\IntCondition("a.id");
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
    public function getQuestionId()
    {
        if (!isset($this->conditions['questionId'])) {
            $this->conditions["questionId"] = new Conditions\IntCondition("a.question_id");
        }
        return $this->conditions['questionId'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByQuestionId($direction = 'asc')
    {
        if ($direction) {
            $this->order["questionId"] = $direction;
        } else {
            unset($this->order["questionId"]);
        }
        return $this;
    }

    /**
     * @return Conditions\StringCondition
     */
    public function getAnswer()
    {
        if (!isset($this->conditions['answer'])) {
            $this->conditions["answer"] = new Conditions\StringCondition("a.answer");
        }
        return $this->conditions['answer'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByAnswer($direction = 'asc')
    {
        if ($direction) {
            $this->order["answer"] = $direction;
        } else {
            unset($this->order["answer"]);
        }
        return $this;
    }

    /**
     * @return Conditions\BoolCondition
     */
    public function getIsCorrect()
    {
        if (!isset($this->conditions['isCorrect'])) {
            $this->conditions["isCorrect"] = new Conditions\BoolCondition("a.is_correct");
        }
        return $this->conditions['isCorrect'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByIsCorrect($direction = 'asc')
    {
        if ($direction) {
            $this->order["isCorrect"] = $direction;
        } else {
            unset($this->order["isCorrect"]);
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
     * @param AnswerConditions $conditions
     * @param string $mode
     * @return $this
     */
    public function addConditions(AnswerConditions $conditions, $mode = 'AND')
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
