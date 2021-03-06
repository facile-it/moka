<?php
declare(strict_types=1);

namespace Moka\Stub\Helper;

use Moka\Exception\InvalidArgumentException;

/**
 * @param string $name
 * @return void
 *
 * @throws InvalidArgumentException
 */
function validateMethodName(string $name): void
{
    validateName(stripName($name, ['static']));
}
