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
use Tmvc\Framework\Cli\Request\OptionFactory;
use Tmvc\Framework\Cli\Response;
use Tmvc\Framework\Tools\File;
use Tmvc\Framework\Tools\ReflectionClass;
use Tmvc\Framework\Module\Manager as ModuleManager;
use Tmvc\Framework\Tools\StringUtils;

class Generate extends AbstractCommand
{
    /**
     * @var File
     */
    private $file;

    /**
     * @var ModuleManager
     */
    private $moduleManager;
    /**
     * @var StringUtils
     */
    private $stringUtils;

    /**
     * Generate constructor.
     * @param OptionFactory $optionFactory
     * @param File $file
     * @param ModuleManager $moduleManager
     * @param StringUtils $stringUtils
     */
    public function __construct(
        OptionFactory $optionFactory,
        File $file,
        ModuleManager $moduleManager,
        StringUtils $stringUtils
    )
    {
        parent::__construct($optionFactory);
        $this->file = $file;
        $this->moduleManager = $moduleManager;
        $this->stringUtils = $stringUtils;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function run(Request $request, Response $response)
    {
        $response->setForegroundColor(Response\Colors::FOREGROUND_COLOR_GREEN)
            ->writeln("Generating factory classes");
        $response->setForegroundColor(Response\Colors::NO_COLOUR);
        foreach ($this->moduleManager->getModuleList() as $module) {
            $files = $this->file->getFilesRecursively($module->getPath(), "/\.php$/");
            foreach ($files as $file) {
                $class = str_replace("_", "\\", $module->getName()).str_replace($module->getPath(), "", preg_replace("/\.php$/", "", $file['path']));
                $class = str_replace("/", "\\", $class);
                foreach (explode("\\", $class) as $item) {
                    if (!$this->stringUtils->starts_with_upper($item)) {
                        continue 2;
                    }
                }
                try {
                    @ReflectionClass::parseClass($class);
                } catch (\ArgumentCountError $argumentCountError) {
                    // ignore class
                } finally {
                    $response->write(".");
                }
            }
        }
        $response->writeln();
        $response->setForegroundColor(Response\Colors::FOREGROUND_COLOR_GREEN)
            ->writeln("Factory classes generated successfully");
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