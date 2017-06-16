<?php
declare(strict_types=1);

namespace Moka\Strategy;

use Moka\Exception\MockNotCreatedException;
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
     */
    public function decorate($mock, StubSet $stubs);

    /**
     * @param object $mock
     * @return mixed
     */
    public function get($mock);
}
