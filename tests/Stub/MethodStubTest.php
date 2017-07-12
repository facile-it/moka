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

        $this->setStubType(MethodStub::class);
    }

    public function testConstructMethodSuccess()
    {
        $fqcn = $this->fqcn;

        $stub = new $fqcn('name', 1138);

        $this->assertInstanceOf(StubInterface::class, $stub);
    }

    public function testConstructMethodFailure()
    {
        $fqcn = $this->fqcn;

        $this->expectException(InvalidArgumentException::class);

        new $fqcn(
            sprintf(
                '%s%s',
                StubInterface::PREFIX_PROPERTY,
                'name'
            ),
            true
        );
    }

    public function testGetName()
    {
        $fqcn = $this->fqcn;

        /** @var StubInterface $stub */
        $stub = new $fqcn('name', 1138);

        $this->assertEquals('name', $stub->getName());
    }

    public function testGetValue()
    {
        $fqcn = $this->fqcn;

        /** @var StubInterface $stub */
        $stub = new $fqcn('name', 1138);

        $this->assertEquals(1138, $stub->getValue());
    }
}
