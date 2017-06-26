<?php
declare(strict_types=1);

namespace Moka\Proxy;

use Moka\Exception\InvalidIdentifierException;
use Psr\Container\ContainerInterface;

/**
 * Class ProxyContainer
 * @package Moka\Proxy
 */
final class ProxyContainer implements ContainerInterface
{
    /**
     * @var array
     */
    private $mocks;

    /**
     * ProxyContainer constructor.
     */
    public function __construct()
    {
        $this->mocks = [];
    }

    /**
     * @param string $id
     * @return Proxy
     *
     * @throws InvalidIdentifierException
     */
    public function get($id): Proxy
    {
        if (!$this->has($id)) {
            throw new InvalidIdentifierException(
                sprintf(
                    'Cannot find mock object with identifier "%s"',
                    $id
                )
            );
        }

        return $this->mocks[$id];
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id): bool
    {
        return isset($this->mocks[$id]);
    }

    /**
     * @param string $id
     * @param Proxy $mock
     */
    public function set(string $id, Proxy $mock)
    {
        $this->mocks[$id] = $mock;
    }
}
