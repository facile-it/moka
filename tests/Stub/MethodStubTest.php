<?php
declare(strict_types=1);

namespace Tests\Stub;

use Moka\Exception\InvalidArgumentException;
use Moka\Stub\MethodStub;
use Moka\Stub\StubInterface;

class MethodStubTest extends StubTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setStubFQCN(MethodStub::class);
    }

    public function testConstructMethodSuccess()
    {
        $fqcn = $this->stubFQCN;

        $stub1 = new $fqcn('name', 1138);
        $stub2 = new $fqcn('::name', 1138);

        $this->assertInstanceOf(StubInterface::class, $stub1);
        $this->assertInstanceOf(StubInterface::class, $stub2);
    }

    public function testConstructMethodFailure()
    {
        $fqcn = $this->stubFQCN;

        $this->expectException(InvalidArgumentException::class);

        new $fqcn('$name', true);
    }

    public function testGetName()
    {
        $fqcn = $this->stubFQCN;

        /** @var StubInterface $stub */
        $stub = new $fqcn('name', 1138);

        $this->assertEquals('name', $stub->getName());
    }

    public function testGetValue()
    {
        $fqcn = $this->stubFQCN;

        /** @var StubInterface $stub */
        $stub = new $fqcn('name', 1138);

        $this->assertEquals(1138, $stub->getValue());
    }
}
