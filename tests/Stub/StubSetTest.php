<?php
declare(strict_types=1);

namespace Tests\Stub;

use Moka\Exception\InvalidArgumentException;
use Moka\Stub\Stub;
use Moka\Stub\StubSet;
use PHPUnit\Framework\TestCase;

class StubSetTest extends TestCase
{
    /**
     * @var StubSet
     */
    private $set;

    protected function setUp()
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
