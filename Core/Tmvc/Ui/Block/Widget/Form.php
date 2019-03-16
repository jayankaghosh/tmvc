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
use Tmvc\Framework\DataObjectFactory;
use Tmvc\Framework\Tools\Url;
use Tmvc\Framework\View\ViewFactory;

class Form extends DataObject
{

    const FIELD_TYPE_HIDDEN = "hidden";
    const FIELD_TYPE_TEXT = "text";
    const FIELD_TYPE_TEXTAREA = "textarea";
    const FIELD_TYPE_PASSWORD = "password";
    const FIELD_TYPE_SELECT = "select";
    const FIELD_TYPE_MULTISELECT = "multiselect";
    const FIELD_TYPE_CHECKBOX = "checkbox";

    /**
     * @var DataObject
     */
    private $dataSource;

    /**
     * @var DataObject[]
     */
    private $fields;
    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;
    /**
     * @var ViewFactory
     */
    private $viewFactory;
    /**
     * @var string
     */
    private $actionService;
    /**
     * @var Url
     */
    private $url;

    /**
     * @var string
     */
    private $title;

    /**
     * Form constructor.
     * @param ViewFactory $viewFactory
     * @param DataObjectFactory $dataObjectFactory
     * @param Url $url
     * @param $dataSource
     * @param string $actionService
     * @param array $fields
     */
    public function __construct(
        ViewFactory $viewFactory,
        DataObjectFactory $dataObjectFactory,
        Url $url,
        $dataSource,
        $actionService = "",
        $fields = []
    )
    {
        $this->dataObjectFactory = $dataObjectFactory;
        $this->dataSource = $dataSource;
        $this->actionService = $actionService;
        $this->fields = [];
        $this->viewFactory = $viewFactory;
        $this->url = $url;
        foreach ($fields as $field) {
            $field = $this->getNewDataObject($field);
            $this->addField($field->getData('id'), $field->getData('label'), $field->getData('type'), $field->getData('value'));
        }
    }

    /**
     * @param $data
     * @return DataObject
     */
    protected function getNewDataObject($data)
    {
        return $this->dataObjectFactory->create()->setData($data);
    }

    /**
     * @return string
     */
    public function getWidgetId()
    {
        if (!$this->getData('widget_id')) {
            $this->setData('widget_id', strtolower(str_replace("\\", "_", get_class($this->dataSource)) . '_' . uniqid()));
        }
        return $this->getData('widget_id');
    }

    /**
     * @param $id
     * @param $label
     * @param string $type
     * @param array $additional
     * @param null $value
     * @return $this
     */
    public function addField($id, $label, $type = self::FIELD_TYPE_TEXT, $additional = [], $value = null)
    {
        if (is_null($value)) {
            $value = $this->dataSource->getData($id);
        }
        $this->fields[$id] = $this->getNewDataObject([
            'label'     =>  $label,
            'type'      =>  $type,
            'additional'=>  $this->getNewDataObject($additional),
            'value'     =>  $value
        ]);
        return $this;
    }

    /**
     * @return \Tmvc\Framework\View\View
     * @throws \Tmvc\Framework\Exception\ArgumentMismatchException
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function render()
    {
        return $this->viewFactory->create()->loadView(get_class($this->dataSource), "Tmvc_Ui::widget/form.phtml", $this);
    }

    /**
     * @return string
     */
    public function getFormAction()
    {
        return $this->url->getUrl('backend/service/index', ['service' => $this->getActionService()]);
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
     * @return string
     */
    public function getActionService(): string
    {
        return $this->actionService;
    }

    /**
     * @param string $actionService
     * @return $this
     */
    public function setActionService(string $actionService)
    {
        $this->actionService = $actionService;
        return $this;
    }

    /**
     * @return DataObject[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }
}