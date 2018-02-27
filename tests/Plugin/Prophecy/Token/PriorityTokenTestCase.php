<?php
declare(strict_types=1);

namespace Tests\Plugin\Prophecy\Token;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument\Token\TokenInterface;

class PriorityTokenTestCase extends TestCase
{
    /**
     * @var TokenInterface
     */
    protected $token;

    public function testTokensAreDifferent(): void
    {
        $fqcn = \get_class($this->token);
        $token = new $fqcn();

        $this->assertNotEquals((array)$this->token, (array)$token);
    }

    public function testScoreArgument(): void
    {
        $this->assertInternalType('int', $this->token->scoreArgument(null));
    }

    public function testIsLast(): void
    {
        $this->assertTrue($this->token->isLast());
    }

    public function testToString(): void
    {
        $this->assertInternalType('string', $this->token->__toString());
    }

    protected function setToken(TokenInterface $token): void
    {
        $this->token = $token;
    }
}
