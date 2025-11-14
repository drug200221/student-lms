<?php

namespace Psk\LmsModule\Services\REST\Admin;

use Psk\LmsModule\Forms\Requests\Course\AddCourseFormModel;
use Psk\LmsModule\Models\CourseModel;
use Psk\LmsModule\Repository\CourseRepository;
use Psk\RestModule\RestServiceInterface;
use Psk\RestModule\Results\AbstractResult;
use Psk\RestModule\Results\InternalServerErrorResult;
use Psk\RestModule\Results\NotFoundResult;
use Psk\RestModule\Results\SuccessResult;
use Psk\RestModule\Results\ValidationErrorsResult;

class CourseService implements RestServiceInterface
{
    /** @var AddCourseFormModel */
    private $addCourseForm;

    /** @var CourseRepository */
    private $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * @inheritDoc
     */
    public function find($params)
    {
        // TODO: Implement find() method.
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        $course = $this->courseRepository->findById($id);

        return $course ? new SuccessResult($course) : new NotFoundResult();
    }

    /**
     * @param $data
     * @return AbstractResult
     * @throws \ReflectionException
     */
    public function create($data)
    {
        $form = $this->getAddCourseForm();
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
                ->setFillProgress(0);

            $this->courseRepository->save($course);
        }

        $concurrentDirectory = $_SERVER['DOCUMENT_ROOT'] . '/../uploads/courses/' . $course->getId();

        if (!is_dir($concurrentDirectory) && !mkdir($concurrentDirectory) && !is_dir($concurrentDirectory)) {
            return new InternalServerErrorResult(sprintf('Каталог "%s" не был создан', $concurrentDirectory));
        }

        return new SuccessResult($course);
    }

    /**
     * @inheritDoc
     */
    public function update($id, $data)
    {
        // TODO: Implement update() method.
    }

    /**
     * @inheritDoc
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @return AddCourseFormModel
     */
    private function getAddCourseForm(): AddCourseFormModel
    {
        return $this->addCourseForm ?: ($this->addCourseForm = new AddCourseFormModel());
    }
}
