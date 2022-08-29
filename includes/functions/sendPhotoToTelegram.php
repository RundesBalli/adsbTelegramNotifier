<?php
/**
 * sendPhotoToTelegram.php
 */

/**
 * Function to send messages to a Telegram client.
 * 
 * @param string The text to send.
 * @param string The URL of the photograph.
 * 
 * @return boolean TRUE in case of success, FALSE in case of error.
 */
function sendPhotoToTelegram(string $text, string $photoURL) {
  /**
   * Exit function call when text is empty or not provided.
   */
  if(empty($text) OR empty($photoURL)) {
    return FALSE;
  }

  $photoURL = filter_var($photoURL, FILTER_VALIDATE_URL);
  if($photoURL === FALSE) {
    return FALSE;
  }

  /**
   * Get global variables.
   * Impractical, but I want to avoid an extremely long function call.
   */
  global $botToken;
  global $chatId;
  global $disableNotification;
  global $networkInterface;

  /**
   * Prepare POST data.
   */
  $postData = [
  'chat_id' => $chatId,
  'photo' => $photoURL,
  'caption' => $text,
  'parse_mode' => 'Markdown',
  'disable_notification' => $disableNotification,
  'disable_web_page_preview' => TRUE
  ];
  $data = http_build_query($postData);

  /**
   * Open cURL handle, prepare and set options.
   */
  $ch = curl_init();
  $curlOpts = [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.telegram.org/bot'.$botToken.'/sendPhoto',
    CURLOPT_POST => 1,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_POSTFIELDS => $data
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
    return FALSE;
  }

  /**
   * Close cURL handle and return.
   */
  curl_close($ch);
  return TRUE;
}
?>
