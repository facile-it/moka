<?php
declare(strict_types=1);

namespace Tests\Strategy;

class FakeMockingStrategy extends IncompleteMockingStrategy
{
    public function __construct()
    {
        self::checkDependencies('FooBar' . mt_rand(), 'php:^1.0');
    }
}
