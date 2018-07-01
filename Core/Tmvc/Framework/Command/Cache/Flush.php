<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Command\Cache;


use Tmvc\Framework\Cache;
use Tmvc\Framework\Cli\AbstractCommand;
use Tmvc\Framework\Cli\Request;
use Tmvc\Framework\Cli\Request\OptionFactory;
use Tmvc\Framework\Cli\Response;

class Flush extends AbstractCommand
{
    /**
     * @var Cache
     */
    private $cache;

    /**
     * Flush constructor.
     * @param OptionFactory $optionFactory
     * @param Cache $cache
     */
    public function __construct(
        OptionFactory $optionFactory,
        Cache $cache
    )
    {
        parent::__construct($optionFactory);
        $this->cache = $cache;
    }

    public function run(Request $request, Response $response)
    {
        $response->setForegroundColor(Response\Colors::FOREGROUND_COLOR_GREEN)->writeln("Flushing cache directory");
        $this->cache->flush();
        $response->writeln("Cache directory flushed");
    }

    public function getTitle()
    {
        return "Flush the cache";
    }

    public function getDescription()
    {
        return "Delete the entire cache directory resulting in all cache files to be deleted";
    }
}