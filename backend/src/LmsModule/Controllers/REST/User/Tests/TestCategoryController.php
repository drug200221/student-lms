<?php

declare(strict_types=1);

namespace Psk\LmsModule\Controllers\REST\User\Tests;

use Ox3a\Annotation\Route;
use Ox3a\Common\Service\RequestService;
use Psk\LmsModule\Services\REST\User\Tests\TestCategoryService;
use Psk\RestModule\AbstractRestController;

/**
 * @internal
 * TODO: ДОБАВИТЬ ПРОВЕРКУ ПРАВ И АВТОРИЗАЦИИ
 * @Route("/lms/api/v1/courses/test-categories", name="lms.Courses.TestCategories")
 */
final class TestCategoryController extends AbstractRestController
{
    /** @var TestCategoryService */
    protected $testCategoryService;

    /** @var RequestService */
    protected $requestService;

    public function __construct(
        TestCategoryService $testCategoryService,
        RequestService      $requestService
    )
    {
        $this->testCategoryService = $testCategoryService;
        $this->requestService      = $requestService;
    }

    public function getService(): TestCategoryService
    {
        return $this->testCategoryService;
    }
}
