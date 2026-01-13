<?php

/**
 * auto generated
 */

namespace Psk\LmsModule\Repositories\Db\ContentNodeModel;

use Ox3a\Core\ConditionsBuilder\ConditionsBuilder;
use Ox3a\Service\DbServiceInterface;
use Psk\LmsModule\Models\ContentNodeModel;
use ReflectionException;
use Zend\Db\Sql\Platform\Platform;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class ContentNodeMapper
 * @package Psk\LmsModule\Repositories\Db\ContentNodeModel
 */
class ContentNodeMapper
{
    /** @var non-empty-string */
    private $table = 'lms_contents';

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
     * Найти сущности по условиям
     * @param ContentNodeConditions $conditions
     * @return list<ContentNodeModel>
     */
    public function findBy(ContentNodeConditions $conditions)
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
     * Получить селект для выборки
     * @return Select
     */
    public function getSelect()
    {
        $select = new Select();

        return $select
            ->from(
                ['cnts' => $this->table]
            )
            ->columns(
                [
                    'id' => "id",
                    'courseId' => "course_id",
                    'title' => "title",
                    'parentId' => "parent_id",
                    'type' => "type",
                    'treeLevel' => "tree_level",
                    'treeLeft' => "tree_left",
                    'treeRight' => "tree_right",
                    'treeOrder' => "tree_order",
                ]
            );
    }

    /**
     * Создать сущность
     * @param array<non-empty-string, mixed> $data
     * @return ContentNodeModel
     */
    public function createEntity($data = [])
    {
        $entity = new ContentNodeModel();

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
     * @return ContentNodeHydrator
     */
    protected function getHydrator()
    {
        return new ContentNodeHydrator();
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
