<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\Tests\ResultModel;

use Ox3a\Core\ConditionsBuilder\Conditions;

/**
 * Class ResultConditions
 * @package Psk\LmsModule\Repositories\Db\Tests\ResultModel
 */
class ResultConditions
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
     *          attemptId?: Conditions\IntCondition,
     *          questionId?: Conditions\IntCondition,
     *          questionType?: Conditions\IntCondition,
     *          question?: Conditions\IntCondition,
     *          answerId?: Conditions\IntCondition,
     *          answerText?: Conditions\StringCondition,
     *      }
     */
    private $conditions = [];

    /**
     * Список дополнительных условий
     * @var array{ResultConditions, string}[]
     */
    private $extraConditions = [];

    /**
     * @return Conditions\IntCondition
     */
    public function getId()
    {
        if (!isset($this->conditions['id'])) {
            $this->conditions["id"] = new Conditions\IntCondition("r.id");
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
    public function getAttemptId()
    {
        if (!isset($this->conditions['attemptId'])) {
            $this->conditions["attemptId"] = new Conditions\IntCondition("r.attempt_id");
        }
        return $this->conditions['attemptId'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByAttemptId($direction = 'asc')
    {
        if ($direction) {
            $this->order["attemptId"] = $direction;
        } else {
            unset($this->order["attemptId"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getQuestionId()
    {
        if (!isset($this->conditions['questionId'])) {
            $this->conditions["questionId"] = new Conditions\IntCondition("r.question_id");
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
     * @return Conditions\IntCondition
     */
    public function getQuestionType()
    {
        if (!isset($this->conditions['questionType'])) {
            $this->conditions["questionType"] = new Conditions\IntCondition("q.type");
        }
        return $this->conditions['questionType'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByQuestionType($direction = 'asc')
    {
        if ($direction) {
            $this->order["questionType"] = $direction;
        } else {
            unset($this->order["questionType"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getQuestion()
    {
        if (!isset($this->conditions['question'])) {
            $this->conditions["question"] = new Conditions\IntCondition("q.question");
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
    public function getAnswerId()
    {
        if (!isset($this->conditions['answerId'])) {
            $this->conditions["answerId"] = new Conditions\IntCondition("r.answer_id");
        }
        return $this->conditions['answerId'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByAnswerId($direction = 'asc')
    {
        if ($direction) {
            $this->order["answerId"] = $direction;
        } else {
            unset($this->order["answerId"]);
        }
        return $this;
    }

    /**
     * @return Conditions\StringCondition
     */
    public function getAnswerText()
    {
        if (!isset($this->conditions['answerText'])) {
            $this->conditions["answerText"] = new Conditions\StringCondition("r.answer_text");
        }
        return $this->conditions['answerText'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByAnswerText($direction = 'asc')
    {
        if ($direction) {
            $this->order["answerText"] = $direction;
        } else {
            unset($this->order["answerText"]);
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
     * @param ResultConditions $conditions
     * @param string $mode
     * @return $this
     */
    public function addConditions(ResultConditions $conditions, $mode = 'AND')
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
