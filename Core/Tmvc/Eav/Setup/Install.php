<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Eav\Setup;


use Tmvc\Framework\Model\Resource\Db;
use Tmvc\Framework\Model\Resource\Table;
use Tmvc\Framework\Module\Setup\Installer;
use Tmvc\Framework\Module\Setup\SetupInstallInterface;

class Install implements SetupInstallInterface
{
    /**
     * @var Db
     */
    private $db;

    /**
     * Install constructor.
     * @param Db $db
     */
    public function __construct(
        Db $db
    )
    {
        $this->db = $db;
    }

    /**
     * @param Installer $installer
     * @return string
     */
    public function execute(Installer $installer)
    {
        $this->createEntityTable($installer);
        $this->createAttributeTable($installer);

        $attributes = [Table::TYPE_INTEGER, Table::TYPE_TEXT, Table::TYPE_SMALLINT, Table::TYPE_BIGINT, Table::TYPE_BLOB, Table::TYPE_BOOLEAN, Table::TYPE_DATE, Table::TYPE_DATETIME, Table::TYPE_DECIMAL, Table::TYPE_FLOAT, Table::TYPE_NUMERIC, Table::TYPE_TIMESTAMP];
        foreach ($attributes as $attribute) {
            $this->createValueTable($installer, $attribute);
        }
    }

    /**
     * @param Installer $installer
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    protected function createEntityTable(Installer $installer) {
        $entityTable = $installer->getTable("eav_entity")
            ->addColumn(
                "entity_id",
                Table::TYPE_INTEGER,
                null,
                [
                    "AUTO_INCREMENT",
                    "NOT NULL"
                ]
            )->addColumn(
                "entity_code",
                Table::TYPE_TEXT,
                255,
                [
                    "NOT NULL"
                ]
            )->addColumn(
                "entity_name",
                Table::TYPE_TEXT,
                255,
                [
                    "NOT NULL"
                ]
            )->addColumn(
                "entity_description",
                Table::TYPE_TEXT,
                255,
                [
                    "NOT NULL"
                ]
            );
        $installer->createTable($entityTable);
    }

    /**
     * @param Installer $installer
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    protected function createAttributeTable(Installer $installer) {
        $attributeTable = $installer->getTable("eav_attribute")
            ->addColumn(
                "attribute_id",
                Table::TYPE_INTEGER,
                null,
                [
                    "AUTO_INCREMENT",
                    "NOT NULL"
                ]
            )->addColumn(
                "entity_id",
                Table::TYPE_INTEGER,
                null,
                [
                    "NOT NULL"
                ]
            )->addColumn(
                "attribute_code",
                "VARCHAR",
                255,
                [
                    "NOT NULL"
                ]
            )->addColumn(
                "attribute_label",
                "VARCHAR",
                255,
                [
                    "NOT NULL"
                ]
            )->addColumn(
                "attribute_type",
                "VARCHAR",
                255,
                [
                    "NOT NULL"
                ]
            );
        $installer->createTable($attributeTable);

        $this->db->query("ALTER TABLE eav_attribute ADD FOREIGN KEY (entity_id) REFERENCES eav_entity(entity_id)");
        $this->db->query("ALTER TABLE eav_attribute ADD CONSTRAINT UNIQUE_ATTRIBUTE_CONSTRAINT UNIQUE (entity_id, attribute_code, attribute_type)");
    }

    protected function createValueTable(Installer $installer, $type) {
        $attributeTable = $installer->getTable("eav_value_$type")
            ->addColumn(
                "value_id",
                Table::TYPE_INTEGER,
                null,
                [
                    "AUTO_INCREMENT",
                    "NOT NULL"
                ]
            )->addColumn(
                "entity_id",
                Table::TYPE_INTEGER,
                null,
                [
                    "NOT NULL"
                ]
            )->addColumn(
                "attribute_id",
                Table::TYPE_INTEGER,
                null,
                [
                    "NOT NULL"
                ]
            )->addColumn(
                "attribute_value",
                $type,
                null
            );
            $installer->createTable($attributeTable);
        $this->db->query("ALTER TABLE eav_value_$type ADD FOREIGN KEY (attribute_id) REFERENCES eav_attribute(attribute_id)");
        $this->db->query("ALTER TABLE eav_value_$type ADD CONSTRAINT UNIQUE_ATTRIBUTE_CONSTRAINT_".strtoupper($type)." UNIQUE (entity_id, attribute_id)");
    }


}