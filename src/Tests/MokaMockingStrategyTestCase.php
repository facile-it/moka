<?php
declare(strict_types=1);

namespace Moka\Tests;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Strategy\MockingStrategyInterface;
use PHPUnit\Framework\TestCase;

abstract class MokaMockingStrategyTestCase extends TestCase
{
    private const FQCN_EMPTY = '';
    private const FQCN_INVALID = 'Foo Bar';
    private const FQCN_NONEXISTENT_TEMPLATE = 'Foo_%d';

    /**
     * @var MockingStrategyInterface
     */
    protected $strategy;

    /**
     * @var object
     */
    protected $mock;

    /**
     * @var array
     */
    protected $namesWithValues;

    /**
     *
     */
    protected function setUp()
    {
        $this->namesWithValues = [
            '$property' => mt_rand(),
            '$public' => mt_rand(),
            '$private' => mt_rand(),
            'isTrue' => (bool)random_int(0, 1),
            'getInt' => mt_rand(),
            'withArgument' => mt_rand(),
            'throwException' => new \Exception((string)mt_rand())
        ];

        $this->mock = $this->strategy->build($this->getRandomFQCN());
        $this->strategy->decorate($this->mock, $this->namesWithValues);
    }

    /**
     * @throws \Exception
     * @throws \Moka\Exception\NotImplementedException
     */
    final public function testGetMockTypeSuccess()
    {
        $this->assertInternalType('string', $this->strategy->getMockType());
    }

    /**
     * @dataProvider fqcnProvider
     */
    final public function testBuildAndGet(bool $required, string $fqcnType, string ...$fqcns)
    {
        if (true === $required) {
            $this->buildAndGet(...$fqcns);
        } else {
            $this->tryBuildAndGet($fqcnType, ...$fqcns);
        }
    }

    /**
     * @requires PHP 7.1
     */
    public function testBuildWithPHP71Class()
    {
        $this->checkMock(
            $this->strategy->build(PHP71TestClass::class)
        );
    }

