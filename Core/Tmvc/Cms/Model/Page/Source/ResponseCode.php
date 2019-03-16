<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Model\Page\Source;


use Tmvc\Framework\Model\Source\OptionSourceInterface;

class ResponseCode implements OptionSourceInterface
{
    /**
     * Return an option array with format
     * [ ['label' => "", 'value' => ""], ... ]
     *
     * @return array
     */
    public function toOptionArray()
    {
        $response = [];
        foreach ($this->toArray() as $value => $label) {
            $response[] = [
                'value' =>  $value,
                'label' =>  $label
            ];
        }
        return $response;
    }

    public function toArray()
    {
        return [
            200 => "Success (200)",
            404 => "Not Found (404)",
            500 => "Internal Server Error (500)"
        ];
    }
}