<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Block\Section;


class Section
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $block;

    /**
     * @var string
     */
    private $template;

    /**
     * @var Section[]
     */
    private $children;

    /**
     * @var int
     */
    private $sortOrder;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $iconClass;

    /**
     * Section constructor.
     * @param $id
     * @param $block
     * @param $template
     * @param $children
     * @param $sortOrder
     * @param $label
     * @param string $iconClass
     */
    public function __construct(
        $id,
        $block,
        $template,
        $children,
        $sortOrder,
        $label,
        $iconClass = "fa-folder"
    )
    {
        $this->id = $id;
        $this->block = $block;
        $this->template = $template;
        $this->children = $children;
        $this->sortOrder = $sortOrder;
        $this->label = $label;
        $this->iconClass = $iconClass;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getBlock(): string
    {
        return $this->block;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @return Section[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return bool
     */
    public function hasChildren() {
        return count($this->getChildren()) > 0;
    }

    /**
     * @return int
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getIconClass(): string
    {
        return $this->iconClass;
    }
}