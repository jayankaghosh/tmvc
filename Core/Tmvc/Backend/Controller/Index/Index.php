<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Controller\Index;


use Tmvc\Backend\Block\Backend;
use Tmvc\Backend\Block\Section\SectionPool;
use Tmvc\Backend\Controller\AbstractAction;
use Tmvc\Backend\Model\Session;
use Tmvc\Framework\App\Request;
use Tmvc\Framework\App\Response;
use Tmvc\Framework\Controller\Context;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\View\View;

class Index extends AbstractAction
{
    /**
     * @var SectionPool
     */
    private $sectionPool;
    /**
     * @var Response
     */
    private $response;

    /**
     * Index constructor.
     * @param Context $context
     * @param Session $session
     * @param Response $response
     * @param SectionPool $sectionPool
     * @throws TmvcException
     */
    public function __construct(
        Context $context,
        Session $session,
        Response $response,
        SectionPool $sectionPool
    )
    {
        parent::__construct($context, $session, $response);
        $this->sectionPool = $sectionPool;
        $this->response = $response;
    }

    /**
     * @param Request $request
     * @return View|Response
     * @throws TmvcException
     */
    public function execute(Request $request)
    {
        if (!$this->sectionPool->getSection($request->getParam('section'))) {
            return $this->response->setRedirect($this->getUrlBuilder()->getUrl('*', ['section' => 'dashboard']));
        }
        return $this->getView()->loadView("backend", "Tmvc_Backend::backend.phtml", Backend::class);
    }
}