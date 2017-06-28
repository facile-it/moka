<?php
declare(strict_types=1);

namespace Tests;

abstract class AbstractTestClass implements TestInterface
{
    public function isTrue(): bool
    {
        return true;
    }

    public function getInt(): int
    {
        return 1;
    }

    public function getSelf(): TestInterface
    {
        return $this;
    }

    public function something()
    {

    }

    public function withArgument(int $argument): int
    {
        return $argument;
    }

    public function withArguments(int $argument = 1, string &$aCapo = PHP_EOL, FooTestClass $fooTestClass = null, array $array = [1], ...$otherParameters): int
    {
        return $argument;
    }

    public function throwException()
    {
    }
}
