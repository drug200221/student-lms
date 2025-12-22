<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Requests\Questions;

use Ox3a\Annotation\Form;
use Psk\MessengerModule\Annotations\NoRecordExistsValidator;

/**
 * @internal
 * @Form\Attribute(name="action", value="")
 */
class QuestionCategoryRequestModel
{
    /**
     * @Form\Element("hidden")
     * @Form\Attribute(true, name="required")
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Validator(@NoRecordExistsValidator(select="courseId"))
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var positive-int
     */
    public $courseId;

    /**
     * @Form\Element(label="Вопрос", placeholder="Введите названиие категории")
     * @Form\Attribute(false, name="escapeAttr")
     * @Form\Attribute(true, name="required")
     * @Form\Filter(@Form\Filter\HtmlEntitiesFilter())
     * @Form\Filter(@Form\Filter\TrimFilter())
     * @var string
     */
    public $title;
}
