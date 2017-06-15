<?php
declare(strict_types=1);

namespace Tests\Proxy;

class MockFakeClass
{
    public function isValid(): bool
    {
        return true;
    }

    public function throwException()
    {
    }
}
