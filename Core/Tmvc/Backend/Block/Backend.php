<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Block;

use Tmvc\Backend\Block\Section\Section;
use Tmvc\Backend\Block\Section\SectionPool;
use Tmvc\Framework\App\Request;
use Tmvc\Framework\DataObject;
use Tmvc\Framework\Tools\AppEnv;
use Tmvc\Framework\Tools\ObjectManager;
use Tmvc\Framework\Tools\Url;
use Tmvc\Framework\View\View;

class Backend extends DataObject
{
    /**
     * @var Url
     */
    private $url;
    /**
     * @var SectionPool
     */
    private $sectionPool;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var View
     */
    private $view;
    /**
     * @var AppEnv
     */
    private $appEnv;

    /**
     * Backend constructor.
     * @param Url $url
     * @param SectionPool $sectionPool
     * @param Request $request
     * @param View $view
     * @param AppEnv $appEnv
     */
    public function __construct(
        Url $url,
        SectionPool $sectionPool,
        Request $request,
        View $view,
        AppEnv $appEnv
    )
    {
        $this->url = $url;
        $this->sectionPool = $sectionPool;
        $this->request = $request;
        $this->view = $view;
        $this->appEnv = $appEnv;
    }

    public function getPubUrl($resource, $module = "Tmvc_Backend") {
        return $this->url->getPubUrl("$module/$resource");
    }

    /**
     * @return Section[]
     */
    public function getMenuSections() {
        return $this->sectionPool->getSections();
    }

    /**
     * @return null|Section
     */
    public function getCurrentSection() {
        return $this->sectionPool->getSection($this->request->getParam('section'));
    }

    /**
     * @param Section $section
     * @param Section[] $parents
     * @return string
     */
    public function getSectionUrl(Section $section, $parents = []) {
        $sectionIdentifier = [];
        foreach ($parents as $parent) {
            $sectionIdentifier[] = $parent->getId();
        }
        $sectionIdentifier = urlencode(implode("/", $sectionIdentifier));
        return $section->hasChildren() ? "#" : $this->url->getUrl('*', ['section' => $section->getId()])."?p=$sectionIdentifier&isAjax=false";
    }

    /**
     * @return Section[]
     */
    public function getBreadCrumbs() {
        $path = explode('/', $this->request->getParam('p'));
        $breadcrumbs = [];
        foreach ($path as $item) {
            $breadcrumb = $this->sectionPool->getSection($item);
            if($breadcrumb) {
                $breadcrumbs[] = $breadcrumb;
            }
        }
        $breadcrumbs[] = $this->getCurrentSection();
        return $breadcrumbs;
    }

    /**
     * @param Section $section
     * @param Section[] $parents
     * @param int $level
     * @return string
     */
    public function getSectionHtml(Section $section, $parents = [], $level = 1) {
        $html = '<li class="nav-item '.$section->getId().'" data-level='.$level.'>';
        $html .= '<a class="nav-link" href="'.$this->getSectionUrl($section, $parents).'"><i class="fas fa-fw '.$section->getIconClass().'"></i><span>'.$section->getLabel().'</span></a>';
        if ($section->hasChildren()) {
            $html .= '<ul class="child level-'.$level.'">';
            foreach ($section->getChildren() as $child) {
                $html .= $this->getSectionHtml($child, array_merge($parents, [$section]), $level+1);
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
        return $html;
    }

    public function loadSectionContent() {
        $currentSection = $this->getCurrentSection();
        $block = ObjectManager::create($currentSection->getBlock(), ['currentSection' => $currentSection]);
        return $this->view->loadSection($currentSection->getId(), $currentSection->getTemplate(), $block);
    }

    public function getAppName() {
        return $this->appEnv->read('app.name');
    }

    /**
     * @return Url
     */
    public function getUrlBuilder()
    {
        return $this->url;
    }
}