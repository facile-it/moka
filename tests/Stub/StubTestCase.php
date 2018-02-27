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
    protected $stubFQCN;

    public function testConstructFailure(): void
    {
        $fqcn = $this->stubFQCN;

        $this->expectException(InvalidArgumentException::class);

        new $fqcn('0a', true);
    }

    protected function setStubFQCN(string $fqcn): void
    {
        $this->stubFQCN = $fqcn;
    }
}
