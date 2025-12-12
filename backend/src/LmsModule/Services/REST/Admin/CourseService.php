<?php

declare(strict_types=1);

namespace Psk\LmsModule\Services\REST\Admin;

use Psk\LmsModule\Forms\Requests\CourseFormModel;
use Psk\LmsModule\Models\CourseModel;
use Psk\LmsModule\Repositories\CourseRepository;
use Psk\RestModule\RestServiceInterface;
use Psk\RestModule\Results\AbstractResult;
use Psk\RestModule\Results\InternalServerErrorResult;
use Psk\RestModule\Results\NotFoundResult;
use Psk\RestModule\Results\SuccessResult;
use Psk\RestModule\Results\ValidationErrorsResult;

/**
 * @internal
 */
final class CourseService implements RestServiceInterface
{
    /** @var CourseFormModel|null */
    private $courseForm;

    /** @var CourseRepository */
    private $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * @param array<string,mixed> $params
     * @return SuccessResult
     */
    public function find($params): SuccessResult
    {
        if (isset($params['course-ids'])) {
            $courseIds = explode(',', $params['course-ids']);
            return new SuccessResult($this->courseRepository->findByIds($courseIds));
        }
        if (isset($params['base-id'], $params['type'])) {
            return new SuccessResult($this->courseRepository->findByTitleAndBaseIdAndType(null, (int)$params['base-id'], (int)$params['type']));
        }

        return new SuccessResult($this->courseRepository->getAll());
    }

    /**
     * @param positive-int $id
     * @return NotFoundResult|SuccessResult
     * @throws \ReflectionException
     */
    public function get($id): AbstractResult
    {
        $id = (int)$id;
        $course = $this->courseRepository->findById($id, true);

        return $course ? new SuccessResult($course) : new NotFoundResult();
    }

    /**
     * @param array<string,mixed> $data
     * @return AbstractResult
     * @throws \ReflectionException
     */
    public function create($data): AbstractResult
    {
        $form = $this->getForm();
        if (!$form->setData($data)->isValid()) {
            return new ValidationErrorsResult($form->getMessages());
        }

        $request = $form->getDataModel();

        $course = $this->courseRepository->findByTitleAndBaseIdAndType(
            $request->title,
            $request->baseId,
            $request->type);

        if (!$course) {
            $course = new CourseModel();
            $course
                ->setTitle($request->title)
                ->setBaseId($request->baseId)
                ->setType($request->type)
                ->setCreatedAt(new \DateTimeImmutable());

            $this->courseRepository->save($course);
        }

        $concurrentDirectory = $_SERVER['DOCUMENT_ROOT'] . '/../uploads/courses/' . $course->getId();

        if (!is_dir($concurrentDirectory) && !mkdir($concurrentDirectory) && !is_dir($concurrentDirectory)) {
            return new InternalServerErrorResult(sprintf('Каталог "%s" не был создан', $concurrentDirectory));
        }

        return new SuccessResult($course);
    }

    /**
     * @param positive-int $id
     * @param array<string,mixed> $data
     * @return AbstractResult
     */
    public function update($id, $data): AbstractResult
    {
        $id = (int)$id;
        return new InternalServerErrorResult("Not implemented yet.");
    }

    /**
     * @param positive-int $id
     * @return AbstractResult
     */
    public function delete($id): AbstractResult
    {
        return new InternalServerErrorResult("Not implemented yet.");
    }

    /**
     * @return CourseFormModel
     */
    private function getForm(): CourseFormModel
    {
        return $this->courseForm ?: ($this->courseForm = new CourseFormModel());
    }
}
