<?php
/**
 * checkVersion.php
 */

/**
 * checkVersion
 * 
 * Function to check if a new version is available on GitHub.
 * 
 * @return void
 */
function checkVersion() {
  /**
   * Get global variables.
   * Impractical, but I want to avoid an extremely long function call.
   */
  global $networkInterface;
  global $lang;

  /**
   * Open cURL handle, prepare and set options.
   */
  $ch = curl_init();
  $curlOpts = [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://raw.githubusercontent.com/RundesBalli/adsbTelegramNotifier/master/version.txt',
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 15
  ];
  if(!empty($networkInterface)) {
    $curlOpts[CURLOPT_INTERFACE] = $networkInterface;
  }
  curl_setopt_array($ch, $curlOpts);

  /**
   * Execute cURL.
   */
  $response = curl_exec($ch);

  /**
   * Error handling.
   */
  if(curl_errno($ch) != 0 OR curl_getinfo($ch, CURLINFO_RESPONSE_CODE) != 200) {
    echo logEcho($lang['checkVersion']['error'], 'WARN', COLOR_WARN);
    if(sendMessageToTelegram(EMOJI_WARN.' '.$lang['checkVersion']['error'])) {
      echo logEcho($lang['sendMessage']['ok'], 'OK', COLOR_OK);
    } else {
      echo logEcho($lang['sendMessage']['failed'], 'WARN', COLOR_WARN);
    }
    return;
  }

  /**
   * Close cURL handle.
   */
  curl_close($ch);

  /**
   * Read local version file and check if the version number provided by GitHub is different.
   */
  $versionFile = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.'version.txt';
  $fp = fopen($versionFile, 'r');
  $version = fread($fp, filesize($versionFile));
  fclose($fp);
  $notifyFile = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.'UPDATE_AVAILABLE';
  if($version != $response) {
    /**
     * Versions are different.
     * 
     * Now it is necessary to check whether the user has already been notified or not.
     */
    if(!file_exists($notifyFile)) {
      /**
       * The user has not been notified so far.
       */
      echo logEcho($lang['checkVersion']['updateAvailable'], 'INFO', COLOR_INFO);
      if(sendMessageToTelegram(EMOJI_INFO.' '.$lang['checkVersion']['updateAvailable'])) {
        echo logEcho($lang['sendMessage']['ok'], 'OK', COLOR_OK);
      } else {
        echo logEcho($lang['sendMessage']['failed'], 'WARN', COLOR_WARN);
      }
      return;
    }
  } elseif(file_exists($notifyFile)) {
    unlink($notifyFile);
  }
  echo logEcho($lang['checkVersion']['versionOk'], 'OK', COLOR_OK);
  return;
}
?>
