<?php

declare(strict_types=1);

namespace Psk\LmsModule\Controllers\REST\User;

use Ox3a\Annotation\Route;
use Ox3a\Common\Service\RequestService;
use Psk\LmsModule\Services\REST\Admin\CourseService;
use Psk\RestModule\AbstractRestController;

/**
 * @internal
 * TODO: ДОБАВИТЬ ПРОВЕРКУ ПРАВ И АВТОРИЗАЦИИ
 * @Route("/lms/api/v1/courses", name="lms.Courses")
 */
final class CourseController extends AbstractRestController
{
    /** @var CourseService */
    protected $courseService;

    /** @var RequestService */
    protected $requestService;

    public function __construct(
        CourseService $courseService,
        RequestService $requestService
    )
    {
        $this->courseService = $courseService;
        $this->requestService = $requestService;
    }

    public function getService(): CourseService
    {
        return $this->courseService;
    }
}
