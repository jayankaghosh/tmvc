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

class Cms extends AbstractModel
{

    const IS_ENABLED = 1;
    const IS_NOT_ENABLED = 0;

    const TABLE_NAME = "cms_page";

    protected $indexField = "cms_page_id";

    /**
     * To be overridden by child classes
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
     * @return Cms
     */
    public function setIdentifier($identifier) {
        return $this->setData('identifier', $identifier);
    }

    /**
     * @return string
     */
    public function getPageContent() {
        return $this->getData('page_content');
    }

    /**
     * @param string $pageContent
     * @return Cms
     */
    public function setPageContent($pageContent) {
        return $this->setData('page_content', $pageContent);
    }

    /**
     * @return int
     */
    public function getResponseCode() {
        return (int)$this->getData('response_code');
    }

    /**
     * @param int $pageContent
     * @return Cms
     */
    public function setResponseCode($responseCode) {
        return $this->setData('response_code', $responseCode);
    }

    /**
     * @return bool
     */
    public function getIsEnabled() {
        return (bool)$this->getData('is_enabled');
    }

    /**
     * @param bool $isEnabled
     * @return Cms
     */
    public function setIsEnabled($isEnabled) {
        $isEnabled = $isEnabled ? 1 : 0;
        return $this->setData('is_enabled', $isEnabled);
    }
}