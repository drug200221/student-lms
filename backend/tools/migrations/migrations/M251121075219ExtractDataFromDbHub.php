<?php

declare(strict_types=1);

use Yiisoft\Cache\ArrayCache;
use Yiisoft\Db\Cache\SchemaCache;
use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Mysql\Connection;
use Yiisoft\Db\Mysql\Driver;
use Yiisoft\Db\Mysql\Dsn;

/**
 * Class M251121075219ExtractDataFromDbHub
 */
final class M251121075219ExtractDataFromDbHub implements RevertibleMigrationInterface
{
    private const SEPARATOR = '|__SEP_ANS_AND_QUEST__|'; // сепаратор для ответов и вопросов
    private static function getExternalDbConnection(): Connection
    {
        $dsn_hub = new Dsn(
            'mysql',
            '192.168.100.100',
            getenv('DATABASE_NAME'),
            '3307',
            ['charset' => 'utf8mb4']
        );
        return new Connection(
            new Driver(
                $dsn_hub->asString(),
                'user',
                'user'
            ),
            new SchemaCache(new ArrayCache())
        );
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws NotSupportedException
     */
    public function up(MigrationBuilder $b): void
    {
        while (true) {
            echo "Раскомментировал TODO? (Y/N): ";
            $input = trim(fgets(STDIN));
            $answer = mb_strtolower($input);

            if ($answer === 'y') {
                break;
            }
            if ($answer === 'n') {
                exit("Завершение работы.\n");
            }
            echo "Пожалуйста, введите 'Y' или 'N'.\n";
        }

        $transaction = $b->getDb()->beginTransaction();
        try {
            self::importCourses($b);
            self::importContents($b);
            self::importTests($b);
            self::importTestsCategories($b);
            self::importQuestionsCategories($b);
            self::importQuestionsAndAnswers($b);
            self::importQuestionsOfTests($b);
            self::importAttempts($b);

            $transaction->commit();

            $b->getDb()->close();
        } catch (Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @param MigrationBuilder $b
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function down(MigrationBuilder $b): void
    {
        $tables = [
            'lms_contents',
            'lms_courses',
            'lms_tests',
            'lms_tests_answers',
            'lms_tests_categories',
            'lms_tests_questions_categories',
            'lms_tests_questions',
            'lms_tests_questions_of_tests',
            'lms_tests_attempts',
            'lms_tests_results',
        ];

        foreach ($tables as $table) {
            $b->truncateTable($table);
            $b->execute("ALTER TABLE {$table} AUTO_INCREMENT = 1");
        }
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws NotSupportedException
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importCourses(MigrationBuilder $b): void
    {
        self::getExternalDbConnection()
            ->createCommand("UPDATE AT_courses c SET c.release_date = 
                IF(c.release_date = '0000-00-00 00:00:00', '2015-01-01 00:00:00', c.release_date)")->execute(); // нулевые даты меняем на корректные

        $select = "SELECT course_id, title, description, cat_id, 
           IF(type_course = 'stud', 1, 
              IF(type_course = 'cdpo', 2, 
                 IF(type_course = 'avto', 3, 1)
              )
           ) AS type,
           created_date, state 
           FROM AT_courses";

        $columnMap = [
            'id'            => 'course_id',
            'title'         => 'title',
            'description'   => 'description',
            'base_id'       => 'cat_id',
            'type'          => 'type',
            'created_at'    => 'created_date',
            'fill_progress' => 'state',
        ];

        self::importData($select, 'lms_courses', $columnMap, $b);
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importContents(MigrationBuilder $b): void
    {
        self::getExternalDbConnection()->createCommand(
            "UPDATE AT_content c JOIN AT_courses ac ON c.course_id = ac.course_id
            SET c.release_date =
                CASE
                    WHEN ac.created_date != '0000-00-00 00:00:00' AND c.release_date = '0000-00-00 00:00:00' THEN ac.created_date
                    WHEN c.release_date = '0000-00-00 00:00:00' THEN '2015-01-01 00:00:00'
                END
            WHERE c.release_date = '0000-00-00 00:00:00';")->execute(); // нулевые даты меняем на корректные

        $select = "SELECT content_id, course_id, title, text, content_path, content_parent_id, release_date, last_modified, revision, content_type FROM AT_content";

        $columnMap = [
            'id'         => 'content_id',
            'course_id'  => 'course_id',
            'title'      => 'title',
            'content'    => 'text',
            'path'       => 'content_path',
            'parent_id'  => 'content_parent_id',
            'created_at' => 'release_date',
            'updated_at' => 'last_modified',
            'revision'   => 'revision',
            'type'       => 'content_type',
        ];

        self::importData($select, 'lms_contents', $columnMap, $b);

//        $b->execute("CALL rebuildNestedSetTreeV2('lms_contents', 'tree_order', 'id', 'parent_id')"); TODO: РАСКОММЕНТИРОВАТЬ *длительное время выполнения
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws NotSupportedException
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importTestsCategories(MigrationBuilder $b): void
    {
        $select = "SELECT MIN(test_category_id) as id, course_id,  title FROM AT_tests_categories GROUP BY course_id, title";

        $columnMap = [
            'id'        => 'id',
            'course_id' => 'course_id',
            'title'     => 'title',
        ];
        self::importData($select, 'lms_tests_categories', $columnMap, $b);
    }

    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     * @throws \Yiisoft\Db\Exception\Exception
     * @throws Throwable
     */
    private static function importTests(MigrationBuilder $b): void
    {
        self::getExternalDbConnection()->createCommand(
            "UPDATE AT_tests t JOIN AT_courses ac ON t.course_id = ac.course_id
            SET t.start_date =
                CASE
                    WHEN ac.created_date != '0000-00-00 00:00:00' AND t.start_date = '0000-00-00 00:00:00' THEN ac.created_date
                    WHEN t.start_date = '0000-00-00 00:00:00' THEN '2015-01-01 00:00:00'
                END
            WHERE t.start_date = '0000-00-00 00:00:00';")->execute(); // нулевые даты меняем на корректные

        $select = "SELECT test_id, course_id, test_category_id, title, description, display, result_release, random, num_takes, num_questions, null as `null`, start_date, end_date FROM AT_tests";

        $columnMap = [
            'id'                       => 'test_id',
            'course_id'                => 'course_id',
            'category_id'              => 'test_category_id',
            'title'                    => 'title',
            'description'              => 'description',
            'is_display_all_questions' => 'display',
            'is_visible_result'        => 'result_release',
            'is_random_questions'      => 'random',
            'attempt_count'            => 'num_takes',
            'question_count'           => 'num_questions',
            'time_limit'               => 'null',
            'start_at'                 => 'start_date',
            'end_at'                   => 'end_date',
        ];

        self::importData($select, 'lms_tests', $columnMap, $b);
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws NotSupportedException
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importQuestionsCategories(MigrationBuilder $b): void
    {
        $limit = 10000;
        $offset = 0;

        do {
            $groupMinCategoryIds = self::getExternalDbConnection()
                ->createCommand("SELECT MIN(category_id) as min_id, course_id, title 
                    FROM AT_tests_questions_categories GROUP BY course_id, title LIMIT :limit OFFSET :offset")
                ->bindValues([
                    ':limit' => $limit,
                    ':offset' => $offset,
                ])
                ->queryAll();

            if (empty($groupMinCategoryIds)) {
                break;
            }

            // избавляемся от дублей
            foreach ($groupMinCategoryIds as $group) {
                $minId    = $group['min_id'];
                $courseId = $group['course_id'];
                $title    = $group['title'];

                self::getExternalDbConnection()
                    ->createCommand(
                        "UPDATE AT_tests_questions_categories c SET c.category_id = :minId WHERE c.course_id = :courseId AND c.title = :title"
                    )->bindValues([
                        ':minId' => $minId,
                        ':courseId' => $courseId,
                        ':title' => $title,
                    ])->execute();

                self::getExternalDbConnection()
                    ->createCommand(
                        "UPDATE AT_tests_questions q JOIN AT_tests_questions_categories c
                        ON q.course_id = c.course_id AND q.title = c.title
                     SET q.category_id = :minId WHERE c.course_id = :courseId AND c.title = :title"
                    )->bindValues([
                        ':minId' => $minId,
                        ':courseId' => $courseId,
                        ':title' => $title,
                    ])->execute();
            }

            $select = "SELECT category_id, course_id, title FROM AT_tests_questions_categories GROUP BY course_id, title";

            $columnMap = [
                'id'        => 'category_id',
                'course_id' => 'course_id',
                'title'     => 'title',
            ];

            self::importData($select, 'lms_tests_questions_categories', $columnMap, $b);

            $offset += $limit;
        } while (true);
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws NotSupportedException
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importQuestionsOfTests(MigrationBuilder $b): void
    {
        $select = "SELECT test_id, question_id, weight, required FROM AT_tests_questions_assoc";

        $columnMap = [
            'test_id'     => 'test_id',
            'question_id' => 'question_id',
            'point'       => 'weight',
            'is_required' => 'required',
        ];

        self::importData($select, 'lms_tests_questions_of_tests', $columnMap, $b);
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importQuestionsAndAnswers(MigrationBuilder $b): void
    {
        $limit = 10000;
        $offset = 0;

        do {
            $rows = self::getExternalDbConnection()
                ->createCommand("SELECT * FROM AT_tests_questions ORDER BY question_id ASC LIMIT :limit OFFSET :offset")
                ->bindValues([
                    ':limit' => $limit,
                    ':offset' => $offset,
                ])
                ->queryAll();

            if (empty($rows)) {
                break;
            }

            $mappingType = [
                '1' => 3, // multiChoice
                '2' => 1, // true/false
//            '3' => 2, SKIP  ShortAnswer - уже реализован
//            '4' => 8, SKIP  Likert или какой либо еще тип, подумаем
                '5' => 5, // accordance
                '6' => 7, // ordering
                '7' => 4, // multiResponse
                '8' => 5, // accordance
            ];

            foreach ($rows as $row) {
                if (isset($mappingType[$row['type']])) {
                    $questionData = [
                        'id' => $row['question_id'],
                        'course_id' => $row['course_id'],
                        'category_id' => $row['category_id'] === '0' ? null : $row['category_id'],
                        'type' => $mappingType[$row['type']],
                        'title' => $row['question'],
                    ];

                    $b->insert('lms_tests_questions', $questionData);

                    $answers = [];
                    $ordering = []; // упорядоченный массив: 1 элемент|2 элемент|3 элемент
                    for ($i = 0; $i < 10; $i++) {
                        $choice = $row["choice_$i"];

                        if (($questionData['type'] === 1 && $i < 2) || ($choice !== null && trim($choice) !== '')) {
                            switch ($questionData['type']) {
                                case 1:
                                    $answers[] = [
                                        'question_id' => $row['question_id'],
                                        'title' => $i === 0 ? 'Да' : 'Нет',
                                        'is_correct' => $row["answer_$i"],
                                    ];
                                    break;
                                case 3:
                                case 4:
                                    $answers[] = [
                                        'question_id' => $row['question_id'],
                                        'title' => $choice,
                                        'is_correct' => $row["answer_$i"],
                                    ];
                                    break;
                                case 5:
                                    if ((int)$row["answer_$i"] !== -1) {
                                        $answers[] = [
                                            'question_id' => $row['question_id'],
                                            'title' => $row['option_' . $row["answer_$i"]] . self::SEPARATOR . $choice,
                                            'is_correct' => 1,
                                        ];
                                    } else {
                                        $answer = trim($row['option_' . $i]);
                                        if ($answer !== '') {
                                            $answers[] = [
                                                'question_id' => $row['question_id'],
                                                'title' => $answer . self::SEPARATOR . 'empty',
                                                'is_correct' => 1,
                                            ];
                                        }
                                        $answers[] = [
                                            'question_id' => $row['question_id'],
                                            'title' => 'empty' . self::SEPARATOR . $choice,
                                            'is_correct' => 1,
                                        ];
                                    }
                                    break;
                                case 7:
                                    $ordering[] = $choice;
                                    break;
                            }
                        }
                    }

                    if ($questionData['type'] === 7) {
                        $answers[] = [
                            'question_id' => $questionData['id'],
                            'title' => implode(self::SEPARATOR, $ordering),
                            'is_correct' => 1,
                        ];
                    }

                    $b->batchInsert('lms_tests_answers', ['question_id', 'title', 'is_correct'], $answers);
                }
            }

            $offset += $limit;
        } while (true);
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws NotSupportedException
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importAttempts(MigrationBuilder $b): void
    {
        $select = "SELECT result_id, test_id, member_id, COALESCE(NULLIF(final_score, ''), 0) AS grade, status, date_taken, end_time FROM AT_tests_results";

        $columnMap = [
            'id' => 'result_id',
            'user_id' => 'member_id',
            'test_id' => 'test_id',
            'grade' => 'grade',
            'is_finished' => 'status',
            'start_at' => 'date_taken',
            'end_at' => 'end_time',
        ];

        self::importData($select, 'lms_tests_attempts', $columnMap, $b);

        self::importResults($b);
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws NotSupportedException
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importResults(MigrationBuilder $b): void
    {
        $limit = 10000;
        $offset = 0;

        self::importNotTextResult($limit, $offset, $b);
        self::importTextResult($limit, $offset, $b);
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importNotTextResult(int $limit, int $offset, MigrationBuilder $b): void
    {
        static $questionAnswerIds = [];
        $getAnswerIdsByQuestionId = static function (int $questionId, MigrationBuilder $b) use ($questionAnswerIds)
        {
            if (isset($questionAnswerIds[$questionId])) {
                return $questionAnswerIds[$questionId];
            }

            $command = $b->getDb()
                ->createCommand("SELECT id FROM lms_tests_answers WHERE question_id = :questionId ORDER BY id ASC")
                ->bindValue(':questionId', $questionId);

            $questionAnswerIds[$questionId] = $command->queryColumn();

            return $questionAnswerIds[$questionId];
        };

        do {
            $rows = self::getExternalDbConnection()
                ->createCommand("SELECT result_id, a.question_id, answer FROM AT_tests_answers a
            JOIN AT_tests_questions q ON a.question_id = q.question_id 
                WHERE q.type IN (1, 2, 7) LIMIT :limit OFFSET :offset") // 1,2,7 - вопросы не требующие проверки текста
                ->bindValues([
                    ':limit' => $limit,
                    ':offset' => $offset
                ])
                ->queryAll();

            if (empty($rows)) {
                break;
            }

            $results = [];
            foreach ($rows as $row) {
                $questionId = (int)$row['question_id'];
                if (!isset($questionAnswerIds[$questionId])) {
                    $questionAnswerIds[$questionId] = $getAnswerIdsByQuestionId($questionId, $b);
                }
                $answerIds = $questionAnswerIds[$questionId];

                if (empty($answerIds)) {
                    continue;
                }

                foreach (explode('|', $row['answer']) as $index) {
                    $index = (int)$index;

                    if (isset($answerIds[$index])) {
                        $results[] = [
                            'attempt_id'  => $row['result_id'],
                            'question_id' => $questionId,
                            'answer_id'   => $answerIds[$index],
                        ];
                    }
                }
            }

            if (!empty($results)) {
                $columns = ['attempt_id', 'question_id', 'answer_id'];
                $b->batchInsert('lms_tests_results', $columns, $results);
            }

            $offset += $limit;
        } while (true);
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importTextResult(int $limit, int $offset, MigrationBuilder $b): void
    {
        do {
            $rows = self::getExternalDbConnection()
                ->createCommand("SELECT result_id, a.question_id, answer, q.* FROM AT_tests_answers a
            JOIN AT_tests_questions q ON a.question_id = q.question_id
                WHERE q.type NOT IN (1, 2, 3, 4, 7) LIMIT :limit OFFSET :offset") // 1,2,7 - вопросы требующие проверки текста; 3,4 - пропускаем
                ->bindValues([
                    ':limit' => $limit,
                    ':offset' => $offset
                ])
                ->queryAll();

            if (empty($rows)) {
                break;
            }

            $results = [];
            foreach ($rows as $row) {
                $answerIndexes = explode('|', $row['answer']);
                $ordering = [];
                for ($i = 0; $i < 10; $i++) {
                    $choice = $row["choice_$i"];

                    if (!empty($choice)) {
                        if ($row['type'] === '6') {
                            $ordering[] = $row['choice_' . $answerIndexes[$i]];
                            continue;
                        }
                        $results[] = [
                            'attempt_id'  => $row['result_id'],
                            'question_id' => $row['question_id'],
                            'answer_text' => $row['option_' . $answerIndexes[$i]] . self::SEPARATOR . $row['choice_' . $i],
                        ];
                    }
                }

                if (!empty($ordering)) {
                    $results[] = [
                        'attempt_id'  => $row['result_id'],
                        'question_id' => $row['question_id'],
                        'answer_text' => implode(self::SEPARATOR, $ordering),
                    ];
                }
            }

            if (!empty($results)) {
                $columns = ['attempt_id', 'question_id', 'answer_text'];
                $b->batchInsert('lms_tests_results', $columns, $results);
            }

            $offset += $limit;
        } while (true);
    }

    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     * @throws \Yiisoft\Db\Exception\Exception
     * @throws Throwable
     */
    private static function importData(
        string $select,
        string $insertTable,
        array $columnMap,
        MigrationBuilder $b
    ): void
    {
        $limit = 10000;
        $offset = 0;

        $insertColumns = [];
        foreach ($columnMap as $i => $s) {
            $insertColumns[] = $i;
        }

        do {
            $rows = self::getExternalDbConnection()
                ->createCommand("$select LIMIT :limit OFFSET :offset")
                ->bindValues([
                    ":limit"  => $limit,
                    ':offset' => $offset
                ])
                ->queryAll();

            if (empty($rows)) {
                break;
            }

            $insertData = [];

            foreach ($rows as $row) {
                $data = [];
                foreach ($columnMap as $i => $s) {
                    $data[$i] = $row[$s];
                }
                $insertData[] = $data;
            }

            $b->batchInsert($insertTable, $insertColumns, $insertData);

            $offset += $limit;
        } while (true);
    }
}
