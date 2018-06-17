<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Block;


use Tmvc\Cms\Model\BlockFactory;
use Tmvc\Framework\DataObject;
use Tmvc\Framework\Tools\ObjectManager;

class Footer extends DataObject
{

    const IDENTIFIER = "footer";
    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * Footer constructor.
     * @param BlockFactory $blockFactory
     */
    public function __construct(
        BlockFactory $blockFactory
    )
    {
        $this->blockFactory = $blockFactory;
    }

    public function cmsBlockExists() {
        /* @var \Tmvc\Cms\Model\Block $block */
        $block = $this->blockFactory->create();
        return $block->load(static::IDENTIFIER, "identifier")->getIsEnabled();
    }
}