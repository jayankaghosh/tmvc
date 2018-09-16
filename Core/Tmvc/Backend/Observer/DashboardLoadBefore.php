<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Observer;


use Tmvc\Backend\Block\Section\Section;
use Tmvc\Backend\Block\Section\SectionPool;
use Tmvc\Backend\Model\SectionReader;
use Tmvc\Framework\Event\ObserverInterface;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Backend\Block\Section\SectionFactory;

class DashboardLoadBefore implements ObserverInterface
{
    /**
     * @var SectionReader
     */
    private $sectionReader;
    /**
     * @var SectionPool
     */
    private $sectionPool;
    /**
     * @var SectionFactory
     */
    private $sectionFactory;

    /**
     * DashboardLoadBefore constructor.
     * @param SectionReader $sectionReader
     * @param SectionPool $sectionPool
     * @param SectionFactory $sectionFactory
     */
    public function __construct(
        SectionReader $sectionReader,
        SectionPool $sectionPool,
        SectionFactory $sectionFactory
    )
    {
        $this->sectionReader = $sectionReader;
        $this->sectionPool = $sectionPool;
        $this->sectionFactory = $sectionFactory;
    }

    /**
     * @param string $eventName
     * @param array $eventData
     * @return void
     */
    public function execute($eventName, $eventData)
    {
        $sections = $this->sectionReader->getSections();
        foreach ($sections as $section) {
            $this->sectionPool->addSection($this->buildSection($section));
        }
    }

    /**
     * @param array $section
     * @return Section
     * @throws TmvcException
     */
    protected function buildSection($section) {
        $children = [];
        foreach ($section['children'] as $child) {
            $children[] = $this->buildSection($child);
        }
        $params = [
            'id'        =>  $section['id'],
            'block'     =>  $section['block'],
            'template'  =>  $section['template'],
            'children'  =>  $children,
            'sortOrder' =>  $section['sort_order'],
            'label'     =>  $section['label']
        ];
        if (array_key_exists("icon_class", $section)) {
            $params['iconClass'] = $section['icon_class'];
        }
        return $this->sectionFactory->create($params);
    }
}