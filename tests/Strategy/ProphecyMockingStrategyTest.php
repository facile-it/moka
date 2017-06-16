<?php
declare(strict_types=1);

namespace Tests\Strategy;

use Moka\Strategy\ProphecyMockingStrategy;
use Prophecy\Prophecy\ObjectProphecy;

class ProphecyMockingStrategyTest extends MockingStrategyTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setStrategy(new ProphecyMockingStrategy());
        $this->setMockType(ObjectProphecy::class);
    }
}
