<?php

declare(strict_types=1);

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

/**
 * Class M251029082121CommonTables
 */
final class M251029082121CommonTables implements RevertibleMigrationInterface
{
    /**
     * @throws NotSupportedException
     * @throws InvalidConfigException
     */
    public function up(MigrationBuilder $b): void
    {
        $b->createTable(
            'users',
            [
                'id'             => 'INT AUTO_INCREMENT PRIMARY KEY',
                'pKey'           => 'VARCHAR(40) UNIQUE NOT NULL',
                'pass'           => 'BLOB        NULL',
                'pass_aes'       => 'TEXT        NOT NULL',
                'rang'           => 'VARCHAR(7)  NULL',
                'dr_user'        => 'DATETIME    NOT NULL',
                'dr'             => 'DATE        NOT NULL',
                'birthplace'     => 'TEXT        NOT NULL',
                'pol'            => 'INT         NOT NULL',
                'cat'            => 'SMALLINT    NULL',
                'user_tel'       => 'TEXT        NULL',
                'user_email'     => 'TEXT        NOT NULL',
                'rabotodatel_id' => 'INT         NULL',
                'photo'          => 'VARCHAR(40) NULL',
                'fam'            => 'TEXT        NOT NULL',
                'ima'            => 'TEXT        NOT NULL',
                'otc'            => 'TEXT        NOT NULL',
                'pasp_s'         => 'VARCHAR(10) NULL',
                'pasp_n'         => 'VARCHAR(20) NULL',
                'pasp_d'         => 'DATE        NULL',
                'pasp_kv'        => 'TEXT        NULL',
                'pasp_kp'        => 'VARCHAR(10) NULL',
            ],
        );

        $b->createTable(
            'pasport_history',
            [
                'id'      => 'INT AUTO_INCREMENT PRIMARY KEY',
                'userid'  => 'INT         NOT NULL',
                'fam'     => 'TEXT        NOT NULL',
                'ima'     => 'TEXT        NOT NULL',
                'otc'     => 'TEXT        NOT NULL',
                'pasp_s'  => 'VARCHAR(10) NOT NULL',
                'pasp_n'  => 'VARCHAR(15) NOT NULL',
                'pasp_d'  => 'DATE        NOT NULL',
                'pasp_kv' => 'TEXT        NOT NULL',
                'pasp_kp' => 'VARCHAR(7)  NOT NULL',
            ]
        );
        $b->createIndex('pasport_history', 'userid_2', ['userid', 'pasp_s', 'pasp_n'], 'UNIQUE');
        $b->createIndex('pasport_history', 'userid_3', ['userid', 'pasp_d'], 'UNIQUE');

        $b->createTable(
            'acl_tree',
            [
                'id'         => "INT AUTO_INCREMENT COMMENT 'id узла' PRIMARY KEY",
                'parent_id'  => "INT           NOT NULL COMMENT 'id родительской ветки'",
                'title'      => "CHAR(255)     NOT NULL COMMENT 'название узла'",
                'type'       => "INT           NOT NULL COMMENT 'тип узла'",
                'key'        => "CHAR(255)     NOT NULL COMMENT 'ключ узла'",
                'tree_level' => "INT DEFAULT 0 NOT NULL COMMENT 'уровень вложенности'",
                'tree_left'  => "INT DEFAULT 0 NOT NULL COMMENT 'левая граница'",
                'tree_right' => "INT DEFAULT 0 NOT NULL COMMENT 'правая граница'",
                'tree_order' => "INT DEFAULT 0 NOT NULL COMMENT 'сортировка в ветке'",
            ]
        );
        $b->addCommentOnTable('acl_tree', 'дерево прав');

        $b->createTable(
            'acl_data',
            [
                'id'        => "INT AUTO_INCREMENT COMMENT 'id узла' PRIMARY KEY",
                'item_id'   => "INT  NOT NULL COMMENT 'id объекта для кот. выставлено право'",
                'item_type' => "INT  NOT NULL COMMENT 'тип объекта: 1 - пользователь, 2 - роль'",
                'acl_id'    => "INT  NOT NULL COMMENT 'id права'",
                'value'     => "TEXT NOT NULL COMMENT 'значение'",
            ]
        );
        $b->createIndex('acl_data', 'item_type', ['item_type', 'item_id', 'acl_id'], 'UNIQUE');
        $b->addCommentOnTable('acl_data', 'выставленные права');
    }

    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('users');
        $b->dropTable('pasport_history');
        $b->dropTable('acl_tree');
        $b->dropTable('acl_data');
    }
}
