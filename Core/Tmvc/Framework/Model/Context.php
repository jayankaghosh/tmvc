<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Model;

use Tmvc\Framework\Cache;
use Tmvc\Framework\Event\Manager as EventManager;

class Context
{
    /**
     * @var EventManager
     */
    private $eventManager;
    /**
     * @var Cache
     */
    private $cache;

    /**
     * Context constructor.
     * @param EventManager $eventManager
     * @param Cache $cache
     */
    public function __construct(
        EventManager $eventManager,
        Cache $cache
    )
    {
        $this->eventManager = $eventManager;
        $this->cache = $cache;
    }

    /**
     * @return EventManager
     */
    public function getEventManager(): EventManager
    {
        return $this->eventManager;
    }

    /**
     * @return Cache
     */
    public function getCache(): Cache
    {
        return $this->cache;
    }
}