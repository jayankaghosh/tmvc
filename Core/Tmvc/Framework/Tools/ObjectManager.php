<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Tools;


class ObjectManager
{

    const OBJECT_BUCKET_KEY_PREFIX = "_object_bucket_";

    public static function get($class, $arguments = []) {
        $key = self::OBJECT_BUCKET_KEY_PREFIX.strtolower(str_replace("\\", "_", $class));
        if (!VarBucket::read($key)) {
            $obj = self::create($class, $arguments);
            VarBucket::write($key, $obj);
        }
        return VarBucket::read($key);
    }

    public static function create($class, $arguments = []) {
        return new $class(...$arguments);
    }
}