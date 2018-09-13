<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Eav\Model;


use Tmvc\Eav\Model\Eav\AttributeLoader;
use Tmvc\Framework\Model\Resource\Db;

abstract class AbstractCollection extends \Tmvc\Framework\Model\AbstractCollection
{
    /**
     * @var AttributeLoader
     */
    private $attributeLoader;

    protected $attributesToSelect = [];

    /**
     * AbstractCollection constructor.
     * @param Db $db
     * @param AttributeLoader $attributeLoader
     * @throws \Tmvc\Framework\Exception\TmvcException
     */
    public function __construct(
        Db $db,
        AttributeLoader $attributeLoader
    )
    {
        parent::__construct($db);
        $this->attributeLoader = $attributeLoader;
    }

    protected function itemLoadAfter($item)
    {
        $this->attributeLoader->load($item, $this->attributesToSelect);
        return $item;
    }

    /**
     * @param string|string[] $attribute
     * @return $this
     */
    public function addAttributeToSelect($attribute) {
        if (!is_array($attribute)) {
            $attribute = [$attribute];
        }
        $this->attributesToSelect = array_merge($this->attributesToSelect, $attribute);
        return $this;
    }
}