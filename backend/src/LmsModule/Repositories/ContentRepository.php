<?php

declare(strict_types=1);

namespace Psk\LmsModule\Repositories;

use Ox3a\Common\Service\ShareServiceInterface;
use Psk\LmsModule\Models\ContentModel;
use Psk\LmsModule\Repositories\Db\ContentModel\ContentConditions;
use Psk\LmsModule\Repositories\Db\ContentModel\ContentMapper;

/**
 * @internal
 */
final class ContentRepository implements ShareServiceInterface
{
    /** @var ContentMapper */
    private $contentMapper;

    public function __construct(ContentMapper $contentMapper)
    {
        $this->contentMapper = $contentMapper;
    }

    /**
     * @param positive-int $id
     * @return ContentModel|null
     */
    public function findById(int $id): ?ContentModel
    {
        $condition = new ContentConditions();

        $condition->getId()->equal($id);

        $contents = $this->contentMapper->findBy($condition);

        return $contents ? $contents[0] : null;
    }

    /**
     * @param positive-int $courseId
     * @return ContentModel[]
     */
    public function findByCourseId(int $courseId): array
    {
        $condition = new ContentConditions();

        $condition->getCourseId()->equal($courseId);
        $condition->orderByParentId();

        $list = $this->contentMapper->findBy($condition);

        $parents = [0 => new ContentModel()];

        if ($list) {
            foreach ($list as $item) {
                if (isset($parents[$item->getParentId()])) {
                    $parents[$item->getParentId()]->addChild($item);
                }

                $parents[$item->getId()] = $item;
            }
        }

        return $parents[0]->getChildren();
    }

    /**
     * @param ContentModel $content
     * @return bool
     */
    public function issetChildren(ContentModel $content): bool
    {
        $conditions = new ContentConditions();

        $conditions->getParentId()->equal($content->getId());
        $conditions->setLimit(1);

        return (bool)$this->contentMapper->findBy($conditions);
    }

    /**
     * @param ContentModel $content
     * @return void
     * @throws \ReflectionException
     */
    public function save(ContentModel $content): void
    {
        $this->contentMapper->save($content);
    }

    /**
     * @param ContentModel $content
     * @return void
     */
    public function delete(ContentModel $content): void
    {
        $this->contentMapper->delete($content->getId());
    }
}
