<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Cache\Controller\Backend\Cache;


use Tmvc\Backend\Controller\AbstractAction;
use Tmvc\Backend\Model\Session;
use Tmvc\Framework\App\Request;
use Tmvc\Framework\App\Response;
use Tmvc\Framework\Cache;
use Tmvc\Framework\Controller\Context;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\View\View;
use Tmvc\Ui\Model\Message\Manager as MessageManager;

class Clean extends AbstractAction
{
    /**
     * @var Response
     */
    private $response;
    /**
     * @var Cache
     */
    private $cache;
    /**
     * @var MessageManager
     */
    private $messageManager;

    /**
     * Clean constructor.
     * @param Context $context
     * @param Session $session
     * @param Response $response
     * @param Cache $cache
     * @param MessageManager $messageManager
     * @throws TmvcException
     */
    public function __construct(
        Context $context,
        Session $session,
        Response $response,
        Cache $cache,
        MessageManager $messageManager
    )
    {
        parent::__construct($context, $session, $response);
        $this->response = $response;
        $this->cache = $cache;
        $this->messageManager = $messageManager;
    }

    /**
     * @param Request $request
     * @return View|Response
     * @throws TmvcException
     */
    public function execute(Request $request)
    {
        $cacheTypesToClean = array_keys($request->getPostParam('cachekey', []));
        foreach ($cacheTypesToClean as $cacheType) {
            $this->cache->delete($cacheType);
        }
        $this->messageManager->addMessage("Cache types deleted successfully", MessageManager::TYPE_SUCCESS);
        return $this->response->setRedirect($this->getUrlBuilder()->getRefererUrl());
    }
}