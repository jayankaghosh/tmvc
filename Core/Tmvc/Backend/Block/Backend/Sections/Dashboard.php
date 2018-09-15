<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Block\Backend\Sections;


use Tmvc\Backend\Block\Section\AbstractSection;

class Dashboard extends AbstractSection
{

    /**
     * @return string
     */
    public function getTemplate()
    {
        return "Tmvc_Backend::backend/dashboard.phtml";
    }

    /**
     * @return string
     */
    public function getBlock()
    {
        return \Tmvc\Backend\Block\Backend\Dashboard::class;
    }

    /**
     * @return string
     */
    public function getSectionName()
    {
        return "Dashboard";
    }

    public function getIconClass()
    {
        return "fa-tachometer-alt";
    }
}