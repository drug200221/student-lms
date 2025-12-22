<?php

declare(strict_types=1);

namespace Psk\LmsModule\Controllers\REST\Admin\Questions;

use Ox3a\Annotation\Route;
use Ox3a\Common\Service\RequestService;
use Psk\LmsModule\Services\REST\Admin\Questions\QuestionService;
use Psk\RestModule\AbstractRestController;

/**
 * @internal
 * TODO: ДОБАВИТЬ ПРОВЕРКУ ПРАВ И АВТОРИЗАЦИИ
 * @Route("/lms/api/v1/admin/courses/questions", name="lms.Admin.Courses.Questions")
 */
final class QuestionController extends AbstractRestController
{
    /** @var QuestionService */
    protected $questionService;

    /** @var RequestService */
    protected $requestService;

    public function __construct(
        QuestionService $questionService,
        RequestService  $requestService
    )
    {
        $this->questionService = $questionService;
        $this->requestService  = $requestService;
    }

    public function getService(): QuestionService
    {
        return $this->questionService;
    }
}
