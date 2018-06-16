<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Controller;


use Tmvc\Cms\Model\Cms;
use Tmvc\Framework\App\Request;
use Tmvc\Framework\App\Response;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Router\RouterInterface;
use Tmvc\Cms\Model\Cms\Collection as CmsCollection;

class Router implements RouterInterface
{
    /**
     * @var Response
     */
    private $response;
    /**
     * @var CmsCollection
     */
    private $cmsCollection;

    /**
     * Router constructor.
     * @param Response $response
     * @param CmsCollection $cmsCollection
     */
    public function __construct(
        Response $response,
        CmsCollection $cmsCollection
    )
    {
        $this->response = $response;
        $this->cmsCollection = $cmsCollection;
    }

    /**
     * @param Request $request
     * @param string $queryString
     * @param \Application $application
     * @return boolean
     */
    public function route(Request $request, $queryString, \Application $application)
    {
        try {
            /* @var \Tmvc\Cms\Model\Cms $cmsModel */
            $cmsModel = $this->cmsCollection->addFieldToFilter('identifier', $queryString)->addFieldToFilter('is_enabled', Cms::IS_ENABLED)->getFirstItem();
            if ($cmsModel->getId()) {
                $this->response
                    ->setBody($cmsModel->getPageContent())
                    ->setResponseCode($cmsModel->getResponseCode())
                    ->sendResponse();
                return true;
            } else {
                return false;
            }
        } catch (TmvcException $tmvcException) {
            return false;
        }
    }
}