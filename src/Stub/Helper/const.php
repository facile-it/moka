<?php
declare(strict_types=1);

namespace Moka\Stub\Helper;

const PREFIXES = [
    'static' => '::',
    'property' => '\\$'
];

const NAME_TEMPLATE = '/^%s/';

/**
 * http://php.net/manual/en/language.variables.basics.php
 * http://php.net/manual/en/functions.user-defined.php
 */
const REGEX_NAME = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';
