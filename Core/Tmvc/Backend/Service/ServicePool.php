<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Service;


use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Tools\ObjectManager;

class ServicePool
{
    /**
     * @var ServiceInterface[]
     */
    private $services;
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * ServicePool constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(
        ObjectManager $objectManager
    )
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $id
     * @param string|ServiceInterface $instance
     * @return $this
     */
    public function addService($id, $instance)
    {
        if (is_string($instance)) {
            $instance = $this->objectManager::get($instance);
        }
        $this->services[$id] = $instance;
        return $this;
    }

    /**
     * @param $id
     * @return ServiceInterface
     * @throws TmvcException
     */
    public function getService($id)
    {
        if (!array_key_exists($id, $this->services)) {
            throw new TmvcException("Service $id does not exist");
        } else if (!$this->services[$id] instanceof ServiceInterface) {
            throw new TmvcException("Services should implement interface ".ServiceInterface::class);
        }
        return $this->services[$id];
    }
}