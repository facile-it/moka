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
     * @param \Reflector $property
     * @return string
     */
    public static function generate(\Reflector $property): string;
}
