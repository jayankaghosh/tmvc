<?php

require_once 'Autoloader.php';

class Application {

    const INDEX_FILE = "index.php";

    /**
     * @var array
     */
    private $serverParameters;

    /**
     * @var Autoloader
     */
    private $autoloader;

    protected $queryParameters;

    public function __construct(array $serverParameters)
    {
        $this->serverParameters = $serverParameters;
        $this->autoloader = new Autoloader();
    }

    public function run() {
        /* Register the autoloader */
        $this->autoloader->register();

        /* Set MAX_OBJECT_CREATION_LIMIT */
        /* @var \Tmvc\Framework\Tools\AppEnv $appEnv */
        $appEnv = \Tmvc\Framework\Tools\ObjectManager::get(\Tmvc\Framework\Tools\AppEnv::class);
        \Tmvc\Framework\Tools\VarBucket::write(\Tmvc\Framework\Tools\ObjectManager::MAX_OBJECT_LIMIT_KEY, $appEnv->read('max_object_limit'));

        /* Make DB Connection */
        $db = \Tmvc\Framework\Tools\ObjectManager::get(\Tmvc\Framework\Model\Resource\Db::class);
        \Tmvc\Framework\Tools\VarBucket::write(\Tmvc\Framework\Model\Resource\Db::DB_CONNECTION_VAR_KEY, $db);

        /* Route the query */
        /* @var Router $router */
        $router = \Tmvc\Framework\Tools\ObjectManager::get(Router::class);
        $router->route($this->getQueryParameters());
    }

    protected function getQueryParameter($type = "module") {
        return isset($this->getQueryParameters()[$type]) ? $this->getQueryParameters()[$type] : $this->getQueryParameters()["module"];
    }

    protected function getQueryParameters() {
        if (!$this->queryParameters) {
            $queryString = $this->getQueryString();
            $queryParameters = array_filter(explode("/", $queryString));
            if (count($queryParameters) < 3) {
                $queryParameters = array_pad($queryParameters, 3, "index");
            }
            $this->queryParameters = [
                'module' => $queryParameters[0],
                'controller' => $queryParameters[1],
                'action' => $queryParameters[2]
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
        return strtolower($requestUri);
    }
}