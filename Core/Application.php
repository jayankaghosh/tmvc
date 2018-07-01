<?php

require_once 'Autoloader.php';

class Application {

    /**
     * @var Autoloader
     */
    private $autoloader;

    protected $queryParameters;

    public function __construct()
    {
        $this->autoloader = new Autoloader();
    }

    public function run() {
        /* Register the autoloader */
        $this->autoloader->register();

        /* Make DB Connection */
        $db = \Tmvc\Framework\Tools\ObjectManager::get(\Tmvc\Framework\Model\Resource\Db::class);
        \Tmvc\Framework\Tools\VarBucket::write(\Tmvc\Framework\Model\Resource\Db::DB_CONNECTION_VAR_KEY, $db);

        /* Read all module config files */
        /* @var \Tmvc\Framework\ConfigReader $configReader */
        $configReader = \Tmvc\Framework\Tools\ObjectManager::get(\Tmvc\Framework\ConfigReader::class);
        $configReader->read();

        /* Set the app mode */
        $mode = \Tmvc\Framework\Tools\ObjectManager::get(\Tmvc\Framework\App\Mode::class)->getMode();
        switch ($mode) {
            case \Tmvc\Framework\App\Mode::MODE_DEVELOPER:
                error_reporting(E_ALL);
                ini_set('display_errors', true);
                ini_set('display_startup_errors', true);
                break;
            case \Tmvc\Framework\App\Mode::MODE_PRODUCTION:
                error_reporting(0);
                ini_set('display_errors', false);
                ini_set('display_startup_errors', false);
                break;
        }

        /* Set MAX_OBJECT_CREATION_LIMIT */
        /* @var \Tmvc\Framework\Tools\AppEnv $appEnv */
        $appEnv = \Tmvc\Framework\Tools\ObjectManager::get(\Tmvc\Framework\Tools\AppEnv::class);
        \Tmvc\Framework\Tools\VarBucket::write(\Tmvc\Framework\Tools\ObjectManager::MAX_OBJECT_LIMIT_KEY, $appEnv->read('max_object_limit'));

        /* Run module manager to validate modules */
        /* @var \Tmvc\Framework\Module\Manager $moduleManager */
        $moduleManager = \Tmvc\Framework\Tools\ObjectManager::get(\Tmvc\Framework\Module\Manager::class);
        $moduleManager->manage();

        /** @var \Tmvc\Framework\Application\ApplicationInterface $application */
        $application = \Tmvc\Framework\Tools\ObjectManager::get($this->getApplicationType());
        $application->route();
    }

    /**
     * @return string
     */
    private function getApplicationType() {
        if (php_sapi_name() === "cli") {
            return \Tmvc\Framework\Application\Cli::class;
        } else {
            return \Tmvc\Framework\Application\Http::class;
        }
    }
}