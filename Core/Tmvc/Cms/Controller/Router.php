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
use Tmvc\Framework\Application\ApplicationInterface;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Router\RouterInterface;
use Tmvc\Cms\Model\Page\CollectionFactory as CmsPageCollectionFactory;
use Tmvc\Cms\Helper\Render as CmsRenderHelper;

class Router implements RouterInterface
{
    /**
     * @var CmsPageCollectionFactory
     */
    private $cmsPageCollectionFactory;
    /**
     * @var CmsRenderHelper
     */
    private $cmsRenderHelper;

    /**
     * Router constructor.
     * @param CmsPageCollectionFactory $cmsPageCollectionFactory
     * @param CmsRenderHelper $cmsRenderHelper
     */
    public function __construct(
        CmsPageCollectionFactory $cmsPageCollectionFactory,
        CmsRenderHelper $cmsRenderHelper
    )
    {
        $this->cmsPageCollectionFactory = $cmsPageCollectionFactory;
        $this->cmsRenderHelper = $cmsRenderHelper;
    }

    /**
     * @param Request $request
     * @param string $queryString
     * @param ApplicationInterface $application
     * @return boolean
     * @throws TmvcException
     */
    public function route(Request $request, $queryString, ApplicationInterface $application)
    {
        /* @var \Tmvc\Cms\Model\Page $cmsPageModel */
        $cmsPageModel = $this->cmsPageCollectionFactory->create()->addFieldToFilter('identifier', $queryString)->addFieldToFilter('is_enabled', Page::IS_ENABLED)->getFirstItem();
        if ($cmsPageModel->getId()) {
            $this->cmsRenderHelper->renderPage($cmsPageModel)->sendResponse();
            return true;
        } else {
            return false;
        }
    }
}