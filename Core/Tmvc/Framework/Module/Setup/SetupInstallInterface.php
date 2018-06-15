<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Module\Setup;


interface SetupInstallInterface
{
    /**
     * @param Installer $installer
     * @return string
     */
    public function execute(Installer $installer);
}