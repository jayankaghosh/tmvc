<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Ui\Block;


use Tmvc\Framework\DataObject;
use Tmvc\Framework\Tools\Url;

class Init extends DataObject
{
    /**
     * @var Url
     */
    private $urlBuilder;

    /**
     * Init constructor.
     * @param Url $urlBuilder
     */
    public function __construct(
        Url $urlBuilder
    )
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return Url
     */
    public function getUrlBuilder(): Url
    {
        return $this->urlBuilder;
    }
}