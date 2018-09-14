<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Model;

use Tmvc\Framework\Cache;
use Tmvc\Framework\DataObject;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Model\Resource\Db;
use Tmvc\Framework\Model\Resource\Delete;
use Tmvc\Framework\Model\Resource\Save;
use Tmvc\Framework\Model\Resource\Select;
use Tmvc\Framework\Tools\ObjectManager;
use Tmvc\Framework\Tools\VarBucket;
use Tmvc\Framework\Event\Manager as EventManager;

abstract class AbstractModel extends DataObject
{

    const EVENT_PREFIX = "abstract_model";

    const CACHE_KEY = "model";

    protected $indexField = "id";

    private $origData;

    private $schema = null;

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
     * @var Cache
     */
    private $cache;
    /**
     * @var Context
     */
    private $context;

    /**
     * AbstractModel constructor.
     * @param Context $context
     * @throws TmvcException
     */
    public function __construct(
        Context $context
    )
    {
        $this->context = $context;
        $this->dbConn = VarBucket::read(Db::DB_CONNECTION_VAR_KEY);
        $this->select = ObjectManager::create(Select::class, ["tableName" => $this->getTableName()]);
        $this->eventManager = $this->context->getEventManager();
        $this->cache = $this->context->getCache();
    }

    /**
     * To be overridden by child classes
     * @return string
     */
    abstract public function getTableName();

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

    protected function beforeLoad() {

    }

    /**
     * @return DataObject[]
     * @throws TmvcException
     */
    public function getSchema() {
        if (!$this->schema) {
            $cacheKey = self::CACHE_KEY . "_" . $this->getTableName();
            $schema = \json_decode($this->cache->get($cacheKey), TRUE);
            if (!$schema) {
                $schema = \json_encode($this->getConnection()->query("DESCRIBE " . $this->getTableName())->getItems());
                $this->cache->set($cacheKey, $schema);
            }
            $this->schema = $schema;
        }
        return $this->schema;
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

        $this->beforeLoad();

        $this->getSelect()->addFieldToFilter($field, $value)->addLimit(1);
        $result = $this->getConnection()->query($this->getSelect())->getFirstItem();
        $this->origData = $result;
        $this->setData($result->getData());

        $this->afterLoad();

        $this->eventManager->dispatch("model_load_after", $eventParameters);
        $this->eventManager->dispatch(static::EVENT_PREFIX."_load_after", $eventParameters);

        return $this;

    }

    protected function afterLoad() {

    }

    protected function beforeSave() {

    }

    protected function getDataToSave() {
        $schema = $this->getSchema();
        $schema = array_values(array_map(function ($row) use ($schema) {
            return $row['Field'];
        }, $schema));
        return array_filter($this->getData(), function ($key) use ($schema) {
            return in_array($key, $schema);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @return $this
     * @throws TmvcException
     */
    public function save() {

        $this->eventManager->dispatch("model_save_before", $this->getEventParameters());
        $this->eventManager->dispatch(static::EVENT_PREFIX."_save_before", $this->getEventParameters());

        $this->beforeSave();

        $query = ObjectManager::create(Save::class, [
            "tableName" => $this->getTableName(),
            "data" => $this->getDataToSave(),
            "id" => $this->indexField
        ]);
        $result = $this->getConnection()->query($query);

        $this->afterSave();

        $this->eventManager->dispatch("model_save_after", $this->getEventParameters());
        $this->eventManager->dispatch(static::EVENT_PREFIX."_save_after", $this->getEventParameters());

        return $this->getId() ? $this : $this->setData($this->indexField, $result->getLastInsertId());
    }

    protected function afterSave() {

    }

    protected function beforeDelete() {

    }

    /**
     * @return $this
     * @throws TmvcException
     */
    public function delete() {
        if ($this->getData($this->indexField)) {

            $this->eventManager->dispatch("model_delete_before", $this->getEventParameters());
            $this->eventManager->dispatch(static::EVENT_PREFIX."_delete_before", $this->getEventParameters());

            $this->beforeDelete();

            $query = ObjectManager::create(Delete::class, [
                "tableName" => $this->getTableName(),
                "idField" => $this->indexField,
                "value" => $this->getData($this->indexField)
            ]);
            $result = $this->getConnection()->query($query);

            $this->afterDelete();

            $this->eventManager->dispatch("model_delete_after", $this->getEventParameters());
            $this->eventManager->dispatch(static::EVENT_PREFIX."_delete_after", $this->getEventParameters());

        }
        return $this;
    }

    protected function afterDelete() {

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

    /**
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }
}