<?php
/**
 * includes/loader.php
 * 
 * Configuration and function loader.
 */

/**
 * Basic configuration
 */
require_once(__DIR__.DIRECTORY_SEPARATOR."config.php");

/**
 * Locale selected in the configuration
 */
require_once(__DIR__.DIRECTORY_SEPARATOR."locales".DIRECTORY_SEPARATOR.$locale.".php");

/**
 * Functions
 */
?>
