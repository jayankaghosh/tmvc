<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Ui\Block\Widget;

use Tmvc\Framework\DataObject;
use Tmvc\Framework\Model\AbstractCollection;
use Tmvc\Framework\View\ViewFactory;

class Listing extends DataObject
{
    /**
     * @var ViewFactory
     */
    private $viewFactory;
    /**
     * @var AbstractCollection
     */
    private $collection;
    /**
     * @var array
     */
    private $options;

    /**
     * Listing constructor.
     * @param ViewFactory $viewFactory
     * @param AbstractCollection $collection
     * @param array $options
     */
    public function __construct(
        ViewFactory $viewFactory,
        $collection,
        $options = []
    )
    {
        $this->viewFactory = $viewFactory;
        $this->collection = $collection;
        $this->options = $options;
    }

    /**
     * @return \Tmvc\Framework\View\View
     * @throws \Tmvc\Framework\Exception\ArgumentMismatchException
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function render() {
        return $this->viewFactory->create()->loadView(get_class($this->collection), "Tmvc_Ui::widget/listing.phtml", $this);
    }

    public function renderCell($name, $data)
    {
        return $data;
    }

    /**
     * @return string
     */
    public function getWidgetId()
    {
        if (!$this->getData('widget_id')) {
            $this->setData('widget_id', strtolower(str_replace("\\", "_", get_class($this->collection)) . '_' . uniqid()));
        }
        return $this->getData('widget_id');
    }

    /**
     * @return AbstractCollection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param $option
     * @return mixed|null
     */
    public function getOption($option)
    {
        return array_key_exists($option, $this->getOptions()) ? $this->getOptions()[$option] : null;
    }

}