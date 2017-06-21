<?php
declare(strict_types=1);

namespace Tests\Strategy\Prophecy;

use Moka\Strategy\Prophecy\MaxPriorityToken;

class MaxPriorityTokenTest extends PriorityTokenTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setToken(new MaxPriorityToken());
    }

    public function testScoreArgumentOrder()
    {
        $token = new MaxPriorityToken();

        $this->assertLessThan($this->token->scoreArgument(null), $token->scoreArgument(null));
    }
}
