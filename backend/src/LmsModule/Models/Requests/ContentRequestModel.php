<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Requests;

use Ox3a\Annotation\Form;
use Psk\MessengerModule\Annotations\RecordExistsValidator;

/**
 * @internal
 * @Form\Attribute(name="action", value="")
 */
class ContentRequestModel
{
    /**
     * @Form\Element("hidden")
     * @Form\Attribute(true, name="required")
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var non-negative-int
     */
    public $courseId;

    /**
     * @Form\Element("hidden")
     * @Form\Attribute(true, name="required")
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Validator(@RecordExistsValidator())
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var non-negative-int
     */
    public $parentId;

    /**
     * @Form\Element(label="Название", placeholder="Введите название")
     * @Form\Attribute(false, name="escapeAttr")
     * @Form\Attribute(true, name="required")
     * @Form\Filter(@Form\Filter\HtmlEntitiesFilter())
     * @Form\Filter(@Form\Filter\TrimFilter())
     * @var non-empty-string
     */
    public $title;

    /**
     * @Form\Element(label="Контент")
     * @Form\Attribute(false, name="escapeAttr")
     * @Form\Filter(@Form\Filter\HtmlEntitiesFilter())
     * @Form\Filter(@Form\Filter\TrimFilter())
     * @var string
     */
    public $content;
}
