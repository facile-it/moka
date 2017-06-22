<?php
declare(strict_types=1);

namespace Tests\Strategy;

use Moka\Strategy\AbstractMockingStrategy;
use Moka\Stub\Stub;

class IncompleteMockingStrategy extends AbstractMockingStrategy
{
    /*
     * Missing call to setMockType().
     */

    protected function doBuild(string $fqcn)
    {
    }

    protected function doDecorate($mock, Stub $stub)
    {
    }

    protected function doGet($mock)
    {
        return $mock;
    }
}
