<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\Tests\QuestionOfTestModel;

use Ox3a\Core\ConditionsBuilder\Conditions;

/**
 * Class QuestionOfTestConditions
 * @package Psk\LmsModule\Repositories\Db\Tests\QuestionOfTestModel
 */
class QuestionOfTestConditions
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
     *          testId?: Conditions\IntCondition,
     *          questionId?: Conditions\IntCondition,
     *          point?: Conditions\IntCondition,
     *          isRequired?: Conditions\BoolCondition,
     *          question?: Conditions\StringCondition,
     *          type?: Conditions\IntCondition,
     *          category?: Conditions\StringCondition,
     *      }
     */
    private $conditions = [];

    /**
     * Список дополнительных условий
     * @var array{QuestionOfTestConditions, string}[]
     */
    private $extraConditions = [];

    /**
     * @return Conditions\IntCondition
     */
    public function getId()
    {
        if (!isset($this->conditions['id'])) {
            $this->conditions["id"] = new Conditions\IntCondition("qt.id");
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
    public function getTestId()
    {
        if (!isset($this->conditions['testId'])) {
            $this->conditions["testId"] = new Conditions\IntCondition("qt.test_id");
        }
        return $this->conditions['testId'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByTestId($direction = 'asc')
    {
        if ($direction) {
            $this->order["testId"] = $direction;
        } else {
            unset($this->order["testId"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getQuestionId()
    {
        if (!isset($this->conditions['questionId'])) {
            $this->conditions["questionId"] = new Conditions\IntCondition("qt.question_id");
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
    public function getPoint()
    {
        if (!isset($this->conditions['point'])) {
            $this->conditions["point"] = new Conditions\IntCondition("qt.point");
        }
        return $this->conditions['point'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByPoint($direction = 'asc')
    {
        if ($direction) {
            $this->order["point"] = $direction;
        } else {
            unset($this->order["point"]);
        }
        return $this;
    }

    /**
     * @return Conditions\BoolCondition
     */
    public function getIsRequired()
    {
        if (!isset($this->conditions['isRequired'])) {
            $this->conditions["isRequired"] = new Conditions\BoolCondition("qt.is_required");
        }
        return $this->conditions['isRequired'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByIsRequired($direction = 'asc')
    {
        if ($direction) {
            $this->order["isRequired"] = $direction;
        } else {
            unset($this->order["isRequired"]);
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
     * @return Conditions\StringCondition
     */
    public function getCategory()
    {
        if (!isset($this->conditions['category'])) {
            $this->conditions["category"] = new Conditions\StringCondition("tc.title");
        }
        return $this->conditions['category'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByCategory($direction = 'asc')
    {
        if ($direction) {
            $this->order["category"] = $direction;
        } else {
            unset($this->order["category"]);
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
     * @param QuestionOfTestConditions $conditions
     * @param string $mode
     * @return $this
     */
    public function addConditions(QuestionOfTestConditions $conditions, $mode = 'AND')
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
