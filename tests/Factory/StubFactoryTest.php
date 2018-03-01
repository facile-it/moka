<?php
declare(strict_types=1);

namespace Tests\Factory;

use Moka\Exception\InvalidArgumentException;
use Moka\Factory\StubFactory;
use Moka\Stub\StubInterface;
use PHPUnit\Framework\TestCase;
use function Moka\Factory\createStubs;

class StubFactoryTest extends TestCase
{
    public function testSuccess(): void
    {
        $array = [
            'methodName' => true,
            '$propertyName' => false
        ];

        $stubs = createStubs($array);

        $this->assertNotEmpty($stubs);
        $this->containsOnlyInstancesOf(StubInterface::class);
        $this->assertCount(\count($array), $stubs);
    }

    public function testFailure(): void
    {
        $this->expectException(InvalidArgumentException::class);

        createStubs([
            true => false
        ]);
    }
}
