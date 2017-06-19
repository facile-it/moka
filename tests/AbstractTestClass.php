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

    public function throwException()
    {
    }
}
