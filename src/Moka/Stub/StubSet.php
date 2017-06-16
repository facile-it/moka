<?php
declare(strict_types=1);

namespace Moka\Stub;

use Moka\Exception\InvalidArgumentException;
use PhpCollection\Set;

/**
 * Class StubSet
 * @package Moka\Stub
 */
class StubSet extends Set
{
    /**
     * @param Stub $elem
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function add($elem)
    {
        if (!$elem instanceof Stub) {
            throw new InvalidArgumentException(
                sprintf(
                    'Element must be of type %s, %s given',
                    Stub::class,
                    gettype($elem)
                )
            );
        }

        parent::add($elem);
    }
}
