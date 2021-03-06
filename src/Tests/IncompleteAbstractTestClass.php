<?php
declare(strict_types=1);

namespace Moka\Tests;

abstract class IncompleteAbstractTestClass implements TestInterface
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

    public function & getReference(): array
    {
        return [];
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

    abstract public function abstractMethod();
}
