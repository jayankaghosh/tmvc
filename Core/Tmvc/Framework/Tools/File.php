<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Tools;


class File
{
    /**
     * Use to store fOpen connection
     * @type resource
     * @access private
     */
    private $handle;

    /**
     * To store the file URL/location
     * @type string
     * @access private
     */
    private $file;

    /**
     * Used to initialize the file
     * @access public
     * @param string $file_url
     * File location/url
     * @example 'dir/mytext.txt'
     * @return bool|$this
     */
    public function load($file_url) {
        $this->file = $file_url;
        $dirname = dirname($file_url);
        if (!is_dir($dirname)) {
            mkdir($dirname, 0755, true);
        }
        if ($this->handle = fopen($file_url, 'c+')) {
            return $this;
        } else {
            return false;
        }
    }

    /**
     * Used to write inside the file,
     * If file doesn't exists it will create it
     * @access public
     * @param string $text
     * The text which we have to write
     * @example 'My text here in the file.';
     * @return bool
     */
    public function write($text) {
        if ($this->handle && fwrite($this->handle, $text)) {
            fclose($this->handle);
            return true;
        }
        else {
            fclose($this->handle);
            return false;
        }
    }

    /**
     * To read the contents of the file
     * @access public
     * @param bool $nl2br
     * By default set to false, if set to true will return
     * the contents of the file by preserving the data.
     * @example (true)
     * @return string|bool
     */
    public function read($nl2br = false) {
        if ($this->handle) {
            $filesize = filesize($this->file);
            if ($filesize && $read = fread($this->handle, $filesize)) {
                if ($nl2br == true) {
                    fclose($this->handle);
                    return nl2br($read);
                }

                fclose($this->handle);
                return $read;
            } else {
                fclose($this->handle);
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Use to delete the file
     * @access public
     * @return bool
     */
    public function delete()
    {
        @fclose($this->handle);
        if (file_exists($this->file)) {
            if (unlink($this->file)) {
                return true;
            }
            else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param string $dir
     * @param string|null $regex
     * @return array
     */
    public function getFilesRecursively($path, $regex = null){
        return $this->_getFilesRecursively($path, $regex);
    }

    /**
     * @param string $source
     * @param string $dest
     * @param int $permissions
     * @return bool
     */
    public function mirror($source, $dest, $permissions = 0755)
    {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest, $permissions);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            $this->mirror("$source/$entry", "$dest/$entry", $permissions);
        }

        // Clean up
        $dir->close();
        return true;
    }

    protected function _getFilesRecursively($dir, $regex = null, &$results = []) {
        $files = scandir($dir);
        foreach($files as $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
            if(!is_dir($path)) {
                $matches = [];
                if ($regex && !preg_match($regex, $path, $matches)) continue;
                $results[] = [
                    'path'    => $path,
                    'matches' => $matches
                ];
            } else if($value != "." && $value != "..") {
                $this->_getFilesRecursively($path, $regex, $results);
            }
        }
        return $results;
    }

    public function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }
        return rmdir($dir);
    }
}