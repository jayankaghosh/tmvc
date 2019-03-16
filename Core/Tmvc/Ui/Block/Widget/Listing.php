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
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Model\AbstractCollection;
use Tmvc\Framework\Tools\Url;
use Tmvc\Framework\View\ViewFactory;
use Tmvc\Ui\Block\Widget\Listing\RendererInterface;

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
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var array
     */
    private $actions;
    /**
     * @var Url
     */
    private $url;

    /**
     * Listing constructor.
     * @param ViewFactory $viewFactory
     * @param Url $url
     * @param AbstractCollection $collection
     * @param array $options
     */
    public function __construct(
        ViewFactory $viewFactory,
        Url $url,
        $collection,
        $options = []
    )
    {
        $this->viewFactory = $viewFactory;
        $this->collection = $collection;
        $this->options = $options;
        $this->renderer = null;
        $this->actions = [];
        $this->url = $url;
    }

    /**
     * @return \Tmvc\Framework\View\View
     * @throws \Tmvc\Framework\Exception\ArgumentMismatchException
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function render() {
        return $this->viewFactory->create()->loadView(get_class($this->collection), "Tmvc_Ui::widget/listing.phtml", $this);
    }

    public function renderRow($data)
    {
        $renderer = $this->getRenderer();
        if ($renderer) {
            $data = $renderer->render($data);
        }
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

    /**
     * @return string
     * @throws \Tmvc\Framework\Exception\ArgumentMismatchException
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function __toString()
    {
        return $this->render()->__toString();
    }

    /**
     * @return RendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param RendererInterface $renderer
     * @return $this
     */
    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    public function getSectionOrServiceUrl($action, $row)
    {
        $idFieldName = $this->getCollection()->getFirstItem()->getIdFieldName();
        if (array_key_exists('section', $action)) {
            return $this->url->getUrl('backend/index/index', ['section' => $action['section'], 'id' => $row[$idFieldName]]);
        } else if (array_key_exists('service', $action)) {
            return $this->url->getUrl('backend/service/index', ['service' => $action['service'], 'id' => $row[$idFieldName]]);
        } else {
            throw new TmvcException("Section or Service is required for any action");
        }
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param array $actions
     * @return $this
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
        return $this;
    }

}