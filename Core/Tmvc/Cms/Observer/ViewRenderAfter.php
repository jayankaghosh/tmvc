<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Observer;


use Tmvc\Cms\Helper\Render as RenderHelper;
use Tmvc\Framework\Event\ObserverInterface;

class ViewRenderAfter implements ObserverInterface
{
    /**
     * @var RenderHelper
     */
    private $renderHelper;

    /**
     * ViewRenderAfter constructor.
     * @param RenderHelper $renderHelper
     */
    public function __construct(
        RenderHelper $renderHelper
    )
    {
        $this->renderHelper = $renderHelper;
    }

    /**
     * @param string $eventName
     * @param array $eventData
     * @return void
     */
    public function execute($eventName, $eventData)
    {
        /* @var \Tmvc\Framework\View\View $view */
        $view = $eventData['view'];
        $result = $this->renderHelper->parseTemplate($view->getResult());
        $view->setResult($result);
    }
}