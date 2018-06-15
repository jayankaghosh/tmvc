<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Module\Setup;


use Tmvc\Framework\Cache;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Tools\ObjectManager;

class Manager
{

    const SETUP_CACHE_KEY = "tmvc_setup_";

    /**
     * @var Cache
     */
    private $cache;
    /**
     * @var Installer
     */
    private $installer;

    /**
     * Manager constructor.
     * @param Cache $cache
     * @param Installer $installer
     */
    public function __construct(
        Cache $cache,
        Installer $installer
    )
    {
        $this->cache = $cache;
        $this->installer = $installer;
    }

    public function validate($moduleName) {
        $cacheKey = self::SETUP_CACHE_KEY.$moduleName;
        if (!$this->cache->get($cacheKey)) {
            $response = $this->runSetupScript($moduleName);
            $this->cache->set($cacheKey, $response);
        }
    }

    /**
     * @param string $moduleName
     * @return string
     */
    protected function runSetupScript($moduleName) {
        $setupFile = str_replace("_", "\\", $moduleName)."\\Setup\\Install";
        try {
            /* @var \Tmvc\Framework\Module\Setup\SetupInstallInterface $setupScript */
            $setupScript = ObjectManager::get($setupFile);
            return $setupScript->execute($this->installer);
        } catch (TmvcException $tmvcException) {
            return $tmvcException->getMessage();
        }
    }
}