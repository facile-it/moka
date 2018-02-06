<?php
declare(strict_types=1);

namespace Moka\Factory;

use function Moka\Stub\Helper\isPropertyName;
use Moka\Stub\MethodStub;
use Moka\Stub\PropertyStub;
use Moka\Stub\StubInterface;

/**
 * @param string $name
 * @param mixed $value
 * @return StubInterface
 */
function buildStub(string $name, $value): StubInterface
{
    return isPropertyName($name)
        ? new PropertyStub($name, $value)
        : new MethodStub($name, $value);
}
