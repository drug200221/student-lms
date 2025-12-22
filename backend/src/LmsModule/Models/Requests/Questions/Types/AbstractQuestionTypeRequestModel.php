<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Requests\Questions\Types;

/**
 * @internal
 */
abstract class AbstractQuestionTypeRequestModel
{
    /** @var positive-int */
    public $courseId;

    /** @var positive-int */
    public $categoryId;

    /** @var string */
    public $question;

    /** @var positive-int */
    public $type;
}
