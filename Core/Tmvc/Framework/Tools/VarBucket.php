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

class VarBucket
{
    private static $varBucket = [];

    public static function write($key, $value, $override = false) {
        if (self::read($key) && !$override) {
            throw new TmvcException("Var $key already exists");
        }
        self::$varBucket[$key] = $value;
    }

    public static function read($key, $elegant = true) {
        if (!isset(self::$varBucket[$key])) {
            if ($elegant) {
                return null;
            } else {
                throw new TmvcException("Var $key not set");
            }
        }
        return self::$varBucket[$key];
    }
}