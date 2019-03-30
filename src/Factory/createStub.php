<?php
declare(strict_types=1);

namespace Moka\Factory;

use Moka\Stub\MethodStub;
use Moka\Stub\PropertyStub;
use Moka\Stub\StubInterface;
use function Moka\Stub\Helper\isPropertyName;

/**
 * @param string $name
 * @param mixed $value
 * @return StubInterface
 */
function createStub(string $name, $value): StubInterface
{
    return isPropertyName($name)
        ? new PropertyStub($name, $value)
        : new MethodStub($name, $value);
}
