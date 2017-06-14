<?php
declare(strict_types=1);

namespace Moka;

use Moka\Exception\InvalidIdentifierException;
use Psr\Container\ContainerInterface;

final class Container implements ContainerInterface
{
    /**
     * @var array
     */
    private $mocks;

    public function __construct()
    {
        $this->mocks = [];
    }

    public function get($id): Proxy
    {
        if (!$this->has($id)) {
            throw new InvalidIdentifierException(sprintf('The mock object with id "%s" is not found', $id));
        }

        return $this->mocks[$id];
    }

    public function has($id): bool
    {
        return isset($this->mocks[$id]);
    }

    public function set(string $id, Proxy $mock): void
    {
        $this->mocks[$id] = $mock;
    }
}
