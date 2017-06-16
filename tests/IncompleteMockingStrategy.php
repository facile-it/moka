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

    public function build(string $fqcn)
    {
    }

    public function decorate($mock, StubSet $stubs)
    {
    }

    public function get($mock)
    {
        $this->checkMockType($mock);

        return $mock;
    }
}
