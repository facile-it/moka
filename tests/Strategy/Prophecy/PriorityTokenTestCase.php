<?php
declare(strict_types=1);

namespace Tests\Strategy\Prophecy;

use Moka\Strategy\Prophecy\LowPriorityToken;
use Moka\Strategy\Prophecy\NoPriorityToken;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument\Token\TokenInterface;

class PriorityTokenTestCase extends TestCase
{
    const SCORE_MIN = 1; // 0 is forbidden.
    const SCORE_MAX = 3; // 2 is the lowest number in Prophecy tokens.

    /**
     * @var TokenInterface
     */
    private $token;

    public function testScoreArgumentRange()
    {
        $this->assertGreaterThanOrEqual(
            self::SCORE_MIN,
            $this->token->scoreArgument(null)
        );

        $this->assertLessThanOrEqual(
            self::SCORE_MAX,
            $this->token->scoreArgument(null)
        );
    }

    public function testScoreArgumentOrder()
    {
        $this->assertGreaterThan(
            (new NoPriorityToken())->scoreArgument(null),
            (new LowPriorityToken())->scoreArgument(null)
        );
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
