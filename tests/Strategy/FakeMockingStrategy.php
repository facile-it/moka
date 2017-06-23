<?php
declare(strict_types=1);

namespace Tests\Strategy;

use Moka\Strategy\AbstractMockingStrategy;
use Moka\Stub\Stub;

class FakeMockingStrategy extends AbstractMockingStrategy
{
    public function __construct()
    {
        self::checkDependencies('FooBar' . mt_rand(), 'php:^1.0');
    }

    protected function doBuild(string $fqcn)
    {
        // TODO: Implement doBuild() method.
    }

    protected function doDecorate($mock, Stub $stub)
    {
        // TODO: Implement doDecorate() method.
    }

    protected function doGet($mock)
    {
        // TODO: Implement doGet() method.
    }
}
