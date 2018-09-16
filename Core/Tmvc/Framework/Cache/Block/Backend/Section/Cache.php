<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Cache\Block\Backend\Section;


use Tmvc\Backend\Block\Backend\Sections\AbstractSection;
use Tmvc\Backend\Block\Backend\Sections\Context;
use Tmvc\Backend\Block\Section\Section;
use Tmvc\Framework\Tools\Crypto\Encryptor;
use Tmvc\Framework\Tools\File;

class Cache extends AbstractSection
{
    /**
     * @var File
     */
    private $file;
    /**
     * @var Encryptor
     */
    private $encryptor;

    /**
     * Cache constructor.
     * @param Context $context
     * @param Section $currentSection
     * @param File $file
     */
    public function __construct(
        Context $context,
        Section $currentSection,
        File $file,
        Encryptor $encryptor
    )
    {
        parent::__construct($context, $currentSection);
        $this->file = $file;
        $this->encryptor = $encryptor;
    }

    public function getCacheTreeHtml() {
        $tree = $this->file->getStructure(\Tmvc\Framework\Cache::CACHE_DIR);
        return $this->_getCacheTreeHtml($tree);
    }

    protected function _getCacheTreeHtml($tree, $parent = []) {
        $treeHtml = "<ul class='cache-node'>";
        foreach ($tree as $key => $node) {
            $actualParent = $parent;
            if (is_string($node)) {
                $key = $node;
            }
            $key = $this->encryptor->decrypt($key);
            $actualParent[] = $key;
            $cacheKey = implode("_", $actualParent);
            $treeHtml .= "<li class='cache-key'>
                    <div class='checkbox'>
                        <input type='checkbox' class='cache-key-input' id='$cacheKey' name='cachekey[$cacheKey]'/>
                        <label for='$cacheKey'>".$key."</label>
                    </div>";
            if (is_array($node)) {
                $treeHtml .= $this->_getCacheTreeHtml($node, $actualParent);
            }
            $treeHtml .= "</li>";
        }
        $treeHtml .= "</ul>";
        return $treeHtml;
    }
}