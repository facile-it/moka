<?php
declare(strict_types=1);

namespace Tests\Stub;

use Moka\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

abstract class StubTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $fqcn;

    public function testConstructFailure()
    {
        $fqcn = $this->fqcn;

        $this->expectException(InvalidArgumentException::class);

        new $fqcn('0a', true);
    }

    protected function setStubType(string $fqcn)
    {
        $this->fqcn = $fqcn;
    }
}
