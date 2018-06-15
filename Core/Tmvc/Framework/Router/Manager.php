<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Router;


class Manager
{
    private $_routerPool;

    public function __construct()
    {
        $this->_routerPool = [];
    }

    public function addRouter(RouterInterface $router) {
        $this->_routerPool[] = $router;
    }

    /**
     * @return RouterInterface[]
     */
    public function getRouterPool() {
        return $this->_routerPool;
    }
}