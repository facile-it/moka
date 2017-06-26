<?php

declare(strict_types=1);

namespace Tests\Plugin\Phake;

use Moka\Exception\MockNotCreatedException;
use Moka\Factory\StubFactory;
use Moka\Plugin\Phake\PhakeMockingStrategy;
use Moka\Tests\MokaMockingStrategyTestCase;
use Tests\BarTestClass;
use Tests\FooTestClass;
use Tests\TestInterface;

class PhakeMockingStrategyTest extends MokaMockingStrategyTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setStrategy(new PhakeMockingStrategy());
    }

    public function testBuildEmptyFQCNFailure()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->strategy->build('');
    }

    public function testBuildParseFailure()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->strategy->build('foo bar');
    }

    public function testBuildFakeFQCNFailure()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->strategy->build($this->getRandomFQCN());
    }

    public function testBuildMultipleFQCNFailure()
    {
        $this->expectException(MockNotCreatedException::class);

        $this->strategy->build(FooTestClass::class.', '.BarTestClass::class);
    }

    public function testDecorateFakeMethodFailure()
    {
        $this->strategy->decorate($this->mock, StubFactory::fromArray([
            'fakeMethod' => true,
        ]));

        $this->expectException(\Error::class);
        $this->strategy->get($this->mock)->fakeMethod();
    }

    public function testCallMissingMethodFailure()
    {
        $mock = $this->strategy->build(FooTestClass::class);

        $this->expectException(\Throwable::class);
        $this->strategy->get($mock)->getSelf();
    }

    public function testGetMultipleFQCNFailure()
    {
        $this->expectException(\Exception::class);

        $this->strategy->build(FooTestClass::class.', '.TestInterface::class);
    }
}
