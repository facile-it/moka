<?php
declare(strict_types=1);

namespace Tests\Strategy\Prophecy;

use Moka\Strategy\Prophecy\LowPriorityToken;

class LowPriorityTokenTest extends PriorityTokenTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setToken(new LowPriorityToken());
    }
}
