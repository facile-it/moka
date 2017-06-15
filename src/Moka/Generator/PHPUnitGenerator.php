<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 15/06/2017
 * Time: 14:35
 */

namespace Moka\Generator;

use PHPUnit_Framework_MockObject_Generator as MockGenerator;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class PHPUnitGenerator implements MockGeneratorInterface
{
    /**
     * @var MockGenerator
     */
    private $generator;

    /**
     * PHPUnitGenerator constructor.
     */
    public function __construct()
    {
        $this->generator = new MockGenerator();
    }

    /**
     * @param string $fqcn
     * @return MockObject|object
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit_Framework_MockObject_RuntimeException
     */
    public function generate(string $fqcn)
    {
        return $this->generator->getMock(
            $fqcn,
            $methods = [],
            $arguments = [],
            $mockClassName = '',
            $callOriginalConstructor = false
        );
    }
}
