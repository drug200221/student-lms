<?php

declare(strict_types=1);

namespace Psk\LmsModule\Controllers\REST\Admin\Tests;

use Ox3a\Annotation\Route;
use Ox3a\Common\Service\RequestService;
use Psk\LmsModule\Services\REST\Admin\Tests\QuestionOfTestService;
use Psk\RestModule\AbstractRestController;

/**
 * @internal
 * TODO: ДОБАВИТЬ ПРОВЕРКУ ПРАВ И АВТОРИЗАЦИИ
 * @Route("/lms/api/v1/admin/courses/tests/questions", name="lms.Admin.Courses.Tests.Questions")
 */
final class QuestionOfTestController extends AbstractRestController
{
    /** @var QuestionOfTestService */
    protected $questionOfTestService;

    /** @var RequestService */
    protected $requestService;

    public function __construct(
        QuestionOfTestService $questionOfTestService,
        RequestService        $requestService
    )
    {
        $this->questionOfTestService = $questionOfTestService;
        $this->requestService = $requestService;
    }

    public function getService(): QuestionOfTestService
    {
        return $this->questionOfTestService;
    }
}
