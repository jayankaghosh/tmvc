<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Backend\Service;


use Tmvc\Framework\App\Request;
use Tmvc\Framework\App\Response;
use Tmvc\Framework\Exception\TmvcException;

interface ServiceInterface
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws TmvcException
     */
    public function execute(Request $request, Response $response);
}