<?php
declare(strict_types=1);

namespace Tests\Plugin\Prophecy;

use Moka\Plugin\Prophecy\ProphecyMockingStrategy;
use Moka\Plugin\Prophecy\ProphecyPlugin;
use PHPUnit\Framework\TestCase;

class ProphecyPluginTest extends TestCase
{
    /**
     * @var ProphecyPlugin
     */
    private $prophecyPlugin;

    protected function setUp()
    {
        $this->prophecyPlugin = new ProphecyPlugin();
    }

    public function testGetStrategy()
    {
        $this->assertInstanceOf(ProphecyMockingStrategy::class, $this->prophecyPlugin::getStrategy());
    }
}
