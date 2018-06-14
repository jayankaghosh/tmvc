<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\App;


class Config
{
    /**
     * @param $route
     * @param $method
     * @param $callback
     * @throws \Tmvc\Framework\Exception\EntityAlreadyExistsException
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function addRoute($route, $method, $callback) {
        \Router::addRoute($route, $method, $callback);
    }
}