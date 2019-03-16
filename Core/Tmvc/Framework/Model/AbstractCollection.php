<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Model;


use Tmvc\Framework\DataObject;
use Tmvc\Framework\Model\Collection\Context;
use Tmvc\Framework\Model\Collection\Iterator;
use Tmvc\Framework\Model\Resource\Db;
use Tmvc\Framework\Model\Resource\Result;
use Tmvc\Framework\Model\Resource\Select;
use Tmvc\Framework\Tools\ObjectManager;
use Traversable;

abstract class AbstractCollection extends DataObject implements \IteratorAggregate
{

    /**
     * @var bool
     */
    private $_loaded = false;

    /**
     * @var Db
     */
    private $_db;

    /**
     * @var Select
     */
    private $_select;

    /**
     * @var Result
     */
    private $_result;
    /**
     * @var Context
     */
    private $context;


    /**
     * AbstractCollection constructor.
     * @param Context $context
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function __construct(
        Context $context
    )
    {
        $this->context = $context;
        $this->_db = $this->context->getDb();
        /* @var AbstractModel $model */
        $model = ObjectManager::get($this->getModelName());
        $this->_select = ObjectManager::create(Select::class, [
            'tableName' =>  $model->getTableName()
        ]);
    }

    /**
     * To be overridden by child classes
     * @return string
     */
    abstract public function getModelName();

    /**
     * @param string|array $field
     * @param string|array $value
     * @return $this
     */
    public function addFieldToFilter($field, $value = null) {
        $this->getSelect()->addFieldToFilter($field, $value);
        return $this;
    }

    /**
     * @param string|string[] $field
     * @return $this
     */
    public function addFieldToSelect($field) {
        $this->getSelect()->addFieldToSelect($field);
        return $this;
    }

    /**
     * @return Select
     */
    public function getSelect() {
        return $this->_select;
    }

    /**
     * @throws \Tmvc\Framework\Exception\TmvcException
     * @return $this
     */
    public function load() {
        if (!$this->_loaded) {
            $this->_result = $this->_db->query($this->_select);
            $items = $this->_result->getItems();
            $data = [];
            foreach ($items as $item) {
                $this->itemLoadBefore($item);
                $item = $this->_getNewModelInstance()->setData($item->getData());
                $data[] = $this->itemLoadAfter($item);
            }
            $this->setData($data);
            $this->_loaded = true;
        }
        return $this;
    }

    /**
     * @param DataObject $item
     * @return DataObject
     */
    protected function itemLoadBefore($item) {
        return $item;
    }

    /**
     * @param AbstractModel $item
     * @return AbstractModel
     */
    protected function itemLoadAfter($item) {
        return $item;
    }

    /**
     * @return AbstractModel
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function getFirstItem() {
        $this->load();
        $data = $this->_result->getFirstItem()->getData();
        $this->itemLoadBefore($data);
        $item = $this->_getNewModelInstance()->setData($data);
        return $this->itemLoadAfter($item);
    }

    /**
     * @return AbstractModel
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function getLastItem() {
        $this->load();
        $data = $this->_result->getLastItem()->getData();
        $this->itemLoadBefore($data);
        $item = $this->_getNewModelInstance()->setData($data);
        return $this->itemLoadAfter($item);
    }

    /**
     * @return AbstractModel
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    private function _getNewModelInstance() {
        return ObjectManager::create($this->getModelName());
    }


    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function getIterator()
    {
        $this->load();
        return new \ArrayIterator($this->data);
    }

    public function getData($key = null, $default = null)
    {
        $this->load();
        return parent::getData($key, $default);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = [];
        foreach ($this->getData() as $item)
        {
            $data[] = $item->getData();
        }
        return $data;
    }

    /**
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }
}