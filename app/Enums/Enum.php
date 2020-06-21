<?php

namespace App\Enums;

use phpDocumentor\Reflection\Types\Static_;

abstract class Enum
{
    abstract public static function all(): array;

    public static function getValue($key)
    {
        return static::all()[$key];
    }

    public static function getKey($value)
    {
        return array_search($value, static::all());
    }
}