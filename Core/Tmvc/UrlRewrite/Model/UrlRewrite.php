<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\UrlRewrite\Model;


use Tmvc\Framework\Model\AbstractModel;

class UrlRewrite extends AbstractModel
{
    const TABLE_NAME = "url_rewrite";
    const EVENT_PREFIX = "url_rewrite";

    protected $tableName = self::TABLE_NAME;
    protected $indexField = "url_rewrite_id";


    /**
     * @return string
     */
    public function getRequestPath() {
        return $this->getData('request_path');
    }

    /**
     * @param string $requestPath
     * @return UrlRewrite
     */
    public function setRequestPath($requestPath) {
        return $this->setData('request_path', $requestPath);
    }

    /**
     * @return string
     */
    public function getActualPath() {
        return $this->getData('actual_path');
    }

    /**
     * @param string $actualPath
     * @return UrlRewrite
     */
    public function setActualPath($actualPath) {
        return $this->setData('actual_path', $actualPath);
    }

    /**
     * @return array|null
     */
    public function getAdditionalInformation() {
        return \json_decode($this->getData('additional_information'), true);
    }

    /**
     * @param array $additionalInformation
     * @return UrlRewrite
     */
    public function setAdditionalInformation($additionalInformation) {
        $additionalInformation = \json_encode($additionalInformation);
        return $this->setData("additional_information", $additionalInformation);
    }
}