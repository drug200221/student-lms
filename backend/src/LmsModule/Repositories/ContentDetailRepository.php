<?php

declare(strict_types=1);

namespace Psk\LmsModule\Repositories;

use Ox3a\Common\Service\ShareServiceInterface;
use Psk\LmsModule\Models\ContentDetailModel;
use Psk\LmsModule\Repositories\Db\ContentDetailModel\ContentDetailConditions;
use Psk\LmsModule\Repositories\Db\ContentDetailModel\ContentDetailMapper;

/**
 * @internal
 */
final class ContentDetailRepository implements ShareServiceInterface
{
    /** @var ContentDetailMapper */
    private $contentDetailMapper;

    public function __construct(ContentDetailMapper $contentDetailMapper)
    {
        $this->contentDetailMapper = $contentDetailMapper;
    }

    /**
     * @param positive-int $id
     * @return ContentDetailModel|null
     */
    public function findById(int $id): ?ContentDetailModel
    {
        $condition = new ContentDetailConditions();

        $condition->getId()->equal($id);

        $contents = $this->contentDetailMapper->findBy($condition);

        return $contents ? $contents[0] : null;
    }

    /**
     * @param positive-int $courseId
     * @return ContentDetailModel[]
     */
    public function findByCourseId(int $courseId): array
    {
        $condition = new ContentDetailConditions();

        $condition->getCourseId()->equal($courseId);
        $condition->orderByParentId();

        $list = $this->contentDetailMapper->findBy($condition);

        $parents = [0 => new ContentDetailModel()];

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
     * @param ContentDetailModel $content
     * @return bool
     */
    public function issetChildren(ContentDetailModel $content): bool
    {
        $conditions = new ContentDetailConditions();

        $conditions->getParentId()->equal($content->getId());
        $conditions->setLimit(1);

        return (bool)$this->contentDetailMapper->findBy($conditions);
    }

    /**
     * @param ContentDetailModel $content
     * @return void
     * @throws \ReflectionException
     */
    public function save(ContentDetailModel $content): void
    {
        $this->contentDetailMapper->save($content);
    }

    /**
     * @param ContentDetailModel $content
     * @return void
     */
    public function delete(ContentDetailModel $content): void
    {
        $this->contentDetailMapper->delete($content->getId());
    }
}
