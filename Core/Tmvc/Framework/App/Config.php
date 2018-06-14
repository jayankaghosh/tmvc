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

class Config
{
    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * Config constructor.
     * @param EventManager $eventManager
     */
    public function __construct(
        EventManager $eventManager
    )
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @param $route
     * @param $method
     * @param $callback
     * @throws \Tmvc\Framework\Exception\EntityAlreadyExistsException
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function addRoute($route, $method, $callback) {
        \Router::addRoute($route, $method, $callback);
    }

    /**
     * @param string $eventName
     * @param string $className
     */
    public function addObserver($eventName, $className) {
        $this->eventManager->addObserver($eventName, $className);
    }
}