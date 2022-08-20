<?php

namespace Devengine\RequestQueryBuilder\Exceptions;

use JetBrains\PhpStorm\Pure;
use RuntimeException;

class FieldOrderException extends RuntimeException
{
    #[Pure]
    public static function invalidOrderDirection(string $direction): static
    {
        return new static("An invalid order direction '$direction' provided.");
    }
}