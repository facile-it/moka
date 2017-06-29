<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

use Moka\Generator\ProxyTrait;
use Moka\Proxy\ProxyInterface;

/**
 * Class ProxyClassTemplate
 * @package Moka\Generator\Template
 */
class ProxyClassTemplate implements ProxyTemplateInterface
{
    const UNSAFE_METHODS = ['__construct', '__destruct', '__call', '__clone'];

    const TEMPLATE_FQCN = 'Moka_%s_%s';

    const TEMPLATE = '
    class %s extends %s implements %s
    {
        use %s;

        %s
        
        public function __call(%s $name, %s $arguments)
        {
            return $this->doCall($name, $arguments);
        }
    };
    
    return "%s";
    ';

    /**
     * @param \ReflectionClass $class
     * @return string
     */
    public static function generate(\Reflector $class): string
    {
        return static::doGenerate($class);
    }

    /**
     * @param \ReflectionClass $class
     * @return string
     */
    protected static function doGenerate(\ReflectionClass $class): string
    {
        $methods = $class->getMethods();
        $methodsCode = [];

        $callParametersTypes = array_fill(0, 2, '');

        foreach ($methods as $method) {
            if (
                !$method->isFinal() &&
                !in_array($method->getName(), self::UNSAFE_METHODS, true)
            ) {
                $methodsCode[] = ProxyMethodTemplate::generate($method);
            }

            if ('__call' === $method->getName()) {
                $callParameters = $method->getParameters();
                foreach ($callParameters as $callParameter) {
                    $callParametersTypes[$callParameter->getPosition()] = (string)$callParameter->getType();
                }
            }
        }

        $mockClassName = $class->getName();
        $proxyClassName = sprintf(
            self::TEMPLATE_FQCN,
            preg_replace('/\\\/', '__', $mockClassName),
            mt_rand()
        );

        list($callNameType, $callArgumentsType) = $callParametersTypes;

        return sprintf(
            self::TEMPLATE,
            $proxyClassName,
            $mockClassName,
            ProxyInterface::class,
            ProxyTrait::class,
            implode(PHP_EOL, $methodsCode),
            $callNameType ?? '',
            $callArgumentsType ?? '',
            $proxyClassName
        );
    }
}
