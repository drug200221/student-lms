<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Requests\Tests;

use Ox3a\Annotation\Form;
use Psk\MessengerModule\Annotations\RecordExistsValidator;
/**
 * @internal
 * @Form\Attribute(name="action", value="")
 */
class TestCategoryRequestModel
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
     * @Form\Element(label="Вопрос", placeholder="Введите вопрос")
     * @Form\Attribute(false, name="escapeAttr")
     * @Form\Attribute(true, name="required")
     * @Form\Filter(@Form\Filter\HtmlEntitiesFilter())
     * @Form\Filter(@Form\Filter\TrimFilter())
     * @var string
     */
    public $title;
}
