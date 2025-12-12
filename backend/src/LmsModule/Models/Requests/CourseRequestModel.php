<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Requests;

use Ox3a\Annotation\Form;

/**
 * @internal
 * @Form\Attribute(name="action", value="")
 */
class CourseRequestModel
{
    /**
     * @Form\Element(label="Название", placeholder="Введите название")
     * @Form\Attribute(false, name="escapeAttr")
     * @Form\Attribute(true, name="required")
     * @Form\Filter(@Form\Filter\HtmlEntitiesFilter())
     * @Form\Filter(@Form\Filter\TrimFilter())
     * @var string
     */
    public $title;

    /**
     * @Form\Element(label="База")
     * @Form\Attribute(true, name="required")
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var positive-int
     */
    public $baseId;

    /**
     * @Form\Element(label="Тип")
     * @Form\Attribute(true, name="required")
     * @Form\Validator(@Form\Validator\GreaterThanValidator(1, inclusive=true))
     * @Form\Validator(@Form\Validator\LessThanValidator(3, inclusive=true))
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var int<1|2|3>
     */
    public $type;
}
