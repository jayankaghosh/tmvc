<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Model\Source;


interface OptionSourceInterface
{
    /**
     * Return an option array with format
     * [ ['label' => "", 'value' => ""], ... ]
     *
     * @return array
     */
    public function toOptionArray();
}