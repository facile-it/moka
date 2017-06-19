<?php
declare(strict_types=1);

namespace Tests;

class TestClass
{
    public function isValid(): bool
    {
        return true;
    }

    public function getInt(): int
    {
        return 1;
    }

    public function throwException()
    {
    }

    public function getSelf(): self
    {
        return $this;
    }
}
