<?php

declare(strict_types=1);

namespace Psk\LmsModule\Repositories\Tests;

use Ox3a\Common\Service\ShareServiceInterface;
use Psk\LmsModule\Models\Tests\TestCategoryModel;
use Psk\LmsModule\Repositories\Db\Tests\TestCategoryModel\TestCategoryConditions;
use Psk\LmsModule\Repositories\Db\Tests\TestCategoryModel\TestCategoryMapper;

/**
 * @internal
 */
final class TestCategoryRepository implements ShareServiceInterface
{
    /** @var TestCategoryMapper */
    private $testCategoryMapper;

    public function __construct(TestCategoryMapper $testCategoryMapper)
    {
        $this->testCategoryMapper = $testCategoryMapper;
    }

    /**
     * @param positive-int $id
     * @return TestCategoryModel[]|null
     */
    public function findById(int $id): ?TestCategoryModel
    {
        $condition = new TestCategoryConditions();

        $condition->getId()->equal($id);

        $list = $this->testCategoryMapper->findBy($condition);

        return $list ? $list[0] : null;
    }

    /**
     * @param positive-int $courseId
     * @return TestCategoryModel[]
     */
    public function findByCourseId(int $courseId): array
    {
        $condition = new TestCategoryConditions();

        $condition->getCourseId()->equal($courseId);

        return $this->testCategoryMapper->findBy($condition);
    }

    /**
     * @param TestCategoryModel $testCategory
     * @return void
     * @throws \ReflectionException
     */
    public function save(TestCategoryModel $testCategory): void
    {
        $this->testCategoryMapper->save($testCategory);
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->testCategoryMapper->delete($id);
    }
}
