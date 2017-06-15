<?php
declare(strict_types=1);

namespace Tests\Factory;


use Moka\Exception\InvalidArgumentException;
use Moka\Factory\StubFactory;
use Moka\Stub\Stub;
use PHPUnit\Framework\TestCase;

class StubFactoryTest extends TestCase
{
    public function testSuccess()
    {
        $array = [
            'methodName' => true
        ];

        $stubs = StubFactory::fromArray($array);

        $this->assertNotEmpty($stubs);
        $this->containsOnlyInstancesOf(Stub::class);
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
