<?php

use Tmvc\Framework\Controller\AbstractController;

class Router {

    const CUSTOM_ROUTES_VAR_KEY = "_tmvc_custom_routes";

    protected $initiated = false;

    /**
     * @param \Tmvc\Framework\App\Request $request
     * @throws \Tmvc\Framework\Exception\ArgumentMismatchException
     * @throws \Tmvc\Framework\Exception\EntityNotFoundException
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function route(\Tmvc\Framework\App\Request $request) {

        try {

            /* Check custom routes first */
            $customRouteKey = $request->getFullRoute()."_".$request->getMethod();
            if (isset(self::getRoutes()[$customRouteKey])) {
                $customRoute = self::getRoutes()[$customRouteKey];
                $callback = $customRoute['callback'];
                switch (gettype($callback)) {
                    case "object":
                        if (is_callable($callback)) {
                            return $callback($request);
                        }
                    case "string":
                        return $this->executeController($callback, $request);
                }
            }

            if (
                ($request->getModule() === $request->getController()) &&
                ($request->getController() === $request->getAction()) &&
                ($request->getAction() === "index")
            ) {
                $indexPage = array_pad(explode("/", $this->getAppEnv()->read('default_pages.index')), 3, "index");
                $request
                    ->setModule($indexPage[0])
                    ->setController($indexPage[1])
                    ->setAction($indexPage[2]);
            }

            $route = [
                $request->getModule(),
                "Controller",
                $request->getController(),
                $request->getAction()
            ];

            $className = implode("\\", explode(" ", ucwords(implode(" ", $route))));
            $this->executeController($className, $request);
        } catch (\Tmvc\Framework\Exception\EntityNotFoundException $entityNotFoundException) {
            if (!$this->initiated) {
                $noRoutePage = array_pad(explode("/", $this->getAppEnv()->read('default_pages.404')), 3, "index");
                $this->initiated = true;
                $request
                    ->setModule($noRoutePage[0])
                    ->setController($noRoutePage[1])
                    ->setAction($noRoutePage[2]);
                $this->route($request);
            } else {
                throw $entityNotFoundException;
            }
        }
    }


    /**
     * @param string|object $class
     * @param \Tmvc\Framework\App\Request $request
     * @throws \Tmvc\Framework\Exception\ArgumentMismatchException
     * @throws \Tmvc\Framework\Exception\EntityNotFoundException
     */
    protected function executeController ($class, $request) {
        /* @var AbstractController $obj */
        switch (gettype($class)) {
            case "string":
                try {
                    $obj = \Tmvc\Framework\Tools\ObjectManager::get($class);
                } catch (\Exception $exception) {
                    throw new \Tmvc\Framework\Exception\EntityNotFoundException("Controller Action Not found");
                }
                break;
            case "object":
                $obj = $class;
                break;
            default:
                throw new InvalidArgumentException(gettype($class)." is not a valid controller type");
        }
        if ($obj instanceof AbstractController) {
            $result = $obj->execute($request);

            if ($result instanceof \Tmvc\Framework\View\View) {
                /* @var \Tmvc\Framework\App\Response $response */
                $response = \Tmvc\Framework\Tools\ObjectManager::get(\Tmvc\Framework\App\Response::class);
                $response->setBody($result->render());
            } else if ($result instanceof \Tmvc\Framework\App\Response) {
                $response = $result;
            } else {
                throw new \Tmvc\Framework\Exception\ArgumentMismatchException("Controllers should only return object of type " . \Tmvc\Framework\View\View::class . " or " . \Tmvc\Framework\App\Response::class);
            }
            $response->sendResponse();
        } else {
            throw new \Tmvc\Framework\Exception\EntityNotFoundException("Controller Action Not found");
        }
    }

    /**
     * @param string $route
     * @param string $method
     * @param AbstractController|array|callable $callback
     * @throws \Tmvc\Framework\Exception\EntityAlreadyExistsException
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public static function addRoute($route, $method, $callback) {
        $key = $route."_".$method;
        $routes = self::getRoutes();
        if (array_key_exists($key, $routes)) {
            throw new \Tmvc\Framework\Exception\EntityAlreadyExistsException("Route $route with method $method was already defined");
        } else {
            $routes[$key] = [
                'route'     =>  $route,
                'method'    =>  $method,
                'callback'  =>  $callback
            ];
            \Tmvc\Framework\Tools\VarBucket::write(self::CUSTOM_ROUTES_VAR_KEY, $routes, true);
        }
    }

    public static function getRoutes() {
       try {
           if (!\Tmvc\Framework\Tools\VarBucket::read(self::CUSTOM_ROUTES_VAR_KEY)) {
               \Tmvc\Framework\Tools\VarBucket::write(self::CUSTOM_ROUTES_VAR_KEY, []);
           }
           return \Tmvc\Framework\Tools\VarBucket::read(self::CUSTOM_ROUTES_VAR_KEY);
       } catch (\Tmvc\Framework\Exception\TmvcException $tmvcException) {
           return [];
       }
    }

    /**
     * @return \Tmvc\Framework\Tools\AppEnv
     */
    private function getAppEnv() {
        return \Tmvc\Framework\Tools\ObjectManager::get(\Tmvc\Framework\Tools\AppEnv::class);
    }

}