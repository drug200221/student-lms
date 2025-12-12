<?php

declare(strict_types=1);

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

/**
 * Class M251030082629CreateLmsTables
 */
final class M251030082629CreateLmsTables implements RevertibleMigrationInterface
{
    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function up(MigrationBuilder $b): void
    {
        $b->createTable('lms_courses', [
            'id'            => 'INT        UNSIGNED AUTO_INCREMENT PRIMARY KEY         COMMENT "Id курса(учебника)"',
            'title'         => 'TEXT                NOT NULL                           COMMENT "Название курса(учебника)"',
            'description'   => 'TEXT                    NULL                           COMMENT "Описание курса(учебника)"',
            'base_id'       => 'INT        UNSIGNED NOT NULL                           COMMENT "Id базы (spec_baza_id, cdpo_progs_baza_id, avto_baza_id)"',
            'type'          => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT 1                 COMMENT "Тип курса(учебника) [1 - студенты, 2 - ЦДПО, 3 - автошкола]"',
            'created_at'    => 'DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "Дата создания"',
            'fill_progress' => 'INT        UNSIGNED NOT NULL DEFAULT 0                 COMMENT "Заполненность учебника в % (есть ли тесты, темы из ктп и т.д.) *пока не планируется дальнейшее использование"',
        ], 'COMMENT "Курсы(учебники)"');

        $b->createTable('lms_contents', [
            'id'         => 'INT        UNSIGNED AUTO_INCREMENT PRIMARY KEY         COMMENT "Id материала"',
            'course_id'  => 'INT        UNSIGNED NOT NULL                           COMMENT "Id курса(учебника)"',
            'title'      => 'TEXT                NOT NULL                           COMMENT "Заголовок материала"',
            'content'    => 'TEXT                    NULL                           COMMENT "Контент"',
            'path'       => 'TEXT                    NULL                           COMMENT "Директория для хранения файлов"',
            'parent_id'  => 'INT        UNSIGNED NOT NULL DEFAULT 0                 COMMENT "Id родительского материала"',
            'created_at' => 'DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "Дата создания"',
            'updated_at' => 'DATETIME                NULL                           COMMENT "Дата изменения"',
            'revision'   => 'INT        UNSIGNED NOT NULL DEFAULT 0                 COMMENT "Кол-во правок"',
            'type'       => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT 1                 COMMENT "1 - текст,\n2 - папка,\n3 - веб-ссылка\n*Используется ли???"',
            'tree_level' => 'INT        UNSIGNED NOT NULL DEFAULT 0                 COMMENT "Уровень вложенности"',
            'tree_left'  => 'INT        UNSIGNED NOT NULL DEFAULT 0                 COMMENT "Левая граница"',
            'tree_right' => 'INT        UNSIGNED NOT NULL DEFAULT 0                 COMMENT "Правая граница"',
            'tree_order' => 'INT        UNSIGNED NOT NULL DEFAULT 0                 COMMENT "Сортировка в ветке"',
        ], 'COMMENT "Контент курсов(учебников)"');

        $b->createIndex('lms_courses', 'lms_courses_course_id', ['course_id']);

        $b->createTable('lms_tests', [
            'id'                       => 'INT      UNSIGNED AUTO_INCREMENT PRIMARY KEY         COMMENT "Id теста"',
            'course_id'                => 'INT      UNSIGNED NOT NULL                           COMMENT "Id курса(учебника)"',
            'category_id'              => 'INT      UNSIGNED     NULL                           COMMENT "Id категории теста"',
            'title'                    => 'TEXT              NOT NULL                           COMMENT "Название теста"',
            'description'              => 'TEXT                  NULL                           COMMENT "Описание теста"',
            'is_display_all_questions' => 'BOOLEAN           NOT NULL DEFAULT 0                 COMMENT "Отображение вопросов на странице (0 - один/ 1 - все)"',
            'is_visible_result'        => 'BOOLEAN           NOT NULL DEFAULT 0                 COMMENT "Видны ли ответы после прохождения теста (0 - нет/ 1 - да)"',
            'is_random_questions'      => 'BOOLEAN           NOT NULL DEFAULT 0                 COMMENT "Перемешивать вопросы (0 - нет/ 1 - да)"',
            'attempt_count'            => 'INT      UNSIGNED NOT NULL DEFAULT 0                 COMMENT "Количество попыток"',
            'question_count'           => 'INT      UNSIGNED NOT NULL DEFAULT 0                 COMMENT "Кол-во вопросов"',
            'time_limit'               => 'INT      UNSIGNED NOT NULL DEFAULT 0                 COMMENT "Ограничение времени (в секундах)"',
            'start_at'                 => 'DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "Дата открытия теста"',
            'end_at'                   => 'DATETIME              NULL                           COMMENT "Дата закрытия теста"',
        ], 'COMMENT "Тесты"');

        $b->createIndex('lms_tests', 'lms_tests_course_id', ['course_id']);
        $b->createIndex('lms_tests', 'lms_tests_category_id', ['category_id']);

        $b->createTable('lms_tests_categories', [
            'id'        => 'INT          UNSIGNED AUTO_INCREMENT COMMENT "Id теста" PRIMARY KEY',
            'course_id' => 'INT          UNSIGNED NOT NULL       COMMENT "Id курса(учебника)"',
            'title'     => 'VARCHAR(200)          NOT NULL       COMMENT "Название категории"',
        ], 'COMMENT "Категории тестов"');

        $b->createIndex(
            'lms_tests_categories',
            'lms_tests_categories_uniq',
            ['course_id', 'title'],
            'UNIQUE');

        $b->createTable('lms_tests_questions', [
            'id'          => 'INT        UNSIGNED AUTO_INCREMENT COMMENT "Id вопроса" PRIMARY KEY',
            'course_id'   => 'INT        UNSIGNED NOT NULL       COMMENT "Id курса(учебника)"',
            'category_id' => 'INT        UNSIGNED     NULL       COMMENT "Id категории"',
            'title'       => 'TEXT                NOT NULL       COMMENT "Название вопроса"',
            'type'        => 'TINYINT(1) UNSIGNED NOT NULL       COMMENT "1 - \"Да/Нет\",\n2 - \"Короткий ответ\",\n3 - \"Множ. выбор\",\n4 - \"Множ. ответ\",\n5 - \"Соответствие\",\n6 - \"Сортировка\""',
        ], 'COMMENT "Вопросы теста"');

        $b->createIndex('lms_tests_questions', 'lms_tests_questions_course_id', ['course_id']);
        $b->createIndex('lms_tests_questions', 'lms_tests_questions_category_id', ['category_id']);

        $b->createTable('lms_tests_questions_categories', [
            'id'        => 'INT          UNSIGNED AUTO_INCREMENT COMMENT "Id категории" PRIMARY KEY',
            'course_id' => 'INT          UNSIGNED NOT NULL       COMMENT "Id курса(учебника)"',
            'title'     => 'VARCHAR(200)          NOT NULL       COMMENT "Название категории"',
        ], 'COMMENT "Категории вопросов"');

        $b->createIndex(
            'lms_tests_questions_categories',
            'lms_tests_questions_categories_uniq',
            ['course_id', 'title'],
            'UNIQUE');

        $b->createTable('lms_tests_questions_of_tests', [
            'id'          => 'INT     UNSIGNED AUTO_INCREMENT     COMMENT "Id вопроса" PRIMARY KEY',
            'test_id'     => 'INT     UNSIGNED NOT NULL           COMMENT "Id теста"',
            'question_id' => 'INT     UNSIGNED NOT NULL           COMMENT "Id вопроса"',
            'point'       => 'INT     UNSIGNED NOT NULL DEFAULT 1 COMMENT "Балл(вес)"',
            'is_required' => 'BOOLEAN          NOT NULL DEFAULT 1 COMMENT "0 - не обязателен/ 1 - обязателен ответ"',
        ], 'COMMENT "Связь вопросов и тестов"');

        $b->createIndex(
            'lms_tests_questions_of_tests',
            'lms_tests_questions_of_tests_uniq',
            ['test_id', 'question_id'],
            'UNIQUE');

        $b->createTable('lms_tests_answers', [
            'id'          => 'INT     UNSIGNED AUTO_INCREMENT     COMMENT "Id ответа" PRIMARY KEY',
            'question_id' => 'INT     UNSIGNED NOT NULL           COMMENT "Id вопроса"',
            'title'       => 'TEXT             NOT NULL           COMMENT "Название ответа"',
            'is_correct'  => 'BOOLEAN          NOT NULL DEFAULT 0 COMMENT "0 - не верный/1 - верный"',
        ], 'COMMENT "Ответы на вопросы теста"');

        $b->createIndex('lms_tests_answers', 'lms_tests_answers_question_id', ['question_id']);

        $b->createTable('lms_tests_attempts', [
            'id'          => 'INT        UNSIGNED AUTO_INCREMENT PRIMARY KEY         COMMENT "Id попытки"',
            'user_id'     => 'INT        UNSIGNED NOT NULL                           COMMENT "Id пользователя"',
            'test_id'     => 'INT        UNSIGNED NOT NULL                           COMMENT "Id теста"',
            'start_at'    => 'DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "Время начала попытки"',
            'end_at'      => 'DATETIME                NULL                           COMMENT "Время окончания попытки"',
            'is_finished' => 'BOOLEAN             NOT NULL DEFAULT 0                 COMMENT "0 - не окончена/ 1 - окончена"',
            'grade'       => 'FLOAT(5,2) UNSIGNED NOT NULL DEFAULT 0                 COMMENT "Оценка в %"'
        ], 'COMMENT "Попытки прохождения теста"');

        $b->createIndex('lms_tests_attempts', 'lms_tests_attempts_user_id', ['user_id']);
        $b->createIndex('lms_tests_attempts', 'lms_tests_attempts_test_id', ['test_id']);

        $b->createTable('lms_tests_results', [
            'id'          => 'INT   UNSIGNED AUTO_INCREMENT COMMENT "Id результата" PRIMARY KEY',
            'attempt_id'  => 'INT   UNSIGNED NOT NULL       COMMENT "Id попытки"',
            'question_id' => 'INT   UNSIGNED NOT NULL       COMMENT "Id вопроса"',
            'answer_id'   => 'INT   UNSIGNED     NULL       COMMENT "Id ответа"',
            'answer_text' => 'TEXT               NULL       COMMENT "текст ответа (если текстовый ответ)"',
        ], 'COMMENT "Результаты"');

        $b->createIndex('lms_tests_results', 'lms_tests_results_attempt_id', ['attempt_id']);
        $b->createIndex('lms_tests_results', 'lms_tests_results_question_id', ['question_id']);
    }

    /**
     * @throws NotSupportedException
     * @throws InvalidConfigException
     */
    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('lms_courses');
        $b->dropTable('lms_contents');
        $b->dropTable('lms_tests');
        $b->dropTable('lms_tests_categories');
        $b->dropTable('lms_tests_questions');
        $b->dropTable('lms_tests_questions_categories');
        $b->dropTable('lms_tests_questions_of_tests');
        $b->dropTable('lms_tests_answers');
        $b->dropTable('lms_tests_attempts');
        $b->dropTable('lms_tests_results');
    }
}
