<?php
/**
 *
 * @package     tmvc
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Tmvc\Framework\Cli\Response;

class Colors {

    const NO_COLOUR = null;

    const FOREGROUND_COLOR_BLACK = "0;30";
    const FOREGROUND_COLOR_DARK_GRAY = "1;30";
    const FOREGROUND_COLOR_BLUE = "0;34";
    const FOREGROUND_COLOR_LIGHT_BLUE = "1;34";
    const FOREGROUND_COLOR_GREEN = "0;32";
    const FOREGROUND_COLOR_LIGHT_GREEN = "1;32";
    const FOREGROUND_COLOR_CYAN = "0;36";
    const FOREGROUND_COLOR_LIGHT_CYAN = "1;36";
    const FOREGROUND_COLOR_RED = "0;31";
    const FOREGROUND_COLOR_LIGHT_RED = "1;31";
    const FOREGROUND_COLOR_PURPLE = "0;35";
    const FOREGROUND_COLOR_LIGHT_PURPLE = "1;35";
    const FOREGROUND_COLOR_BROWN = "0;33";
    const FOREGROUND_COLOR_YELLOW = "1;33";
    const FOREGROUND_COLOR_LIGHT_GRAY = "0;37";
    const FOREGROUND_COLOR_WHITE = "1;37";

    const BACKGROUND_COLOR_BLACK = "40";
    const BACKGROUND_COLOR_RED = "41";
    const BACKGROUND_COLOR_GREEN = "42";
    const BACKGROUND_COLOR_YELLOW = "43";
    const BACKGROUND_COLOR_BLUE = "44";
    const BACKGROUND_COLOR_MAGENTA = "45";
    const BACKGROUND_COLOR_CYAN = "46";
    const BACKGROUND_COLOR_LIGHT_GRAY = "47";

    /**
     * @param string $string
     * @param string $foreground_color
     * @param string $background_color
     * @return string
     */
    public function getColoredString($string, $foreground_color = self::NO_COLOUR, $background_color = self::NO_COLOUR) {
        $colored_string = "";
        if ($foreground_color) {
            $colored_string .= "\033[" . $foreground_color . "m";
        }
        if ($background_color) {
            $colored_string .= "\033[" . $background_color . "m";
        }
        return $colored_string .  $string . "\033[0m";

    }

}