<?php
declare(strict_types=1);

namespace Tests\Plugin\PHPUnit;

use Moka\Plugin\PHPUnit\PHPUnitMockingStrategy;
use Moka\Tests\MokaMockingStrategyTestCase;

class PHPUnitMockingStrategyTest extends MokaMockingStrategyTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setStrategy(new PHPUnitMockingStrategy());
    }
}
