<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Model;

use Tmvc\Backend\Model\Admin\CollectionFactory as AdminCollectionFactory;
use Tmvc\Backend\Model\AdminFactory;
use Tmvc\Framework\Exception\AuthenticationException;
use Tmvc\Framework\Tools\Crypto\Hasher;

class AdminManagement
{
    /**
     * @var AdminCollectionFactory
     */
    private $adminCollectionFactory;
    /**
     * @var Hasher
     */
    private $hasher;
    /**
     * @var \Tmvc\Backend\Model\AdminFactory
     */
    private $adminFactory;

    /**
     * AdminManagement constructor.
     * @param AdminCollectionFactory $adminCollectionFactory
     * @param Hasher $hasher
     * @param \Tmvc\Backend\Model\AdminFactory $adminFactory
     */
    public function __construct(
        AdminCollectionFactory $adminCollectionFactory,
        Hasher $hasher,
        AdminFactory $adminFactory
    )
    {
        $this->adminCollectionFactory = $adminCollectionFactory;
        $this->hasher = $hasher;
        $this->adminFactory = $adminFactory;
    }

    /**
     * @param string $username
     * @param string $password
     * @return Admin
     * @throws AuthenticationException
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function authenticate($username, $password) {
        $admin = $this->adminCollectionFactory->create()
            ->addFieldToFilter('username', $username)
            ->addFieldToFilter('password', $this->getPasswordHash($password))
            ->getFirstItem();
        if (!$admin->getId()) {
            throw new AuthenticationException("Incorrect username or password");
        }
        return $admin;
    }

    /**
     * @param string $username
     * @param string|null $password
     * @param array $additional
     * @return Admin
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function create($username, $password = null, $additional = []) {
        if (!$password) {
            $password = $this->hasher->hash(uniqid());
        }
        $admin = $this->adminFactory->create();
        $admin->setData($additional)
            ->setData('username', $username)
            ->setData('password', $this->getPasswordHash($password))
            ->save();
        return $admin;
    }

    /**
     * @param string $password
     * @return string
     */
    public function getPasswordHash($password) {
        return $this->hasher->hash($password);
    }
}