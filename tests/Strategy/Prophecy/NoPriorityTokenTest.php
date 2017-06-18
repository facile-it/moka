<?php
declare(strict_types=1);

namespace Tests\Strategy\Prophecy;

use Moka\Strategy\Prophecy\NoPriorityToken;
use Tests\Strategy\Prophecy\PriorityTokenTestCase;

class NoPriorityTokenTest extends PriorityTokenTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setToken(new NoPriorityToken());
    }
}
