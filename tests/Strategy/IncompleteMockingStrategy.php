<?php
declare(strict_types=1);

namespace Tests\Strategy;

use Moka\Strategy\AbstractMockingStrategy;
use Moka\Stub\MethodStub;

class IncompleteMockingStrategy extends AbstractMockingStrategy
{
    /*
     * Missing call to setMockType().
     */

    protected function doBuild(string $fqcn)
    {
        throw new \Exception();
    }

    protected function doDecorateWithMethod($mock, MethodStub $stub)
    {
    }

    protected function doGet($mock)
    {
        return $mock;
    }
}
