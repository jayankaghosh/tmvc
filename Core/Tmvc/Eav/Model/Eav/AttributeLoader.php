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
use Tmvc\Eav\Model\Attribute;
use Tmvc\Eav\Model\Attribute\CollectionFactory as AttributeCollectionFactory;
use Tmvc\Framework\Cache;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Model\Resource\Db;
use Tmvc\Framework\Model\Resource\Raw;
use Tmvc\Framework\Model\Resource\SelectFactory;
use Tmvc\Eav\Model\AttributeFactory;
use Tmvc\Framework\Model\Resource\Table;

class AttributeLoader
{
    const ATTRIBUTES_CACHE_KEY = "tmvc_eav_attributes";

    /**
     * @var AttributeCollectionFactory
     */
    private $attributeCollectionFactory;
    /**
     * @var SelectFactory
     */
    private $selectFactory;
    /**
     * @var AttributeFactory
     */
    private $attributeFactory;
    /**
     * @var Cache
     */
    private $cache;
    /**
     * @var Db
     */
    private $db;

    /**
     * AttributeLoader constructor.
     * @param AttributeCollectionFactory $attributeCollectionFactory
     * @param SelectFactory $selectFactory
     * @param AttributeFactory $attributeFactory
     * @param Cache $cache
     * @param Db $db
     */
    public function __construct(
        AttributeCollectionFactory $attributeCollectionFactory,
        SelectFactory $selectFactory,
        AttributeFactory $attributeFactory,
        Cache $cache,
        Db $db
    )
    {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        $this->selectFactory = $selectFactory;
        $this->attributeFactory = $attributeFactory;
        $this->cache = $cache;
        $this->db = $db;
    }

    public function load(AbstractModel $model, $attributesToFetch = []) {
        if (!$model->getId()) {
            return;
        }
        $attributes = $this->fetchAttributes($model, $attributesToFetch);
        $typeSections = $this->formAttributeTypeSections($attributes, '"');
        foreach ($typeSections as $section => $attributes) {
            $this->appendSectionDataToModel($model, $section, $attributes);
        }
    }

    /**
     * @param AbstractModel $model
     * @param bool $segregateToTypeSections
     * @return string[]|Attribute[]
     * @throws TmvcException
     */
    public function getAllAttributes(AbstractModel $model, $segregateToTypeSections = true) {
        $attributes = $this->fetchAttributes($model, null);
        return $segregateToTypeSections ? $this->formAttributeTypeSections($attributes) : $attributes;
    }


    /**
     * @param AbstractModel $model
     * @param array|null $attributesToFetch
     * @return Attribute[]
     * @throws TmvcException
     */
    protected function fetchAttributes(AbstractModel $model, $attributesToFetch = []) {
        $cacheKey = self::ATTRIBUTES_CACHE_KEY."_".$model->getEntityCode();
        $attributes = $this->cache->get($cacheKey);
        if (!$attributes) {
            $attributeCollection = $this->attributeCollectionFactory->create();
            $attributeCollection->addFieldToFilter(
                'entity_id',
                new Raw("(".
                    $this->selectFactory->create([
                        "tableName" => "eav_entity",
                        "fields" => ["entity_id"]
                    ])->addFieldToFilter("entity_code", $model->getEntityCode())
                    .")"
                )
            );

            if (is_array($attributesToFetch) && count($attributesToFetch)) {
                foreach ($attributesToFetch as $key => $attributeToFetch) {
                    $attributesToFetch[$key] = '"'.$attributeToFetch.'"';
                }
                $attributesToFetch = implode(",", $attributesToFetch);
                $attributeCollection->addFieldToFilter(new Raw("`attribute_code` IN ($attributesToFetch)"));
            }

            $attributes = $attributeCollection->getData();
            $this->cache->set($cacheKey, \json_encode($attributes));
        } else {
            $attributes = \json_decode($attributes, true);
            if (!is_array($attributes)) {
                throw new TmvcException("Attribute $cacheKey cache has been corrupted");
            }
            foreach ($attributes as $index => $attribute) {
                if ($attributesToFetch && !in_array($attribute['attribute_code'], $attributesToFetch)) {
                    unset($attributes[$index]);
                    continue;
                }
                $attributes[$index] = $this->attributeFactory->create()->setData($attribute);
            }
        }
        return $attributes;
    }

    /**
     * @param Attribute[] $attributes
     * @param string $padding
     * @return string[]
     */
    protected function formAttributeTypeSections($attributes, $padding = '') {
        $typeSections = [];
        foreach ($attributes as $attribute) {
            if (!isset($typeSections[$attribute->getAttributeType()])) {
                $typeSections[$attribute->getAttributeType()] = [];
            }
            $typeSections[$attribute->getAttributeType()][] = $padding.$attribute->getAttributeCode().$padding;
        }
        return $typeSections;
    }

    /**
     * @param AbstractModel $model
     * @param string $section
     * @param array $attributes
     */
    protected function appendSectionDataToModel($model, $section, $attributes = []) {
        $attributes = implode(",", $attributes);
        $sectionData = $this->getConnection()->query("SELECT `main_table`.`attribute_value`, `a`.`attribute_code` FROM `eav_value_$section` AS `main_table` LEFT JOIN `eav_attribute` AS `a` ON `main_table`.`attribute_id` = `a`.`attribute_id` WHERE `a`.`attribute_code` IN ($attributes) AND main_table.entity_id = ".$model->getId())->getItems();
        foreach ($sectionData as $value) {
            $model->setData(
                $value->getData('attribute_code'),
                $this->formSectionValueByType(
                    $section,
                    $value->getData('attribute_value')
                )
            );
        }
    }

    /**
     * @return Db
     */
    public function getConnection() {
        return $this->db;
    }

    private function formSectionValueByType($type, $value) {
        switch ($type) {
            case Table::TYPE_INTEGER:
            case Table::TYPE_NUMERIC:
            case Table::TYPE_DECIMAL:
            case Table::TYPE_BIGINT:
            case Table::TYPE_SMALLINT:
                return intval($value);
            case Table::TYPE_FLOAT:
                return floatval($value);

            default:
                return $value;
        }
    }
}