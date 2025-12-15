<?php

declare(strict_types=1);

namespace Psk\LmsModule\Services\REST\User\Tests;

use Psk\RestModule\RestServiceInterface;
use Psk\RestModule\Results\AbstractResult;
use Psk\RestModule\Results\ForbiddenResult;

/**
 * @internal
 */
final class TestCategoryService implements RestServiceInterface
{
    /** @var \Psk\LmsModule\Services\REST\Admin\Tests\TestCategoryService */
    private $adminCategoryService;

    public function __construct(\Psk\LmsModule\Services\REST\Admin\Tests\TestCategoryService $adminTestCategoryService)
    {
        $this->adminCategoryService = $adminTestCategoryService;
    }

    /**
     * @param array<string,mixed> $params
     * @return AbstractResult
     */
    public function find($params): AbstractResult
    {
        return $this->adminCategoryService->find($params);
    }

    /**
     * @param positive-int $id
     * @return AbstractResult
     */
    public function get($id): AbstractResult
    {
        return $this->adminCategoryService->get($id);
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
