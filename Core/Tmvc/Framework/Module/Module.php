<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Module;


use Tmvc\Framework\DataObject;

class Module extends DataObject
{
    /**
     * @return string
     */
    public function getName() {
        return $this->getData('name');
    }

    /**
     * @return string
     */
    public function getVersion() {
        return $this->getData('version');
    }

    /**
     * @return float
     */
    public function getSortOrder() {
        return (float)$this->getData('sort_order');
    }

    /**
     * @return string
     */
    public function getPath() {
        return $this->getData('path');
    }

    /**
     * @return string
     */
    public function getCliCode() {
        return $this->getData('cli_code');
    }
}