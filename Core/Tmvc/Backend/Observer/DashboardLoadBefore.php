<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Observer;


use Tmvc\Backend\Block\Section\AbstractSection;
use Tmvc\Backend\Block\Section\SectionPool;
use Tmvc\Backend\Model\SectionReader;
use Tmvc\Framework\Event\ObserverInterface;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Tools\ObjectManager;

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
     * DashboardLoadBefore constructor.
     * @param SectionReader $sectionReader
     * @param SectionPool $sectionPool
     */
    public function __construct(
        SectionReader $sectionReader,
        SectionPool $sectionPool
    )
    {
        $this->sectionReader = $sectionReader;
        $this->sectionPool = $sectionPool;
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
     * @return AbstractSection
     * @throws TmvcException
     */
    protected function buildSection($section) {
        $sectionObj = ObjectManager::create($section['section'], ['id' => $section['id']]);
        if (!$sectionObj instanceof AbstractSection) {
            throw new TmvcException("Error in backend section ".get_class($sectionObj).". All sections must extends ".AbstractSection::class);
        }
        foreach ($section['children'] as $child) {
            $sectionObj->addChild($this->buildSection($child));
        }
        return $sectionObj;
    }
}