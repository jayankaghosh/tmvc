<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Deploy\Strategy;


use Tmvc\Framework\Tools\File;

class Copy
{
    /**
     * @var File
     */
    private $file;

    /**
     * Copy constructor.
     * @param File $file
     */
    public function __construct(
        File $file
    )
    {
        $this->file = $file;
    }

    /**
     * @param string $source
     * @param string $destination
     * @return bool
     */
    public function execute($source, $destination) {
        return $this->file->mirror($source, $destination);
    }
}