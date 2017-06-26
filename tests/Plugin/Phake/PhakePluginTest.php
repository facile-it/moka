<?php
declare(strict_types=1);

namespace Tests\Plugin\Phake;

use Moka\Plugin\Phake\PhakeMockingStrategy;
use Moka\Plugin\Phake\PhakePlugin;
use PHPUnit\Framework\TestCase;

class PhakePluginTest extends TestCase
{
    /**
     * @var PhakePlugin
     */
    private $phakePlugin;

    protected function setUp()
    {
        $this->phakePlugin = new PhakePlugin();
    }

    public function testGetStrategy()
    {
        $this->assertInstanceOf(PhakeMockingStrategy::class, $this->phakePlugin::getStrategy());
    }
}
