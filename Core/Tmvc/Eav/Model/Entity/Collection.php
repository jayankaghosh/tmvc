<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Eav\Model\Entity;


use Tmvc\Eav\Model\Entity;
use Tmvc\Framework\Model\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * To be overridden by child classes
     * @return string
     */
    public function getModelName()
    {
        return Entity::class;
    }
}