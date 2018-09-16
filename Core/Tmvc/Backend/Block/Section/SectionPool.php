<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Block\Section;


class SectionPool
{
    /**
     * @var Section[]
     */
    private $sections;

    /**
     * @var Section[]
     */
    private $sectionCache = [];

    public function __construct()
    {
        $this->sections = [];
    }

    /**
     * @return Section[]
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param $sectionId
     * @return null|Section
     */
    public function getSection($sectionId) {
        if (!array_key_exists($sectionId, $this->sectionCache)) {
            $this->sectionCache[$sectionId] = $this->_getSection($sectionId, null);
        }
        return $this->sectionCache[$sectionId];
    }

    /**
     * @param string $sectionId
     * @param Section|null $parentSection
     * @return null|Section
     */
    protected function _getSection($sectionId, $parentSection) {
        $sections = $parentSection ? $parentSection->getChildren() : $this->getSections();
        foreach ($sections as $section) {
            if ($section->getId() === $sectionId) {
                return $section;
            } else if ($section->hasChildren()){
                $section = $this->_getSection($sectionId, $section);
                if ($section) {
                    return $section;
                }
            }
        }
        return null;
    }

    /**
     * @param Section $section
     * @return $this
     */
    public function addSection(Section $section)
    {
        $this->sections[] = $section;
        return $this;
    }
}