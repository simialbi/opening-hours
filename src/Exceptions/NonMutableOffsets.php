<?php

namespace Spatie\OpeningHours\Exceptions;

class NonMutableOffsets extends Exception
{
    public static function forClass(string $className)
    {
        return new self("Offsets of `{$className}` objects are not mutable.");
    }
}
