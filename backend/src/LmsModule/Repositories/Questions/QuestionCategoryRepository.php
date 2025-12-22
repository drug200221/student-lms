<?php

declare(strict_types=1);

namespace Psk\LmsModule\Repositories\Questions;

use Ox3a\Common\Service\ShareServiceInterface;
use Psk\LmsModule\Models\Questions\QuestionCategoryModel;
use Psk\LmsModule\Repositories\Db\Questions\QuestionCategoryModel\QuestionCategoryConditions;
use Psk\LmsModule\Repositories\Db\Questions\QuestionCategoryModel\QuestionCategoryMapper;

/**
 * @internal
 */
final class QuestionCategoryRepository implements ShareServiceInterface
{
    /** @var QuestionCategoryMapper */
    private $questionCategoryMapper;

    public function __construct(QuestionCategoryMapper $questionCategoryMapper)
    {
        $this->questionCategoryMapper = $questionCategoryMapper;
    }

    /**
     * @param positive-int $id
     * @return QuestionCategoryModel[]|null
     */
    public function findById(int $id): ?QuestionCategoryModel
    {
        $condition = new QuestionCategoryConditions();

        $condition->getId()->equal($id);

        $list = $this->questionCategoryMapper->findBy($condition);

        return $list ? $list[0] : null;
    }

    /**
     * @param positive-int $courseId
     * @return QuestionCategoryModel[]
     */
    public function findByCourseId(int $courseId): array
    {
        $condition = new QuestionCategoryConditions();

        $condition->getCourseId()->equal($courseId);

        return $this->questionCategoryMapper->findBy($condition);
    }

    /**
     * @param QuestionCategoryModel $questionCategory
     * @return void
     * @throws \ReflectionException
     */
    public function save(QuestionCategoryModel $questionCategory): void
    {
        $this->questionCategoryMapper->save($questionCategory);
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->questionCategoryMapper->delete($id);
    }
}
