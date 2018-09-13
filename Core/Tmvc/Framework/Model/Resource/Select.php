<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Model\Resource;

class Select extends Raw
{
    protected $tableName;
    protected $fields;
    protected $conditions;
    protected $limit;

    public function __construct($tableName, $fields = [])
    {
        $this->tableName = $tableName;
        $this->fields = $fields;
        $this->conditions = [];
        $this->limit = "";
    }

    /**
     * @param string $field
     * @return $this
     */
    public function addFieldToSelect($field) {
        $this->fields[] = $field;
        return $this;
    }

    /**
     * @param string|array $field
     * @param string|array $value
     * @return $this
     */
    public function addFieldToFilter($field, $value = null) {
        if (is_string($field)) {
            $field = "`$field`";
        }
        if (is_string($value)) {
            $value = "'$value'";
        }

        if ($field instanceof Raw) {
            $field = $field->__toString();
        }
        if ($value instanceof Raw) {
            $value = $value->__toString();
        }

        if (is_string($field) && !$value) {
            $this->conditions[] = $field;
        } else if (is_string($field) && !is_array($value)){
            $this->conditions[] = "($field = $value)";
        } else if (is_string($field) && is_array($value)) {
            $condition = [];
            foreach($value as $conditionType => $conditionValue) {
                $condition[] = "($field $conditionType $conditionValue)";
            }
            $this->conditions[] = "(".implode(" OR ", $condition).")";
        } else if (is_array($field) && !is_array($value)) {
            $condition = [];
            foreach ($field as $item) {
                $condition[] = "($item = $value)";
            }
            $this->conditions[] = implode(" OR ", $condition);
        } else if(is_array($field) && is_array($value)) {
            $counter = 0;
            $condition = [];
            foreach($value as $conditionType => $conditionValue) {
                $condition[] = "(".$field[$counter]." $conditionType $conditionValue)";
                $counter++;
            }
            $this->conditions[] = "(".implode(" OR ", $condition).")";
        }
        return $this;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function addLimit($limit, $offset = 0) {
        $this->limit = " LIMIT $offset, $limit";
        return $this;
    }



    public function __toString()
    {
        $fields = $this->fields;
        if (!count($this->fields)) {
            $fields = "*";
        } else {
            $fields = implode(", ", $this->fields);
        }

        $conditions = $this->conditions;
        if (!count($this->conditions)) {
            $conditions = "TRUE";
        } else {
            $conditions = implode(" AND ", $this->conditions);
        }

        return "SELECT ".$fields." FROM ".$this->tableName." WHERE (".$conditions.") ".$this->limit;
    }
}