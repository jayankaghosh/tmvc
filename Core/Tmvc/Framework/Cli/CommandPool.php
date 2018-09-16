<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Cli;


use Tmvc\Framework\Cache;
use Tmvc\Framework\Module\Manager as ModuleManager;
use Tmvc\Framework\Tools\File;
use Tmvc\Framework\Tools\StringUtils;

class CommandPool
{

    const COMMAND_POOL_CACHE_KEY = "tmvc_cli_commands";

    /**
     * @var CommandInterface[]
     */
    private $_commands = [];
    /**
     * @var ModuleManager
     */
    private $moduleManager;
    /**
     * @var File
     */
    private $file;
    /**
     * @var Cache
     */
    private $cache;
    /**
     * @var StringUtils
     */
    private $stringUtils;

    /**
     * CommandPool constructor.
     * @param ModuleManager $moduleManager
     * @param File $file
     * @param Cache $cache
     * @param StringUtils $stringUtils
     */
    public function __construct(
        ModuleManager $moduleManager,
        File $file,
        Cache $cache,
        StringUtils $stringUtils
    )
    {
        $this->moduleManager = $moduleManager;
        $this->file = $file;
        $this->cache = $cache;
        $this->stringUtils = $stringUtils;
        $this->prepareCommands();
    }

    protected function prepareCommands() {
        $cache = $this->cache->get(self::COMMAND_POOL_CACHE_KEY);
        if (!$cache) {
            foreach ($this->moduleManager->getModuleList() as $module) {
                if ($module->getCliCode()) {
                    $files = $this->file->getFilesRecursively($module->getPath(), "/Command\/(.*)\.php$/");
                    foreach ($files as $file) {
                        $namespace = [str_replace("_", "\\", $module->getName())];
                        $namespace[] = str_replace(
                            "/",
                            "\\",
                            preg_replace(
                                "/\.php$/",
                                "",
                                trim(
                                    str_replace(
                                        $module->getPath(),
                                        "",
                                        $file['path']
                                    ),
                                    "/"
                                )
                            )
                        );
                        $namespace = implode("\\", $namespace);
                        $command = $module->getCliCode().":". str_replace("/", ":", $file['matches'][1]);
                        $command = explode(":", $command);
                        foreach ($command as $key => $section) {
                            $command[$key] = str_replace("_", "-", $this->stringUtils->camelToSnakeCase($section));
                        }
                        $command = strtolower(implode(":", $command));
                        $this->_commands[$command] = $namespace;
                    }
                }
            }
            $this->cache->set(self::COMMAND_POOL_CACHE_KEY, \json_encode($this->_commands));
        } else {
            $this->_commands = \json_decode($cache, true);
        }
        return $this->_commands;
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return $this->_commands;
    }
}