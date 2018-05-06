<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Tools;


use Tmvc\Framework\Exception\TmvcException;

class AppEnv
{

    const APP_ENV_VAR_KEY = "_tmvc_app_env";

    private $fileName = "app_env.json";

    private $env;

    public function __construct()
    {
        if (!VarBucket::read(self::APP_ENV_VAR_KEY)) {
            $file = ObjectManager::create(File::class);
            $file = $file->load($this->fileName);
            if (!$file instanceof File) {
                throw new TmvcException($this->fileName." not found");
            } else {
                $json = \json_decode($file->read(), true);
                if (!$json) {
                    throw new TmvcException("Invalid JSON in ".$this->fileName);
                } else {
                    VarBucket::write(self::APP_ENV_VAR_KEY, $json);
                }
            }
        }
        $this->env = VarBucket::read(self::APP_ENV_VAR_KEY);
    }

    public function read($node) {
        if ($this->env) {
            $env = $this->env;
            $node = explode(".", $node);
            foreach ($node as $branch) {
                if (isset($env[$branch])) {
                    $env = $env[$branch];
                } else {
                    throw new TmvcException("Node $branch not found in ".$this->fileName);
                }
            }
            return $env;
        } else {
            throw new TmvcException("ENV may be corrupted");
        }
    }

}