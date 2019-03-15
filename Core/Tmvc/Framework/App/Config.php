<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\App;


use Tmvc\Framework\Event\Manager as EventManager;
use Tmvc\Framework\Tools\ObjectManager;
use Tmvc\Framework\Router\Manager as RouterManager;

class Config
{
    /**
     * @var EventManager
     */
    private $eventManager;
    /**
     * @var RouterManager
     */
    private $routerManager;

    /**
     * Config constructor.
     * @param EventManager $eventManager
     * @param RouterManager $routerManager
     */
    public function __construct(
        EventManager $eventManager,
        RouterManager $routerManager
    )
    {
        $this->eventManager = $eventManager;
        $this->routerManager = $routerManager;
    }

    /**
     * @param $route
     * @param $method
     * @param $callback
     * @param bool $override
     * @throws \Tmvc\Framework\Exception\EntityAlreadyExistsException
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function addRoute($route, $method, $callback, $override = false) {
        \Tmvc\Framework\Router\Router::addRoute($route, $method, $callback, $override);
    }

    /**
     * @param string $eventName
     * @param string $className
     */
    public function addObserver($eventName, $className) {
        $this->eventManager->addObserver($eventName, $className);
    }

    /**
     * @param string $sourceClass
     * @param string $preferredClass
     */
    public function addClassPreference($sourceClass, $preferredClass) {
        ObjectManager::addClassPreference($sourceClass, $preferredClass);
    }

    /**
     * @param string $routerName
     */
    public function addRouter($routerName) {
        $router = ObjectManager::get($routerName);
        $this->routerManager->addRouter($router);
    }
}