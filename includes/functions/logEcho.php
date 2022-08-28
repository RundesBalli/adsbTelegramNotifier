<?php
/**
 * includes/functions/logEcho.php
 */

/**
 * logEcho
 * 
 * Function to nicely output log messages.
 * 
 * @param string The text to output.
 * @param string The log level, e.g. INFO.
 * @param string The ANSI escape sequence color code.
 * 
 * @return string The formatted log string.
 */
function logEcho($text = NULL, $logLevel = "INFO", $color = COLOR_RESET) {
  return COLOR_RESET.'['.$color.str_pad(strtoupper($logLevel), 4, ' ', STR_PAD_BOTH).COLOR_RESET.'] '.$text."\n";
}
?>
