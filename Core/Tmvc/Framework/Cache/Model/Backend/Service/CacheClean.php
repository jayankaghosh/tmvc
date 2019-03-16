<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Cache\Model\Backend\Service;

use Tmvc\Backend\Service\ServiceInterface;
use Tmvc\Framework\App\Request;
use Tmvc\Framework\App\Response;
use Tmvc\Framework\Cache;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Tools\Url;
use Tmvc\Ui\Model\Message\Manager as MessageManager;

class CacheClean implements ServiceInterface
{
    /**
     * @var Cache
     */
    private $cache;
    /**
     * @var MessageManager
     */
    private $messageManager;
    /**
     * @var Url
     */
    private $url;

    /**
     * Clean constructor.
     * @param Cache $cache
     * @param MessageManager $messageManager
     * @param Url $url
     */
    public function __construct(
        Cache $cache,
        MessageManager $messageManager,
        Url $url
    )
    {
        $this->cache = $cache;
        $this->messageManager = $messageManager;
        $this->url = $url;
    }


    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws TmvcException
     */
    public function execute(Request $request, Response $response)
    {
        $cacheTypesToClean = array_keys($request->getPostParam('cachekey', []));
        foreach ($cacheTypesToClean as $cacheType) {
            $this->cache->delete($cacheType);
        }
        $this->messageManager->addMessage("Cache types deleted successfully", MessageManager::TYPE_SUCCESS);
        return $response->setRedirect($this->url->getRefererUrl());
    }
}