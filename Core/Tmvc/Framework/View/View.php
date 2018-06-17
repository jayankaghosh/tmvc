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
use Tmvc\Framework\Exception\ArgumentMismatchException;
use Tmvc\Framework\Tools\ObjectManager;
use Tmvc\Framework\Module\Manager as ModuleManager;
use Tmvc\Framework\Event\Manager as EventManager;

class View
{

    private $id;
    private $layout;
    private $block;
    private $result = "";

    /**
     * @var ModuleManager
     */
    private $moduleManager;
    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * View constructor.
     * @param ModuleManager $moduleManager
     * @param EventManager $eventManager
     */
    public function __construct(
        ModuleManager $moduleManager,
        EventManager $eventManager
    )
    {
        $this->moduleManager = $moduleManager;
        $this->eventManager = $eventManager;
    }

    /**
     * @param string $id
     * @param string $layout
     * @param array|string|DataObject $block
     * @return $this
     * @throws ArgumentMismatchException
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function loadView($id, $layout, $block = []) {
        $this->setId($id);
        $this->setLayout($layout);
        $this->setBlock($block);
        return $this;
    }

    /**
     * @param string $id
     * @param string $layout
     * @param null|string|array|DataObject $block
     * @return View
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function loadSection($id, $layout, $block = null) {
        if (!$block) {
            $block = $this->getBlock();
        }
        return ObjectManager::create(self::class)->loadView($id, $layout, $block)->render();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render()
    {
        if (!$this->getLayout()) {
            throw new \InvalidArgumentException("No view was initialized");
        }
        $eventParameters = ['view' => $this];
        $this->eventManager->dispatch("view_render_before", $eventParameters);
        $this->eventManager->dispatch("view_".$this->getId()."_render_before", $eventParameters);
        $viewRender = \Closure::bind(function ($block) {
            ob_start();
            include $this->getLayout();
            return ob_get_clean();
        }, $this, self::class);
        $this->setResult(call_user_func($viewRender, $this->getBlock()));
        $this->eventManager->dispatch("view_render_after", $eventParameters);
        $this->eventManager->dispatch("view_".$this->getId()."_render_after", $eventParameters);
        return $this->getResult();
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLayout() {
        return $this->layout;
    }

    /**
     * @param string $layout
     * @return $this
     */
    public function setLayout($layout) {
        $name = explode("::", $layout);
        if (count($name) > 1) {
            $path = $this->moduleManager->getModule($name[0]) . "/view/" . $name[1];
        } else {
            $path = $name[0];
        }
        $this->layout = $path;
        return $this;
    }

    /**
     * @return DataObject
     */
    public function getBlock() {
        return $this->block;
    }

    /**
     * @param null|string|array|DataObject $block
     * @return $this
     * @throws ArgumentMismatchException
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function setBlock($block = null) {
        if (!$block) {
            $block = ObjectManager::create(DataObject::class);
        } else if (is_array($block)) {
            $block = ObjectManager::create(DataObject::class)->setData($block);
        } else if (is_string($block)) {
            $block = ObjectManager::create($block);
        }
        if (!$block instanceof DataObject) {
            throw new ArgumentMismatchException("Block passed to view ".$this->getId()." should be an instance of ".DataObject::class);
        }
        $this->block = $block;
        return $this;
    }

    /**
     * @return string
     */
    public function getResult() {
        return (string)$this->result;
    }

    /**
     * @param string $result
     * @return $this
     */
    public function setResult($result) {
        $this->result = $result;
        return $this;
    }

    public function __toString()
    {
        return $this->render();
    }
}