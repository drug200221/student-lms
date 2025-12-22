<?php

declare(strict_types=1);

namespace Psk\LmsModule\Models\Requests\Questions\Types;

/**
 * @internal
 */
final class MultipleChoiceRequestModel extends MultiAnswerRequestModel
{
    /**
     * @var positive-int
     */
    public $correct;
}
