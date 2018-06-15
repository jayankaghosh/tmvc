<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Module\Setup;


use Tmvc\Framework\Model\Resource\Db;
use Tmvc\Framework\Model\Resource\Table;
use Tmvc\Framework\Tools\ObjectManager;

class Installer
{
    /**
     * @var Db
     */
    private $db;

    /**
     * Installer constructor.
     * @param Db $db
     */
    public function __construct(
        Db $db
    )
    {
        $this->db = $db;
    }

    /**
     * @param string $tableName
     * @return Table
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function getTable($tableName) {
        return ObjectManager::create(Table::class, ['tableName' => $tableName]);
    }

    /**
     * @param Table $table
     * @return \Tmvc\Framework\Model\Resource\Result
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function createTable(Table $table) {
        return $this->db->query($table);
    }
}