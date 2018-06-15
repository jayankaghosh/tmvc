<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\UrlRewrite\Setup;


use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Model\Resource\Table;
use Tmvc\Framework\Module\Setup\Installer;
use Tmvc\Framework\Module\Setup\SetupInstallInterface;
use Tmvc\UrlRewrite\Model\UrlRewrite;

class Install implements SetupInstallInterface
{

    /**
     * @param Installer $installer
     * @return string
     */
    public function execute(Installer $installer)
    {
        try {
            $table = $installer->getTable(UrlRewrite::TABLE_NAME);
            $table->addColumn(
                "url_rewrite_id",
                Table::TYPE_INTEGER,
                null,
                [
                    "AUTO_INCREMENT",
                    "NOT NULL"
                ]
            )->addColumn(
                "request_path",
                Table::TYPE_TEXT,
                1000,
                [
                    "NOT NULL"
                ]
            )->addColumn(
                "actual_path",
                Table::TYPE_TEXT,
                1000,
                [
                    "NOT NULL"
                ]
            )->addColumn(
                "additional_information",
                Table::TYPE_TEXT,
                1000
            );
            $installer->createTable($table);
        } catch (TmvcException $tmvcException) {
            return $tmvcException->getTraceAsString();
        }
    }
}