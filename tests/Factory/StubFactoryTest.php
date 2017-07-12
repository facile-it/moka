<?php
declare(strict_types=1);

namespace Tests\Factory;

use Moka\Exception\InvalidArgumentException;
use Moka\Factory\StubFactory;
use Moka\Stub\StubInterface;
use PHPUnit\Framework\TestCase;

class StubFactoryTest extends TestCase
{
    public function testSuccess()
    {
        $array = [
            'methodName' => true,
            '$propertyName' => false
        ];

        $stubs = StubFactory::fromArray($array);

        $this->assertNotEmpty($stubs);
        $this->containsOnlyInstancesOf(StubInterface::class);
        $this->assertCount(count($array), $stubs);
    }

    public function testFailure()
    {
        $this->expectException(InvalidArgumentException::class);

        StubFactory::fromArray([
            true => false
        ]);
    }
}
