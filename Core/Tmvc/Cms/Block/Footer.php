<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Block;


use Tmvc\Cms\Model\Block;
use Tmvc\Framework\DataObject;
use Tmvc\Framework\Tools\ObjectManager;

class Footer extends DataObject
{

    const IDENTIFIER = "footer";

    public function cmsBlockExists() {
        /* @var \Tmvc\Cms\Model\Block $block */
        $block = ObjectManager::create(Block::class);
        return $block->load(static::IDENTIFIER, "identifier")->getIsEnabled();
    }
}