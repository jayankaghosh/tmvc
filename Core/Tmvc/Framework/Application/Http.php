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
use Tmvc\Framework\Router\Manager as RouterManager;

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
     * Http constructor.
     * @param Request $request
     * @param RouterManager $routerManager
     */
    public function __construct(
        Request $request,
        RouterManager $routerManager
    )
    {
        $this->serverParameters = $_SERVER;
        $this->request = $request;
        $this->routerManager = $routerManager;
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
            $queryString = $this->getQueryString();
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
            $router = \Tmvc\Framework\Tools\ObjectManager::get(\Router::class);
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
            $queryString = str_replace("?".urldecode(http_build_query($_GET)), "", $queryString);

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

    protected function getQueryString() {
        if (
            $this->serverParameters &&
            isset($this->serverParameters['REQUEST_URI']) &&
            isset($this->serverParameters['PHP_SELF'])
        ) {
            /* Fetch request URI */
            $requestUri = $this->serverParameters['REQUEST_URI'];
            /* Get index file URI from PHP_SELF */
            $indexFileUri = str_replace(self::INDEX_FILE, "", $this->serverParameters['PHP_SELF']);

            /* Remove URI leading to index file from Request URI */
            $requestUri = str_replace($indexFileUri, "", $requestUri);

            /* Remove index file itself from Request URI */
            $requestUri = str_replace(self::INDEX_FILE, "", $requestUri);

        } else {
            $requestUri = "";
        }
        return urldecode(strtolower($requestUri));
    }
}