<?php
declare(strict_types=1);

namespace Moka\Proxy;

use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class Proxy
 * @package Moka\Proxy
 */
interface ProxyInterface
{
    /**
     * @return MockObject
     */
    public function serve(): MockObject;

    /**
     * @param array $methodsWithValues
     * @return ProxyInterface
     */
    public function stub(array $methodsWithValues): ProxyInterface;
}
