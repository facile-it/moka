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
     * @param StubInterface $elem
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function add($elem)
    {
        if (!$elem instanceof StubInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'Element must be an instance of "%s", "%s" given',
                    StubInterface::class,
                    gettype($elem)
                )
            );
        }

        parent::add($elem);
    }
}
