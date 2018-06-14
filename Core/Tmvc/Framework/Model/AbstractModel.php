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

class AbstractModel extends DataObject
{
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
     * AbstractModel constructor.
     * @throws TmvcException
     */
    public function __construct()
    {
        if (!$this->tableName || !$this->indexField) {
            throw new TmvcException("Table name or Index field are not defined. Please redefine these variables in your Model \"".get_called_class()."\".");
        }
        $this->dbConn = VarBucket::read(Db::DB_CONNECTION_VAR_KEY);
        $this->select = ObjectManager::create(Select::class, [$this->tableName]);
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
        $this->getSelect()->addFieldToFilter($field, $value)->addLimit(1);
        $result = $this->getConnection()->query($this->getSelect())->getFirstItem();
        $this->origData = $result;
        return $this->setData($result->getData());
    }

    /**
     * @return $this
     * @throws TmvcException
     */
    public function save() {
        $query = ObjectManager::create(Save::class, [
            $this->tableName,
            $this->getData(),
            $this->indexField
        ]);
        $result = $this->getConnection()->query($query);
        return $this->getId() ? $this : $this->setData($this->indexField, $result->getLastInsertId());
    }

    /**
     * @return $this
     * @throws TmvcException
     */
    public function delete() {
        if ($this->getData($this->indexField)) {
            $query = ObjectManager::create(Delete::class, [
                $this->tableName,
                $this->indexField,
                $this->getData($this->indexField)
            ]);
            $result = $this->getConnection()->query($query);
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
}