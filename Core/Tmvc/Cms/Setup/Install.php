<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Setup;


use Tmvc\Cms\Model\Block;
use Tmvc\Cms\Model\Page;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Model\Resource\Table;
use Tmvc\Framework\Module\Setup\Installer;
use Tmvc\Framework\Module\Setup\SetupInstallInterface;

class Install implements SetupInstallInterface
{

    /**
     * @param Installer $installer
     * @return string
     */
    public function execute(Installer $installer)
    {
        return  implode(
            "\n\n",
            [
                $this->createCmsPageTable($installer),
                $this->createCmsBlockTable($installer)
            ]
        );
    }

    /**
     * @param Installer $installer
     * @return string
     */
    protected function createCmsPageTable(Installer $installer) {
        try {
            $table = $installer->getTable(Page::TABLE_NAME);
            $table->addColumn(
                "cms_page_id",
                Table::TYPE_INTEGER,
                null,
                [
                    "AUTO_INCREMENT",
                    "NOT NULL"
                ]
            )->addColumn(
                "identifier",
                Table::TYPE_TEXT,
                1000,
                [
                    "NOT NULL"
                ]
            )->addColumn(
                "page_content",
                Table::TYPE_TEXT,
                null,
                [
                    "NOT NULL"
                ]
            )->addColumn(
                "response_code",
                Table::TYPE_INTEGER,
                5,
                [
                    "NOT NULL"
                ]
            )->addColumn(
                "is_enabled",
                Table::TYPE_SMALLINT,
                1,
                [
                    "NOT NULL"
                ]
            );
            $installer->createTable($table);
            return "CMS Page table created with query: ".$table->__toString();
        } catch (TmvcException $tmvcException) {
            return $tmvcException->getMessage()."\n\n".$tmvcException->getTraceAsString();
        }
    }

    /**
     * @param Installer $installer
     * @return string
     */
    protected function createCmsBlockTable(Installer $installer) {
        try {
            $table = $installer->getTable(Block::TABLE_NAME);
            $table->addColumn(
                "cms_block_id",
                Table::TYPE_INTEGER,
                null,
                [
                    "AUTO_INCREMENT",
                    "NOT NULL"
                ]
            )->addColumn(
                "identifier",
                Table::TYPE_TEXT,
                1000,
                [
                    "NOT NULL"
                ]
            )->addColumn(
                "block_content",
                Table::TYPE_TEXT,
                null,
                [
                    "NOT NULL"
                ]
            )->addColumn(
                "is_enabled",
                Table::TYPE_SMALLINT,
                1,
                [
                    "NOT NULL"
                ]
            );
            $installer->createTable($table);
            return "CMS Block table created with query: ".$table->__toString();
        } catch (TmvcException $tmvcException) {
            return $tmvcException->getMessage()."\n\n".$tmvcException->getTraceAsString();
        }
    }
}