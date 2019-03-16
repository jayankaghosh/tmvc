<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Block\Backend\Sections\Page;


use Tmvc\Backend\Block\Backend\Sections\AbstractSection;
use Tmvc\Backend\Block\Backend\Sections\Context;
use Tmvc\Cms\Model\Page\CollectionFactory as PageCollectionFactory;

class Listing extends AbstractSection
{
    /**
     * @var PageCollectionFactory
     */
    private $pageCollectionFactory;

    /**
     * Listing constructor.
     * @param Context $context
     * @param PageCollectionFactory $pageCollectionFactory
     * @param $currentSection
     */
    public function __construct(
        Context $context,
        PageCollectionFactory $pageCollectionFactory,
        $currentSection
    )
    {
        parent::__construct($context, $currentSection);
        $this->pageCollectionFactory = $pageCollectionFactory;
    }

    public function loadWidget()
    {
        return $this->getNewListingWidget(
            $this->pageCollectionFactory->create()->addFieldToSelect([
                'cms_page_id',
                'identifier',
                'response_code',
                'is_enabled'
            ])
        );
    }
}