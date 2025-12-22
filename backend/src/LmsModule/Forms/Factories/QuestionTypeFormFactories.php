<?php

declare(strict_types=1);

namespace Psk\LmsModule\Forms\Factories;

use Ox3a\Form\Model\FormModel;
use Ox3a\Service\DbService;
use Psk\LmsModule\Forms\Dynamic\QuestionTypes\AccordanceFormModel;
use Psk\LmsModule\Forms\Dynamic\QuestionTypes\MultipleChoiceFormModel;
use Psk\LmsModule\Forms\Dynamic\QuestionTypes\MultipleResponseFormModel;
use Psk\LmsModule\Forms\Dynamic\QuestionTypes\OrderingFormModel;
use Psk\LmsModule\Forms\Dynamic\QuestionTypes\ShortResponseFormModel;
use Psk\LmsModule\Forms\Requests\Questions\Types\TrueOrFalseFormModel;
use Psk\LmsModule\Models\Questions\QuestionModel;
use Psk\LmsModule\Repositories\Questions\QuestionCategoryRepository;

/**
 * @internal
 */
final class QuestionTypeFormFactories
{
    /** @var FormModel|null */
    private $form;

    /** @var array<string,mixed> */
    private $additionalFormOptions = [];

    /** @var QuestionCategoryRepository */
    public $questionCategoryRepository;

    /** @var DbService */
    public $dbService;

    public function __construct(
        QuestionCategoryRepository $questionCategoryRepository,
        DbService                  $dbService
    )
    {
        $this->questionCategoryRepository = $questionCategoryRepository;
        $this->dbService                  = $dbService;
    }

    /**
     * @param array<string,mixed> $data
     * @return FormModel
     */
    public function initializeForm(array $data): FormModel
    {
        return $this->getForm($data);
    }

    /**
     * @param array<string,mixed> $data
     * @return FormModel
     */
    private function getForm(array $data): FormModel
    {
        $courseId = $data['courseId'] ? (int)$data['courseId'] : 0;
        $type     = $data['type']     ? (int)$data['type']     : 0;

        $formModelMap = [
            QuestionModel::TRUE_OR_FALSE     => TrueOrFalseFormModel::class,
            QuestionModel::SHORT_RESPONSE      => ShortResponseFormModel::class,
            QuestionModel::MULTIPLE_CHOICE   => MultipleChoiceFormModel::class,
            QuestionModel::MULTIPLE_RESPONSE => MultipleResponseFormModel::class,
            QuestionModel::ACCORDANCE        => AccordanceFormModel::class,
            QuestionModel::ORDERING          => OrderingFormModel::class,
        ];

        if (!isset($formModelMap[$type])) {
            $this->form = null;
            throw new \RuntimeException("Неизвестный тип формы $type");
        }

        if ($this->form === null) {
            $formClass = $formModelMap[$type];
            $options = $this->getFormOptions($courseId);
            $this->form = new $formClass(null, $options);
        }

        return $this->form->setData($data);
    }

    /**
     * @param positive-int|null $courseId
     * @return array<string,mixed>|null
     */
    private function getFormOptions(int $courseId): ?array
    {
        if ($courseId === 0) {
            return null;
        }

        $categories = ['' => '-Выберите категорию-'];

        foreach ($this->questionCategoryRepository->findByCourseId($courseId) as $category) {
            $categories[$category->getId()] = $category->getTitle();
        }

        $options = $this->additionalFormOptions;
        $options['db'] = $this->dbService;
        $options['categories'] = $categories;

        return $options;
    }
}
