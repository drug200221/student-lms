<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Requests\Questions\Types;

use Ox3a\Annotation\Form;
use Psk\MessengerModule\Annotations\RecordExistsValidator;

/**
 * @internal
 * @Form\Attribute(name="action", value="")
 */
final class TrueOrFalseRequestModel extends AbstractQuestionTypeRequestModel
{
    /**
     * @Form\Element("hidden")
     * @Form\Attribute(true, name="required")
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Validator(@RecordExistsValidator(table="lms_courses", field="id"))
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var positive-int
     */
    public $courseId;

    /**
     * @Form\Element("select", label="Категория")
     * @Form\Attribute("categories", name="options")
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var positive-int
     */
    public $categoryId;

    /**
     * @Form\Element(label="Вопрос", placeholder="Введите вопрос")
     * @Form\Attribute(false, name="escapeAttr")
     * @Form\Attribute(true, name="required")
     * @Form\Filter(@Form\Filter\HtmlEntitiesFilter())
     * @Form\Filter(@Form\Filter\TrimFilter())
     * @var string
     */
    public $question;

    /**
     * @Form\Element("hidden")
     * @Form\Attribute(true, name="required")
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var positive-int
     */
    public $type;

    /**
     * @Form\Element(type="checkbox", label="Верный")
     * @Form\Option(true, name="continueIfEmpty")
     * @Form\Filter(@Form\Filter\ToBoolFilter(type="511"))
     * @var bool
     */
    public $isCorrect;
}
