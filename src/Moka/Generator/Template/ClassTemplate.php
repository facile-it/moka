<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

use Moka\Proxy\ProxyInterface;
use Moka\Proxy\ProxyTrait;

/**
 * Class ClassTemplate
 * @package Moka\Generator\Template
 */
class ClassTemplate implements TemplateInterface
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
                !in_array($method->name, self::UNSAFE_METHODS, true)
            ) {
                $methodsCode[] = MethodTemplate::generate($method);
            }

            if ('__call' === $method->name) {
                $callParameters = $method->getParameters();
                foreach ($callParameters as $callParameter) {
                    $callParametersTypes[$callParameter->getPosition()] = (string)$callParameter->getType();
                }
            }
        }

        $mockClassName = $class->name;
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
            $callNameType ?: '',
            $callArgumentsType ?: '',
            $proxyClassName
        );
    }
}
