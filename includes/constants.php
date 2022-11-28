<?php
/**
 * includes/constants.php
 */

/**
 * Minimum config version
 */
const MIN_CONFIG_VERSION = 7;

/**
 * Clear screen
 */
const CLEAR_SCREEN = "\e[2J\e[1;1H";

/**
 * Emojis
 */
const EMOJI_AIRCRAFT = "\xF0\x9F\x9B\xA9";
const EMOJI_INFO = "\xE2\x84\xB9";
const EMOJI_WARN = "\xE2\x9A\xA0";
const EMOJI_SPEAKER = "\xF0\x9F\x93\xA2";

/**
 * Colors (Terminal)
 */
const COLOR_WARN = "\e[38;5;1m";
const COLOR_CRIT = "\e[38;5;15m\e[48;5;1m";
const COLOR_OPENING = "\e[38;5;6m";
const COLOR_FILE = "\e[38;5;6m";
const COLOR_INFO = "\e[38;5;3m";
const COLOR_OK = "\e[38;5;2m";
const COLOR_RESET = "\e[0m";

/**
 * dbFlags
 */
const DBFLAGS = [
  0 => 'non special',
  1 => 'military',
  2 => 'interesting',
  4 => 'PIA',
  8 => 'LADD'
];
?>
