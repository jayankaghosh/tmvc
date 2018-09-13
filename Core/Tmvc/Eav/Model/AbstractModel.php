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
use Tmvc\Framework\Model\Resource\Table;

abstract class AbstractModel extends \Tmvc\Framework\Model\AbstractModel
{

    protected $indexField = "entity_id";
    /**
     * @var AttributeLoader
     */
    private $attributeLoader;
    /**
     * @var AttributeSaver
     */
    private $attributeSaver;

    /**
     * AbstractModel constructor.
     * @param EventManager $eventManager
     * @param Cache $cache
     * @param AttributeLoader $attributeLoader
     * @param AttributeSaver $attributeSaver
     * @throws \Tmvc\Framework\Exception\TmvcException
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
     * @return string
     */
    abstract public function getEntityCode();

    protected function afterLoad()
    {
        parent::afterLoad();
        $this->attributeLoader->load($this, null);
    }

    protected function afterSave()
    {
        parent::afterSave();
        $this->attributeSaver->save($this);
    }

    protected function afterDelete()
    {
        parent::afterDelete();
        $this->attributeSaver->delete($this);
    }
}