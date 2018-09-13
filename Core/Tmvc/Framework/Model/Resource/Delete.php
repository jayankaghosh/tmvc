<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Model\Resource;


class Delete extends Raw
{
    protected $tableName;
    protected $idField;
    protected $value;

    public function __construct($tableName, $idField, $value)
    {
        $this->tableName = $tableName;
        $this->idField = $idField;
        $this->value = $value;
    }

    public function __toString()
    {
        return "DELETE FROM ".$this->tableName." WHERE ".$this->idField." = ".$this->value;
    }
}