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
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Model\Resource\Db;
use Tmvc\Framework\Model\Resource\Delete;
use Tmvc\Framework\Model\Resource\Save;
use Tmvc\Framework\Model\Resource\Select;
use Tmvc\Framework\Tools\ObjectManager;
use Tmvc\Framework\Tools\VarBucket;
use Tmvc\Framework\Event\Manager as EventManager;

class AbstractModel extends DataObject
{

    const EVENT_PREFIX = "abstract_model";

    /* To be overridden by child classes */
    protected $tableName = null;

    protected $indexField = "id";

    private $origData;

    /**
     * @var Db
     */
    private $dbConn;

    /**
     * @var Select
     */
    protected $select;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * AbstractModel constructor.
     * @param EventManager $eventManager
     * @throws TmvcException
     */
    public function __construct(
        EventManager $eventManager
    )
    {
        if (!$this->tableName || !$this->indexField) {
            throw new TmvcException("Table name or Index field are not defined. Please redefine these variables in your Model \"".get_called_class()."\".");
        }
        $this->dbConn = VarBucket::read(Db::DB_CONNECTION_VAR_KEY);
        $this->select = ObjectManager::create(Select::class, ["tableName" => $this->tableName]);
        $this->eventManager = $eventManager;
    }

    public function getId() {
        return $this->getData($this->indexField);
    }

    public function getOrigData($key = null) {
        if ($this->origData instanceof DataObject) {
            if ($key) {
                return $this->origData->getData($key);
            } else {
                return $this->origData->getData();
            }
        }
        return [];
    }

    /**
     * @param string $value
     * @param null|string $field
     * @return $this
     * @throws TmvcException
     */
    public function load($value, $field = null) {
        if (!$field) {
            $field = $this->indexField;
        }

        $eventParameters = $this->getEventParameters();
        $params = new DataObject();
        $params->setData([
            'value' =>  $value,
            'field' =>  $field
        ]);
        $eventParameters['params'] = $params;

        $this->eventManager->dispatch("model_load_before", $eventParameters);
        $this->eventManager->dispatch(static::EVENT_PREFIX."_load_before", $eventParameters);

        $field = $params->getData('field');
        $value = $params->getData('value');

        $this->getSelect()->addFieldToFilter($field, $value)->addLimit(1);
        $result = $this->getConnection()->query($this->getSelect())->getFirstItem();
        $this->origData = $result;
        $this->setData($result->getData());

        $this->eventManager->dispatch("model_load_after", $eventParameters);
        $this->eventManager->dispatch(static::EVENT_PREFIX."_load_after", $eventParameters);

        return $this;

    }

    /**
     * @return $this
     * @throws TmvcException
     */
    public function save() {

        $this->eventManager->dispatch("model_save_before", $this->getEventParameters());
        $this->eventManager->dispatch(static::EVENT_PREFIX."_save_before", $this->getEventParameters());

        $query = ObjectManager::create(Save::class, [
            "tableName" => $this->tableName,
            "data" => $this->getData(),
            "id" => $this->indexField
        ]);
        $result = $this->getConnection()->query($query);

        $this->eventManager->dispatch("model_save_after", $this->getEventParameters());
        $this->eventManager->dispatch(static::EVENT_PREFIX."_save_after", $this->getEventParameters());

        return $this->getId() ? $this : $this->setData($this->indexField, $result->getLastInsertId());
    }

    /**
     * @return $this
     * @throws TmvcException
     */
    public function delete() {
        if ($this->getData($this->indexField)) {

            $this->eventManager->dispatch("model_delete_before", $this->getEventParameters());
            $this->eventManager->dispatch(static::EVENT_PREFIX."_delete_before", $this->getEventParameters());

            $query = ObjectManager::create(Delete::class, [
                "tableName" => $this->tableName,
                "idField" => $this->indexField,
                "value" => $this->getData($this->indexField)
            ]);
            $result = $this->getConnection()->query($query);

            $this->eventManager->dispatch("model_delete_after", $this->getEventParameters());
            $this->eventManager->dispatch(static::EVENT_PREFIX."_delete_after", $this->getEventParameters());

        }
        return $this;
    }

    /**
     * @return Db
     */
    protected function getConnection() {
        return $this->dbConn;
    }

    public function getSelect() {
        return $this->select;
    }

    /**
     * @return array
     */
    protected function getEventParameters() {
        return [
            "model" =>  $this,
            static::EVENT_PREFIX  => $this
        ];
    }
}