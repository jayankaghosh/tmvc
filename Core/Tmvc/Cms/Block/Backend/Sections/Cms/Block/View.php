<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Block\Backend\Sections\Cms\Block;

use Tmvc\Backend\Block\Section\AbstractSection;

class View extends AbstractSection
{
    /**
     * @return string
     */
    public function getTemplate()
    {
        return "Tmvc_Cms::backend/block/view.phtml";
    }

    /**
     * @return string
     */
    public function getBlock()
    {
        return \Tmvc\Cms\Block\Backend\Cms\Block\View::class;
    }

    /**
     * @return string
     */
    public function getSectionName()
    {
        return "View All Blocks";
    }
}