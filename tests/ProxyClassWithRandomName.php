<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 4/16/18
 * Time: 10:51 PM
 */

namespace Tests;


use Moka\Proxy\ProxyInterface;
use Moka\Proxy\ProxyTrait;

class ProxyClassWithRandomName
    extends MockedObjectFQCN
    implements ProxyInterface
{
    use ProxyTrait;
}