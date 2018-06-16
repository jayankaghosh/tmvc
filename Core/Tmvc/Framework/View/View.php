<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\View;


use Tmvc\Framework\DataObject;
use Tmvc\Framework\Exception\EntityNotFoundException;
use Tmvc\Framework\Tools\ObjectManager;
use Tmvc\Framework\Module\Manager as ModuleManager;

class View
{

    private $layout;

    private $block;
    /**
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * View constructor.
     * @param ModuleManager $moduleManager
     */
    public function __construct(
        ModuleManager $moduleManager
    )
    {
        $this->moduleManager = $moduleManager;
    }

    public function loadView($name, $block = []) {
        $name = explode("::", $name);
        if (count($name) > 1) {
            $path = $this->moduleManager->getModule($name[0]) . "/view/" . $name[1];
        } else {
            $path = $name[0];
        }
        $this->layout = $path;
        if (is_array($block)) {
            $block = ObjectManager::create(DataObject::class)->setData($block);
        } else if (is_string($block)) {
            $block = ObjectManager::create($block);
        }
        $this->block = $block;
        return $this;
    }

    public function loadSection($name, $data = null) {
        if (!$data) {
            $data = $this->block;
        }
        return ObjectManager::create(self::class)->loadView($name, $data)->render();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render()
    {
        if (!$this->layout) {
            throw new \InvalidArgumentException("No view was initialized");
        }
        $viewRender = \Closure::bind(function ($block) {
            ob_start();
            include $this->layout;
            return ob_get_clean();
        }, $this, self::class);
        return call_user_func($viewRender, $this->block);
    }
}