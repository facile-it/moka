<?php
declare(strict_types=1);

namespace Moka;

use Psr\Container\NotFoundExceptionInterface;

class InvalidIdentifierException extends \Exception implements NotFoundExceptionInterface
{
}
