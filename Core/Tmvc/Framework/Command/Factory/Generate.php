<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Command\Factory;


use Tmvc\Framework\Cli\AbstractCommand;
use Tmvc\Framework\Cli\Request;
use Tmvc\Framework\Cli\Response;

class Generate extends AbstractCommand
{

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function run(Request $request, Response $response)
    {

    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return "Generate Factory Classes";
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Generates all factory classes required for the application to run";
    }
}