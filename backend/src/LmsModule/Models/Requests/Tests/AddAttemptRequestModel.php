<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Requests\Tests;

use Ox3a\Annotation\Form;
use Psk\MessengerModule\Annotations\RecordExistsValidator;

/**
 * @internal
 * @Form\Attribute(name="action", value="")
 */
class AddAttemptRequestModel
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
     * @Form\Validator(@Form\Validator\DigitsValidator())
     * @Form\Validator(@RecordExistsValidator(select="testId"))
     * @Form\Filter(@Form\Filter\ToIntFilter())
     * @var positive-int
     */
    public $testId;
}
