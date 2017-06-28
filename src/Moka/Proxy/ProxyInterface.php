<?php
declare(strict_types=1);

namespace Moka\Proxy;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\MockNotServedException;

/**
 * Class Proxy
 * @package Moka\Proxy
 */
interface ProxyInterface
{
    /**
     * @param array $methodsWithValues
     * @return ProxyInterface
     *
     * @throws InvalidArgumentException
     * @throws MockNotCreatedException
     */
    public function stub(array $methodsWithValues): ProxyInterface;

    /**
     * @return object
     *
     * @throws MockNotServedException
     *
     * @deprecated since v2.0.0
     */
    public function serve();
}
