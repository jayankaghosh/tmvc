<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Controller;


use Tmvc\Framework\Tools\Url;
use Tmvc\Framework\View\View;

class Context
{
    /**
     * @var View
     */
    private $view;
    /**
     * @var Url
     */
    private $url;

    /**
     * Context constructor.
     * @param View $view
     * @param Url $url
     */
    public function __construct(
        View $view,
        Url $url
    )
    {
        $this->view = $view;
        $this->url = $url;
    }

    /**
     * @return View
     */
    public function getView(): View
    {
        return $this->view;
    }

    /**
     * @return Url
     */
    public function getUrl(): Url
    {
        return $this->url;
    }
}