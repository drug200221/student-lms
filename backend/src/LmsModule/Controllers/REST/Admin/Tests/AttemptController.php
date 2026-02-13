<?php

declare(strict_types=1);

namespace Psk\LmsModule\Controllers\REST\Admin\Tests;

use Ox3a\Annotation\Route;
use Ox3a\Common\Service\RequestService;
use Psk\LmsModule\Services\REST\Admin\Tests\AttemptService;
use Psk\RestModule\AbstractRestController;

/**
 * @internal
 * TODO: ДОБАВИТЬ ПРОВЕРКУ ПРАВ И АВТОРИЗАЦИИ
 * @Route("/lms/api/v1/admin/courses/tests/attempts", name="lms.Admin.Courses.Tests.Attempts")
 */
final class AttemptController extends AbstractRestController
{
    /** @var AttemptService */
    protected $attemptService;

    /** @var RequestService */
    protected $requestService;

    public function __construct(
        AttemptService $attemptService,
        RequestService $requestService
    )
    {
        $this->attemptService = $attemptService;
        $this->requestService = $requestService;
    }

    public function getService(): AttemptService
    {
        return $this->attemptService;
    }
}
