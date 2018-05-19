<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Tools;


use Tmvc\Framework\Exception\TmvcException;

class ReflectionClass
{
    public static function parseClass($class, $arguments = []) {
        try {
            $constructorArguments = [];
            $class = new \ReflectionClass($class);
            $constructor = $class->getConstructor();
            if ($constructor && count($constructor->getParameters())) {
                foreach ($constructor->getParameters() as $key => $constructorParameter) {
                    if ($constructorParameterClass = $constructorParameter->getClass()) {
                        $constructorParameterName = $constructorParameterClass->getName();
                        $object = ObjectManager::get($constructorParameterName);
                        $constructorArguments[$key] = $object;
                    } else {
                        if (isset($arguments[$constructorParameter->getName()])) {
                            $constructorArguments[$key] = $arguments[$constructorParameter->getName()];
                        }
                    }
                }
            }
            return $constructorArguments;
        } catch (\Exception $exception) {
            throw new TmvcException($exception->getMessage());
        }
    }
}