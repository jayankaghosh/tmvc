<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Model;


use Tmvc\Eav\Model\AbstractModel;

class Admin extends AbstractModel
{

    /**
     * @return string
     */
    public function getEntityCode()
    {
        return "admin";
    }

    /**
     * To be overridden by child classes
     * @return string
     */
    public function getTableName()
    {
        return "admin_entity";
    }
}