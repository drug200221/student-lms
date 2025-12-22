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
    private const SEPARATOR = '|__SEP_ANSWER__|'; // сепаратор для ответов
    private const LIMIT = 5000;

    /** @var Connection|null */
    private static $externalConnection;

    private static function getExternalDbConnection(): Connection
    {
        if (self::$externalConnection) {
            return self::$externalConnection;
        }
        
        $dsnHub = new Dsn(
            'mysql',
            '192.168.100.100',
            getenv('DATABASE_NAME'),
            '3306',
            ['charset' => 'utf8mb4']
        );
        
        self::$externalConnection =  new Connection(
            new Driver(
                $dsnHub->asString(),
                'user',
                'user'
            ),
            new SchemaCache(new ArrayCache())
        );

        return self::$externalConnection;
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws NotSupportedException
     */
    public function up(MigrationBuilder $b): void
    {
        self::getExternalDbConnection()->open();
        $b->getDb()->open();

        $transaction = $b->getDb()->beginTransaction();
        $externalTransaction = self::getExternalDbConnection()->beginTransaction();

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
        } catch (Throwable $e) {
            $transaction->rollBack();
            throw $e;
        } finally {
            $externalTransaction->rollBack(); // отменяем все изменения внесенные в бд ХАБа

            self::getExternalDbConnection()->close();
            $b->getDb()->close();
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

        $select = "SELECT course_id, title, NULLIF(TRIM(description), '') AS description, cat_id, 
           IF(type_course = 'stud', 1, 
              IF(type_course = 'cdpo', 2, 
                 IF(type_course = 'avto', 3, 1)
              )
           ) AS type,
           created_date, state 
           FROM AT_courses WHERE course_id > :lastId ORDER BY course_id";

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

        $columnMap = [
            'id'         => 'content_id',
            'course_id'  => 'course_id',
            'title'      => 'title',
            'content'    => 'text',
            'path'       => 'path',
            'parent_id'  => 'content_parent_id',
            'created_at' => 'release_date',
            'updated_at' => 'last_modified',
            'revision'   => 'revision',
            'type'       => 'content_type',
            'tree_level' => 'zero_value',
            'tree_left'  => 'zero_value',
            'tree_right' => 'zero_value',
            'tree_order' => 'zero_value',
        ];

        $courseIds = self::getExternalDbConnection()->createCommand("SELECT DISTINCT course_id FROM AT_content")->queryColumn();

        foreach ($courseIds as $courseId) {
            $select = "SELECT content_id, course_id, title, text, NULLIF(TRIM(content_path), '') AS path, content_parent_id, release_date, last_modified, revision, content_type, 0 as zero_value 
                            FROM AT_content WHERE course_id = :course_id";

            $rows =  self::getExternalDbConnection()->createCommand($select)->bindValue(':course_id', $courseId)->queryAll();

            $insertData = [];
            foreach ($rows as $row) {
                $data = [];
                foreach ($columnMap as $i => $s) {
                    $data[$i] = $row[$s];
                }
                $insertData[] = $data;
            }

            $counter = 1;
            self::rebuildNestedSetTree($insertData, 0, 1, $counter);

            $b->batchInsert('lms_contents', array_keys($columnMap), $insertData);
        }
    }

    private static function rebuildNestedSetTree(array &$contents, int $parentId, int $level, int &$counter): void
    {
        foreach ($contents as &$node) {
            if ((int)$node['parent_id'] === $parentId) {
                $node['tree_left'] = $counter++;
                $node['tree_level'] = $level;

                self::rebuildNestedSetTree($contents, (int) $node['id'], $level + 1, $counter);

                $node['tree_right'] = $counter++;
            }
        }
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws NotSupportedException
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importTestsCategories(MigrationBuilder $b): void
    {
        $select = "SELECT MIN(test_category_id) as id, course_id,  title FROM AT_tests_categories GROUP BY course_id, title HAVING MIN(test_category_id) > :lastId ORDER BY id";

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

        self::getExternalDbConnection()->createCommand(
            "UPDATE AT_tests t SET t.test_category_id = null WHERE t.test_category_id <= 0")->execute(); // категории меньше положительного делаем null

        $select = "SELECT test_id, course_id, test_category_id, title, description, display, result_release, random, num_takes, num_questions, 0 as `zero`, start_date, end_date 
                    FROM AT_tests WHERE test_id > :lastId ORDER BY test_id";

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
            'time_limit'               => 'zero',
            'start_at'                 => 'start_date',
            'end_at'                   => 'end_date',
        ];

        self::importData($select, 'lms_tests', $columnMap, $b);
    }

    /**
     * @param MigrationBuilder $b
     * @throws InvalidConfigException
     * @throws NotSupportedException
     * @throws Throwable
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importQuestionsCategories(MigrationBuilder $b): void
    {
        $select = "SELECT MIN(category_id) as id, course_id,  title FROM AT_tests_questions_categories GROUP BY course_id, title HAVING MIN(category_id) > :lastId ORDER BY id";

        $columnMap = [
            'id'        => 'category_id',
            'course_id' => 'course_id',
            'title'     => 'title',
        ];

        self::importData($select, 'lms_tests_questions_categories', $columnMap, $b);
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws NotSupportedException
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importQuestionsOfTests(MigrationBuilder $b): void
    {
        $select = "SELECT test_id, question_id, weight, required FROM AT_tests_questions_assoc ORDER BY test_id";

        $columnMap = [
            'test_id'     => 'test_id',
            'question_id' => 'question_id',
            'point'       => 'weight',
            'is_required' => 'required',
        ];

        self::importData($select, 'lms_tests_questions_of_tests', $columnMap, $b, true);
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importQuestionsAndAnswers(MigrationBuilder $b): void
    {
        $lastQuestionId = 0;

        do {
            $rows = self::getExternalDbConnection()
                ->createCommand("SELECT * FROM AT_tests_questions WHERE question_id > :lastQuestionId ORDER BY question_id ASC LIMIT :limit")
                ->bindValues([
                    ':lastQuestionId' => $lastQuestionId,
                    ':limit' => self::LIMIT,
                ])
                ->queryAll();

            if (empty($rows)) {
                break;
            }

            $mappingType = [
                '1' => 3, // multiChoice
                '2' => 1, // true/false
               // '3' => 2, SKIP  ShortAnswer - уже реализован
               // '4' => 8, SKIP  Likert или какой либо еще тип, подумаем
                '5' => 5, // accordance
                '6' => 6, // ordering
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
                        'question' => $row['question'],
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
                                        'answer' => $i === 0 ? 'Да' : 'Нет',
                                        'is_correct' => $row["answer_$i"],
                                    ];
                                    break;
                                case 3:
                                case 4:
                                    $answers[] = [
                                        'question_id' => $row['question_id'],
                                        'answer' => $choice,
                                        'is_correct' => $row["answer_$i"],
                                    ];
                                    break;
                                case 5:
                                    if ((int) $row["answer_$i"] !== -1) {
                                        $answers[] = [
                                            'question_id' => $row['question_id'],
                                            'answer' => $choice . self::SEPARATOR . $row['option_' . $row["answer_$i"]],
                                            'is_correct' => 1,
                                        ];
                                    } else {
                                        $answer = trim($row['option_' . $i]);
                                        if ($answer !== '') {
                                            $answers[] = [
                                                'question_id' => $row['question_id'],
                                                'answer' => 'empty' . self::SEPARATOR . $answer,
                                                'is_correct' => 1,
                                            ];
                                        }
                                        $answers[] = [
                                            'question_id' => $row['question_id'],
                                            'answer' => $choice . self::SEPARATOR . 'empty',
                                            'is_correct' => 1,
                                        ];
                                    }
                                    break;
                                case 6:
                                    $ordering[] = $choice;
                                    break;
                            }
                        }
                    }

                    if ($questionData['type'] === 6) {
                        $answers[] = [
                            'question_id' => $questionData['id'],
                            'answer' => implode(self::SEPARATOR, $ordering),
                            'is_correct' => 1,
                        ];
                    }

                    $b->batchInsert('lms_tests_answers', ['question_id', 'answer', 'is_correct'], $answers);
                }

                $lastQuestionId = $row['question_id'];
            }
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
        $select = "SELECT result_id, test_id, member_id, COALESCE(CAST(NULLIF(final_score, '') AS DECIMAL(5,2)), 0) AS grade, status, date_taken, end_time 
                FROM AT_tests_results WHERE result_id > :lastId ORDER BY result_id";

        $columnMap = [
            'id'          => 'result_id',
            'user_id'     => 'member_id',
            'test_id'     => 'test_id',
            'grade'       => 'grade',
            'is_finished' => 'status',
            'start_at'    => 'date_taken',
            'end_at'      => 'end_time',
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
        self::importNotTextResult($b);
        self::importTextResult($b);
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importNotTextResult(MigrationBuilder $b): void
    {
        $offset = 0;

        static $questionAnswerIds = [];
        $getAnswerIdsByQuestionId = static function (int $questionId, MigrationBuilder $b) use (&$questionAnswerIds)
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
                    ':limit' => self::LIMIT,
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
                $b->batchInsert('lms_tests_results', ['attempt_id', 'question_id', 'answer_id'], $results);
            }

            $offset += self::LIMIT;
            echo 'Offset: ' . $offset . PHP_EOL;
        } while (true);
    }

    /**
     * @throws InvalidConfigException
     * @throws NotSupportedException
     * @throws Throwable
     * @throws \Yiisoft\Db\Exception\Exception
     */
    private static function importTextResult(MigrationBuilder $b): void
    {
        $offset = 0;

        do {
            $rows = self::getExternalDbConnection()
                ->createCommand("SELECT result_id, a.question_id, answer, q.* FROM AT_tests_answers a
            JOIN AT_tests_questions q ON a.question_id = q.question_id
                WHERE q.type NOT IN (1, 2, 3, 4, 7) LIMIT :limit OFFSET :offset") // 1,2,7 - вопросы требующие проверки текста; 3,4 - пропускаем
                ->bindValues([
                    ':limit' => self::LIMIT,
                    ':offset' => $offset
                ])
                ->queryAll();

            if (empty($rows)) {
                break;
            }

            $results = [];
            foreach ($rows as $row) {
                $answerIndexes = explode('|', $row['answer']);

                if ($row['type'] === '6') {
                    $ordering = [];
                    for ($i = 0; $i < 10; $i++) {
                        $choice = $row["choice_$i"];

                        if (!empty($choice)) {
                            if (isset($answerIndexes[$i]) && $answerIndexes[$i]) {
                                $answer = (int)$answerIndexes[$i] === -1 ? 'empty' : $row['choice_' . $answerIndexes[$i]];
                                $ordering[] = $answer;
                            } else {
                                $ordering[] = '';
                            }
                        }
                    }
                    $results[] = [
                        'attempt_id'  => $row['result_id'],
                        'question_id' => $row['question_id'],
                        'answer_text' => implode(self::SEPARATOR, $ordering),
                    ];
                } else {
                    foreach ($answerIndexes as $i => $value) {
                        $answer = 'empty';

                        if ($value) {
                            $answer = (int)$value === -1 ? 'empty' : $row['option_' . $value];
                        }

                        $results[] = [
                            'attempt_id'  => $row['result_id'],
                            'question_id' => $row['question_id'],
                            'answer_text' => $answer . self::SEPARATOR . $row['choice_' . $i],
                        ];
                    }
                }
            }

            if (!empty($results)) {
                $b->batchInsert('lms_tests_results', ['attempt_id', 'question_id', 'answer_text'], $results);
            }

            $offset += self::LIMIT;
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
        array  $columnMap,
        MigrationBuilder $b,
        bool $setOffset = false
    ): void
    {
        $limit = self::LIMIT;
        $lastId = 0;

        do {
            if ($setOffset) {
                $rows = self::getExternalDbConnection()
                    ->createCommand("$select LIMIT :limit OFFSET :offset")
                    ->bindValues([
                        ':offset' => $lastId,
                        ':limit' => $limit,
                    ])
                    ->queryAll();
            } else {
                $rows = self::getExternalDbConnection()
                    ->createCommand("$select LIMIT :limit")
                    ->bindValues([
                        ':lastId' => $lastId,
                        ':limit' => $limit,
                    ])
                    ->queryAll();
            }

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

            unset($rows);

            $b->batchInsert($insertTable, array_keys($columnMap), $insertData);

            $setOffset ? $lastId += self::LIMIT : $lastId = $b->getDb()->getLastInsertID();
        } while (true);
    }
}
