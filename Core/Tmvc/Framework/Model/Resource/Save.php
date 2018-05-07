<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Model\Resource;


class Save
{
    protected $tableName;
    protected $data;
    protected $id;

    public function __construct($tableName, $data, $id)
    {
        $this->tableName = $tableName;
        $this->data = $data;
        $this->id = $id;
    }

    public function __toString()
    {
        if (isset($this->data[$this->id])) {
            /* Update existing row */
            $updateEntries = [];
            foreach ($this->data as $column => $value) {
                if ($column === $this->id) { // ignore ID field
                    continue;
                }
                $updateEntries[] = "$column = '".addslashes($value)."'";
            }
            return "UPDATE `".$this->tableName."` SET ".implode(", ", $updateEntries)." WHERE ".$this->id." = ".$this->data[$this->id];
        } else {
            /* Insert new row */
            $columns = [];
            $values = [];
            foreach ($this->data as $column => $value) {
                $columns[] = $column;
                $values[] = "'".addslashes($value)."'";
            }
            return "INSERT INTO `".$this->tableName."` (".implode(", ", $columns).") VALUES (".implode(", ", $values).")";
        }
    }
}