<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Model\Resource;


use Tmvc\Framework\DataObject;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Tools\AppEnv;
use Tmvc\Framework\Tools\ObjectManager;
use Tmvc\Framework\Tools\VarBucket;

class Db
{

    const DB_CONNECTION_VAR_KEY = "_tmvc_db_connection_var_key";

    protected $mysqlObj;

    public function __construct()
    {
        /* @var \Tmvc\Framework\Tools\AppEnv $appEnv */
        $appEnv = ObjectManager::get(AppEnv::class);
        $this->mysqlObj = new \mysqli(
            $appEnv->read('db.servername'),
            $appEnv->read('db.username'),
            $appEnv->read('db.password'),
            $appEnv->read('db.dbname')
        );
    }

    public function getConnection() {
        return $this->mysqlObj;
    }


    /**
     * @param string $query
     * @return Result
     * @throws TmvcException
     */
    public function query($query) {
        /* @var \Tmvc\Framework\Model\Resource\Result $result */
        $result = $this->getConnection()->query($query);
        if ($result) {
            $result = ObjectManager::create(DataObject::class)->setData([
                'result' => $result
            ]);
            if ($this->getConnection()->insert_id > 0) {
                $result->setData("last_insert_id", $this->getConnection()->insert_id);
            }
            $result = ObjectManager::create(Result::class, [$result]);
        } else {
            throw new TmvcException("MYSQL ERROR: ".$this->getConnection()->error);
        }

        return $result;
    }


}