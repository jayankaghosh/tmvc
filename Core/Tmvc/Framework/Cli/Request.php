<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Cli;


class Request
{
    protected $options = [];

    /**
     * @param array $excluding
     * @return array
     */
    public function getOptions($excluding = []): array
    {
        return array_diff_key($this->options, array_flip($excluding));
    }

    public function getOption($key, $default = null) {
        return array_key_exists($key, $this->getOptions()) ? $this->getOptions()[$key] : $default;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }
}