<?php
declare(strict_types=1);

namespace Moka\Stub\Helper;

use Moka\Exception\InvalidArgumentException;

/**
 * @param string $name
 * @return bool
 *
 * @throws InvalidArgumentException
 */
function isMethodName(string $name): bool
{
    return !isPropertyName($name);
}