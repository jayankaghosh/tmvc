<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Session;


use Tmvc\Framework\DataObject;
use Tmvc\Framework\DataObjectFactory;
use Tmvc\Framework\Tools\File;

class Storage
{

    protected $storageDirectory = PROJECT_ROOT_PATH."var/session/";
    /**
     * @var File
     */
    private $file;
    /**
     * @var string
     */
    private $sessionName;

    /**
     * @var string
     */
    private $sessionId = "";
    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;

    /**
     * @var DataObject
     */
    private $sessionData = null;

    /**
     * Storage constructor.
     * @param File $file
     * @param DataObjectFactory $dataObjectFactory
     * @param string $sessionName
     */
    public function __construct(
        File $file,
        DataObjectFactory $dataObjectFactory,
        $sessionName = ""
    )
    {
        $this->file = $file;
        $this->sessionName = $sessionName;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->initSession();
    }

    protected function initSession() {
        if (!$this->sessionStarted()) {
            session_start();
        }
        $this->sessionId = session_id();
        if (!isset($_SESSION['ACTIVE_SESSIONS'])) {
            $_SESSION['ACTIVE_SESSIONS'] = [];
        }
        $_SESSION['ACTIVE_SESSIONS'][$this->sessionName] = true;
        $this->sessionName = md5($this->sessionName);
        $this->set("session_name", $this->sessionName);
    }

    /**
     * @return bool
     */
    protected function sessionStarted() {
        return session_status() !== PHP_SESSION_NONE;
    }

    protected function getStorageDirectory() {
        return $this->storageDirectory.$this->sessionId."/".$this->sessionName;
    }

    /**
     * @return DataObject
     */
    protected function getSessionData() {
        if (!$this->sessionData) {
            $file = $this->file->load($this->getStorageDirectory());
            $data = $file ? unserialize($file->read()) : [];
            $this->sessionData = $this->dataObjectFactory->create()->setData($data);
        }
        return $this->sessionData;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name) {
        return $this->getSessionData()->getData($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function set($name, $value) {
        $this->getSessionData()->setData($name, $value);
        $this->file->load($this->getStorageDirectory())->write(serialize($this->getSessionData()->getData()));
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function uns($name) {
        $this->getSessionData()->unsetData($name);
        $this->file->load($this->getStorageDirectory())->write(serialize($this->getSessionData()->getData()));
        return $this;
    }
}