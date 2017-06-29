<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

/**
 * Interface ProxyTemplateInterface
 * @package Moka\Generator\Template
 */
interface ProxyTemplateInterface
{
    /**
     * @param \Reflector $reflector
     * @return string
     */
    public static function generate(\Reflector $reflector): string;
}
