<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 12/06/2017
 * Time: 16:35
 */

namespace Moka;

use Psr\Container\NotFoundExceptionInterface;

class InvalidIdentifierException extends \Exception implements NotFoundExceptionInterface
{

}