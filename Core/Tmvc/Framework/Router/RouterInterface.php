<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Router;


use Tmvc\Framework\App\Request;

interface RouterInterface
{
    /**
     * @param Request $request
     * @param string $queryString
     * @param \Application $application
     * @return boolean
     */
    public function route(Request $request, $queryString, \Application $application);
}