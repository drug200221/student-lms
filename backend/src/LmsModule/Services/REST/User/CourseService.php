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
final class CourseService implements RestServiceInterface
{
    /** @var \Psk\LmsModule\Services\REST\Admin\CourseService */
    private $adminCourseService;
    public function __construct(\Psk\LmsModule\Services\REST\Admin\CourseService $adminCourseService)
    {
        $this->adminCourseService = $adminCourseService;
    }

    /**
     * @param array<string,mixed> $params
     * @return AbstractResult
     */
    public function find($params): AbstractResult
    {
        return $this->adminCourseService->find($params);
    }

    /**
     * @param positive-int $id
     * @return NotFoundResult|SuccessResult
     * @throws \ReflectionException
     */
    public function get($id): AbstractResult
    {
        return $this->adminCourseService->get($id);
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
