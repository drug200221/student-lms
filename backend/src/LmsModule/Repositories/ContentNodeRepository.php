<?php

declare(strict_types=1);

namespace Psk\LmsModule\Repositories;

use Ox3a\Common\Service\ShareServiceInterface;
use Psk\LmsModule\Models\ContentNodeModel;
use Psk\LmsModule\Repositories\Db\ContentNodeModel\ContentNodeConditions;
use Psk\LmsModule\Repositories\Db\ContentNodeModel\ContentNodeMapper;

/**
 * @internal
 */
final class ContentNodeRepository implements ShareServiceInterface
{
    /** @var ContentNodeMapper */
    private $contentNodeMapper;

    public function __construct(ContentNodeMapper $contentNodeMapper)
    {
        $this->contentNodeMapper = $contentNodeMapper;
    }

    /**
     * @param positive-int $courseId
     * @return ContentNodeModel[]
     */
    public function findByCourseId(int $courseId): array
    {
        $condition = new ContentNodeConditions();

        $condition->getCourseId()->equal($courseId);
        $condition->orderByParentId();

        $list = $this->contentNodeMapper->findBy($condition);

        $parents = [0 => new ContentNodeModel()];

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
}
