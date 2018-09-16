<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Block\Backend\Sections;

use Tmvc\Framework\Tools\Url as UrlBuilder;

class Context
{
    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * Context constructor.
     * @param UrlBuilder $urlBuilder
     */
    public function __construct(
        UrlBuilder $urlBuilder
    )
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return UrlBuilder
     */
    public function getUrlBuilder(): UrlBuilder
    {
        return $this->urlBuilder;
    }
}