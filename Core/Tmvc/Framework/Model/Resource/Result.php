<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Model\Resource;


use Tmvc\Framework\DataObject;
use Tmvc\Framework\Tools\ObjectManager;

class Result
{

    private $result;
    private $lastInsertId;

    private $data;

    public function __construct($result)
    {
        /* @var DataObject $result */
        $this->result = $result->getData('result');
        $this->lastInsertId = $result->getData('last_insert_id');
    }

    public function getLastInsertId() {
        return $this->lastInsertId;
    }

    /**
     * @return array
     */
    public function getItems() {
        if (!$this->data) {
            $this->data = [];
            if ($this->result->num_rows > 0) {
                while ($row = $this->result->fetch_assoc()) {
                    $this->data[] = ObjectManager::create(DataObject::class)->setData($row);
                }
            }
        }
        return $this->data;
    }

    /**
     * @return DataObject
     */
    public function getFirstItem() {
        return count($this->getItems()) ? $this->getItems()[0]: ObjectManager::create(DataObject::class);
    }

    /**
     * @return DataObject
     */
    public function getLastItem() {
        return count($this->getItems()) ? end($this->getItems()): ObjectManager::create(DataObject::class);
    }

    /**
     * @param int $index
     * @return DataObject|null
     */
    public function getItem($index = 0) {
        return isset($this->getItems()[$index]) ? $this->getItems()[$index] : null;
    }
}