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
     * @param array $namesWithValues
     * @return ProxyInterface
     *
     * @throws InvalidArgumentException
     * @throws MockNotCreatedException
     */
    public function stub(array $namesWithValues): ProxyInterface;
}
