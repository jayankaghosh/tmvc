<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Module;


use Tmvc\Framework\Cache;
use Tmvc\Framework\Tools\File;
use Tmvc\Framework\Module\Setup\Manager as SetupManager;

class Manager
{

    const MODULE_LIST_CACHE_KEY = "tmvc_module_list";

    /**
     * @var null|array
     */
    private $_moduleList = null;

    /**
     * @var Cache
     */
    private $cache;
    /**
     * @var File
     */
    private $file;
    /**
     * @var SetupManager
     */
    private $setupManager;

    /**
     * Manager constructor.
     * @param Cache $cache
     * @param File $file
     * @param SetupManager $setupManager
     */
    public function __construct(
        Cache $cache,
        File $file,
        SetupManager $setupManager
    )
    {
        $this->cache = $cache;
        $this->file = $file;
        $this->setupManager = $setupManager;
    }

    public function manage() {
        $modules = $this->getModuleList();
        foreach ($modules as $moduleName => $modulePath) {
            $this->setupManager->validate($moduleName);
        }
    }

    /**
     * @param $moduleName
     * @return string|null
     */
    public function getModule($moduleName) {
        return isset($this->getModuleList()[$moduleName]) ? $this->getModuleList()[$moduleName] : null;
    }

    public function getModuleList() {
        if (!$this->_moduleList) {
            $cache = $this->cache->get(self::MODULE_LIST_CACHE_KEY);
            if (!$cache) {
                $modules = [];
                $path = __DIR__ . "/../../../../app/code/";
                $files = glob($path . "*/*");
                array_walk($files, function ($file) use (&$modules) {
                    $module = realpath($file);
                    $moduleName = explode("/", $module);
                    $moduleName = $moduleName[count($moduleName) - 2] . "_" . $moduleName[count($moduleName) - 1];
                    $modules[$moduleName] = $module;
                });
                $cache = \json_encode($modules);
                $this->cache->set(self::MODULE_LIST_CACHE_KEY, $cache);
            }
            $this->_moduleList = \json_decode($cache, true);
        }
        return $this->_moduleList;
    }
}