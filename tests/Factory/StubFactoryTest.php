<?php
declare(strict_types=1);

namespace Tests\Factory;

use Moka\Exception\InvalidArgumentException;
use function Moka\Factory\buildStubs;
use Moka\Factory\StubFactory;
use Moka\Stub\StubInterface;
use PHPUnit\Framework\TestCase;

class StubFactoryTest extends TestCase
{
    public function testSuccess(): void
    {
        $array = [
            'methodName' => true,
            '$propertyName' => false
        ];

        $stubs = buildStubs($array);

        $this->assertNotEmpty($stubs);
        $this->containsOnlyInstancesOf(StubInterface::class);
        $this->assertCount(\count($array), $stubs);
    }

    public function testFailure(): void
    {
        $this->expectException(InvalidArgumentException::class);

        buildStubs([
            true => false
        ]);
    }
}
