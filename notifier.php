<?php
/**
 * adsbTelegramNotifier
 * 
 * Notifies via Telegram when an aircraft passes over a certain area.
 * 
 * @author    RundesBalli <GitHub@RundesBalli.com>
 * @copyright 2022 RundesBalli
 * @see       https://github.com/RundesBalli/adsbTelegramNotifier
 */

/**
 * Including the configuration and function loader.
 */
require_once(__DIR__.DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."loader.php");

/**
 * This script always runs in verbose mode. Therefore its output has to be discarded when using cron.
 */

/**
 * Clear screen and show opening ascii art.
 */
echo CLEAR_SCREEN;
openingArt();

