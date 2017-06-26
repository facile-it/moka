<?php

declare(strict_types=1);

namespace Tests\Plugin\Phake\Matcher;

use Moka\Plugin\Phake\Matcher\FirstStubMatcher;
use Phake_Exception_MethodMatcherException as MethodMatcherException;
use Phake_IMock as PhakeMock;
use PHPUnit\Framework\TestCase;

class FirstStubMatcherTest extends TestCase
{
    /**
     * @var FirstStubMatcher
     */
    private $matcher;

    /**
     * @var PhakeMock
     */
    private $mock;

    /**
     * @var array
     */
    private $arguments = [];

    protected function setUp()
    {
        $this->mock = $this->getMockBuilder(PhakeMock::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->matcher = new FirstStubMatcher($this->mock, 'foo');
    }

    public function testDoArgumentsMatchSuccess()
    {
        $this->matcher->doArgumentsMatch($this->arguments);

        $this->assertTrue(true);
    }

    public function testDoArgumentsMatchSameMockFailure()
    {
        $matcher = new FirstStubMatcher($this->mock, 'foo');

        $this->expectException(MethodMatcherException::class);
        $matcher->doArgumentsMatch($this->arguments);
    }

    public function testDoArgumentsMatchDifferentMockSuccess()
    {
        $mock = $this->getMockBuilder(PhakeMock::class)
            ->disableOriginalConstructor()
            ->getMock();

        $matcher = new FirstStubMatcher($mock, 'foo');

        $matcher->doArgumentsMatch($this->arguments);
        $this->assertTrue(true);
    }

    public function testDoArgumentsMatchSameMockDifferentMethodSuccess()
    {
        $matcher = new FirstStubMatcher($this->mock, 'bar');

        $matcher->doArgumentsMatch($this->arguments);
        $this->assertTrue(true);
    }

    public function testToString()
    {
        $this->assertInternalType('string', $this->matcher->__toString());
    }

    public function testSetNextMatcher()
    {
        $this->matcher->setNextMatcher($this->matcher);

        $this->assertTrue(true);
    }
}
