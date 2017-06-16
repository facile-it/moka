<?php
declare(strict_types=1);

namespace Moka\Strategy;

use Moka\Exception\InvalidArgumentException;

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
     * @throws InvalidArgumentException
     */
    final protected function checkMockType($mock)
    {
        if (!$this->mockType) {
            return;
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
