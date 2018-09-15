<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Block\Backend\Sections;


use Tmvc\Backend\Block\Section\AbstractSection;

class Cms extends AbstractSection
{

    /**
     * @return string
     */
    public function getTemplate()
    {
        return "Tmvc_Cms::backend/page/view.phtml";
    }

    /**
     * @return string
     */
    public function getBlock()
    {
        return \Tmvc\Cms\Block\Backend\Cms\Page\View::class;
    }

    /**
     * @return string
     */
    public function getSectionName()
    {
        return "Cms";
    }
}