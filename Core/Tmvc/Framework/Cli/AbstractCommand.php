<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Cli;


use Tmvc\Framework\Cli\Request\Option;
use Tmvc\Framework\Cli\Request\OptionFactory;

abstract class AbstractCommand implements CommandInterface
{
    /**
     * @var OptionFactory
     */
    private $optionFactory;

    /**
     * AbstractCommand constructor.
     * @param OptionFactory $optionFactory
     */
    public function __construct(
        OptionFactory $optionFactory
    )
    {
        $this->optionFactory = $optionFactory;
    }

    /**
     * @return Option[]
     */
    public function getOptions()
    {
        return [];
    }

    public function getTitle()
    {
        return "Default Command Title";
    }

    public function getDescription()
    {
        return "Default Command Description";
    }

    abstract function run(Request $request, Response $response);

    /**
     * @param string $name
     * @param boolean $isRequired
     * @return Option
     */
    public function getNewOption($name, $isRequired = false)
    {
        return $this->optionFactory->create()->setName($name)->setIsRequired($isRequired);
    }
}