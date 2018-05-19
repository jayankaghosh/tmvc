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
use Tmvc\Framework\View\ViewLoader;

class ObjectManager
{

    const OBJECT_BUCKET_KEY_PREFIX = "_object_bucket_";

    const MAX_OBJECT_LIMIT_KEY = "_tmvc_objectmanager_max_object_limit";

    private static $maxObjectLimit;

    private static $objects = [];

    public static function get($class, $arguments = []) {
        $key = self::OBJECT_BUCKET_KEY_PREFIX.strtolower(str_replace("\\", "_", $class));
        if (!VarBucket::read($key)) {
            $obj = self::create($class, $arguments);
            VarBucket::write($key, $obj);
        }
        return VarBucket::read($key);
    }

    public static function create($class, $arguments = []) {
        if (!isset(self::$objects[$class])) {
            self::$objects[$class] = 0;
        }

        if (self::getMaxObjectLimit() <= 0 || self::$objects[$class] < self::getMaxObjectLimit()) {
            self::$objects[$class]++;
            $arguments = ReflectionClass::parseClass($class, $arguments);
            return new $class(...$arguments);
        } else {
            throw new TmvcException(self::$objects[$class] . " objects already created for class $class");
        }
    }

    public static function getMaxObjectLimit() {
        if (!self::$maxObjectLimit) {
            self::$maxObjectLimit = intval(VarBucket::read(self::MAX_OBJECT_LIMIT_KEY));
        }
        return self::$maxObjectLimit;
    }
}