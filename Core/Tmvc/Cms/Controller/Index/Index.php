<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Cms\Controller\Index;


use Tmvc\Framework\App\Request;
use Tmvc\Framework\App\Response;
use Tmvc\Framework\Controller\AbstractController;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\View\View;

class Index extends AbstractController
{

    /**
     * @param Request $request
     * @return View|Response
     * @throws TmvcException
     */
    public function execute(Request $request)
    {
        return $this->getView()->loadView("cms_homepage", 'Tmvc_Cms::index.phtml');
    }
}