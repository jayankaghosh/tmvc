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
        $cache = $this->cache->getCacheFileName(self::CONFIG_FILES_CACHE_KEY);
        if (file_exists($cache)) {
            return [$cache];
        }
        $path = __DIR__."/../../../app/code/";
        $files = glob($path."*/etc/config.php");

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

        /* Remove blank lines */
        $cache = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $cache);


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