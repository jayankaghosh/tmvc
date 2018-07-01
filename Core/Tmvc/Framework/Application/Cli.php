<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Application;

use Tmvc\Framework\Cli\Response;
use Tmvc\Framework\Cli\CommandInterface;
use Tmvc\Framework\Cli\CommandPool;
use Tmvc\Framework\Cli\Request;
use Tmvc\Framework\Exception\ArgumentMismatchException;
use Tmvc\Framework\Exception\TmvcException;
use Tmvc\Framework\Tools\ObjectManager;

class Cli implements ApplicationInterface
{
    /**
     * @var array
     */
    protected $arguments;
    /**
     * @var CommandPool
     */
    private $commandPool;
    /**
     * @var Response
     */
    private $response;
    /**
     * @var Request
     */
    private $request;

    /**
     * Cli constructor.
     * @param CommandPool $commandPool
     * @param Request $request
     * @param Response $response
     */
    public function __construct(
        CommandPool $commandPool,
        Request $request,
        Response $response
    )
    {
        $this->arguments = ARGV;
        array_shift($this->arguments);
        $this->commandPool = $commandPool;
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * @return void
     */
    public function route()
    {
        try {
            $commands = $this->commandPool->getCommands();
            foreach ($commands as $command => $class) {
                /** @var CommandInterface $commandInstance */
                $commandInstance = ObjectManager::get($class);
                $commands[$command] = $commandInstance;
            }
            /** @var CommandInterface[] $commands */
            if(!$this->arguments) {
                $this->listAllCommands($commands);
            } else {
                if (isset($commands[$this->arguments[0]])) {
                    $command = $commands[$this->arguments[0]];
                    $options = [];
                    foreach ($command->getOptions() as $option) {
                        foreach ($this->arguments as $argument) {
                            $argument = explode("=", trim($argument, "-"));
                            if ($argument[0] === $option->getName()) {
                                $options[$option->getName()] = ((isset($argument[1])) ? $argument[1] : true);
                                break 2;
                            }
                        }
                        if ($option->isRequired()) {
                            throw new ArgumentMismatchException($option->getName()." is a required option");
                        }
                    }
                    $this->request->setOptions($options);
                    $command->run($this->request, $this->response);
                } else {
                    throw new ArgumentMismatchException("Command \"".$this->arguments[0]."\" not found");
                }
            }
        } catch (TmvcException $tmvcException) {
            $this->_displayException($tmvcException);
        }
    }

    private function _displayException(\Exception $exception) {
        $title = "Exception ".get_class($exception)." occurred";
        $description = $exception->getMessage();
        $length = max(strlen($title), strlen($description));
        $this->response->writeln();
        $this->response
            ->setBackgroundColor(Response\Colors::BACKGROUND_COLOR_RED)
            ->writeln($this->_formExceptionMessage(" ", $length));
        $this->response->setForegroundColor(Response\Colors::FOREGROUND_COLOR_WHITE)->writeln($this->_formExceptionMessage($title, $length));
        $this->response->writeln($this->_formExceptionMessage(" ", $length));
        $this->response
            ->setForegroundColor(Response\Colors::NO_COLOUR)
            ->setBackgroundColor(Response\Colors::BACKGROUND_COLOR_RED)
            ->writeln($this->_formExceptionMessage($description, $length));
        $this->response->writeln($this->_formExceptionMessage(" ", $length));
        $this->response->setBackgroundColor(Response\Colors::NO_COLOUR);
        $this->response->writeln();
    }

    /**
     * @param string $message
     * @param int $length
     * @param int $padding
     * @return string
     */
    private function _formExceptionMessage($message, $length, $padding = 6) {
        $message = str_pad($message, $length, " ", STR_PAD_RIGHT);
        return str_pad($message, $length+$padding, " ", STR_PAD_BOTH);
    }

    /**
     * @param CommandInterface[] $commands
     */
    protected function listAllCommands($commands) {
        $this->response->writeln();
        foreach ($commands as $code => $command) {
            $this->response
                ->setBackgroundColor(Response\Colors::BACKGROUND_COLOR_BLUE)
                ->writeln($command->getTitle());
            $this->response
                ->setForegroundColor(Response\Colors::FOREGROUND_COLOR_GREEN)
                ->setBackgroundColor(Response\Colors::NO_COLOUR)
                ->writeln($code);
            $this->response
                ->setForegroundColor(Response\Colors::NO_COLOUR)
                ->setBackgroundColor(Response\Colors::NO_COLOUR);
            $this->response->write($command->getDescription());
            $this->response->writeln();
            $this->response->writeln();
        }
        $this->response->writeln();
    }
}