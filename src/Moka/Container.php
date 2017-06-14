<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 12/06/2017
 * Time: 12:18
 */

namespace Moka;


use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class MockContainer
 * @package Phantom\PhantomBundle\Test\Mock
 */
final class Container implements ContainerInterface
{

    /** @var array */
    private $mocks;

    /**
     * MockContainer constructor.
     * @param array $mocks
     */
    public function __construct(array $mocks = [])
    {
        $this->mocks = $mocks;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new InvalidIdentifierException(sprintf('The mock with id "%s" is not found.', $id));
        }

        return $this->mocks[$id];
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->mocks[$id]);
    }

    /**
     * @param $id
     * @param $mock
     */
    public function set($id, $mock)
    {
        $this->mocks[$id] = $mock;
    }

}