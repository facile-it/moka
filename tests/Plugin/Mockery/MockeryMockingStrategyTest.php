<?php
declare(strict_types=1);

namespace Tests\Plugin\Mockery;

use Moka\Plugin\Mockery\MockeryMockingStrategy;
use Moka\Tests\MokaMockingStrategyTestCase;

class MockeryMockingStrategyTest extends MokaMockingStrategyTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setStrategy(new MockeryMockingStrategy());
    }
}
