<?php

declare(strict_types=1);

namespace Psk\LmsModule\Controllers\REST\Admin\Tests;

use Ox3a\Annotation\Route;
use Ox3a\Common\Service\RequestService;
use Psk\LmsModule\Services\REST\Admin\Tests\TestService;
use Psk\RestModule\AbstractRestController;

/**
 * @internal
 * TODO: ДОБАВИТЬ ПРОВЕРКУ ПРАВ И АВТОРИЗАЦИИ
 * @Route("/lms/api/v1/admin/courses/tests", name="lms.Admin.Courses.Tests")
 */
final class TestController extends AbstractRestController
{
    /** @var TestService */
    protected $testService;

    /** @var RequestService */
    protected $requestService;

    public function __construct(
        TestService $testService,
        RequestService $requestService
    )
    {
        $this->testService = $testService;
        $this->requestService = $requestService;
    }

    public function getService(): TestService
    {
        return $this->testService;
    }
}
