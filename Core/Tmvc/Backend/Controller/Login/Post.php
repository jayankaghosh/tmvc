<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Controller\Login;


use Tmvc\Backend\Model\AdminManagement;
use Tmvc\Backend\Model\Session;
use Tmvc\Framework\App\Request;
use Tmvc\Framework\App\Response;
use Tmvc\Framework\Controller\AbstractController;
use Tmvc\Framework\Controller\Context;
use Tmvc\Framework\Exception\AuthenticationException;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\View\View;

class Post extends AbstractController
{
    /**
     * @var Response
     */
    private $response;
    /**
     * @var AdminManagement
     */
    private $adminManagement;
    /**
     * @var Session
     */
    private $session;

    /**
     * Post constructor.
     * @param Context $context
     * @param Response $response
     * @param AdminManagement $adminManagement
     * @param Session $session
     */
    public function __construct(
        Context $context,
        Response $response,
        AdminManagement $adminManagement,
        Session $session
    )
    {
        parent::__construct($context);
        $this->response = $response;
        $this->adminManagement = $adminManagement;
        $this->session = $session;
    }

    /**
     * @param Request $request
     * @return View|Response
     * @throws TmvcException
     */
    public function execute(Request $request)
    {
        try {
            if (!$this->session->isLoggedIn()) {
                $admin = $this->adminManagement->authenticate(
                    $request->getPostParam('username'),
                    $request->getPostParam('password')
                );
                $this->session->setUserAsLoggedIn($admin);
            }
            $this->response->setRedirect($this->getUrlBuilder()->getUrl('*'));
        } catch (AuthenticationException $authenticationException) {
            $this->response->setRedirect($this->getUrlBuilder()->getUrl('*/*'));
        }
        return $this->response;
    }
}