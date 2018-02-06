<?php
declare(strict_types=1);

namespace Moka\Factory;
use Moka\Exception\InvalidArgumentException;
use Moka\Stub\MethodStub;
use Moka\Stub\PropertyStub;
use Moka\Stub\StubHelper;
use Moka\Stub\StubInterface;
use Moka\Stub\StubSet;

/**
 * @param array $namesWithValues
 * @return StubSet|StubInterface[]
 *
 * @throws InvalidArgumentException
 * @throws \LogicException
 */
function buildStubs(array $namesWithValues): StubSet
{
    $stubSet = new StubSet();
    foreach ($namesWithValues as $name => $value) {
        try {
            $stub = StubHelper::isPropertyName($name)
                ? new PropertyStub($name, $value)
                : new MethodStub($name, $value);

            $stubSet->add($stub);
        } catch (\Error $error) {
            throw new InvalidArgumentException($error->getMessage());
        }
    }

    return $stubSet;
}
