<?php
declare(strict_types=1);

namespace Moka\Stub;

use Moka\Exception\InvalidArgumentException;
use PhpCollection\Set;

/**
 * Class StubSet
 * @package Moka\Stub
 */
final class StubSet extends Set
{
    /**
     * @param object|\PhpCollection\scalar $elem
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function add($elem)
    {
        if (!$elem instanceof Stub) {
            throw new InvalidArgumentException(
                sprintf(
                    'The first parameter must be an instance of %s, %s given',
                    Stub::class,
                    gettype($elem)
                )
            );
        }

        parent::add($elem);
    }

}
