<?php

declare(strict_types=1);

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

/**
 * Class M251029130137CreateFiles
 */
final class M251029130137CreateFiles implements RevertibleMigrationInterface
{
    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function up(MigrationBuilder $b): void
    {
        $b->createTable(
            'files',
            [
                'file_id' => 'int NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'name' => 'text NOT NULL',
                'type' => 'text NOT NULL',
                'data_up' => 'datetime NOT NULL',
                'key' => 'varchar(40) NOT NULL',
                'dir_id' => "int NOT NULL DEFAULT '1' COMMENT 'id папки размещения файлов'",
            ]
        );
        $b->createIndex('files', 'files_key_index', 'key');

        $b->createTable(
            'files_dirs',
            [
                'id'=> "int NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'id папки'",
                'path'=> "char(255) NOT NULL UNIQUE COMMENT 'полный путь до папки'",
            ]
        );
    }

    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('files');
        $b->dropTable('files_dirs');
    }
}
