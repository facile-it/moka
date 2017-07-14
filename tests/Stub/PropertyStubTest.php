<?php
declare(strict_types=1);

namespace Tests\Stub;

use Moka\Exception\InvalidArgumentException;
use Moka\Stub\PropertyStub;
use Moka\Stub\StubInterface;

class PropertyStubTest extends StubTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setStubFQCN(PropertyStub::class);
    }

    public function testConstructPropertySuccess()
    {
        $fqcn = $this->stubFQCN;

        $stub1 = new $fqcn('$name', 1138);
        $stub2 = new $fqcn('::$name', 1138);

        $this->assertInstanceOf(StubInterface::class, $stub1);
        $this->assertInstanceOf(StubInterface::class, $stub2);
    }

    public function testConstructPropertyFailure()
    {
        $fqcn = $this->stubFQCN;

        $this->expectException(InvalidArgumentException::class);

        new $fqcn('name', true);
    }

    public function testGetName()
    {
        $fqcn = $this->stubFQCN;

        /** @var StubInterface $stub */
        $stub = new $fqcn('$name', 1138);

        $this->assertEquals('name', $stub->getName());
    }

    public function testGetValue()
    {
        $fqcn = $this->stubFQCN;

        /** @var StubInterface $stub */
        $stub = new $fqcn('$name', 1138);

        $this->assertEquals(1138, $stub->getValue());
    }
}
