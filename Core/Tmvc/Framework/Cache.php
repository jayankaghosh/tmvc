<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework;


use Tmvc\Framework\Tools\File;

class Cache
{
    const CACHE_DIR = __DIR__."/../../../var/cache/";
    /**
     * @var File
     */
    private $file;

    /**
     * Cache constructor.
     * @param File $file
     */
    public function __construct(
        File $file
    )
    {
        $this->file = $file;
    }

    /**
     * @param string $cacheKey
     * @return string|null
     */
    public function get($cacheKey) {
        $file = $this->file->load($this->getCacheFileName($cacheKey));
        return $file ? $file->read() : null;
    }

    /**
     * @param string $cacheKey
     * @return string
     */
    public function getCacheFileName($cacheKey) {
        return $this->_formCacheFilePath($cacheKey);
    }

    /**
     * @param string $cacheKey
     * @param string $data
     */
    public function set($cacheKey, $data) {
        $this->file->load($this->_formCacheFilePath($cacheKey))->write($data);
    }

    public function delete($cacheKey) {
        $cache = $this->_formCacheFilePath($cacheKey);
        if (is_file($cache)) {
            $this->file->load($cache)->delete();
        } else if (is_dir($cache)) {
            $this->file->deleteDirectory($cache);
        }
    }

    private function _formCacheFilePath($cacheKey) {
        $cacheKeys = explode("_", $cacheKey);
        foreach ($cacheKeys as &$cacheKey) {
            $cacheKey = md5($cacheKey);
        }
        return self::CACHE_DIR.implode("/", $cacheKeys);
    }

    public function flush() {
        $this->file->deleteDirectory(self::CACHE_DIR);
    }
}