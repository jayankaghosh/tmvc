<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Cli;


use Tmvc\Framework\Cli\Response\Colors;

class Response
{
    /**
     * @var Colors
     */
    private $colors;

    private $foregroundColor = Colors::FOREGROUND_COLOR_WHITE;
    private $backgroundColor = Colors::NO_COLOUR;

    /**
     * Response constructor.
     * @param Colors $colors
     */
    public function __construct(
        Colors $colors
    )
    {
        $this->colors = $colors;
    }

    public function write($message = "") {
        $this->_echo($this->prepareMessage($message));
    }

    public function writeln($message = "") {
        $this->write($message."\n");
    }

    /**
     * @return string|null
     */
    public function getForegroundColor()
    {
        return $this->foregroundColor;
    }

    /**
     * @param string|null $foregroundColor
     * @return $this
     */
    public function setForegroundColor($foregroundColor)
    {
        $this->foregroundColor = $foregroundColor;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param string|null $backgroundColor
     * @return $this
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }

    private function prepareMessage($message) {
        return $this->colors->getColoredString($message, $this->getForegroundColor(), $this->getBackgroundColor());
    }

    /**
     * @param string $message
     */
    private function _echo($message) {
        echo $message;
    }

}