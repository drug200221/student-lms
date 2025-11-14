<?php

namespace Psk\LmsModule\Repository;

use Ox3a\Common\Service\ShareServiceInterface;
use Psk\LmsModule\Models\CourseModel;
use Psk\LmsModule\Repositories\Db\CourseModel\CourseConditions;
use Psk\LmsModule\Repositories\Db\CourseModel\CourseMapper;

class CourseRepository implements ShareServiceInterface
{
    /** @var CourseMapper */
    private $courseMapper;

    public function __construct(CourseMapper $courseMapper)
    {
        $this->courseMapper = $courseMapper;
    }

    public function findById(int $id): ?CourseModel
    {
        $condition = new CourseConditions();

        $condition->getId()->equal($id);

        $list = $this->courseMapper->findBy($condition);

        return $list ? $list[0] : null;
    }

    /**
     * @param string $title
     * @param positive-int $baseId
     * @param int<1|2|3> $type
     * @return CourseModel|null
     */
    public function findByTitleAndBaseIdAndType(string $title, int $baseId, int $type): ?CourseModel
    {
        $condition = new CourseConditions();

        $condition->getTitle()->equal($title);
        $condition->getBaseId()->equal($baseId);
        $condition->getType()->equal($type);

        $list = $this->courseMapper->findBy($condition);

        return $list ? $list[0] : null;
    }

    /**
     * @param CourseModel $courseBook
     * @return void
     * @throws \ReflectionException
     */
    public function save(CourseModel $courseBook): void
    {
        $this->courseMapper->save($courseBook);
    }
}
