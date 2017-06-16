<?php
declare(strict_types=1);

namespace Tests;

class TestClass
{
    public function isValid(): bool
    {
        return true;
    }

    public function throwException()
    {
    }

    public function __magicMethod()
    {
    }

    final public function finalMethod()
    {
    }
}
