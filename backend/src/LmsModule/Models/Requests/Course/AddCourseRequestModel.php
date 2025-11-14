<?php

namespace Psk\LmsModule\Models\Requests\Course;

use Ox3a\Annotation\Form;

/**
 * @Form\Attribute(name="action", value="")
 */
class AddCourseRequestModel
{
    /**
     * @Form\Element(label="Название", placeholder="Введите название")
     * @Form\Attribute(false, name="escapeAttr")
     * @Form\Validator("required")
     * @Form\Filter\HtmlEntitiesFilter()
     * @Form\Filter\TrimFilter()
     * @var string
     */
    public $title;

    /**
     * @Form\Element(label="База")
     * @Form\Validator("required")
     * @Form\Validator\DigitsValidator()
     * @Form\Filter\ToIntFilter()
     * @var positive-int
     */
    public $baseId;

    /**
     * @Form\Element(label="Тип")
     * @Form\Validator("required")
     * @Form\Validator\DigitsValidator()
     * @Form\Validator\GreaterThanValidator(1, inclusive=true)
     * @Form\Validator\LessThanValidator(3, inclusive=true)
     * @Form\Filter\ToIntFilter()
     * @var positive-int
     */
    public $type;
}
