<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Block\Section;


abstract class AbstractSection
{
    /**
     * @var AbstractSection[]
     */
    private $children = [];
    /**
     * @var string
     */
    private $id;

    /**
     * AbstractSection constructor.
     * @param string $id
     */
    public function __construct(
        $id
    )
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    abstract public function getTemplate();

    /**
     * @return string
     */
    abstract public function getBlock();

    /**
     * @return string
     */
    abstract public function getSectionName();

    public function getIconClass() {
        return "fa-folder";
    }

    /**
     * @return AbstractSection[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param AbstractSection $child
     * @return $this
     */
    public function addChild(AbstractSection $child)
    {
        $this->children[] = $child;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function hasChildren() {
        return count($this->getChildren()) > 0;
    }
}