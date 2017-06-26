<?php

declare(strict_types=1);

namespace Tests\Plugin\Prophecy;

use Moka\Plugin\Prophecy\ProphecyPlugin;
use Moka\Tests\MokaPluginTestCase;

class ProphecyPluginTest extends MokaPluginTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->setPluginFQCN(ProphecyPlugin::class);
    }
}
