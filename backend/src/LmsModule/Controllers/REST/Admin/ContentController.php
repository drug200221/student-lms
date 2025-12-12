<?php

declare(strict_types=1);

namespace Psk\LmsModule\Controllers\REST\Admin;

use Ox3a\Annotation\Route;
use Ox3a\Common\Service\RequestService;
use Psk\LmsModule\Services\REST\Admin\ContentService;
use Psk\RestModule\AbstractRestController;

/**
 * @internal
 * TODO: ДОБАВИТЬ ПРОВЕРКУ ПРАВ И АВТОРИЗАЦИИ
 * @Route("/lms/api/v1/admin/courses/contents", name="lms.Admin.Courses.Contents")
 */
final class ContentController extends AbstractRestController
{
    /** @var ContentService */
    protected $contentService;

    /** @var RequestService */
    protected $requestService;

    public function __construct(
        ContentService $contentService,
        RequestService $requestService
    )
    {
        $this->contentService = $contentService;
        $this->requestService = $requestService;
    }

    public function getService(): ContentService
    {
        return $this->contentService;
    }
}
