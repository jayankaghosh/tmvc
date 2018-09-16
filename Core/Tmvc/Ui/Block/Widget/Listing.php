<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Ui\Block\Widget;

use Tmvc\Framework\Model\AbstractCollection;
use Tmvc\Framework\View\ViewFactory;

class Listing
{
    /**
     * @var ViewFactory
     */
    private $viewFactory;
    /**
     * @var AbstractCollection
     */
    private $collection;

    /**
     * Listing constructor.
     * @param ViewFactory $viewFactory
     * @param AbstractCollection $collection
     */
    public function __construct(
        ViewFactory $viewFactory,
        $collection
    )
    {
        $this->viewFactory = $viewFactory;
        $this->collection = $collection;
    }

    public function render() {
        return $this->viewFactory->create()->loadView(get_class($this->collection), "Tmvc_Ui::widget/listing.phtml", $this);
    }

    /**
     * @return AbstractCollection
     */
    public function getCollection()
    {
        return $this->collection;
    }

}