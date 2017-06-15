<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 15/06/2017
 * Time: 14:44
 */

namespace Moka\Generator;

use PHPUnit_Framework_MockObject_MockObject as MockObject;

interface MockGeneratorInterface
{
    /**
     * @param string $fqcn
     * @return MockObject|object
     *
     * @throws \InvalidArgumentException
     */
    public function generate(string $fqcn);
}
