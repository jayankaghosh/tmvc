<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Model;

use Tmvc\Framework\Cache;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Module\Manager as ModuleManager;
use Tmvc\Framework\Tools\File;

class SectionReader
{

    const SECTION_CACHE_KEY = "tmvc_backend_section";

    /**
     * @var ModuleManager
     */
    private $moduleManager;
    /**
     * @var File
     */
    private $file;
    /**
     * @var Cache
     */
    private $cache;

    /**
     * SectionReader constructor.
     * @param ModuleManager $moduleManager
     * @param File $file
     * @param Cache $cache
     */
    public function __construct(
        ModuleManager $moduleManager,
        File $file,
        Cache $cache
    )
    {
        $this->moduleManager = $moduleManager;
        $this->file = $file;
        $this->cache = $cache;
    }

    public function getSections() {
        $sections = json_decode($this->cache->get(self::SECTION_CACHE_KEY), TRUE);
        if (!$sections) {
            $sections = [];
            foreach ($this->moduleManager->getModuleList() as $module) {
                $sectionFile = $module->getPath() . "/etc/backend/section.json";
                if (file_exists($sectionFile)) {
                    $sectionData = \json_decode($this->file->load($sectionFile)->read(), TRUE);
                    if (!$sectionData) {
                        throw new TmvcException("File $sectionFile is corrupted");
                    }
                    foreach ($sectionData['sections'] as $section) {
                        $this->validateSection($section);
                        $sections[] = $section;
                    }
                }
            }
            $sections = $this->buildSectionData($sections);
            $this->cache->set(self::SECTION_CACHE_KEY, json_encode($sections));
        }
        return $sections;
    }

    /**
     * @param array $sections
     * @return array
     */
    protected function buildSectionData($sections) {
        $sectionData = [];

        foreach ($sections as $section) {
            $sectionData[$section['id']] = $section;
        }

        foreach ($sectionData as $key => $section) {
            if (!isset($sectionData[$key]['_children'])) {
                $sectionData[$key]['_children'] = [];
            }
            if ($section['parent']) {
                $sectionData[$section['parent']]['_children'][] = $section['id'];
            }
        }

        foreach ($sectionData as $key => $section) {
            $sectionData[$key]['children'] = [];
            foreach ($section['_children'] as $child) {
                $sectionData[$key]['children'][] = &$sectionData[$child];
            }
            unset($sectionData[$key]['_children']);
        }

        foreach ($sectionData as $key => $section) {
            if ($section['parent']) {
                unset($sectionData[$key]);
            }
        }

        $this->_sortSections($sectionData);
        return $sectionData;
    }

    private function _sortSections(&$sections) {
        uasort($sections, function ($first, $second) {
            return $first['sort_order'] <=> $second['sort_order'];
        });
    }

    /**
     * @param $section
     */
    protected function validateSection($section) {
        $fields = ["id", "label", "block", "template", "parent", "sort_order"];
        foreach ($fields as $field) {
            if (!array_key_exists($field, $section)) {
                throw new TmvcException("section.json corrupted. $field is required");
            }
        }
    }
}