<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Block\Backend\Cms\Page;


use Tmvc\Framework\DataObject;
use Tmvc\Cms\Model\Page\CollectionFactory as CmsPageCollectionFactory;

class View extends DataObject
{
    /**
     * @var CmsPageCollectionFactory
     */
    private $cmsPageCollectionFactory;

    /**
     * View constructor.
     * @param CmsPageCollectionFactory $cmsPageCollectionFactory
     */
    public function __construct(
        CmsPageCollectionFactory $cmsPageCollectionFactory
    )
    {
        $this->cmsPageCollectionFactory = $cmsPageCollectionFactory;
    }

    public function getCollection() {
        return $this->cmsPageCollectionFactory->create();
    }
}