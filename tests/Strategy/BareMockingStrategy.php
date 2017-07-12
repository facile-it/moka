<?php
declare(strict_types=1);

namespace Tests\Strategy;

class BareMockingStrategy extends IncompleteMockingStrategy
{
    public function __construct()
    {
        $this->setMockType(\stdClass::class);
    }
}
