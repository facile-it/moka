<?php
declare(strict_types=1);

namespace Moka\Strategy;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\NotImplementedException;

/**
 * Interface MockingStrategyInterface
 * @package Moka\Strategy
 */
interface MockingStrategyInterface
{
    /**
     * @param string $fqcn
     * @return object
     *
     * @throws MockNotCreatedException
     */
    public function build(string $fqcn);

    /**
     * @param object $mock
     * @param array $stubs
     * @return void
     */
    public function decorate($mock, array $stubs);

    /**
     * @param object $mock
     * @return object
     *
     * @throws InvalidArgumentException
     * @throws MockNotCreatedException
     */
    public function get($mock);

    /**
     * @param object $mock
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function call($mock, string $name, array $arguments);

    /**
     * @return string
     *
     * @throws NotImplementedException
     */
    public function getMockType(): string;
}