    /**
     *
     */
    final public function testDecorateFakeMockFailure()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->strategy->decorate(new FooTestClass(), $this->namesWithValues);
    }

    /**
     * @throws \Exception
     */
    final public function testDecorateWithPropertySuccess()
    {
        $this->assertEquals(
            $this->namesWithValues['$property'],
            $this->strategy->get($this->mock)->property
        );
    }

    /**
     * @throws \Exception
     */
    final public function testDecorateWithPublicPropertySuccess()
    {
        $this->assertEquals(
            $this->namesWithValues['$public'],
            $this->strategy->get($this->mock)->public
        );
    }

    /**
     *
     */
    final public function testDecorateWithProtectedPropertyFailure()
    {
        $this->expectException(\Error::class);

        $this->strategy->decorate($this->mock, [
            '$protected' => mt_rand()
        ]);
    }

    /**
     * @throws \Exception
     */
    final public function testDecorateWithPrivatePropertySuccess()
    {
        $this->assertEquals(
            $this->namesWithValues['$private'],
            $this->strategy->get($this->mock)->private
        );
    }

    /**
     *
     */
    final public function testDecorateWithWrongTypeHintFailure()
    {
        $this->strategy->decorate($this->mock, [
            'getSelf' => mt_rand()
        ]);

        $this->expectException(\TypeError::class);
        $this->strategy->get($this->mock)->getSelf();
    }

    /**
     *
     */
    final public function testDecorateWithNonexistentMethod()
    {
        try {
            $value = mt_rand();
            $this->strategy->decorate($this->mock, [
                'nonexistentMethod' => $value
            ]);

            $this->assertSame(
                $value,
                $this->strategy->get($this->mock)->nonexistentMethod()
            );
        } catch (\Throwable $e) {
            $this->markFeatureUnsupported('stubbing a nonexistent method');
        }
    }

    /**
     *
     */
    final public function testCallUnstubbedMethod()
    {
        try {
            $mock = $this->strategy->build(FooTestClass::class);

            $this->assertInstanceOf(
                TestInterface::class,
                $this->strategy->get($mock)->getSelf()
            );
        } catch (\Throwable $e) {
            $this->markFeatureUnsupported('calling an unstubbed method');
        }
    }

    /**
     * @throws \Exception
     */
    final public function testSingleMethodCallSuccess()
    {
        $this->assertSame(
            $this->namesWithValues['isTrue'],
            $this->strategy->get($this->mock)->isTrue()
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($this->namesWithValues['throwException']->getMessage());
        $this->strategy->get($this->mock)->throwException();
    }

    /**
     * @throws \Exception
     */
    final public function testMultipleMethodCallsSuccess()
    {
        $this->assertSame(
            $this->namesWithValues['getInt'],
            $this->strategy->get($this->mock)->getInt()
        );

        $this->assertSame(
            $this->namesWithValues['getInt'],
            $this->strategy->get($this->mock)->getInt()
        );
    }

    /**
     * @throws \Exception
     */
    final public function testOverrideMethodStubFailure()
    {
        $this->expectException(\Exception::class);

        $this->strategy->decorate($this->mock, [
            'getInt' => mt_rand(),
            'throwException' => mt_rand()
        ]);

        $this->assertSame(
            $this->namesWithValues['getInt'],
            $this->strategy->get($this->mock)->getInt()
        );

        $this->assertSame(
            $this->namesWithValues['getInt'],
            $this->strategy->get($this->mock)->getInt()
        );

        $this->strategy->get($this->mock)->throwException();
    }

    /**
     * @throws \Exception
     */
    final public function testCallMethodWithArgumentSuccess()
    {
        $this->assertSame(
            $this->namesWithValues['withArgument'],
            $this->strategy->get($this->mock)->withArgument(mt_rand())
        );
    }

    /**
     *
     */
    final public function testCallMethodWithoutArgumentFailure()
    {
        $this->expectException(\Error::class);

        $this->strategy->get($this->mock)->withArgument();
    }

    /**
     *
     */
    final public function testCallMethodWithWrongArgumentFailure()
    {
        $this->expectException(\TypeError::class);

        $this->strategy->get($this->mock)->withArgument('string');
    }

    /**
     *
     */
    final public function testGetFakeMockFailure()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->strategy->get(new \stdClass());
    }

    /**
     * @return array
     */
    final public function fqcnProvider(): array
    {
        $required = [
            ['an interface', TestInterface::class],
            ['an abstract class', AbstractTestClass::class],
            ['a class', $this->getRandomFQCN()]
        ];

        $optional = [
            ['an empty FQCN', self::FQCN_EMPTY],
            ['an invalid FQCN', self::FQCN_INVALID],
            ['a nonexistent FQCN', $this->getNonexistentFQCN()],
            ['multiple interfaces', FooTestInterface::class, BarTestInterface::class],
            ['class and interface', FooTestClass::class, BarTestInterface::class],
            ['multiple classes', FooTestClass::class, BarTestClass::class],
            ['multiple nonexistent FQCNs', $this->getNonexistentFQCN(), $this->getNonexistentFQCN()]
        ];

        $data = array_merge(
            array_map(function (array $set) {
                array_unshift($set, $required = true);
                return $set;
            }, $required),
            array_map(function (array $set) {
                array_unshift($set, $required = false);
                return $set;
            }, $optional)
        );

        return array_reduce($data, function (array $data, array $set) {
            $key = preg_replace('/^an? +/', '', $set[1]);
            $data[$key] = $set;

            return $data;
        }, []);
    }

    /**
     * @param MockingStrategyInterface $strategy
     */
    final protected function setStrategy(MockingStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @return string
     */
    final protected function getRandomFQCN(): string
    {
        return [
            FooTestClass::class,
            BarTestClass::class
        ][random_int(0, 1)];
    }

    /**
     * @return string
     */
    final protected function getNonexistentFQCN(): string
    {
        return sprintf(
            self::FQCN_NONEXISTENT_TEMPLATE,
            mt_rand()
        );
    }

    /**
     * @param $mock
     * @throws \Exception
     * @throws \Moka\Exception\NotImplementedException
     */
    final protected function checkMock($mock)
    {
        $this->assertInstanceOf($this->strategy->getMockType(), $mock);
    }

    /**
     * @param string $feature
     */
    final protected function markFeatureUnsupported(string $feature)
    {
        $this->markTestSkipped(
            sprintf(
                'Strategy "%s" doesn\'t support %s',
                get_class($this->strategy),
                $feature
            )
        );
    }

    /**
     * @param string[] ...$fqcns
     * @throws \Exception
     * @throws \Moka\Exception\NotImplementedException
     */
    private function buildAndGet(string ...$fqcns)
    {
        $this->checkMock(
            $mock = $this->strategy->build(implode(', ', $fqcns))
        );

        $object = $this->strategy->get($mock);
        foreach ($fqcns as $fqcn) {
            $this->assertInstanceOf($fqcn, $object);
        }
    }

    /**
     * @param string $fqcnType
     * @param string[] ...$fqcns
     * @throws \Exception
     * @throws \Moka\Exception\NotImplementedException
     */
    private function tryBuildAndGet(string $fqcnType, string ...$fqcns)
    {
        try {
            $this->checkMock(
                $mock = $this->strategy->build(implode(', ', $fqcns))
            );
        } catch (MockNotCreatedException $e) {
            $this->markFeatureUnsupported(
                sprintf(
                    'building with %s: %s',
                    $fqcnType,
                    $e->getMessage()
                )
            );

            return;
        }

        $object = $this->strategy->get($mock);
        foreach ($fqcns as $fqcn) {
            if (!is_a($object, $fqcn)) {
                $this->markFeatureUnsupported(
                    sprintf(
                        'getting a valid mock from %s',
                        $fqcnType
                    )
                );

                return;
            }
        }
    }
}
