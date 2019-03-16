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
use Tmvc\Cms\Block\Backend\Sections\Page\Listing\Renderer;
use Tmvc\Cms\Model\Page\CollectionFactory as PageCollectionFactory;

class Listing extends AbstractSection
{
    /**
     * @var PageCollectionFactory
     */
    private $pageCollectionFactory;
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * Listing constructor.
     * @param Context $context
     * @param PageCollectionFactory $pageCollectionFactory
     * @param $currentSection
     * @param Renderer $renderer
     */
    public function __construct(
        Context $context,
        PageCollectionFactory $pageCollectionFactory,
        $currentSection,
        Renderer $renderer
    )
    {
        parent::__construct($context, $currentSection);
        $this->pageCollectionFactory = $pageCollectionFactory;
        $this->renderer = $renderer;
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
        )->setRenderer(
            $this->renderer
        )->setActions([
            [
                'label'     =>  "Edit",
                'section'   =>  "cms-page-addedit"
            ],
            [
                'label'     =>  "Delete",
                'service'   =>  "cms_page_delete"
            ]
        ]);
    }
}