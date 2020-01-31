<?php
declare(strict_types=1);

namespace Tests;

use Moka\Exception\NotImplementedException;
use Moka\Moka;
use Moka\Proxy\ProxyInterface;
use Moka\Proxy\ProxyTrait;
use Moka\Tests\FooTestClass;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument\Token\AnyValuesToken;
use Prophecy\Exception\Prediction\PredictionException;

class MokaTest extends TestCase
{
    const METHODS = [
        'mockery',
        'phake',
        'phpunit',
        'prophecy'
    ];

    public function testCallStaticSuccess()
    {
        foreach (self::METHODS as $method) {
            $this->assertInstanceOf(
                ProxyInterface::class,
                Moka::$method(\stdClass::class)
            );
        }
    }

    public function testPHPUnitExpectation()
    {
        /** @var ProxyTrait $proxy */
        $proxy = Moka::phpunit(FooTestClass::class, 'foo');

        $proxy->expects($this->once())
            ->method('getInt');

        try {
            $proxy->__moka_getMock()->__phpunit_verify();
        } catch (ExpectationFailedException $e) {
            $proxy->getInt();
            return;
        }

        $this->fail('Mock object was not registered within test case');
    }

    public function testProphecyExpectation()
    {
        /** @var ProxyTrait $proxy */
        $proxy = Moka::prophecy(FooTestClass::class, 'foo');

        $proxy->getInt->set(new AnyValuesToken())
            ->willReturn(1138)
            ->shouldBeCalledTimes(1);

        try {
            $proxy->__moka_getMock()->checkProphecyMethodsPredictions();
        } catch (PredictionException $e) {
            $proxy->getInt();
            return;
        }

        $this->fail('Mock object was not registered within test case');
    }

    public function testClean()
    {
        $proxy1 = Moka::phpunit(\stdClass::class);
        Moka::clean();
        $proxy2 = Moka::phpunit(\stdClass::class);

        $this->assertNotSame($proxy1, $proxy2);
    }
}
