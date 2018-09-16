<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Deploy;


use Tmvc\Framework\Deploy\Strategy\Copy;
use Tmvc\Framework\Exception\ArgumentMismatchException;

class Deploy
{
    const STRATEGY_COPY = "copy";
    /**
     * @var Copy
     */
    private $copy;

    /**
     * Deploy constructor.
     * @param Copy $copy
     */
    public function __construct(
        Copy $copy
    )
    {
        $this->copy = $copy;
    }

    /**
     * @param string $source
     * @param string $destination
     * @param string $strategy
     * @return bool
     * @throws ArgumentMismatchException
     */
    public function execute($source, $destination, $strategy = self::STRATEGY_COPY) {
        switch ($strategy) {
            case self::STRATEGY_COPY:
                return $this->copy->execute($source, $destination);
            default:
                throw new ArgumentMismatchException("Strategy $strategy not supported");
        }
    }
}