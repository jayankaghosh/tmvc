<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Block\Backend\Sections;


use Tmvc\Backend\Block\Section\Section;
use Tmvc\Framework\DataObject;
use Tmvc\Framework\Tools\Url;

class AbstractSection extends DataObject
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var Section
     */
    private $currentSection;

    /**
     * AbstractSection constructor.
     * @param Context $context
     * @param $currentSection
     */
    public function __construct(
        Context $context,
        $currentSection
    )
    {
        $this->context = $context;
        $this->currentSection = $currentSection;
    }

    /**
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }

    /**
     * @return Url
     */
    public function getUrlBuilder(): Url
    {
        return $this->context->getUrlBuilder();
    }

    /**
     * @return Section
     */
    public function getCurrentSection(): Section
    {
        return $this->currentSection;
    }

    /**
     * @param string $sectionId
     * @return string
     */
    public function getSectionUrl($sectionId) {
        return $this->getUrlBuilder()->getUrl('backend', ['section' => $sectionId]);
    }

    /**
     * @param $collection
     * @param array $options
     * @return \Tmvc\Framework\View\View
     * @throws \Tmvc\Framework\Exception\ArgumentMismatchException
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function getNewListingWidget($collection, $options = [])
    {
        return $this->getContext()->getListingWidgetFactory()->create([
            'collection'    =>  $collection,
            'options'       =>  $options
        ])->render();
    }
}
