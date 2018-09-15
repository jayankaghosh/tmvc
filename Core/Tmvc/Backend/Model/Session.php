<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Model;


use Tmvc\Framework\Session\AbstractSession;
use Tmvc\Framework\Session\StorageFactory;
use Tmvc\Backend\Model\AdminFactory;

class Session extends AbstractSession
{
    /**
     * @var \Tmvc\Backend\Model\AdminFactory
     */
    private $adminFactory;

    /**
     * Session constructor.
     * @param StorageFactory $storageFactory
     * @param \Tmvc\Backend\Model\AdminFactory $adminFactory
     */
    public function __construct(
        \Tmvc\Framework\Session\StorageFactory $storageFactory,
        AdminFactory $adminFactory
    )
    {
        parent::__construct($storageFactory);
        $this->adminFactory = $adminFactory;
    }

    public function getSessionName()
    {
        return "backend";
    }

    /**
     * @param Admin $admin
     * @return Session
     */
    public function setUserAsLoggedIn(Admin $admin) {
        return $this->setData('user', $admin);
    }

    public function logout() {
        return $this->unsetData('user');
    }

    /**
     * @return Admin|null
     */
    public function getLoggedInUser() {
        return $this->getData('user');
    }

    /**
     * @return bool
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function isLoggedIn() {
        $loggedInUser = $this->getLoggedInUser();
        $isLoggedIn = $loggedInUser &&
            $loggedInUser instanceof Admin &&
            $this->adminFactory->create()->load($loggedInUser->getId())->getId();
        if (!$isLoggedIn) {
            $this->logout();
        }
        return $isLoggedIn;
    }
}