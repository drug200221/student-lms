<?php

declare(strict_types=1);

namespace Psk\LmsModule\Repositories\Tests;

use Ox3a\Common\Service\ShareServiceInterface;
use Ox3a\Service\DbService;
use Psk\LmsModule\Models\Tests\TestModel;
use Psk\LmsModule\Repositories\Db\Tests\TestModel\TestConditions;
use Psk\LmsModule\Repositories\Db\Tests\TestModel\TestMapper;

/**
 * @internal
 */
final class TestRepository implements ShareServiceInterface
{
    /** @var TestMapper */
    private $testMapper;

    /** @var DbService */
    private $dbService;

    public function __construct(
        TestMapper $testMapper,
        DbService  $dbService
    )
    {
        $this->testMapper = $testMapper;
        $this->dbService = $dbService;
    }

    /**
     * @param positive-int $id
     * @return TestModel|null
     */
    public function findById(int $id): ?TestModel
    {
        $conditions = new TestConditions();
        $conditions->getId()->equal($id);

        $list = $this->testMapper->findBy($conditions);

        return $list ? $list[0] : null;
    }

    /**
     * @param positive-int $courseId
     * @return TestModel[]
     */
    public function findByCourseId(int $courseId): array
    {
        $conditions = new TestConditions();
        $conditions->getCourseId()->equal($courseId);
        $conditions->orderByTitle();
        $conditions->orderByStartAt();

        return $this->testMapper->findBy($conditions);
    }

    /**
     * @param positive-int $categoryId
     * @return TestModel[]
     */
    public function findByCategoryId(int $categoryId): array
    {
        $conditions = new TestConditions();
        $conditions->getCategoryId()->equal($categoryId);
        $conditions->orderByTitle();
        $conditions->orderByStartAt();

        return $this->testMapper->findBy($conditions);
    }

    /**
     * @param TestModel $test
     * @return void
     * @throws \ReflectionException
     */
    public function save(TestModel $test): void
    {
        $this->testMapper->save($test);
    }

    /**
     * @param positive-int $testId
     * @return void
     */
    public function delete(int $testId): void
    {
        $this->dbService->query("DELETE FROM lms_tests_attempts WHERE test_id = ?", [$testId]);

        $this->dbService->query("DELETE FROM lms_tests_questions_of_tests WHERE test_id = ?", [$testId]);

        $this->testMapper->delete($testId);
    }
}
