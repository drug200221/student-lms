<?php

namespace Psk\LmsModule\Services\REST\User\Tests;

use Psk\RestModule\RestServiceInterface;
use Psk\RestModule\Results\AbstractResult;
use Psk\RestModule\Results\ForbiddenResult;
use Psk\RestModule\Results\NotFoundResult;
use Psk\RestModule\Results\SuccessResult;

/**
 * @internal
 */
final class TestService implements RestServiceInterface
{
    /** @var \Psk\LmsModule\Services\REST\Admin\Tests\TestService  */
    private $adminTestService;

    public function __construct(\Psk\LmsModule\Services\REST\Admin\Tests\TestService $adminTestService)
    {
        $this->adminTestService = $adminTestService;
    }

    /**
     * @param array<string,mixed> $params
     * @return NotFoundResult|SuccessResult
     */
    public function find($params): AbstractResult
    {
        return $this->adminTestService->find($params);
    }

    /**
     * @param positive-int $id
     * @return NotFoundResult|SuccessResult
     */
    public function get($id): AbstractResult
    {
        return $this->adminTestService->get($id);
    }

    /**
     * @param array<string,mixed> $data
     * @return ForbiddenResult
     */
    public function create($data): AbstractResult
    {
        return new ForbiddenResult();
    }

    /**
     * @param positive-int $id
     * @param array<string,mixed> $data
     * @return ForbiddenResult
     */
    public function update($id, $data): AbstractResult
    {
        return new ForbiddenResult();
    }

    /**
     * @param positive-int $id
     * @return ForbiddenResult
     */
    public function delete($id): AbstractResult
    {
        return new ForbiddenResult();
    }
}
