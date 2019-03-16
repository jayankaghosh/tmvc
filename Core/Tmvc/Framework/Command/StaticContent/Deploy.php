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
        $this->file->load(PROJECT_ROOT_PATH."pub/.htaccess")->write('RewriteRule ^(.*)$ get.php?u=$1 [NC,QSA]');
        $this->file->load(PROJECT_ROOT_PATH."pub/get.php")->write('<?php

$deploymentVersion = "pub/".\file_get_contents("deployment_version");
$requestUri = $_SERVER[\'REQUEST_URI\'];
$resource = substr($requestUri, strpos($requestUri, $deploymentVersion) + strlen($deploymentVersion) + 1);

$mime_types = array(

    \'txt\' => \'text/plain\',
    \'htm\' => \'text/html\',
    \'html\' => \'text/html\',
    \'php\' => \'text/html\',
    \'css\' => \'text/css\',
    \'js\' => \'application/javascript\',
    \'json\' => \'application/json\',
    \'xml\' => \'application/xml\',
    \'swf\' => \'application/x-shockwave-flash\',
    \'flv\' => \'video/x-flv\',

    // images
    \'png\' => \'image/png\',
    \'jpe\' => \'image/jpeg\',
    \'jpeg\' => \'image/jpeg\',
    \'jpg\' => \'image/jpeg\',
    \'gif\' => \'image/gif\',
    \'bmp\' => \'image/bmp\',
    \'ico\' => \'image/vnd.microsoft.icon\',
    \'tiff\' => \'image/tiff\',
    \'tif\' => \'image/tiff\',
    \'svg\' => \'image/svg+xml\',
    \'svgz\' => \'image/svg+xml\',

    // archives
    \'zip\' => \'application/zip\',
    \'rar\' => \'application/x-rar-compressed\',
    \'exe\' => \'application/x-msdownload\',
    \'msi\' => \'application/x-msdownload\',
    \'cab\' => \'application/vnd.ms-cab-compressed\',

    // audio/video
    \'mp3\' => \'audio/mpeg\',
    \'qt\' => \'video/quicktime\',
    \'mov\' => \'video/quicktime\',

    // adobe
    \'pdf\' => \'application/pdf\',
    \'psd\' => \'image/vnd.adobe.photoshop\',
    \'ai\' => \'application/postscript\',
    \'eps\' => \'application/postscript\',
    \'ps\' => \'application/postscript\',

    // ms office
    \'doc\' => \'application/msword\',
    \'rtf\' => \'application/rtf\',
    \'xls\' => \'application/vnd.ms-excel\',
    \'ppt\' => \'application/vnd.ms-powerpoint\',

    // open office
    \'odt\' => \'application/vnd.oasis.opendocument.text\',
    \'ods\' => \'application/vnd.oasis.opendocument.spreadsheet\',

    \'woff\'=> \'application/x-font-woff\'
);


if ($resource && file_exists($resource)) {
    $extension = pathinfo($resource, PATHINFO_EXTENSION);
    $mimeType = array_key_exists($extension, $mime_types) ? $mime_types[$extension] : $mime_types[\'txt\'];
    header("Content-type: $mimeType; charset: UTF-8");
    readfile($resource);
} else {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>The requested resource $resource not found</h1>";
}');
        $version = $this->hasher->hash(strtotime('now'));
        $this->file->load(PROJECT_ROOT_PATH."pub/deployment_version")->write($version);
    }
}