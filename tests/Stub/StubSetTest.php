<?php
declare(strict_types=1);

namespace Stub;

use Moka\Exception\InvalidArgumentException;
use Moka\Stub\Stub;
use Moka\Stub\StubSet;

class StubSetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StubSet
     */
    private $set;

    public function setUp()
    {
        $this->set = new StubSet();
    }

    public function testAddSuccess()
    {
        $stub = $this->getMockBuilder(Stub::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->set->add($stub);

        $this->assertCount(1, $this->set);

        $this->assertContains($stub, $this->set);
    }

    public function testAddFailure()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->set->add(true);
    }
}