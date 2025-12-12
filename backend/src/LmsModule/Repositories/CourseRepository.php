<?php

declare(strict_types=1);

namespace Psk\LmsModule\Repositories;

use Ox3a\Common\Service\ShareServiceInterface;
use Psk\LmsModule\Models\CourseModel;
use Psk\LmsModule\Repositories\Db\CourseModel\CourseConditions;
use Psk\LmsModule\Repositories\Db\CourseModel\CourseHydrator;
use Psk\LmsModule\Repositories\Db\CourseModel\CourseMapper;

/**
 * @internal
 */
final class CourseRepository implements ShareServiceInterface
{
    /** @var CourseMapper */
    private $courseMapper;

    /** @var ContentRepository */
    private $contentRepository;

    public function __construct(
        CourseMapper $courseMapper,
        ContentRepository $contentRepository
    ) {
        $this->courseMapper = $courseMapper;
        $this->contentRepository = $contentRepository;
    }

    /**
     * @return CourseModel[]
     */
    public function getAll(): array
    {
        $conditions = new CourseConditions();

        $conditions->orderByTitle();

        return $this->courseMapper->findBy($conditions);
    }

    /**
     * @param positive-int $id
     * @param bool $isHydrate
     * @return CourseModel|null
     * @throws \ReflectionException
     */
    public function findById(int $id, bool $isHydrate = false): ?CourseModel
    {
        $condition = new CourseConditions();

        $condition->getId()->equal($id);

        if ($list = $this->courseMapper->findBy($condition)) {
            $course = $list[0];

            if ($isHydrate) {
                $contents = $this->contentRepository->findByCourseId($course->getId());
                (new CourseHydrator())->hydrateProperty($course, 'contents', $contents);
            }

            return $course;
        }

        return null;
    }

    /**
     * @param array<int> $courseIds
     * @return CourseModel[]
     */
    public function findByIds(array $courseIds): array
    {
        $conditions = new CourseConditions();

        $conditions->getId()->in($courseIds);

        return $this->courseMapper->findBy($conditions);
    }

    /**
     * @param string|null $title
     * @param positive-int $baseId
     * @param int<1|2|3> $type
     * @return CourseModel[]
     */
    public function findByTitleAndBaseIdAndType(?string $title, int $baseId, int $type): array
    {
        $condition = new CourseConditions();

        if ($title) {
            $condition->getTitle()->equal($title);
        }
        $condition->getBaseId()->equal($baseId);
        $condition->getType()->equal($type);
        $condition->orderByTitle();

        return $this->courseMapper->findBy($condition);
    }

    /**
     * @param CourseModel $course
     * @return void
     * @throws \ReflectionException
     */
    public function save(CourseModel $course): void
    {
        $this->courseMapper->save($course);
    }
}
