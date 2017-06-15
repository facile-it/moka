<?php
declare(strict_types=1);

namespace Stub;

use Moka\Exception\InvalidArgumentException;
use Moka\Stub\Stub;
use Moka\Stub\StubSet;
use Moka\Traits\MokaCleanerTrait;
use Moka\Traits\MokaTrait;

class StubSetSelfTest extends \PHPUnit_Framework_TestCase
{
    use MokaTrait;
    use MokaCleanerTrait;

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
        $this->set->add($this->mock(Stub::class)->serve());

        $this->assertCount(1, $this->set);

        $this->assertContains($this->mock(Stub::class)->serve(), $this->set);
    }

    public function testAddFailure()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->set->add(true);
    }
}
