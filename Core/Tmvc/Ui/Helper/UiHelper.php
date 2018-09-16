<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Ui\Helper;


use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\View\View;

class UiHelper
{
    /**
     * @var View
     */
    private $view;

    /**
     * Init constructor.
     * @param View $view
     */
    public function __construct(
        View $view
    )
    {
        $this->view = $view;
    }

    /**
     * @return string
     */
    public function getInitHtml() {
        try {
            return (string)$this->view->loadView('Tmvc_Ui/init', 'Tmvc_Ui::init.phtml', \Tmvc\Ui\Block\Init::class);
        } catch (TmvcException $tmvcException) {
            return "";
        }
    }
}