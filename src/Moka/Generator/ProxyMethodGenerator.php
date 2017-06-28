<?php
declare(strict_types=1);

namespace Moka\Generator;


class ProxyMethodGenerator
{
    private $template = '
        public %s function %s(%s)%s
        {
            %s$this->__call("%s", func_get_args());
        }
    ';

    private $argumentGenerator;
    private $returnGenerator;

    public function __construct()
    {
        $this->argumentGenerator = new ProxyArgumentGenerator();
        $this->returnGenerator = new ProxyReturnGenerator();
    }

    public function generateMethodString(\ReflectionMethod $method)
    {
        $static = $method->isStatic() ? 'static' : '';
        $originalReturnType = $method->getReturnType();

        $returnType = !$originalReturnType ? '' : $this->returnGenerator->generateMethodReturnType($originalReturnType, $method);

        $parameters = $method->getParameters();
        $arguments = [];
        if ($parameters) {
            foreach ($method->getParameters() as $parameter) {
                $arguments[] = $this->argumentGenerator->generateMethodParameter($parameter);
            }
        }

        $method->getReturnType();

        $returnStatement = 'return ';
        if (null !== $originalReturnType && $this->getType($originalReturnType) === 'void') {
            $returnStatement = '';
        }


        return sprintf(
            $this->template,
            $static,
            $method->getName(),
            implode(', ', $arguments),
            $returnType,
            $returnStatement,
            $method->getName()
        );
    }

    protected function getType(\ReflectionType $type)
    {
        return (string)$type;
    }

}
