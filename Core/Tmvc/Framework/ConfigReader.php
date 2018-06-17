<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework;


use Tmvc\Framework\App\Config;
use Tmvc\Framework\Exception\EntityNotFoundException;
use Tmvc\Framework\Tools\File;
use Tmvc\Framework\Module\Manager as ModuleManager;

class ConfigReader
{

    const CONFIG_FILES_CACHE_KEY = "tmvc_config_files";

    const PHP_STARTING_TAG = "<?php";
    const PHP_ENDING_TAG = "?>";

    /**
     * @var File
     */
    private $file;
    /**
     * @var Cache
     */
    private $cache;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * ConfigReader constructor.
     * @param File $file
     * @param Cache $cache
     * @param Config $config
     * @param ModuleManager $moduleManager
     */
    public function __construct(
        File $file,
        Cache $cache,
        Config $config,
        ModuleManager $moduleManager
    )
    {
        $this->file = $file;
        $this->cache = $cache;
        $this->config = $config;
        $this->moduleManager = $moduleManager;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function read() {
        $files = $this->getConfigFiles();
        foreach ($files as $file) {
            /* Pass an object of class Config to the config files */
            $config = $this->config;
            if(!@require_once $file) {
                throw new EntityNotFoundException("Config file $file not found");
            }
        }
    }

    public function getConfigFiles() {
        $cache = $this->cache->getCacheFileName(self::CONFIG_FILES_CACHE_KEY);
        if (file_exists($cache)) {
            return [$cache];
        }

        $files = [];

        $modules = $this->moduleManager->getModuleList();
        foreach ($modules as $module) {
            $configFilePath = $module['path']."/etc/config.php";
            if (file_exists($configFilePath)) {
                $files[] = $configFilePath;
            }
        }

        $cache = [
            self::PHP_STARTING_TAG
        ];
        foreach ($files as $file) {
            $file = $this->file->load($file)->read();

            /* Remove starting php tag if exists */
            if (substr($file, 0, strlen(self::PHP_STARTING_TAG)) === self::PHP_STARTING_TAG) {
                $file = substr($file, strlen(self::PHP_STARTING_TAG));
            }

            /* Remove ending php tag if exists */
            if (substr($file, -1*strlen(self::PHP_ENDING_TAG)) === self::PHP_ENDING_TAG) {
                $file = substr($file, 0, -1*strlen(self::PHP_ENDING_TAG));
            }

            $cache[] = $file;
        }

        $cache[] = self::PHP_ENDING_TAG;

        $cache = implode("", $cache);

        /* Remove comments and blank lines */
        $cache = preg_replace('!/\*.*?\*/!s', '', $cache);
        $cache = preg_replace('/\n\s*\n/', "\n", $cache);

        $this->cache->set(self::CONFIG_FILES_CACHE_KEY, $cache);
        return $files;
    }

    private function rglob($pattern, $flags = 0) {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
            $files = array_merge($files, $this->rglob($dir.'/'.basename($pattern), $flags));
        }
        return $files;
    }
}