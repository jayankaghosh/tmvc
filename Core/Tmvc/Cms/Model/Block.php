<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Model;


use Tmvc\Framework\Model\AbstractModel;

class Block extends AbstractModel
{

    const IS_ENABLED = 1;
    const IS_NOT_ENABLED = 0;

    const TABLE_NAME = "cms_block";

    protected $indexField = "cms_block_id";

    /**
     * @return string
     */
    public function getTableName()
    {
        return self::TABLE_NAME;
    }

    /**
     * @return string
     */
    public function getIdentifier() {
        return $this->getData('identifier');
    }

    /**
     * @param string $identifier
     * @return $this
     */
    public function setIdentifier($identifier) {
        return $this->setData('identifier', $identifier);
    }

    /**
     * @return string
     */
    public function getBlockContent() {
        return $this->getData('block_content');
    }

    /**
     * @param string $blockContent
     * @return $this
     */
    public function setBlockContent($blockContent) {
        return $this->setData('block_content', $blockContent);
    }

    /**
     * @return bool
     */
    public function getIsEnabled() {
        return (bool)$this->getData('is_enabled');
    }

    /**
     * @param bool $isEnabled
     * @return $this
     */
    public function setIsEnabled($isEnabled) {
        $isEnabled = $isEnabled ? 1 : 0;
        return $this->setData('is_enabled', $isEnabled);
    }
}