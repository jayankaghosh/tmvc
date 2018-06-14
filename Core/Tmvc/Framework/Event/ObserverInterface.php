<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Event;


interface ObserverInterface
{
    /**
     * @param string $eventName
     * @param array $eventData
     * @return void
     */
    public function execute($eventName, $eventData);
}