<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Requests\Questions\Types;

/**
 * @internal
 */
class MultiAnswerRequestModel extends AbstractQuestionTypeRequestModel
{
    /**
     * @var array
     */
    public $answers;
}
