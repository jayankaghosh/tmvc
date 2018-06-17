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
use Tmvc\Framework\Exception\TmvcException;
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
        foreach ($modules as $moduleName => $module) {
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
                $systemModulesPath = __DIR__ . "/../../../";
                $userModulesPath = __DIR__ . "/../../../../app/code/";
                $systemModules = $this->_getModules($systemModulesPath);
                $userModules = $this->_getModules($userModulesPath);
                $modules = array_merge($systemModules, $userModules);
                $this->_sortModules($modules);
                $cache = \json_encode($modules);
                $this->cache->set(self::MODULE_LIST_CACHE_KEY, $cache);
            }
            $this->_moduleList = \json_decode($cache, true);
        }
        return $this->_moduleList;
    }

    private function _getModules($path) {
        $modules = [];
        $files = glob($path . "*/*");
        array_walk($files, function ($file) use (&$modules) {
            try {
                $path = realpath($file);
                $definition = $this->getDefinition($path);
                $moduleName = $definition['name'];
                $modules[$moduleName] = $definition;
                $modules[$moduleName]["path"] = $path;
            } catch (\Exception $exception) {
                throw new TmvcException($exception->getMessage());
            }
        });
        return $modules;
    }

    private function _sortModules(&$modules) {
        uasort($modules, function ($first, $second) {
            return $first['sort_order'] <=> $second['sort_order'];
        });
    }

    protected function getDefinition ($modulePath) {
        $file = $modulePath . "/module.json";
        if (file_exists($file)) {
            $definition = \json_decode($this->file->load($file)->read(), true);
        } else {
            $definition = [];
        }

        $requiredParameters = [
            "name",
            "version",
            "sort_order"
        ];

        if (array_diff_key(array_flip($requiredParameters), $definition)) {
            throw new TmvcException("Definition not proper for $modulePath");
        }
        return $definition;
    }
}