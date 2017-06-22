<?php
declare(strict_types=1);

namespace Tests\Plugin\Prophecy\Token;

use PHPUnit\Framework\TestCase;

class UnsaltedPriorityTokenTest extends TestCase
{
    public function testTokensAreNotDifferent()
    {
        $token1 = new UnsaltedPriorityToken();
        $token2 = new UnsaltedPriorityToken();

        $this->assertEquals((array)$token1, (array)$token2);
    }
}
