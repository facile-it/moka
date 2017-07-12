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

        $this->setStubType(PropertyStub::class);
    }

    public function testConstructPropertySuccess()
    {
        $fqcn = $this->fqcn;

        $stub = new $fqcn(
            sprintf(
                '%s%s',
                StubInterface::PREFIX_PROPERTY,
                'name'
            ),
            1138);

        $this->assertInstanceOf(StubInterface::class, $stub);
    }

    public function testConstructPropertyFailure()
    {
        $fqcn = $this->fqcn;

        $this->expectException(InvalidArgumentException::class);

        new $fqcn('name', true);
    }

    public function testGetName()
    {
        $fqcn = $this->fqcn;

        /** @var StubInterface $stub */
        $stub = new $fqcn(
            sprintf(
                '%s%s',
                StubInterface::PREFIX_PROPERTY,
                'name'
            ),
            1138
        );

        $this->assertEquals('name', $stub->getName());
    }

    public function testGetValue()
    {
        $fqcn = $this->fqcn;

        /** @var StubInterface $stub */
        $stub = new $fqcn(
            sprintf(
                '%s%s',
                StubInterface::PREFIX_PROPERTY,
                'name'
            ),
            1138
        );

        $this->assertEquals(1138, $stub->getValue());
    }
}
