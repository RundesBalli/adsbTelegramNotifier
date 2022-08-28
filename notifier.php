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

/**
 * Check config version
 */
echo logEcho($lang['notifier']['checkConfigVersion'], 'INFO', COLOR_INFO);
if($configVersion < MIN_CONFIG_VERSION) {
  echo logEcho($lang['notifier']['updateConfig'], 'CRIT', COLOR_CRIT);
  if(sendMessageToTelegram(EMOJI_WARN.' '.$lang['notifier']['updateConfig'])) {
    echo logEcho($lang['sendMessage']['ok'], 'OK', COLOR_OK);
  } else {
    echo logEcho($lang['sendMessage']['failed'], 'WARN', COLOR_WARN);
  }
  echo logEcho($lang['exiting'], 'CRIT', COLOR_CRIT);
  die();
}
echo logEcho($lang['notifier']['checkConfigVersionDone'], 'OK', COLOR_OK);

/**
 * Check version
 */
echo logEcho($lang['notifier']['checkUpdateAvailable'], 'INFO', COLOR_INFO);
checkVersion();

/**
 * Read the previous seen aircraft file.
 */
$previousFile = __DIR__.DIRECTORY_SEPARATOR.'previous.json';
echo logEcho(sprintf($lang['notifier']['previousFile'], $previousFile), 'FILE', COLOR_FILE);
if(!file_exists($previousFile)) {
  $previous = [];
  echo logEcho($lang['notifier']['previousFileNotExists'], 'INFO', COLOR_INFO);
} else {
  if(filesize($previousFile) == 0) {
    $previous = [];
    unlink($previousFile);
    echo logEcho($lang['notifier']['previousFileEmpty'], "WARN", COLOR_WARN);
  } else {
    $fp = fopen($previousFile, 'r');
    $previous = json_decode(fread($fp, filesize($previousFile)), TRUE);
    fclose($fp);
    if(!is_array($previous)) {
      $previous = [];
      unlink($previousFile);
      echo logEcho($lang['notifier']['previousFileReadFailed'], 'WARN', COLOR_WARN);
    } else {
      echo logEcho($lang['notifier']['previousFileRead'], 'OK', COLOR_OK);
    }
  }
}

/**
 * Delete all aircraft that are outside the timeout.
 */
if(!empty($previous)) {
  echo logEcho(sprintf($lang['notifier']['checkTimeout'], $timeout), 'INFO', COLOR_INFO);
  $timeoutAircrafts = 0;
  foreach($previous AS $icao => $time) {
    if($time <= (time()-$timeout)) {
      $timeoutAircrafts++;
      unset($previous[$icao]);
      echo logEcho(sprintf($lang['notifier']['deleteAircraft'], $icao, date('d.m.Y, H:i:s', $time)), 'OK', COLOR_OK);
    }
  }
}

/**
 * Check if the radius in the configuration file was entered in kilometers or nautical miles.
 * If the value was entered in kilometers, it must be converted to nautical miles, since readsb gives
 * distances in nautical miles.
 */
if($useMetric !== FALSE) {
  $radius = $radius*0.539956803;
}

