<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\UrlRewrite\Controller;


use Tmvc\Framework\App\Request;
use Tmvc\Framework\Router\RouterInterface;
use Tmvc\UrlRewrite\Model\UrlRewrite\CollectionFactory;

class Router implements RouterInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * Router constructor.
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param Request $request
     * @param string $queryString
     * @param \Application $application
     * @return boolean
     */
    public function route(Request $request, $queryString, \Application $application)
    {
        $collection = $this->collectionFactory->create();
        foreach($collection as $item) {
            /* @var \Tmvc\UrlRewrite\Model\UrlRewrite $item */
            if ($item->getRequestPath() === $queryString) {
                $application->forward($item->getActualPath());
                return true;
            }
        }
        return false; // Let the next router in the pool handle the request
    }
}