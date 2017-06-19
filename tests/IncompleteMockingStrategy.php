<?php
declare(strict_types=1);

namespace Tests;

use Moka\Strategy\AbstractMockingStrategy;
use Moka\Stub\StubSet;

class IncompleteMockingStrategy extends AbstractMockingStrategy
{
    /*
     * Missing call to setMockType().
     */

    protected function doBuild(string $fqcn)
    {
    }

    protected function doDecorate($mock, StubSet $stubs)
    {
    }

    protected function doGet($mock)
    {
        return $mock;
    }
}
