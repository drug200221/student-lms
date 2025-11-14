<?php

namespace Psk\LmsModule\Controllers\REST\Admin;

use Ox3a\Annotation\Route;
use Ox3a\Common\Service\RequestService;
use Psk\LmsModule\Services\REST\Admin\CourseService;
use Psk\RestModule\AbstractRestController;

/**
 * @Route("/lms/api/v1/admin/courses", name="lms.Admin.Courses")
 * TODO: ДОБАВИТЬ ПРОВЕРКУ ПРАВ И АВТОРИЗАЦИИ
 */
class CourseController extends AbstractRestController
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

    protected function getService(): CourseService
    {
        return $this->courseService;
    }
}
