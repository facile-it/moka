<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 13/06/2017
 * Time: 12:32
 */

namespace Moka;


trait MokaCleanerTrait
{
    public function tearDown(): void
    {
        BuilderFactory::get()->resetContainer();
    }
}