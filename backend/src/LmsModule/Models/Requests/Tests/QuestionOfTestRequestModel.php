<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Requests\Tests;

use Ox3a\Annotation\Form;
use Psk\MessengerModule\Annotations\NoRecordExistsValidator;
use Psk\MessengerModule\Annotations\RecordExistsValidator;

/**
 * @internal
 * @Form\Attribute(name="action", value="")
 */
class QuestionOfTestRequestModel
{
    /**
     * @Form\Element("hidden")
     * @Form\Attribute(true, name="required")
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var positive-int
     */
    public $courseId;

    /**
     * @Form\Element("hidden")
     * @Form\Attribute(true, name="required")
     * @Form\Validator(@RecordExistsValidator(select="testId"))
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var positive-int
     */
    public $testId;

    /**
     * @Form\Element("select", label="Вопрос")
     * @Form\Attribute("questions", name="options")
     * @Form\Attribute(true, name="required")
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Validator(@RecordExistsValidator(select="questionId"))
     * @Form\Validator(@NoRecordExistsValidator(select="questionOfTestIds"))
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var positive-int
     */
    public $questionId;

    /**
     * @Form\Element(label="Баллов")
     * @Form\Attribute(true, name="required")
     * @Form\Validator(@Form\Validator\LessThanValidator(10, inclusive="true"))
     * @Form\Validator(@Form\Validator\GreaterThanValidator(1, inclusive="true"))
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var non-negative-int
     */
    public $point;

    /**
     * @Form\Element(type="checkbox", label="Обязателен ответ")
     * @Form\Option(true, name="continueIfEmpty")
     * @Form\Filter(@Form\Filter\ToBoolFilter())
     * @var bool
     */
    public $isRequired;
}
