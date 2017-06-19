<?php
declare(strict_types=1);

namespace Moka\Strategy;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\MockNotCreatedException;
use Moka\Exception\NotImplementedException;
use Moka\Stub\StubSet;

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
     * @param StubSet $stubs
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function decorate($mock, StubSet $stubs);

    /**
     * @param object $mock
     * @return object
     *
     * @throws InvalidArgumentException
     * @throws MockNotCreatedException
     */
    public function get($mock);

    /**
     * @return string
     *
     * @throws NotImplementedException
     */
    public function getMockType(): string;
}
