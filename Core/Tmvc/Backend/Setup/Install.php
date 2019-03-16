<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Setup;


use Tmvc\Eav\Setup\EavSetup;
use Tmvc\Framework\Model\Resource\Table;
use Tmvc\Framework\Module\Setup\Installer;
use Tmvc\Framework\Module\Setup\SetupInstallInterface;

class Install implements SetupInstallInterface
{
    /**
     * @var EavSetup
     */
    private $eavSetup;

    /**
     * Install constructor.
     * @param EavSetup $eavSetup
     */
    public function __construct(
        EavSetup $eavSetup
    )
    {
        $this->eavSetup = $eavSetup;
    }

    /**
     * @param Installer $installer
     * @return string
     */
    public function execute(Installer $installer)
    {
        $this->createAdminTable($installer);
    }

    protected function createAdminTable(Installer $installer) {
        $table = $installer->getTable("admin_entity");
        $table->addColumn(
            "entity_id",
            Table::TYPE_INTEGER,
            null,
            [
                "AUTO_INCREMENT",
                "NOT NULL"
            ]
        )->addColumn(
            "username",
            "varchar",
            255,
            [
                "NOT NULL",
                "UNIQUE"
            ]
        )->addColumn(
            "password",
            Table::TYPE_TEXT,
            null,
            [
                "NOT NULL"
            ]
        )->addColumn(
            "created_at",
            Table::TYPE_TIMESTAMP,
            null,
            [
                "NOT NULL",
                "DEFAULT CURRENT_TIMESTAMP"
            ]
        );
        $installer->createTable($table);

        $entity = $this->eavSetup->createEntity("admin", "Admin User");
        $this->eavSetup->createAttribute($entity->getId(), "name", Table::TYPE_TEXT, "Name");
    }
}