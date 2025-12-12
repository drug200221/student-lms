<?php

declare(strict_types=1);

namespace Psk\LmsModule\Services\REST\User;

use Psk\RestModule\RestServiceInterface;
use Psk\RestModule\Results\AbstractResult;
use Psk\RestModule\Results\ForbiddenResult;
use Psk\RestModule\Results\NotFoundResult;
use Psk\RestModule\Results\SuccessResult;

/**
 * @internal
 */
final class ContentService implements RestServiceInterface
{
    /** @var \Psk\LmsModule\Services\REST\Admin\ContentService */
    private $adminContentService;
    public function __construct(\Psk\LmsModule\Services\REST\Admin\ContentService  $adminContentService)
    {
        $this->adminContentService = $adminContentService;
    }

    /**
     * @param array<string,mixed> $params
     * @return AbstractResult
     */
    public function find($params): AbstractResult
    {
        return $this->adminContentService->find($params);
    }

    /**
     * @param positive-int $id
     * @return NotFoundResult|SuccessResult
     */
    public function get($id): AbstractResult
    {
        return $this->adminContentService->get($id);
    }

    /**
     * @param array<string,mixed> $data
     * @return ForbiddenResult
     */
    public function create($data): ForbiddenResult
    {
        return new ForbiddenResult();
    }

    /**
     * @param positive-int $id
     * @param array<string,mixed> $data
     * @return ForbiddenResult
     */
    public function update($id, $data): ForbiddenResult
    {
        return new ForbiddenResult();
    }

    /**
     * @param positive-int $id
     * @return ForbiddenResult
     */
    public function delete($id): ForbiddenResult
    {
        return new ForbiddenResult();
    }
}
