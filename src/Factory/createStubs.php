<?php
declare(strict_types=1);

namespace Moka\Factory;

use Moka\Exception\InvalidArgumentException;
use Moka\Stub\StubInterface;
use Moka\Stub\StubSet;

/**
 * @param array $namesWithValues
 * @return StubSet|StubInterface[]
 *
 * @throws InvalidArgumentException
 */
function createStubs(array $namesWithValues): StubSet
{
    $stubSet = new StubSet();
    foreach ($namesWithValues as $name => $value) {
        try {
            $stub = createStub($name, $value);
        } catch (\Error $error) {
            throw new InvalidArgumentException($error->getMessage());
        }

        $stubSet->add($stub);
    }

    return $stubSet;
}
