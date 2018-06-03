<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework;


use Tmvc\Framework\Exception\EntityNotFoundException;
use Tmvc\Framework\Tools\File;

class ConfigReader
{

    const CONFIG_FILES_CACHE_KEY = "_config_files";

    /**
     * @var File
     */
    private $file;
    /**
     * @var Cache
     */
    private $cache;

    /**
     * ConfigReader constructor.
     * @param File $file
     * @param Cache $cache
     */
    public function __construct(
        File $file,
        Cache $cache
    )
    {
        $this->file = $file;
        $this->cache = $cache;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function read() {
        $files = $this->getConfigFiles();
        foreach ($files as $file) {
            if(!@require_once $file) {
                throw new EntityNotFoundException("Config file $file not found");
            }
        }
    }

    public function getConfigFiles() {
        if ($cache = $this->cache->get(self::CONFIG_FILES_CACHE_KEY)) {
            return \json_decode($cache, true);
        }
        $path = __DIR__."/../../../app/code/";
        $files = glob($path."*/etc/config.php");
        $this->cache->set(self::CONFIG_FILES_CACHE_KEY, \json_encode($files));
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