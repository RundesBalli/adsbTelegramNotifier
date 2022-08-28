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

/**
 * Read the contents from the current decoder output.
 */
echo logEcho(sprintf($lang['notifier']['aircraftJsonFile'], $aircraftJsonFile), 'FILE', COLOR_FILE);
if(!file_exists($aircraftJsonFile)) {
  echo logEcho($lang['notifier']['aircraftJsonFileNotExists'], 'CRIT', COLOR_CRIT);
  if(sendMessageToTelegram(EMOJI_WARN.' '.$lang['notifier']['aircraftJsonFileNotExists'])) {
    echo logEcho($lang['sendMessage']['ok'], 'OK', COLOR_OK);
  } else {
    echo logEcho($lang['sendMessage']['failed'], 'WARN', COLOR_WARN);
  }
  echo logEcho($lang['exiting'], 'CRIT', COLOR_CRIT);
  die();
}
$fp = fopen($aircraftJsonFile, 'r');
$aircrafts = json_decode(fread($fp, filesize($aircraftJsonFile)), TRUE);
fclose($fp);

/**
 * If the decoder output is no array, the script will exit.
 */
if(!is_array($aircrafts)) {
  echo logEcho($lang['notifier']['aircraftJsonDataFailed'], 'CRIT', COLOR_CRIT);
  if(sendMessageToTelegram(EMOJI_WARN.' '.$lang['notifier']['aircraftJsonDataFailed'])) {
    echo logEcho($lang['sendMessage']['ok'], 'OK', COLOR_OK);
  } else {
    echo logEcho($lang['sendMessage']['failed'], 'WARN', COLOR_WARN);
  }
  echo logEcho($lang['exiting'], 'CRIT', COLOR_CRIT);
  die();
}

/**
 * Check if the decoder output is empty or not.
 */
if(!empty($aircrafts)) {
  $aircrafts = $aircrafts['aircraft'];
  /**
   * Iterate through the decoder data.
   */
  foreach($aircrafts as $aircraft) {
    /**
     * Check if the distance from the aircraft to the station is within the configured radius.
     */
    if(empty($aircraft['r_dst']) OR (!is_numeric($aircraft['r_dst']) OR $aircraft['r_dst'] > $radius)) {
      echo logEcho(sprintf($lang['notifier']['aircraftOutOfRange'], $aircraft['hex']), 'INFO', COLOR_INFO);
      continue;
    }

    /**
     * Check if the aircraft was seen before.
     */
    if(array_key_exists($aircraft['hex'], $previous)) {
      /**
       * Aircraft has been seen within the defined timeout time and does not need to be notified again. The
       * time of the last sighting is updated.
       */
      echo logEcho(sprintf($lang['notifier']['aircraftUpdated'], $aircraft['hex']), 'OK', COLOR_OK);
    } else {
      /**
       * Aircraft is new within the observation radius.
       */
      echo logEcho(sprintf($lang['notifier']['newAircraft'], $aircraft['hex']), 'OK', COLOR_OK);
      if(sendMessageToTelegram()) {
        echo logEcho($lang['sendMessage']['ok'], 'OK', COLOR_OK);
      } else {
        echo logEcho($lang['sendMessage']['failed'], 'WARN', COLOR_WARN);
      }
    }
    $previous[$aircraft['hex']] = time();
    continue;
  }
}

