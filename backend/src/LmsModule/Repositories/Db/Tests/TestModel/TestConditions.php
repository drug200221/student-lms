<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\Tests\TestModel;

use Ox3a\Core\ConditionsBuilder\Conditions;

/**
 * Class TestConditions
 * @package Psk\LmsModule\Repositories\Db\Tests\TestModel
 */
class TestConditions
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
     *          title?: Conditions\StringCondition,
     *          description?: Conditions\StringCondition,
     *          isDisplayAllQuestions?: Conditions\BoolCondition,
     *          isVisibleResult?: Conditions\BoolCondition,
     *          isRandomQuestion?: Conditions\BoolCondition,
     *          attemptCount?: Conditions\IntCondition,
     *          questionCount?: Conditions\IntCondition,
     *          timeLimit?: Conditions\IntCondition,
     *          startAt?: Conditions\DateTimeCondition,
     *          endAt?: Conditions\DateTimeCondition,
     *      }
     */
    private $conditions = [];

    /**
     * Список дополнительных условий
     * @var array{TestConditions, string}[]
     */
    private $extraConditions = [];

    /**
     * @return Conditions\IntCondition
     */
    public function getId()
    {
        if (!isset($this->conditions['id'])) {
            $this->conditions["id"] = new Conditions\IntCondition("t.id");
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
            $this->conditions["courseId"] = new Conditions\IntCondition("t.course_id");
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
            $this->conditions["categoryId"] = new Conditions\IntCondition("t.category_id");
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
    public function getTitle()
    {
        if (!isset($this->conditions['title'])) {
            $this->conditions["title"] = new Conditions\StringCondition("t.title");
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
            $this->conditions["description"] = new Conditions\StringCondition("t.description");
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
     * @return Conditions\BoolCondition
     */
    public function getIsDisplayAllQuestions()
    {
        if (!isset($this->conditions['isDisplayAllQuestions'])) {
            $this->conditions["isDisplayAllQuestions"] = new Conditions\BoolCondition("t.is_display_all_questions");
        }
        return $this->conditions['isDisplayAllQuestions'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByIsDisplayAllQuestions($direction = 'asc')
    {
        if ($direction) {
            $this->order["isDisplayAllQuestions"] = $direction;
        } else {
            unset($this->order["isDisplayAllQuestions"]);
        }
        return $this;
    }

    /**
     * @return Conditions\BoolCondition
     */
    public function getIsVisibleResult()
    {
        if (!isset($this->conditions['isVisibleResult'])) {
            $this->conditions["isVisibleResult"] = new Conditions\BoolCondition("t.is_visible_result");
        }
        return $this->conditions['isVisibleResult'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByIsVisibleResult($direction = 'asc')
    {
        if ($direction) {
            $this->order["isVisibleResult"] = $direction;
        } else {
            unset($this->order["isVisibleResult"]);
        }
        return $this;
    }

    /**
     * @return Conditions\BoolCondition
     */
    public function getIsRandomQuestion()
    {
        if (!isset($this->conditions['isRandomQuestion'])) {
            $this->conditions["isRandomQuestion"] = new Conditions\BoolCondition("t.is_random_questions");
        }
        return $this->conditions['isRandomQuestion'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByIsRandomQuestion($direction = 'asc')
    {
        if ($direction) {
            $this->order["isRandomQuestion"] = $direction;
        } else {
            unset($this->order["isRandomQuestion"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getAttemptCount()
    {
        if (!isset($this->conditions['attemptCount'])) {
            $this->conditions["attemptCount"] = new Conditions\IntCondition("t.attempt_count");
        }
        return $this->conditions['attemptCount'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByAttemptCount($direction = 'asc')
    {
        if ($direction) {
            $this->order["attemptCount"] = $direction;
        } else {
            unset($this->order["attemptCount"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getQuestionCount()
    {
        if (!isset($this->conditions['questionCount'])) {
            $this->conditions["questionCount"] = new Conditions\IntCondition("t.question_count");
        }
        return $this->conditions['questionCount'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByQuestionCount($direction = 'asc')
    {
        if ($direction) {
            $this->order["questionCount"] = $direction;
        } else {
            unset($this->order["questionCount"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getTimeLimit()
    {
        if (!isset($this->conditions['timeLimit'])) {
            $this->conditions["timeLimit"] = new Conditions\IntCondition("t.time_limit");
        }
        return $this->conditions['timeLimit'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByTimeLimit($direction = 'asc')
    {
        if ($direction) {
            $this->order["timeLimit"] = $direction;
        } else {
            unset($this->order["timeLimit"]);
        }
        return $this;
    }

    /**
     * @return Conditions\DateTimeCondition
     */
    public function getStartAt()
    {
        if (!isset($this->conditions['startAt'])) {
            $this->conditions["startAt"] = new Conditions\DateTimeCondition("t.start_at");
        }
        return $this->conditions['startAt'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByStartAt($direction = 'asc')
    {
        if ($direction) {
            $this->order["startAt"] = $direction;
        } else {
            unset($this->order["startAt"]);
        }
        return $this;
    }

    /**
     * @return Conditions\DateTimeCondition
     */
    public function getEndAt()
    {
        if (!isset($this->conditions['endAt'])) {
            $this->conditions["endAt"] = new Conditions\DateTimeCondition("t.end_at");
        }
        return $this->conditions['endAt'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByEndAt($direction = 'asc')
    {
        if ($direction) {
            $this->order["endAt"] = $direction;
        } else {
            unset($this->order["endAt"]);
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
     * @param TestConditions $conditions
     * @param string $mode
     * @return $this
     */
    public function addConditions(TestConditions $conditions, $mode = 'AND')
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
