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
function validatePropertyName(string $name): void
{
    validateName($name, 'property');
}