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
     * AdminManagement constructor.
     * @param AdminCollectionFactory $adminCollectionFactory
     * @param Hasher $hasher
     */
    public function __construct(
        AdminCollectionFactory $adminCollectionFactory,
        Hasher $hasher
    )
    {
        $this->adminCollectionFactory = $adminCollectionFactory;
        $this->hasher = $hasher;
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
     * @param string $password
     * @return string
     */
    public function getPasswordHash($password) {
        return $this->hasher->hash($password);
    }
}