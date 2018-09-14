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
use Tmvc\Eav\Model\Eav\AttributeSaver;
use Tmvc\Framework\Cache;
use Tmvc\Framework\Event\Manager as EventManager;
use Tmvc\Framework\Model\Context as ParentContext;

class Context extends ParentContext
{
    /**
     * @var AttributeLoader
     */
    private $attributeLoader;
    /**
     * @var AttributeSaver
     */
    private $attributeSaver;

    /**
     * Context constructor.
     * @param EventManager $eventManager
     * @param Cache $cache
     * @param AttributeLoader $attributeLoader
     * @param AttributeSaver $attributeSaver
     */
    public function __construct(
        EventManager $eventManager,
        Cache $cache,
        AttributeLoader $attributeLoader,
        AttributeSaver $attributeSaver
    )
    {
        parent::__construct($eventManager, $cache);
        $this->attributeLoader = $attributeLoader;
        $this->attributeSaver = $attributeSaver;
    }

    /**
     * @return AttributeLoader
     */
    public function getAttributeLoader(): AttributeLoader
    {
        return $this->attributeLoader;
    }

    /**
     * @return AttributeSaver
     */
    public function getAttributeSaver(): AttributeSaver
    {
        return $this->attributeSaver;
    }
}