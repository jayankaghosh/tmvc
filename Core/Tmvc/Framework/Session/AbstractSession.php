<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Session;

use Tmvc\Framework\Session\StorageFactory;
use Tmvc\Framework\Session\Storage;

abstract class AbstractSession
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * AbstractSession constructor.
     * @param StorageFactory $storageFactory
     */
    public function __construct(
        StorageFactory $storageFactory
    )
    {
        $this->storage = $storageFactory->create([
            "sessionName"  =>  $this->getSessionName()
        ]);
    }

    abstract public function getSessionName();

    /**
     * @return \Tmvc\Framework\Session\Storage
     */
    public function getStorage(): \Tmvc\Framework\Session\Storage
    {
        return $this->storage;
    }

    /**
     * @param string|null $name
     * @return mixed
     */
    public function getData($name = null) {
        return $this->getStorage()->get($name);
    }

    /**
     * @param string $name
     * @param mixed|null $value
     * @return $this
     */
    public function setData($name, $value = null) {
        $this->getStorage()->set($name, $value);
        return $this;
    }

    public function unsetData($name) {
        $this->getStorage()->uns($name);
        return $this;
    }

    public function __call($name, $arguments) {
        $type = substr($name, 0, 3);
        $key = $this->camelToSnakeCase(substr($name, 3));
        switch ($type) {
            case "get":
                return $this->getData($key);
            case "set":
                return $this->setData($key, $arguments[0]);
            case "uns":
                return $this->unsetData($key);
        }
    }

    public function camelToSnakeCase($string = "") {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
}