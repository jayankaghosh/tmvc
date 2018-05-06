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
use Tmvc\Framework\Tools\ObjectManager;

class ViewLoader
{

    private $layout;

    private $block;

    public function loadView($name, $block = []) {
        $name = explode("::", $name);
        if (count($name) > 1) {
            $path = $name[0] . "/view/" . $name[1];
        } else {
            $path = $name[0];
        }
        $this->layout = "app/code/".$path;
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
        return ObjectManager::create(ViewLoader::class)->loadView($name, $data);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $viewRender = \Closure::bind(function ($block) {
            ob_start();
            include $this->layout;
            return ob_get_clean();
        }, $this, self::class);
        return call_user_func($viewRender, $this->block);
    }
}