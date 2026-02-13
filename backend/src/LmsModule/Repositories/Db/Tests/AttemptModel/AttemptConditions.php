<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\Tests\AttemptModel;

use Ox3a\Core\ConditionsBuilder\Conditions;

/**
 * Class AttemptConditions
 * @package Psk\LmsModule\Repositories\Db\Tests\AttemptModel
 */
class AttemptConditions
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
     *          userId?: Conditions\IntCondition,
     *          firstName?: Conditions\StringCondition,
     *          lastName?: Conditions\StringCondition,
     *          middleName?: Conditions\StringCondition,
     *          testId?: Conditions\IntCondition,
     *          startAt?: Conditions\DateTimeCondition,
     *          endAt?: Conditions\DateTimeCondition,
     *          isFinished?: Conditions\BoolCondition,
     *          grade?: Conditions\FloatCondition,
     *      }
     */
    private $conditions = [];

    /**
     * Список дополнительных условий
     * @var array{AttemptConditions, string}[]
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
    public function getUserId()
    {
        if (!isset($this->conditions['userId'])) {
            $this->conditions["userId"] = new Conditions\IntCondition("a.user_id");
        }
        return $this->conditions['userId'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByUserId($direction = 'asc')
    {
        if ($direction) {
            $this->order["userId"] = $direction;
        } else {
            unset($this->order["userId"]);
        }
        return $this;
    }

    /**
     * @return Conditions\StringCondition
     */
    public function getFirstName()
    {
        if (!isset($this->conditions['firstName'])) {
            $this->conditions["firstName"] = new Conditions\StringCondition("u.ima");
        }
        return $this->conditions['firstName'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByFirstName($direction = 'asc')
    {
        if ($direction) {
            $this->order["firstName"] = $direction;
        } else {
            unset($this->order["firstName"]);
        }
        return $this;
    }

    /**
     * @return Conditions\StringCondition
     */
    public function getLastName()
    {
        if (!isset($this->conditions['lastName'])) {
            $this->conditions["lastName"] = new Conditions\StringCondition("u.fam");
        }
        return $this->conditions['lastName'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByLastName($direction = 'asc')
    {
        if ($direction) {
            $this->order["lastName"] = $direction;
        } else {
            unset($this->order["lastName"]);
        }
        return $this;
    }

    /**
     * @return Conditions\StringCondition
     */
    public function getMiddleName()
    {
        if (!isset($this->conditions['middleName'])) {
            $this->conditions["middleName"] = new Conditions\StringCondition("u.otc");
        }
        return $this->conditions['middleName'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByMiddleName($direction = 'asc')
    {
        if ($direction) {
            $this->order["middleName"] = $direction;
        } else {
            unset($this->order["middleName"]);
        }
        return $this;
    }

    /**
     * @return Conditions\IntCondition
     */
    public function getTestId()
    {
        if (!isset($this->conditions['testId'])) {
            $this->conditions["testId"] = new Conditions\IntCondition("a.test_id");
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
     * @return Conditions\DateTimeCondition
     */
    public function getStartAt()
    {
        if (!isset($this->conditions['startAt'])) {
            $this->conditions["startAt"] = new Conditions\DateTimeCondition("a.start_at");
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
            $this->conditions["endAt"] = new Conditions\DateTimeCondition("a.end_at");
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
     * @return Conditions\BoolCondition
     */
    public function getIsFinished()
    {
        if (!isset($this->conditions['isFinished'])) {
            $this->conditions["isFinished"] = new Conditions\BoolCondition("a.is_finished");
        }
        return $this->conditions['isFinished'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByIsFinished($direction = 'asc')
    {
        if ($direction) {
            $this->order["isFinished"] = $direction;
        } else {
            unset($this->order["isFinished"]);
        }
        return $this;
    }

    /**
     * @return Conditions\FloatCondition
     */
    public function getGrade()
    {
        if (!isset($this->conditions['grade'])) {
            $this->conditions["grade"] = new Conditions\FloatCondition("a.grade");
        }
        return $this->conditions['grade'];
    }

    /**
     * @param non-empty-string $direction
     * @return $this
     */
    public function orderByGrade($direction = 'asc')
    {
        if ($direction) {
            $this->order["grade"] = $direction;
        } else {
            unset($this->order["grade"]);
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
     * @param AttemptConditions $conditions
     * @param string $mode
     * @return $this
     */
    public function addConditions(AttemptConditions $conditions, $mode = 'AND')
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
