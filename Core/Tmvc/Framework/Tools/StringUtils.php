<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Tools;


class StringUtils
{
    public function starts_with_upper($str) {
        $chr = mb_substr ($str, 0, 1, "UTF-8");
        return mb_strtolower($chr, "UTF-8") != $chr;
    }

    /**
     * @param $delimiter
     * @param $string
     * @param callable|array $callback
     * @return array
     */
    public function explodeAndWalk($delimiter, $string, $callback) {
        $string = explode($delimiter, $string);
        array_walk($string, $callback);
        return $string;
    }
}