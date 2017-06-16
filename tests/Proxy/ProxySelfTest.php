<?php
declare(strict_types=1);

namespace Tests\Proxy;

use Moka\Exception\MockNotCreatedException;
use Moka\Exception\MockNotServedException;
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
            $this->mock(MockingStrategyInterface::class)->serve()
        );
    }

    public function testServeSuccess()
    {
        $this->mock(MockingStrategyInterface::class)->stub([
            'get' => $this->mock(TestClass::class)->serve()
        ]);

        $this->assertInstanceOf(TestClass::class, $this->proxy->serve());
    }

    public function testServeFailure()
    {
        $this->mock(MockingStrategyInterface::class)->stub([
            'get' => new MockNotCreatedException()
        ]);

        $this->expectException(MockNotServedException::class);
        $this->proxy->serve();
    }
}
