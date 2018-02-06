<?php
declare(strict_types=1);

namespace Moka\Stub\Helper;

use Moka\Exception\InvalidArgumentException;

/**
 * @param string $name
 * @return string
 * @throws InvalidArgumentException
 */
function stripName(string $name): string
{
    $name = doStripName($name);

    validateName($name);

    return $name;
}