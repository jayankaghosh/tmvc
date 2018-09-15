<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Controller\Login;


use Tmvc\Backend\Block\LoginForm;
use Tmvc\Backend\Model\Session;
use Tmvc\Framework\App\Request;
use Tmvc\Framework\App\Response;
use Tmvc\Framework\Controller\AbstractController;
use Tmvc\Framework\Controller\Context;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\View\View;

class Index extends AbstractController
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var Response
     */
    private $response;

    /**
     * Index constructor.
     * @param Context $context
     * @param Session $session
     * @param Response $response
     */
    public function __construct(
        Context $context,
        Session $session,
        Response $response
    )
    {
        parent::__construct($context);
        $this->session = $session;
        $this->response = $response;
    }

    /**
     * @param Request $request
     * @return View|Response
     * @throws TmvcException
     */
    public function execute(Request $request)
    {
        if ($this->session->isLoggedIn()) {
            return $this->response->setRedirect($this->getUrlBuilder()->getUrl('*'));
        }
        return $this->getView()->loadView("login.form", "Tmvc_Backend::login_form.phtml", LoginForm::class);
    }
}