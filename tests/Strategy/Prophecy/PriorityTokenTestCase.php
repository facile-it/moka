<?php
declare(strict_types=1);

namespace Tests\Strategy\Prophecy;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument\Token\TokenInterface;

class PriorityTokenTestCase extends TestCase
{
    /**
     * @var TokenInterface
     */
    protected $token;

    public function testTokensAreDifferent()
    {
        $fqcn = get_class($this->token);
        $token = new $fqcn();

        $this->assertNotEquals((array)$this->token, (array)$token);
    }

    public function testScoreArgument()
    {
        $this->assertInternalType('int', $this->token->scoreArgument(null));
    }

    public function testIsLast()
    {
        $this->assertTrue($this->token->isLast());
    }

    public function testToString()
    {
        $this->assertInternalType('string', $this->token->__toString());
    }

    protected function setToken(TokenInterface $token)
    {
        $this->token = $token;
    }
}
