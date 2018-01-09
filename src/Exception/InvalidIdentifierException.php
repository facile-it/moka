<?php
declare(strict_types=1);

namespace Moka\Exception;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Class InvalidIdentifierException
 * @package Moka\Exception
 */
class InvalidIdentifierException extends InvalidArgumentException implements NotFoundExceptionInterface
{
}
