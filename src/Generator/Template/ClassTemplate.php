<?php
declare(strict_types=1);

namespace Moka\Generator\Template;

use Moka\Exception\InvalidArgumentException;
use Moka\Proxy\ProxyInterface;
use Moka\Proxy\ProxyTrait;

/**
 * Class ClassTemplate
 * @package Moka\Generator\Template
 */
class ClassTemplate implements TemplateInterface
{
    private const UNSAFE_METHODS = ['__construct', '__destruct', '__call', '__get', '__clone'];

    private const TEMPLATE_FQCN = 'Moka_%s_%s';

    private const TEMPLATE_CLASS = '
    class %s extends %s implements %s
    {
        use %s;
        
        %s

        public function __construct()
        {
            %s
        }
        
        public function __call(%s $name, %s $arguments)
        {
            return $this->doCall($name, $arguments);
        }
        
        public function __get(%s $name)
        {
            return $this->doGet($name);
        }

        %s
    };
    
    return "%s";
    ';

    /**
     * @param \Reflector|\ReflectionClass $class
     * @return string
     */
    public static function generate(\Reflector $class): string
    {
        return static::doGenerate($class);
    }

    /**
     * @param \ReflectionClass $class
     * @return string
     * @throws \RuntimeException
     * @throws InvalidArgumentException
     */
    protected static function doGenerate(\ReflectionClass $class): string
    {
        $properties = $class->getProperties(\ReflectionProperty::IS_PUBLIC);
        $propertiesCode = [];

        $constructorCode = [];

        $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methodsCode = [];

        $callParametersTypes = array_fill(0, 2, '');
        $getNameType = '';

        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $propertiesCode[] = PropertyTemplate::generate($property);
            $constructorCode[] = PropertyInitializationTemplate::generate($property);
        }

        foreach ($methods as $method) {
            if (
                !$method->isFinal() &&
                !\in_array($method->name, self::UNSAFE_METHODS, $strict = true)
            ) {
                $methodsCode[] = MethodTemplate::generate($method);
            }

            if ('__call' === $method->name) {
                $callParameters = $method->getParameters();
                foreach ($callParameters as $callParameter) {
                    $callParametersTypes[$callParameter->getPosition()] = (string)$callParameter->getType();
                }
            }

            if ('__get' === $method->name) {
                $getNameType = (string)$method->getParameters()[0]->getType();
            }
        }

        $mockClassName = $class->name;
        $proxyClassName = sprintf(
            self::TEMPLATE_FQCN,
            preg_replace('/\\\/', '__', $mockClassName),
            random_int($min = 0, $max = PHP_INT_MAX)
        );

        [$callNameType, $callArgumentsType] = $callParametersTypes;

        return sprintf(
            self::TEMPLATE_CLASS,
            $proxyClassName,
            $mockClassName,
            ProxyInterface::class,
            ProxyTrait::class,
            implode(PHP_EOL, $propertiesCode),
            implode(PHP_EOL, $constructorCode),
            $callNameType ?: '',
            $callArgumentsType ?: '',
            $getNameType,
            implode(PHP_EOL, $methodsCode),
            $proxyClassName
        );
    }
}
