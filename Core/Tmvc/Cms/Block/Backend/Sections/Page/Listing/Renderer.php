<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Block\Backend\Sections\Page\Listing;


use Tmvc\Ui\Block\Widget\Listing\RendererInterface;

class Renderer implements RendererInterface
{

    /**
     * @param array $row
     * @return array
     */
    public function render($row)
    {
        $row['is_enabled'] = $row['is_enabled'] ? "Yes" : "No";
        return $row;
    }
}