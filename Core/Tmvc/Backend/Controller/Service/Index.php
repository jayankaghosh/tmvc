<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Controller\Service;


use Tmvc\Backend\Controller\AbstractAction;
use Tmvc\Backend\Model\Session;
use Tmvc\Backend\Service\ServicePool;
use Tmvc\Framework\App\Request;
use Tmvc\Framework\App\Response;
use Tmvc\Framework\Controller\Context;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\View\View;

class Index extends AbstractAction
{
    /**
     * @var Response
     */
    private $response;
    /**
     * @var ServicePool
     */
    private $servicePool;

    /**
     * Index constructor.
     * @param Context $context
     * @param Session $session
     * @param Response $response
     * @param ServicePool $servicePool
     * @throws TmvcException
     */
    public function __construct(
        Context $context,
        Session $session,
        Response $response,
        ServicePool $servicePool
    )
    {
        parent::__construct($context, $session, $response);
        $this->response = $response;
        $this->servicePool = $servicePool;
    }

    /**
     * @param Request $request
     * @return View|Response
     * @throws TmvcException
     */
    public function execute(Request $request)
    {
        return $this->runService($request);
    }

    /**
     * @param Request $request
     * @return Response
     */
    protected function runService($request)
    {
        $serviceId = $request->getParam('service');
        try {
            $service = $this->servicePool->getService($serviceId);
            $response = $service->execute($request, $this->response);
            if ($response instanceof Response) {
                return $response;
            } else {
                if (is_array($response)) {
                    $data = $response;
                } else {
                    $data = [
                        'status'    =>  true,
                        'message'   =>  (string)$response
                    ];
                }
            }
        } catch (TmvcException $tmvcException) {
            $data = [
                'status'    =>  false,
                'message'   =>  $tmvcException->getMessage()
            ];
        } catch (\Exception $exception) {
            $data = [
                'status'    =>  false,
                'message'   =>  "Could not execute service $serviceId"
            ];
        }
        return $this->response->setBody(\json_encode($data));
    }
}