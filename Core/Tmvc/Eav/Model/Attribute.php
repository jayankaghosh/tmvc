<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Eav\Model;

/**
 * Class Attribute
 * @package Tmvc\Eav\Model
 * @method int getEntityId()
 * @method string getAttributeCode()
 * @method string getAttributeLabel()
 * @method string getAttributeType()
 */
class Attribute extends \Tmvc\Framework\Model\AbstractModel
{

    protected $indexField = "attribute_id";

    /**
     * To be overridden by child classes
     * @return string
     */
    public function getTableName()
    {
        return "eav_attribute";
    }
}