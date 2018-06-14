<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Event;


use Tmvc\Framework\Tools\ObjectManager;

class Manager
{
    private $_observers = [];

    public function dispatch($eventName, $eventData) {
        if (isset($this->_observers[$eventName])) {
            foreach ($this->_observers[$eventName] as $observer) {
                /* @var \Tmvc\Framework\Event\ObserverInterface $object */
                $object = ObjectManager::get($observer);
                $object->execute($eventName, $eventData);
            }
        }
    }

    public function addObserver($eventName, $className) {
        if (!isset($this->_observers[$eventName])) {
            $this->_observers[$eventName] = [];
        }
        $this->_observers[$eventName][] = $className;
    }
}