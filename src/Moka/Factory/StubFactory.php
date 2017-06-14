<?php
declare(strict_types=1);

namespace Moka\Factory;


use Moka\Exception\InvalidArgumentException;
use Moka\Stub\Stub;
use Moka\Stub\StubSet;

/**
 * Class StubFactory
 * @package Moka\Factory
 */
class StubFactory
{
    /**
     * @param array $methodsWithValues
     * @return StubSet|Stub[]
     *
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $methodsWithValues): StubSet
    {
        $stubMap = new StubSet();
        foreach ($methodsWithValues as $methodName => $methodValue) {
            try {
                $stubMap->add(new Stub($methodName, $methodValue));
            } catch (\Error $error) {
                throw new InvalidArgumentException();
            }
        }

        return $stubMap;
    }
}