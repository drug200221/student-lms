<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\Tests\ResultModel;

use Ox3a\Core\ConditionsBuilder\ConditionsBuilder;
use Ox3a\Service\DbServiceInterface;
use Psk\LmsModule\Models\Tests\ResultModel;
use ReflectionException;
use Zend\Db\Sql\Platform\Platform;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class ResultMapper
 * @package Psk\LmsModule\Repositories\Db\Tests\ResultModel
 */
class ResultMapper
{
    /** @var non-empty-string */
    private $table = 'lms_test_results';

    /** @var non-empty-string */
    private $primaryKey = 'id';

    /** @var DbServiceInterface */
    private $dbService;

    /** @var ConditionsBuilder */
    private $conditionsBuilder;

    /**
     * @param DbServiceInterface $dbService
     * @param ConditionsBuilder  $conditionsBuilder
     */
    public function __construct(DbServiceInterface $dbService, ConditionsBuilder $conditionsBuilder)
    {
        $this->dbService         = $dbService;
        $this->conditionsBuilder = $conditionsBuilder;
    }

    /**
     * Сохранить
     * @param ResultModel $entity
     * @return void
     * @throws ReflectionException
     */
    public function save(ResultModel $entity)
    {
        $hydrator = $this->getHydrator();
        $data     = $hydrator->extract($entity);

        $primaryId = $data[$this->primaryKey];

        if ($primaryId) {
            $this->getTable()->update($data, ["{$this->primaryKey}=?" => $primaryId]);
        } else {
            $this->getTable()->insert($data);
            $primaryId = $this->getTable()->getLastInsertValue();
            $hydrator->hydrate($entity, [$this->primaryKey => $primaryId]);
        }
    }

    /**
     * Найти сущности по условиям
     * @param ResultConditions $conditions
     * @return list<ResultModel>
     */
    public function findBy(ResultConditions $conditions)
    {
        $select = $this->getSelect();

        $condition = $this->conditionsBuilder->build($conditions->getConditions());
        if ($condition->count()) {
            $select->where($condition);
        }

        if (($order = $conditions->getOrder())) {
            $select->order($order);
        }

        if ($conditions->getLimit()) {
            $select->limit($conditions->getLimit());
            if ($conditions->getOffset()) {
                $select->offset($conditions->getOffset());
            }
        }

        /** @var list<array<non-empty-string, mixed>> $list */
        $list = $this->dbService->fetchAll($this->buildSql($select));
        return array_map(function ($row) {
            return $this->createEntity($row);
        }, $list);
    }

    /**
     * Удалить
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        $this->getTable()->delete([$this->primaryKey . '=?' => $id]);
    }

    /**
     * Получить селект для выборки
     * @return Select
     */
    public function getSelect()
    {
        $select = new Select();

        return $select
            ->from(
                ['r' => $this->table]
            )
            ->columns(
                [
                    'id' => "id",
                    'attemptId' => "attempt_id",
                    'questionId' => "question_id",
                    'answerId' => "answer_id",
                    'answerText' => "answer_text",
                ]
            )
            ->join(
                ['q' => 'lms_questions'],
                'r.question_id=q.id',
                [
                    'questionType' => "type",
                    'question' => "question",
                ],
                Select::JOIN_INNER
            );
    }

    /**
     * Создать сущность
     * @param array<non-empty-string, mixed> $data
     * @return ResultModel
     */
    public function createEntity($data = [])
    {
        $entity = new ResultModel();

        $this->getHydrator()->hydrate($entity, $data);

        return $entity;
    }

    /**
     * Получить таблицу
     * @return TableGateway
     */
    protected function getTable()
    {
        return $this->dbService->getTable($this->table);
    }

    /**
     * Получить имя поля первичного ключа
     * @return string
     */
    protected function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * Получить гидратор
     * @return ResultHydrator
     */
    protected function getHydrator()
    {
        return new ResultHydrator();
    }

    /**
     * Собрать SQL
     * @param Select $select
     * @return string
     */
    protected function buildSql(Select $select)
    {
        $platform = new Platform($this->dbService->getAdapter());
        $platform->setSubject($select);
        return $platform->getSqlString();
    }
}
