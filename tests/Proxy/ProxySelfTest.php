<?php
declare(strict_types=1);

namespace Tests\Proxy;

use Moka\Proxy\Proxy;
use Moka\Strategy\MockingStrategyInterface;
use Moka\Traits\MokaCleanerTrait;
use PHPUnit\Framework\TestCase;
use Tests\TestClass;
use Tests\TestTrait;

class ProxySelfTest extends TestCase
{
    use TestTrait;
    use MokaCleanerTrait;

    /**
     * @var Proxy
     */
    private $proxy;

    public function setUp()
    {
        $this->proxy = new Proxy(
            \stdClass::class,
            $this->mock(MockingStrategyInterface::class)->stub([
                    'get' => $this->mock(TestClass::class)->serve(),
                ])
                ->serve()
        );
    }

    public function testServe()
    {
        $this->assertInstanceOf(TestClass::class, $this->proxy->serve());
    }
}
