<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 12/06/2017
 * Time: 12:10
 */

namespace Moka;



trait MokaBuilderTrait
{
    /**
     * @param string $className
     * @param string|null $key
     * @return Proxy
     */
    protected function mock(string $className, string $key = null): Proxy
    {
        return BuilderFactory::get()->mock($className, $key);
    }

}