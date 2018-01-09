<?php
declare(strict_types=1);

namespace Moka\Stub;

/**
 * Class StubInterface
 * @package Moka\Stub
 */
interface StubInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return mixed
     */
    public function getValue();
}
