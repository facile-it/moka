<?php
declare(strict_types=1);

namespace Tests\Plugin\Phake;

use Moka\Plugin\Phake\PhakeMockingStrategy;
use Moka\Tests\MokaMockingStrategyTestCase;

class PhakeMockingStrategyTest extends MokaMockingStrategyTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setStrategy(new PhakeMockingStrategy());
    }

    /**
     * @requires PHP 7.1
     */
    public function testBuildWithPHP71Class()
    {
        $this->markFeatureUnsupported('PHP 7.1 features');
    }
}
