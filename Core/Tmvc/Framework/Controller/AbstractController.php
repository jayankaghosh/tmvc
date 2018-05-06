<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Controller;

use Tmvc\Framework\Tools\ObjectManager;
use Tmvc\Framework\View\ViewLoader;

abstract class AbstractController
{
    /**
     * @var ViewLoader
     */
    private $view;

    public function __construct()
    {
        $this->view = ObjectManager::create(ViewLoader::class);
    }

    /**
     * @return ViewLoader
     */
    public function getView() {
        return $this->view;
    }

    abstract public function execute();
}