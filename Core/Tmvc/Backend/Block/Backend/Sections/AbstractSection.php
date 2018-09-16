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
     * @var Url
     */
    private $urlBuilder;

    /**
     * @var Section
     */
    private $currentSection;

    /**
     * AbstractSection constructor.
     * @param Context $context
     * @param Section $currentSection
     */
    public function __construct(
        Context $context,
        Section $currentSection
    )
    {
        $this->context = $context;
        $this->urlBuilder = $context->getUrlBuilder();
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
        return $this->urlBuilder;
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
        return $this->urlBuilder->getUrl('backend', ['section' => $sectionId]);
    }
}