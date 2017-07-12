<?php
declare(strict_types=1);

namespace Moka\Stub;

/**
 * Class StubInterface
 * @package Moka\Stub
 */
interface StubInterface
{
    const PREFIX_PROPERTY = '$';
    const PREFIX_STATIC = '::';

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return mixed
     */
    public function getValue();
}
