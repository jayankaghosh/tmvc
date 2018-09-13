<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Eav\Setup;


use Tmvc\Eav\Model\Eav\AttributeLoader;
use Tmvc\Eav\Model\EntityFactory;
use Tmvc\Eav\Model\AttributeFactory;
use Tmvc\Framework\Cache;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Model\Resource\Table;

class EavSetup
{
    /**
     * @var EntityFactory
     */
    private $entityFactory;
    /**
     * @var AttributeFactory
     */
    private $attributeFactory;
    /**
     * @var Cache
     */
    private $cache;

    /**
     * EavSetup constructor.
     * @param EntityFactory $entityFactory
     * @param AttributeFactory $attributeFactory
     * @param Cache $cache
     */
    public function __construct(
        EntityFactory $entityFactory,
        AttributeFactory $attributeFactory,
        Cache $cache
    )
    {
        $this->entityFactory = $entityFactory;
        $this->attributeFactory = $attributeFactory;
        $this->cache = $cache;
    }

    /**
     * @param string $code
     * @param string $name
     * @param string $description
     * @return \Tmvc\Eav\Model\Entity
     * @throws TmvcException
     */
    public function createEntity($code, $name = "", $description = "") {
        $entity = $this->entityFactory->create();
        $entity->load($code, "entity_code");
        if($entity->getId()) {
            throw new TmvcException("Entity with Code $code already exists");
        }
        $entity->setData([
            "entity_code"       =>  $code,
            "entity_name"       =>  $name,
            "entity_description"=>  $description
        ]);
        $entity->save();
        return $entity;
    }

    public function createAttribute($entityId, $attributeCode, $attributeType = Table::TYPE_TEXT, $attributeLabel = "") {
        $attribute = $this->attributeFactory->create();
        $attribute->setData([
            "entity_id"         =>  $entityId,
            "attribute_code"    =>  $attributeCode,
            "attribute_type"    =>  $attributeType,
            "attribute_label"   =>  $attributeLabel
        ]);
        $attribute->save();
        $this->cache->delete(AttributeLoader::ATTRIBUTES_CACHE_KEY);
        return $attribute;
    }

}