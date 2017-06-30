<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

/**
 * Interface TemplateInterface
 * @package Moka\Generator\Template
 */
interface TemplateInterface
{
    /**
     * @param \Reflector $reflector
     * @return string
     */
    public static function generate(\Reflector $reflector): string;
}
