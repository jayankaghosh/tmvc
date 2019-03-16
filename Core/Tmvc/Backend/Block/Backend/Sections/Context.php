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
     * Context constructor.
     * @param UrlBuilder $urlBuilder
     * @param ListingWidgetFactory $listingWidgetFactory
     */
    public function __construct(
        UrlBuilder $urlBuilder,
        ListingWidgetFactory $listingWidgetFactory
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->listingWidgetFactory = $listingWidgetFactory;
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
}