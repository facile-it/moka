<?php
declare(strict_types=1);

namespace Moka\Strategy;

use Moka\Exception\InvalidArgumentException;
use Moka\Exception\NotImplementedException;

/**
 * Class AbstractMockingStrategy
 * @package Moka\Strategy
 */
abstract class AbstractMockingStrategy implements MockingStrategyInterface
{
    /**
     * @var string
     */
    private $mockType;

    /**
     * @param string $fqcn
     */
    final protected function setMockType(string $fqcn)
    {
        $this->mockType = $fqcn;
    }

    /**
     * @param object $mock
     *
     * @throws NotImplementedException
     * @throws InvalidArgumentException
     */
    final protected function checkMockType($mock)
    {
        if (!$this->mockType) {
            throw new NotImplementedException();
        }

        if (!is_a($mock, $this->mockType)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Mock must be of type %s, %s given',
                    $this->mockType,
                    gettype($mock)
                )
            );
        }
    }
}
