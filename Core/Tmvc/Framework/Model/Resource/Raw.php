<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Model\Resource;


class Raw
{

    protected $query;

    public function __construct($query = '')
    {
        $this->query = $query;
    }

    public function __toString()
    {
        return (string)$this->query;
    }
}