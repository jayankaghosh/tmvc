<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Command\StaticContent;


use Tmvc\Framework\Cli\AbstractCommand;
use Tmvc\Framework\Cli\Request;
use Tmvc\Framework\Cli\Request\OptionFactory;
use Tmvc\Framework\Cli\Response;
use Tmvc\Framework\Module\Manager as ModuleManager;
use Tmvc\Framework\Tools\Crypto\Hasher;
use Tmvc\Framework\Tools\File;

class Deploy extends AbstractCommand
{
    /**
     * @var \Tmvc\Framework\Deploy\Deploy
     */
    private $deploy;
    /**
     * @var ModuleManager
     */
    private $moduleManager;
    /**
     * @var Hasher
     */
    private $hasher;
    /**
     * @var File
     */
    private $file;

    /**
     * Deploy constructor.
     * @param OptionFactory $optionFactory
     * @param \Tmvc\Framework\Deploy\Deploy $deploy
     * @param ModuleManager $moduleManager
     * @param Hasher $hasher
     * @param File $file
     */
    public function __construct(
        OptionFactory $optionFactory,
        \Tmvc\Framework\Deploy\Deploy $deploy,
        ModuleManager $moduleManager,
        Hasher $hasher,
        File $file
    )
    {
        parent::__construct($optionFactory);
        $this->deploy = $deploy;
        $this->moduleManager = $moduleManager;
        $this->hasher = $hasher;
        $this->file = $file;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function run(Request $request, Response $response)
    {
        $response->setForegroundColor(Response\Colors::FOREGROUND_COLOR_GREEN)->writeln("Starting Deployment of static content");
        $response->setForegroundColor(Response\Colors::NO_COLOUR);



        foreach ($this->moduleManager->getModuleList() as $module) {
            if (is_dir($module->getPath()."/view/pub")) {
                $this->deploy->execute($module->getPath() . "/view/pub", PROJECT_ROOT_PATH . "pub/" . $module->getName());
                $response->write(".");
            }
        }
        $this->generateMetaData();
        $response->writeln("");
        $response->setForegroundColor(Response\Colors::FOREGROUND_COLOR_GREEN)->writeln("Deployment Complete");
    }

    protected function generateMetaData() {
        $this->file->load(PROJECT_ROOT_PATH."pub/.htaccess")->write('RewriteRule ^(.*)$ index.php?u=$1 [NC,QSA]');
        $this->file->load(PROJECT_ROOT_PATH."pub/index.php")->write('<?php
$filename = __DIR__.str_replace(file_get_contents(\'deployment_version\'), \'\', $_REQUEST[\'u\']);
header(\'Content-type: text/\'.pathinfo($filename, PATHINFO_EXTENSION));
require_once $filename;');
        $version = $this->hasher->hash(strtotime('now'));
        $this->file->load(PROJECT_ROOT_PATH."pub/deployment_version")->write($version);
    }
}