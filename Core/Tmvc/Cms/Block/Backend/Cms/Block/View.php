<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Block\Backend\Cms\Block;


use Tmvc\Framework\DataObject;
use Tmvc\Cms\Model\Block\CollectionFactory as CmsBlockCollectionFactory;

class View extends DataObject
{
    /**
     * @var CmsBlockCollectionFactory
     */
    private $cmsBlockCollectionFactory;

    /**
     * View constructor.
     * @param CmsBlockCollectionFactory $cmsBlockCollectionFactory
     */
    public function __construct(
        CmsBlockCollectionFactory $cmsBlockCollectionFactory
    )
    {
        $this->cmsBlockCollectionFactory = $cmsBlockCollectionFactory;
    }

    public function getCollection() {
        return $this->cmsBlockCollectionFactory->create();
    }
}