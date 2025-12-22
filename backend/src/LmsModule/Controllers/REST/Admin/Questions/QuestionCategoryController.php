<?php

declare(strict_types=1);

namespace Psk\LmsModule\Controllers\REST\Admin\Questions;

use Ox3a\Annotation\Route;
use Ox3a\Common\Service\RequestService;
use Psk\LmsModule\Services\REST\Admin\Questions\QuestionCategoryService;
use Psk\RestModule\AbstractRestController;

/**
 * @internal
 * TODO: ДОБАВИТЬ ПРОВЕРКУ ПРАВ И АВТОРИЗАЦИИ
 * @Route("/lms/api/v1/admin/courses/question-categories", name="lms.Admin.Courses.QuestionCategories")
 */
final class QuestionCategoryController extends AbstractRestController
{
    /** @var QuestionCategoryService */
    protected $questionCategoryService;

    /** @var RequestService */
    protected $requestService;

    public function __construct(
        QuestionCategoryService $questionCategoryService,
        RequestService          $requestService
    )
    {
        $this->questionCategoryService = $questionCategoryService;
        $this->requestService = $requestService;
    }

    public function getService(): QuestionCategoryService
    {
        return $this->questionCategoryService;
    }
}
