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
     * AbstractCollection constructor.
     * @param Db $db
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function __construct(
        Db $db
    )
    {
        $this->_db = $db;
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
                $data[] = $this->_getNewModelInstance()->setData($item->getData());
            }
            $this->setData($data);
            $this->_loaded = true;
        }
        return $this;
    }

    /**
     * @return AbstractModel
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function getFirstItem() {
        return $this->_getNewModelInstance()->setData($this->_result->getFirstItem()->getData());
    }

    /**
     * @return AbstractModel
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function getLastItem() {
        return $this->_getNewModelInstance()->setData($this->_result->getLastItem()->getData());
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
}