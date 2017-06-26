<?php
declare(strict_types=1);

namespace Tests\Plugin\Mockery;

use Moka\Plugin\Mockery\MockeryMockingStrategy;
use Moka\Plugin\Mockery\MockeryPlugin;
use PHPUnit\Framework\TestCase;

class MockeryPluginTest extends TestCase
{
    /**
     * @var MockeryPlugin
     */
    private $mockeryPlugin;

    protected function setUp()
    {
        $this->mockeryPlugin = new MockeryPlugin();
    }

    public function testGetStrategy()
    {
        $this->assertInstanceOf(MockeryMockingStrategy::class, $this->mockeryPlugin::getStrategy());
    }
}
