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
require_once(__DIR__.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'loader.php');

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
 * Send test message to telegram when test parameter is provided and exit.
 */
$options = getopt('', ['test-telegram']);
if(isset($options['test-telegram'])) {
  echo logEcho($lang['notifier']['sendTestMessage'], 'INFO', COLOR_INFO);
  if(sendMessageToTelegram(EMOJI_SPEAKER.' '.$lang['sendMessage']['testMessage'])) {
    echo logEcho($lang['sendMessage']['ok'], 'OK', COLOR_OK);
  } else {
    echo logEcho($lang['sendMessage']['failed'], 'WARN', COLOR_WARN);
  }
  echo logEcho($lang['exiting'], 'OK', COLOR_OK);
  die();
}

/**
 * Read the previous seen aircraft file.
 */
$previousFile = (!empty($previousJsonFile) ? $previousJsonFile : __DIR__.DIRECTORY_SEPARATOR.'previous.json');
echo logEcho(sprintf($lang['notifier']['previousFile'], $previousFile), 'FILE', COLOR_FILE);
if(!file_exists($previousFile)) {
  $previous = [];
  echo logEcho($lang['notifier']['previousFileNotExists'], 'INFO', COLOR_INFO);
} else {
  if(filesize($previousFile) == 0) {
    $previous = [];
    unlink($previousFile);
    echo logEcho($lang['notifier']['previousFileEmpty'], 'WARN', COLOR_WARN);
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
  if($timeoutAircrafts > 0) {
    echo logEcho(sprintf($lang['notifier']['deleteAircraftCount'], $timeoutAircrafts), 'OK', COLOR_OK);
  } else {
    echo logEcho($lang['notifier']['noDeletedAircrafts'], 'OK', COLOR_OK);
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
     * Check if the datasource from the aircraft is in the configured array.
     */
    if(array_search('all', $dataSources) === FALSE AND array_search($aircraft['type'], $dataSources) === FALSE) {
      echo logEcho(sprintf($lang['notifier']['aircraftWrongDataSource'], $aircraft['hex']), 'INFO', COLOR_INFO);
      continue;
    }

    /**
     * Check if the distance from the aircraft to the station is within the configured radius.
     */
    if(empty($aircraft['r_dst']) OR (!is_numeric($aircraft['r_dst']) OR $aircraft['r_dst'] > $radius)) {
      echo logEcho(sprintf($lang['notifier']['aircraftOutOfRange'], $aircraft['hex']), 'INFO', COLOR_INFO);
      continue;
    }

    /**
     * Check if the $minAlt is reached.
     */
    if(empty($aircraft['alt_baro']) OR $aircraft['alt_baro'] < $minAlt) {
      echo logEcho(sprintf($lang['notifier']['aircraftBelowMinAlt'], $aircraft['hex']), 'INFO', COLOR_INFO);
      continue;
    }

    /**
     * Check if the $maxAlt is reached.
     */
    if(empty($aircraft['alt_baro']) OR $aircraft['alt_baro'] > $maxAlt) {
      echo logEcho(sprintf($lang['notifier']['aircraftAboveMaxAlt'], $aircraft['hex']), 'INFO', COLOR_INFO);
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
      $previous[$aircraft['hex']] = time();
      continue;
    } else {
      /**
       * Aircraft is new within the observation radius.
       */

      /**
       * Prepare text
       */
      $text = sprintf(EMOJI_AIRCRAFT.' '.$lang['notifier']['newAircraftTelegram'], $aircraft['hex']);
      $text.= sprintf($lang['notifier']['aircraftHexLink'], $aircraft['hex'], $aircraft['hex']);
      if(!empty($aircraft['flight'])) {
        $text.= sprintf($lang['notifier']['aircraftFlightLink'], trim($aircraft['flight']), trim($aircraft['flight']));
      }

      /**
       * The following data 'r' (registration) and 'desc' (long description of the aircraft) is only
       * available, if the readsb decoder has the db-file included (see ReadMe).
       */
      if(!empty($aircraft['r'])) {
        $text.= sprintf($lang['notifier']['aircraftRegistration'], trim($aircraft['r']));
        if(!empty($aircraft['desc'])) {
          $text.= sprintf($lang['notifier']['aircraftDesc'], trim($aircraft['desc']));
        }

        /**
         * If the registration is available, the aircraft.csv is included and therefore the dbFlags field is
         * available on special flights.
         */
        $currentDbFlag = (empty($aircraft['dbFlags']) ? 0 : intval($aircraft['dbFlags']));
        if(!in_array($currentDbFlag, $dbFlags, TRUE)) {
          echo logEcho(sprintf($lang['notifier']['aircraftWrongDbFlag'], $aircraft['hex']), 'INFO', COLOR_INFO);
          continue;
        } else {
          $text.= sprintf($lang['notifier']['aircraftDbFlag'], DBFLAGS[$currentDbFlag]);
        }
      } elseif(empty($aircraft['r']) AND $skipWithoutReg === TRUE) {
        /**
         * Skip aircraft, if no registration is available and the skip option is set.
         */
        echo logEcho(sprintf($lang['notifier']['aircraftSkipWithoutReg'], $aircraft['hex']), 'INFO', COLOR_INFO);
        continue;
      }

      /**
       * Check if the coordinates are available and should be posted.
       */
      if((!empty($aircraft['lastPosition']) OR (!empty($aircraft['lat']) AND !empty($aircraft['lon']))) AND $showCoordinates === TRUE) {
        if(!empty($aircraft['lat']) AND !empty($aircraft['lon'])) {
          $lat = $aircraft['lat'];
          $lon = $aircraft['lon'];
        } else {
          $lat = $aircraft['lastPosition']['lat'];
          $lon = $aircraft['lastPosition']['lon'];
        }

        if(!empty($linkToMaps)) {
          /**
           * A map link should be posted.
           */
          $text.= sprintf($lang['notifier']['aircraftCoordinatesLinked'], $lat, $lon, sprintf($linkToMaps, $lat, $lon));
        } else {
          /**
           * No map link should be posted.
           */
          $text.= sprintf($lang['notifier']['aircraftCoordinates'], $lat, $lon);
        }
      }

      /**
       * Check if the altitude is available and should be posted.
       */
      if(!empty($aircraft[$altType]) AND $showAlt === TRUE) {
        $text.= sprintf($lang['notifier']['aircraftAlt'], number_format(($useMetric ? ($aircraft[$altType]/3.28084) : $aircraft[$altType]), 0, $lang['decimalSeparator'], $lang['thousandsSeparator']), ($useMetric ? 'm' : 'ft'));
      }

      /**
       * Show log message, that the aircraft will be notified.
       */
      echo logEcho(sprintf($lang['notifier']['newAircraft'], $aircraft['hex']), 'OK', COLOR_OK);
      $previous[$aircraft['hex']] = time();

      /**
       * Check if planespotters picture is requested.
       */
      if($planespotters == TRUE) {
        echo logEcho($lang['notifier']['planespottersApiCall'], 'INFO', COLOR_INFO);
        $photo = getPlanespottersPhoto($aircraft['hex']);
        if($photo !== FALSE AND is_array($photo)) {
          echo logEcho($lang['notifier']['planespottersApiCallSuccessful'], 'OK', COLOR_OK);
          $textPhoto = sprintf($lang['notifier']['planespottersNote'], $photo['linkToPhoto'], $photo['author']);

          /**
           * Send prepared text with photograph.
           */
          if(sendPhotoToTelegram($text.$textPhoto, $photo['url'])) {
            echo logEcho($lang['sendMessage']['ok'], 'OK', COLOR_OK);
            continue;
          } else {
            echo logEcho($lang['sendMessage']['failed'], 'WARN', COLOR_WARN);
          }
        } else {
          echo logEcho($lang['notifier']['planespottersApiCallFailed'], 'WARN', COLOR_WARN);
        }
        /**
         * If no photograph is available and no notification without photograph is wanted, the aircraft is
         * skipped.
         * 
         * To avoid that the aircraft is requested every time again at planespotters.net, it will be marked as
         * notified.
         */
        if($skipNoPhoto === TRUE) {
          echo logEcho($lang['notifier']['planespottersSkipNoPhoto'], 'WARN', COLOR_WARN);
          $previous[$aircraft['hex']] = time();
          continue;
        }
      }

      /**
       * Send prepared text if no planespotters picture is requested or available.
       */
      if(sendMessageToTelegram($text)) {
        echo logEcho($lang['sendMessage']['ok'], 'OK', COLOR_OK);
      } else {
        echo logEcho($lang['sendMessage']['failed'], 'WARN', COLOR_WARN);
      }
    }
  }
}

/**
 * Write new previous.json file.
 */
echo logEcho($lang['notifier']['previousFileTruncate'], 'FILE', COLOR_FILE);
$fp = fopen($previousFile, 'w+');
fwrite($fp, json_encode($previous));
fclose($fp);

/**
 * Done :-)
 */
echo logEcho($lang['done'], 'OK', COLOR_OK);
?>
