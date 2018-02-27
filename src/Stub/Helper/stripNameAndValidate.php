<?php
declare(strict_types=1);

namespace Moka\Stub\Helper;

use Moka\Exception\InvalidArgumentException;

/**
 * @param string $name
 * @return string
 * @throws InvalidArgumentException
 */
function stripNameAndValidate(string $name): string
{
    $name = stripName($name);

    validateName($name);

    return $name;
}
