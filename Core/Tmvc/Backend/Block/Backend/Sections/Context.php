<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Block\Backend\Sections;

use Tmvc\Framework\Tools\Url as UrlBuilder;
use Tmvc\Ui\Block\Widget\ListingFactory as ListingWidgetFactory;
use Tmvc\Ui\Block\Widget\FormFactory as FormWidgetFactory;

class Context
{
    /**
     * @var UrlBuilder
     */
    private $urlBuilder;
    /**
     * @var ListingWidgetFactory
     */
    private $listingWidgetFactory;
    /**
     * @var FormWidgetFactory
     */
    private $formWidgetFactory;

    /**
     * Context constructor.
     * @param UrlBuilder $urlBuilder
     * @param ListingWidgetFactory $listingWidgetFactory
     * @param FormWidgetFactory $formWidgetFactory
     */
    public function __construct(
        UrlBuilder $urlBuilder,
        ListingWidgetFactory $listingWidgetFactory,
        FormWidgetFactory $formWidgetFactory
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->listingWidgetFactory = $listingWidgetFactory;
        $this->formWidgetFactory = $formWidgetFactory;
    }

    /**
     * @return UrlBuilder
     */
    public function getUrlBuilder(): UrlBuilder
    {
        return $this->urlBuilder;
    }

    /**
     * @return ListingWidgetFactory
     */
    public function getListingWidgetFactory(): ListingWidgetFactory
    {
        return $this->listingWidgetFactory;
    }

    /**
     * @return FormWidgetFactory
     */
    public function getFormWidgetFactory(): FormWidgetFactory
    {
        return $this->formWidgetFactory;
    }
}