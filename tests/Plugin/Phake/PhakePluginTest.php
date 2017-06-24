<?php
declare(strict_types=1);

namespace Tests\Plugin\Phake;

use Moka\Plugin\Phake\PhakePlugin;
use Moka\Tests\MokaPluginTestCase;

class PhakePluginTest extends MokaPluginTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setPluginFQCN(PhakePlugin::class);
    }
}
