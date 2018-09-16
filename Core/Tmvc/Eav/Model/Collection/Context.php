<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Eav\Model\Collection;

use Tmvc\Eav\Model\Eav\AttributeLoader;
use Tmvc\Framework\Model\Collection\Context as ParentContext;
use Tmvc\Framework\Model\Resource\Db;

class Context extends ParentContext
{
    /**
     * @var AttributeLoader
     */
    private $attributeLoader;

    /**
     * Context constructor.
     * @param Db $db
     * @param AttributeLoader $attributeLoader
     */
    public function __construct(
        Db $db,
        AttributeLoader $attributeLoader
    )
    {
        parent::__construct($db);
        $this->attributeLoader = $attributeLoader;
    }

    /**
     * @return AttributeLoader
     */
    public function getAttributeLoader(): AttributeLoader
    {
        return $this->attributeLoader;
    }
}