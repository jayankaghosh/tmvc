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
use Tmvc\Ui\Helper\UiHelper;

class LoginForm extends DataObject
{
    /**
     * @var Url
     */
    private $url;
    /**
     * @var UiHelper
     */
    private $uiHelper;

    /**
     * LoginForm constructor.
     * @param Url $url
     * @param UiHelper $uiHelper
     */
    public function __construct(
        Url $url,
        UiHelper $uiHelper
    )
    {
        $this->url = $url;
        $this->uiHelper = $uiHelper;
    }

    public function getPostUrl() {
        return $this->url->getUrl("*/*/post");
    }

    public function getPubUrl($resource, $module = "Tmvc_Backend") {
        return $this->url->getPubUrl("$module/$resource");
    }

    /**
     * @return UiHelper
     */
    public function getUiHelper(): UiHelper
    {
        return $this->uiHelper;
    }
}