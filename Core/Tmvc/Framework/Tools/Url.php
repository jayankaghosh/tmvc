<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Tools;


use Tmvc\Framework\App\Request;

class Url
{
    /**
     * @var AppEnv
     */
    private $appEnv;

    /**
     * @var string
     */
    private $baseUrl;
    /**
     * @var Request
     */
    private $request;

    /**
     * Url constructor.
     * @param AppEnv $appEnv
     * @param Request $request
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function __construct(
        AppEnv $appEnv,
        Request $request
    )
    {
        $this->appEnv = $appEnv;
        $this->baseUrl = $this->appEnv->read('app.base_url');
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getBaseUrl() {
        return (string)$this->baseUrl;
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route, $params = []) {
        $route = explode("/", $route);
        if (count($route) < 3) {
            $route = array_pad($route, 3, Request::PLACEHOLDER);
        } else if (count($route) > 3) {
            $route = array_slice($route, 0, 3);
        }

        if($route[0] === "*") {
            $route[0] = $this->request->getModule();
        }
        if($route[1] === "*") {
            $route[1] = $this->request->getController();
        }
        if($route[2] === "*") {
            $route[2] = $this->request->getAction();
        }

        foreach ($params as $paramName => $paramValue) {
            $route[] = $paramName;
            $route[] = $paramValue;
        }
        return $this->getBaseUrl().implode("/", $route);
    }
}