<?php
declare(strict_types=1);

namespace Tests;

abstract class AbstractTestClass implements TestInterface
{
    public $public;

    protected $protected;

    private $private;

    public $isTrue;

    public static $getInt;

    public function isTrue(): bool
    {
        return true;
    }

    public function getInt(): int
    {
        return 11;
    }

    public function getSelf(): TestInterface
    {
        return $this;
    }

    public function getCallable(): callable
    {
        return function () {
        };
    }

    public function withArgument(int $argument): int
    {
        return $argument;
    }

    public function withArguments(
        int $required,
        $nullable = null,
        string &$byReference = PHP_EOL,
        FooTestClass $class = null,
        array $array = [3],
        callable $callable = null,
        ...$variadic
    ): int {
        return $required;
    }

    public function throwException()
    {
    }
}
