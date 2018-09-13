<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Model\Resource;


class Table extends Raw
{

    /**
     * Types of columns
     */
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_SMALLINT = 'smallint';
    const TYPE_INTEGER = 'integer';
    const TYPE_BIGINT = 'bigint';
    const TYPE_FLOAT = 'float';
    const TYPE_NUMERIC = 'numeric';
    const TYPE_DECIMAL = 'decimal';
    const TYPE_DATE = 'date';
    const TYPE_TIMESTAMP = 'timestamp';
    const TYPE_DATETIME = 'datetime';
    const TYPE_TEXT = 'text';
    const TYPE_BLOB = 'blob';
    const TYPE_VARBINARY = 'varbinary';

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var array
     */
    private $columns = [];

    /**
     * Table constructor.
     * @param string $tableName
     */
    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $size
     * @param array $additional
     * @param string $comment
     * @return $this
     */
    public function addColumn($name, $type, $size, $additional = [], $comment = "") {
        $this->columns[] = [
            'name'      =>  $name,
            'type'      =>  $type,
            'size'      =>  $size,
            'additional'=>  $additional,
            'comment'   =>  $comment
        ];
        return $this;
    }

    public function __toString()
    {
        $columns = [];
        $primary = null;
        foreach ($this->columns as $column) {

            $column['name'] = "`".$column['name']."`";

            $size = "";
            if ($column['size']) {
                $size = "(".$column['size'].")";
            }
            $comment = "";
            if ($column['comment']) {
                $comment = " COMMENT \"".$column['comment']."\"";
            }
            $additional = " ";
            foreach ($column['additional'] as $additionalField) {
                if ($additionalField === "AUTO_INCREMENT") {
                    $primary = " PRIMARY KEY (".$column['name'].") ";
                }
                $additional .= " $additionalField ";
            }
            $columns[] = $column['name']." ".$column['type'].$size.$additional.$comment;
        }

        if ($primary) {
            $columns[] = $primary;
        }

        return "CREATE TABLE IF NOT EXISTS ".$this->tableName."(".implode(",", $columns).")  ENGINE = InnoDB";
    }
}