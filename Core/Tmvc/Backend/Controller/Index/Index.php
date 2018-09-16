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
     * @var Backend
     */
    private $backend;

    /**
     * Index constructor.
     * @param Context $context
     * @param Session $session
     * @param Response $response
     * @param SectionPool $sectionPool
     * @param Backend $backend
     * @throws TmvcException
     */
    public function __construct(
        Context $context,
        Session $session,
        Response $response,
        SectionPool $sectionPool,
        Backend $backend
    )
    {
        parent::__construct($context, $session, $response);
        $this->sectionPool = $sectionPool;
        $this->response = $response;
        $this->backend = $backend;
    }

    /**
     * @param Request $request
     * @return View|Response
     * @throws TmvcException
     */
    public function execute(Request $request)
    {
        $currentSection = $this->getCurrentSection($request);
        if (!$currentSection) {
            return $this->response->setRedirect($this->getUrlBuilder()->getUrl('*', ['section' => 'dashboard']));
        } else if ($request->getParam('isAjax')) {
            $data = [
                'status'    =>  true,
                'message'   =>  $this->backend->loadSectionContent()
            ];
            return $this->response->setBody(\json_encode($data))->addHeader('Content-Type', "application/json");
        }
        return $this->getView()->loadView("backend", "Tmvc_Backend::backend.phtml", Backend::class);
    }

    protected function getCurrentSection(Request $request) {
        return $this->sectionPool->getSection($request->getParam('section'));
    }
}