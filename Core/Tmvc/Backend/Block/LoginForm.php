<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Block;


use Tmvc\Framework\DataObject;
use Tmvc\Framework\Tools\Url;

class LoginForm extends DataObject
{
    /**
     * @var Url
     */
    private $url;

    /**
     * LoginForm constructor.
     * @param Url $url
     */
    public function __construct(
        Url $url
    )
    {
        $this->url = $url;
    }

    public function getPostUrl() {
        return $this->url->getUrl("*/*/post");
    }

    public function getPubUrl($resource, $module = "Tmvc_Backend") {
        return $this->url->getPubUrl("$module/$resource");
    }
}