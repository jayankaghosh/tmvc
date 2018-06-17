<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Controller;


use Tmvc\Cms\Model\Page;
use Tmvc\Framework\App\Request;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Router\RouterInterface;
use Tmvc\Cms\Model\Page\Collection as CmsPageCollection;
use Tmvc\Cms\Helper\Render as CmsRenderHelper;

class Router implements RouterInterface
{
    /**
     * @var CmsPageCollection
     */
    private $cmsPageCollection;
    /**
     * @var CmsRenderHelper
     */
    private $cmsRenderHelper;

    /**
     * Router constructor.
     * @param CmsPageCollection $cmsPageCollection
     * @param CmsRenderHelper $cmsRenderHelper
     */
    public function __construct(
        CmsPageCollection $cmsPageCollection,
        CmsRenderHelper $cmsRenderHelper
    )
    {
        $this->cmsPageCollection = $cmsPageCollection;
        $this->cmsRenderHelper = $cmsRenderHelper;
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
            /* @var \Tmvc\Cms\Model\Page $cmsPageModel */
            $cmsPageModel = $this->cmsPageCollection->addFieldToFilter('identifier', $queryString)->addFieldToFilter('is_enabled', Page::IS_ENABLED)->getFirstItem();
            if ($cmsPageModel->getId()) {
                $this->cmsRenderHelper->renderPage($cmsPageModel)->sendResponse();
                return true;
            } else {
                return false;
            }
        } catch (TmvcException $tmvcException) {
            return false;
        }
    }
}