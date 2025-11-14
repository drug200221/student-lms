<?php

declare(strict_types=1);

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

/**
 * Class M251029130137CreateLmsTables
 */
final class M251029130137CreateLmsTables implements RevertibleMigrationInterface
{

    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function up(MigrationBuilder $b): void
    {
        $b->createTable('lms_courses', [
            'id'            => 'INT AUTO_INCREMENT PRIMARY KEY  COMMENT "Id курса(учебника)" ',
            'title'         => 'VARCHAR(100) NOT NULL           COMMENT "Название курса(учебника)"',
            'description'   => 'VARCHAR(255)     NULL           COMMENT "Описание курса(учебника)"',
            'base_id'       => 'INT          NOT NULL           COMMENT "Id базы (spec_baza_id, cdpo_progs_baza_id, avto_baza_id)"',
            'type'        => 'TINYINT(1)   NOT NULL DEFAULT 1 COMMENT "Тип курса(учебника) [1 - студенты, 2 - ЦДПО, 3 - автошкола]"',
            'fill_progress' => 'INT          NOT NULL DEFAULT 0 COMMENT "Заполненность учебника в % (есть ли тесты, темы из ктп и т.д.) *пока не планируется дальнейшее использование"'
        ], 'COMMENT "Курсы(учебники)"');

        $b->createTable('lms_contents', [
            'id'            => 'INT AUTO_INCREMENT PRIMARY KEY  COMMENT "Id материала"',
            'course_id'     => 'INT          NOT NULL           COMMENT "Id курса(учебника)"',
            'title'         => 'VARCHAR(100) NOT NULL           COMMENT "Заголовок материала"',
            'content'       => 'TEXT             NULL           COMMENT "Контент"',
            'content_path'  => 'VARCHAR(255)     NULL           COMMENT "Директория для хранения файлов"',
            'parent_id'     => 'INT          NOT NULL           COMMENT "Id родительского материала"',
            'date_added'    => 'DATE         NOT NULL DEFAULT (CURRENT_DATE()) COMMENT "Дата создания"',
            'date_modified' => 'DATE             NULL           COMMENT "Дата изменения"',
            'tree_level'    => 'INT          NOT NULL DEFAULT 0 COMMENT "Уровень вложенности"',
            'tree_left'     => 'INT          NOT NULL DEFAULT 0 COMMENT "Левая граница"',
            'tree_right'    => 'INT          NOT NULL DEFAULT 0 COMMENT "Правая граница"',
            'tree_order'    => 'INT          NOT NULL DEFAULT 0 COMMENT "Сортировка в ветке"',
        ], 'COMMENT "Контент курсов(учебников)"');

        $b->createTable('lms_tests_categories', [
            'id'        => 'INT AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT "Id категории"',
            'course_id' => 'INT NOT NULL COMMENT "Id курса(учебника)"',
            'title'     => 'VARCHAR(50) NOT NULL COMMENT "Название категории"',
        ], 'COMMENT "Категории вопросов"');

        $b->createIndex(
            'lms_tests_categories',
            'lms_tests_categories_uniq',
            ['course_id', 'title'],
            'UNIQUE');

        $b->createTable('lms_tests_question_categories', [
            'id'        => 'INT AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT "Id категории"',
            'course_id' => 'INT NOT NULL COMMENT "Id курса(учебника)"',
            'title'     => 'VARCHAR(50) NOT NULL COMMENT "Название категории"',
        ], 'COMMENT "Категории вопросов"');

        $b->createIndex(
            'lms_tests_question_categories',
            'lms_tests_question_categories_uniq',
            ['course_id', 'title'],
            'UNIQUE');

        $b->createTable('lms_tests', [
            'id'          => 'INT AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT "Id теста"',
            'course_id'   => 'INT NOT NULL COMMENT "Id курса(учебника)"',
            'title'       => 'VARCHAR(100) NOT NULL COMMENT "Название теста"',
            'description' => 'VARCHAR(255) NULL COMMENT "Описание теста"',
            'time_limit'  => 'INT NULL COMMENT "Ограничение времени (в секундах)"',
            'start_date'  => 'DATETIME NOT NULL COMMENT "Дата открытия теста"',
            'end_date'    => 'DATETIME NULL COMMENT "Дата закрытия теста"',
        ], 'COMMENT "Тесты"');

        $b->createTable('lms_tests_configurations', [
            'id'                       => 'INT AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT "Id конфигурации теста"',
            'test_id'                  => 'INT NOT NULL COMMENT "Id теста"',
            'count_attempt'            => 'INT NULL COMMENT "Количество попыток"',
            'is_display_all_questions' => 'BOOLEAN NOT NULL COMMENT "Отображение вопросов (0 - один вопрос на странице/ 1 - все вопросы на странице)"',
            'is_visible_result'        => 'BOOLEAN NOT NULL COMMENT "Видны ли ответы после прохождения теста (0 - нет/ 1 - да)"',
            'is_random_questions'      => 'BOOLEAN NOT NULL COMMENT "Перемешивать вопросы (0 - нет/ 1 - да)"',
            'count_questions'          => 'INT NULL COMMENT "Кол-во вопросов"',
        ], 'COMMENT "Конфигурации тестов"');

        $b->createIndex('lms_tests_configurations', 'lms_test_configurations_uniq', 'test_id', 'UNIQUE');

        $b->createTable('lms_tests_type_questions', [
            'id'          => 'INT AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT "Id типа вопроса"',
            'title'       => 'VARCHAR(50) NOT NULL COMMENT "Название типа вопроса"',
            'description' => 'VARCHAR(255) NOT NULL COMMENT "Какое действие нужно выполнить"',
        ], 'COMMENT "Типы вопросов (ед. выбор/множ. выбор и т.д)"');

        $b->batchInsert(
            'lms_tests_type_questions',
            ["id", "title", "description"],
            [
                [1, 'Да/Нет',              'Выберите да или нет:'],
                [2, 'Короткий ответ',      'Ответ:'],
                [3, 'Множественный выбор', 'Выберите один ответ:'],
                [4, 'Множественный ответ', 'Выберите один или несколько ответов:'],
                [5, 'Соответствие',        'Соотнесите значения из левого столбца с правым:'],
                [6, 'Перетащить в текст',  'Заполните пропуски в тексте перетаскивая значения из списка:'],
                [7, 'Упорядочивание',      'Расположите элементы в правильном порядке:'],
            ]
        );

        $b->createTable('lms_tests_questions', [
            'id'          => 'INT AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT "Id вопроса"',
            'course_id'   => 'INT NOT NULL COMMENT "Id курса(учебника)"',
            'category_id' => 'INT NULL COMMENT "Id категории"',
            'type_id'     => 'INT NOT NULL COMMENT "Id типа вопроса (ед. выбор/множ. выбор и т.д)"',
            'title'       => 'VARCHAR(100) NOT NULL COMMENT "Название вопроса"',
            'picture_id'  => 'INT NULL COMMENT "Id картинки ответа"',
            'point'       => 'INT NOT NULL DEFAULT 1 COMMENT "Балл"',
        ], 'COMMENT "Вопросы теста"');

        $b->createTable('lms_tests_questions_of_tests', [
            'id'          => 'INT AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT "Id вопроса"',
            'test_id'     => 'INT NOT NULL COMMENT "Id теста"',
            'question_id' => 'INT NOT NULL COMMENT "Id вопроса"',
        ], 'COMMENT "Связь вопросов и тестов"');

        $b->createIndex(
            'lms_tests_questions_of_tests',
            'lms_tests_questions_of_tests_uniq',
            ['test_id', 'question_id'],
            'UNIQUE');

        $b->createTable('lms_tests_answers', [
            'id'          => 'INT AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT "Id ответа"',
            'question_id' => 'INT NOT NULL COMMENT "Id вопроса"',
            'title'       => 'VARCHAR(255) NOT NULL COMMENT "Название ответа"',
            'picture_id'  => 'INT NULL COMMENT "Id картинки ответа"',
            'is_correct'  => 'BOOLEAN NOT NULL DEFAULT 0 COMMENT "0 - не верный/1 - верный"',
        ], 'COMMENT "Ответы на вопросы теста"');

        $b->createTable('lms_tests_attempts', [
            'id'          => 'INT AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT "Id попытки"',
            'user_id'     => 'INT NOT NULL COMMENT "Id пользователя"',
            'test_id'     => 'INT NOT NULL COMMENT "Id теста"',
            'time_start'  => 'DATETIME NOT NULL COMMENT "Время начала попытки"',
            'time_end'    => 'DATETIME NULL COMMENT "Время окончания попытки"',
            'is_finished' => 'BOOLEAN NOT NULL DEFAULT 0 COMMENT "0 - не окончена/ 1 - окончена"',
            'grade'       => 'FLOAT(5,2) NOT NULL COMMENT "Оценка в %"'
        ], 'COMMENT "Попытки прохождения теста"');

        $b->createTable('lms_tests_results', [
            'id'         => 'INT AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT "Id результата"',
            'attempt_id' => 'INT NOT NULL COMMENT "Id попытки"',
        ], 'COMMENT "Результаты"');

        $b->createTable('lms_tests_result_answers', [
            'id'          => 'INT AUTO_INCREMENT PRIMARY KEY COMMENT "Id ответа результата"',
            'result_id'   => 'INT NOT NULL COMMENT "id результата"',
            'question_id' => 'INT NOT NULL COMMENT "id вопроса"',
            'answer_id'   => 'INT NULL COMMENT "id ответа"',
            'answer_text' => 'VARCHAR(255) NULL COMMENT "текст ответа (если текстовый ответ)"',
        ], 'COMMENT "Ответы результата"');
    }

    public function down(MigrationBuilder $b): void
    {
        // TODO: Implement down() method.
    }
}
