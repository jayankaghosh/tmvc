<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\UrlRewrite\Controller;


use Tmvc\Framework\App\Request;
use Tmvc\Framework\Router\RouterInterface;

class Router implements RouterInterface
{

    /**
     * @param Request $request
     * @param string $queryString
     * @return boolean
     */
    public function route(Request $request, $queryString)
    {
        return false; // Let the next router in the pool handle the request
    }
}