<?php
declare(strict_types=1);

namespace Moka\Factory;

use Moka\Exception\InvalidArgumentException;
use Moka\Stub\MethodStub;
use Moka\Stub\PropertyStub;
use Moka\Stub\StubInterface;
use Moka\Stub\StubSet;

/**
 * Class StubFactory
 * @package Moka\Factory
 */
class StubFactory
{
    /**
     * @param array $namesWithValues
     * @return StubSet|StubInterface[]
     *
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $namesWithValues): StubSet
    {
        $stubSet = new StubSet();
        foreach ($namesWithValues as $name => $value) {
            try {
                $stub = StubInterface::PREFIX_PROPERTY === $name[0]
                    ? new PropertyStub($name, $value)
                    : new MethodStub($name, $value);

                $stubSet->add($stub);
            } catch (\Error $error) {
                throw new InvalidArgumentException($error->getMessage());
            }
        }

        return $stubSet;
    }
}
