<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Application;


use Tmvc\Framework\App\Request;
use Tmvc\Framework\Exception\UrlMismatchException;
use Tmvc\Framework\Router\Manager as RouterManager;
use Tmvc\Framework\Tools\Url;

class Http implements ApplicationInterface
{
    const INDEX_FILE = "index.php";

    /**
     * @var array
     */
    private $serverParameters;

    protected $queryParameters;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var RouterManager
     */
    private $routerManager;
    /**
     * @var Url
     */
    private $url;

    /**
     * Http constructor.
     * @param Request $request
     * @param RouterManager $routerManager
     * @param Url $url
     */
    public function __construct(
        Request $request,
        RouterManager $routerManager,
        Url $url
    )
    {
        $this->serverParameters = $_SERVER;
        $this->request = $request;
        $this->routerManager = $routerManager;
        $this->url = $url;
    }

    /**
     * @return void
     */
    public function route() {
        $this->_route();
    }

    /**
     * @param string $queryString
     */
    public function forward($queryString) {
        $this->_route($queryString);
    }

    protected function _route($queryString = null) {
        /* Route the query */

        if (!$queryString) {
            try {
                $queryString = $this->getQueryString();
            } catch (UrlMismatchException $urlMismatchException) {
                header("Location: ".$this->url->getBaseUrl(), true, 302);
                exit(1);
            }
        }

        $query = $this->getQueryParameters($queryString);
        $request = $this->request;
        $request
            ->setModule($query['module'])
            ->setController($query['controller'])
            ->setAction($query['action'])
            ->setParams($query['params'])
            ->setPostParams($query['post_params'])
            ->setMethod($this->serverParameters['REQUEST_METHOD']);

        $routers = $this->routerManager->getRouterPool();

        $routed = false;
        foreach ($routers as $router) {
            if ($routed = $router->route($request, $queryString, $this)) {
                break;
            }
        }

        /* Call default router if none of the routers in the pool can handle the request */
        if (!$routed) {
            /* @var \Tmvc\Framework\Router\RouterInterface $router */
            $router = \Tmvc\Framework\Tools\ObjectManager::get(\Tmvc\Framework\Router\Router::class);
            $router->route($request, $queryString, $this);
        }
    }

    protected function getQueryParameter($type = "module") {
        return isset($this->getQueryParameters()[$type]) ? $this->getQueryParameters()[$type] : $this->getQueryParameters()["module"];
    }

    protected function getQueryParameters($queryString = null) {
        if (!$this->queryParameters || $queryString) {
            if (!$queryString) {
                $queryString = $this->getQueryString();
            }

            /* Remove GET Params from query string */
            $queryString = str_replace("?".strtolower(urldecode(http_build_query($_GET))), "", $queryString);

            $queryParameters = array_filter(explode("/", $queryString));
            $params = $_GET;
            if (count($queryParameters) < 3) {
                $queryParameters = array_pad($queryParameters, 3, \Tmvc\Framework\App\Request::PLACEHOLDER);
            } else if ($queryParameters > 3) {
                $additionalParams = array_slice($queryParameters, 3);
                $index = 0;
                while ($index < count($additionalParams)) {
                    $params[$additionalParams[$index]] = isset($additionalParams[$index + 1]) ? $additionalParams[$index + 1] : null;
                    $index += 2;
                }
            }
            $this->queryParameters = [
                'module'        => $queryParameters[0],
                'controller'    => $queryParameters[1],
                'action'        => $queryParameters[2],
                'params'        => $params,
                'post_params'   => $_POST
            ];
        }
        return $this->queryParameters;
    }

    /**
     * @return string
     * @throws UrlMismatchException
     */
    protected function getQueryString() {
        if (
            $this->serverParameters &&
            isset($this->serverParameters['REQUEST_URI']) &&
            isset($this->serverParameters['HTTP_HOST'])
        ) {
            /* Fetch Host */
            $host = $this->serverParameters['HTTP_HOST'];

            /* Fetch request URI */
            $requestUri = $this->serverParameters['REQUEST_URI'];

            /* Check if site uses HTTPS */
            $isSecure = isset($this->serverParameters['HTTPS']) && $this->serverParameters['HTTPS'] === 'on';

            $fullUrl = ($isSecure ? "https" : "http") . "://$host$requestUri";

            if (strpos($fullUrl, $this->url->getBaseUrl()) !== 0) {
                throw new UrlMismatchException("Base URL must be ".$this->url->getBaseUrl());
            }

            $requestUri = str_replace($this->url->getBaseUrl(), "", $fullUrl);

        } else {
            $requestUri = "";
        }
        return urldecode(strtolower($requestUri));
    }
}