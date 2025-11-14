<?php

declare(strict_types=1);

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

/**
 * Class M251029081939CommonProcedures
 */
final class M251029081939CommonProcedures implements RevertibleMigrationInterface
{
    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function up(MigrationBuilder $b): void
    {
        $b->execute("
CREATE  PROCEDURE rebuildNestedSetTreeV2(IN tableName text, IN orderField text, IN idField text, IN parentField text)
BEGIN

    SET orderField := IFNULL(orderField, 'id');
    SET idField := IFNULL(idField, 'id');
    SET parentField := IFNULL(parentField, 'parent_id');


    -- Изначально сбрасываем все границы
    SET @prepareSql := CONCAT('UPDATE ', tableName, ' SET `tree_level` = 0, `tree_left` = 0, `tree_right` = 0');
    PREPARE stmt1 FROM @prepareSql;
    EXECUTE stmt1;


--   -- Устанавливаем границы корневым элементам
    SET @i := 0;
    SET @prepareSql := CONCAT('UPDATE ', tableName ,'
      SET `tree_left` = (@i := @i + 1), `tree_right` = (@i := @i + 1)
      WHERE `', parentField, '` = 0
      ORDER BY `tree_order`, `', orderField, '`');
    PREPARE stmt1 FROM @prepareSql;
    EXECUTE stmt1;

    SET @parentRight := NULL;

    -- Находим элемент с минимальной правой границей - самый левый в дереве
    SET @prepareSql := CONCAT('SELECT tp.`', idField, '`, tp.`tree_right`
      FROM `', tableName, '` AS tp
      JOIN `', tableName, '` AS tc ON (tc.`', parentField, '` = tp.`', idField, '`)
      WHERE tc.`tree_left` = 0 AND tp.`tree_right` <> 0
      ORDER BY tp.`tree_right`, tp.`', orderField, '` LIMIT 1 INTO @parentId, @parentRight');
    PREPARE stmtLeft FROM @prepareSql;

    -- Вычисляем максимальную правую границу текущего ряда
    SET @prepareSql := CONCAT('SELECT @currentLeft + COUNT(*) * 2 FROM `', tableName, '` WHERE `', parentField, '` = @parentId INTO @parentRight');
    PREPARE stmtCalcRight FROM @prepareSql;

    -- Обновляем правые границы всех элементов, которые правее
    SET @prepareSql := CONCAT('UPDATE `', tableName, '` SET `tree_right` = `tree_right` + @currentLength WHERE `tree_right` >= @currentLeft ORDER BY `tree_right`');
    PREPARE stmtUpdateRight FROM @prepareSql;

    -- Обновляем левые границы всех элементов, которые правее
    SET @prepareSql := CONCAT('UPDATE `', tableName, '` SET `tree_left` = `tree_left` + @currentLength WHERE `tree_left` > @currentLeft ORDER BY `tree_left`');
    PREPARE stmtUpdateLeft FROM @prepareSql;

    -- Обновляем левые границы всех элементов, которые правее
    SET @prepareSql := CONCAT('UPDATE `', tableName, '` SET `tree_left` = (@i := @i + 1), `tree_right` = (@i := @i + 1) WHERE `', parentField, '` = @parentId ORDER BY `tree_order`, `', orderField, '`');
    PREPARE stmtUpdateCurrent FROM @prepareSql;

    forever: LOOP

        SET @parentId := NULL;
        -- Находим элемент с минимальной правой границей - самый левый в дереве
        EXECUTE stmtLeft;

        -- Выходим из бесконечности, когда у нас уже нет незаполненных элементов
        IF @parentId IS NULL THEN
            LEAVE forever;
        END IF;

        -- Сохраняем левую границу текущего ряда
        SET @currentLeft := @parentRight;

        -- Вычисляем максимальную правую границу текущего ряда
        EXECUTE stmtCalcRight;

        -- Вычисляем длину текущего ряда
        SET @currentLength := @parentRight - @currentLeft;

        -- Обновляем правые границы всех элементов, которые правее
        EXECUTE stmtUpdateRight;

        -- Обновляем левые границы всех элементов, которые правее
        EXECUTE stmtUpdateLeft;

        -- И только сейчас обновляем границы текущего ряда
        SET @i := @currentLeft - 1;
        EXECUTE stmtUpdateCurrent;
    END LOOP;


    -- Дальше заполняем поля level
    -- Устанавливаем 1-й уровень всем корневым категориям классификатора
    SET @prepareSql = CONCAT('UPDATE `', tableName, '` SET `tree_level` = 1 WHERE `', parentField, '` = 0');
    PREPARE stmt1 FROM @prepareSql;
    EXECUTE stmt1;

    SET @prepareSql = CONCAT('UPDATE
      `', tableName, '` top,
      `', tableName, '` bottom
      SET bottom.`tree_level` = top.`tree_level` + 1
      WHERE bottom.`tree_level` = 0 AND top.`tree_level` <> 0 AND top.`', idField, '` = bottom.`', parentField, '`');
    PREPARE stmtUpdateLevel FROM @prepareSql;

    SET @prepareSql = CONCAT('SELECT COUNT(*) FROM `', tableName, '` WHERE `tree_level` = 0 LIMIT 1 INTO @unprocessedRowsCount');
    PREPARE stmtSelectCount FROM @prepareSql;

    SET @unprocessedLoop := 0;
    SET @unprocessedRowsCount := 1;
    WHILE @unprocessedRowsCount > 0 AND @unprocessedLoop = 0 DO
            SET @unprocessedRowsCount2 := @unprocessedRowsCount;
            EXECUTE stmtUpdateLevel;
            EXECUTE stmtSelectCount;
            IF (@unprocessedRowsCount2 = @unprocessedRowsCount) THEN
                SET @unprocessedLoop := @unprocessedLoop + 1;
            END IF;
        END WHILE;

END
        ");
        $b->execute("
CREATE  PROCEDURE rebuildNestedSetTree(IN tableName TEXT, IN orderField TEXT)
BEGIN
    CALL rebuildNestedSetTreeV2(tableName, orderField, NULL, NULL);
END
        ");
    }

    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function down(MigrationBuilder $b): void
    {
        $b->execute("DROP PROCEDURE IF EXISTS `rebuildNestedSetTreeV2`");
        $b->execute("DROP PROCEDURE IF EXISTS `rebuildNestedSetTree`");
    }
}
