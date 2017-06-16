<?php
declare(strict_types=1);

namespace Tests\Strategy;

use Moka\Strategy\PHPUnitMockingStrategy;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class PHPUnitMockingStrategyTest extends MockingStrategyTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setStrategy(new PHPUnitMockingStrategy());
        $this->setMockType(MockObject::class);
    }
}
