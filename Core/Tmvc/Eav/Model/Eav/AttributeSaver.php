<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Eav\Model\Eav;


use Tmvc\Eav\Model\AbstractModel;

class AttributeSaver
{
    /**
     * @var AttributeLoader
     */
    private $attributeLoader;

    /**
     * AttributeSaver constructor.
     * @param AttributeLoader $attributeLoader
     */
    public function __construct(
        AttributeLoader $attributeLoader
    )
    {
        $this->attributeLoader = $attributeLoader;
    }

    public function save(AbstractModel $model) {
        $data = $model->getData();
        $attributeSections = $this->attributeLoader->getAllAttributes($model);
        foreach ($attributeSections as $section => $attributes) {
            $query = "INSERT INTO eav_value_$section (entity_id, attribute_id, attribute_value) VALUES ";
            $valueStrings = [];
            foreach ($attributes as $attribute) {
                if (array_key_exists($attribute, $data)) {
                    $valueStrings[] = '('.
                            $model->getId().','.
                            '(SELECT `attribute_id` FROM `eav_attribute` WHERE `attribute_code` = "'.$attribute.'"),'.
                            '"'.$data[$attribute].'"'.
                        ')';
                }
            }
            if (count($valueStrings)) {
                $query .= implode(",", $valueStrings);
                $query .= " ON DUPLICATE KEY UPDATE `attribute_value` = VALUES(attribute_value)";
                $this->attributeLoader->getConnection()->query($query);
            }
        }
    }

    public function delete(AbstractModel $model) {
        $attributeSections = $this->attributeLoader->getAllAttributes($model);
        foreach ($attributeSections as $section => $attributes) {
            $query = "DELETE FROM eav_value_$section WHERE entity_id = ".$model->getId();
            $this->attributeLoader->getConnection()->query($query);
        }
    }
}