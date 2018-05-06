<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Controller;

use Tmvc\Framework\App\Request;
use Tmvc\Framework\App\Response;
use Tmvc\Framework\Tools\ObjectManager;
use Tmvc\Framework\View\View;

abstract class AbstractController
{
    /**
     * @var View
     */
    private $view;

    public function __construct()
    {
        $this->view = ObjectManager::create(View::class);
    }

    /**
     * @return View
     */
    public function getView() {
        return $this->view;
    }

    /**
     * @param Request $request
     * @return View|Response
     */
    abstract public function execute(Request $request);
}