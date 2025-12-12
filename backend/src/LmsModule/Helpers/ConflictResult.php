<?php

declare(strict_types=1);

namespace Psk\LmsModule\Helpers;

use Psk\RestModule\Results\AbstractResult;

/**
 * @internal
 */
final class ConflictResult extends AbstractResult
{
    public function __construct($data)
    {
        $this->data = [
            'success' => false,
            'result'  => $data,
        ];
    }

    public function getStatusCode(): int
    {
        return 409;
    }
}
