<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\App;


use Tmvc\Framework\Tools\AppEnv;
use Tmvc\Framework\Tools\ObjectManager;
use Tmvc\Framework\Tools\VarBucket;

class Mode
{
    const MODE_DEVELOPER = "developer";
    const MODE_PRODUCTION = "production";

    const _MODE_KEY = "_tmvc_app_mode";

    public function getMode() {
        if (!VarBucket::read(self::_MODE_KEY)) {
            $appEnv = ObjectManager::get(AppEnv::class);
            $mode = $appEnv->read('app.mode');
            if ($mode !== self::MODE_DEVELOPER && $mode !== self::MODE_PRODUCTION) {
                $mode = self::MODE_PRODUCTION;
            }
            VarBucket::write(self::_MODE_KEY, $mode);
        }
        return VarBucket::read(self::_MODE_KEY);
    }
}