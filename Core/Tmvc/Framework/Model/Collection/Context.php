<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Model\Collection;


use Tmvc\Framework\Model\Resource\Db;

class Context
{
    /**
     * @var Db
     */
    private $db;

    /**
     * Context constructor.
     * @param Db $db
     */
    public function __construct(
        Db $db
    )
    {
        $this->db = $db;
    }

    /**
     * @return Db
     */
    public function getDb(): Db
    {
        return $this->db;
    }
}