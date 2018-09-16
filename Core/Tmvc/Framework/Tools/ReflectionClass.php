<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Tools;


use Tmvc\Framework\Code\FactoryGenerator;
use Tmvc\Framework\Exception\TmvcException;

class ReflectionClass
{
    public static function parseClass($class, $arguments = []) {
        try {
            $constructorArguments = [];
            try {
                $class = new \ReflectionClass($class);
            } catch (\Exception $exception) {
                self::generateFactoryIfPossible($class);
                $class = new \ReflectionClass($class);
            }
            $constructor = $class->getConstructor();
            if ($constructor && count($constructor->getParameters())) {
                foreach ($constructor->getParameters() as $key => $constructorParameter) {
                    if (array_key_exists($constructorParameter->getName(), $arguments)) {
                        $constructorArguments[$key] = $arguments[$constructorParameter->getName()];
                    } else {
                        try {
                            $constructorParameterClass = $constructorParameter->getClass();
                        } catch (\Exception $exception) {
                            self::generateFactoryIfPossible($constructorParameter->getType());
                            $constructorParameterClass = $constructorParameter->getClass();
                        }
                        if ($constructorParameterClass) {
                            $constructorParameterName = $constructorParameterClass->getName();
                            $object = ObjectManager::get($constructorParameterName);
                            $constructorArguments[$key] = $object;
                        }
                    }
                }
            }
            return $constructorArguments;
        } catch (\Exception $exception) {
            throw new TmvcException($exception->getMessage());
        }
    }

    private static function generateFactoryIfPossible($class) {
        $factoryGenerator = new FactoryGenerator();
        $factorySuffix = "Factory";
        if (substr($class, -1*strlen($factorySuffix)) === $factorySuffix) {
            $originalClass = substr($class, 0, -1*strlen($factorySuffix));
            $factoryGenerator->generate($originalClass);
        }
    }
}