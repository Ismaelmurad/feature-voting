<?php

declare(strict_types=1);

namespace App\Services\Container;

class App
{
    protected static array $registry = [];

    public static function bind($key, $value): void
    {
        static::$registry[$key] = $value;
    }

    public static function get($key = null)
    {
        if (null === $key) {
            return static::$registry;
        }

        if (!array_key_exists($key, static::$registry)) {
            die("No {$key} is bound in the container");
        }
        return static::$registry[$key];
    }
}
