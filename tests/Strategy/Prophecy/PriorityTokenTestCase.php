<?php
declare(strict_types=1);

namespace Tests\Strategy\Prophecy;

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
