<?php
/**
 * includes/functions/getPlanespottersPhoto.php
 * 
 * @see https://www.planespotters.net/photo/api
 * @see https://www.planespotters.net/photo/api/docs
 * @see https://www.planespotters.net/photo/api#terms
 */

/**
 * getPlanespottersPhoto
 * 
 * Function to get a thumbnail URL and the author of a plane picture, selected by ICAO.
 * 
 * @param string The ICAO of the aircraft.
 * 
 * @return array/bool Array with the thumbnail URL and the author of the picture or FALSE if no picture is available.
 */
function getPlanespottersPhoto(string $icao) {
  /**
   * Exit function call when ICAO is empty or not provided.
   */
  if(empty($icao)) {
    return FALSE;
  }

  /**
   * Get global variables.
   * Impractical, but I want to avoid an extremely long function call.
   */
  global $networkInterface;

  /**
   * Open cURL handle, prepare and set options.
   */
  $ch = curl_init();
  $curlOpts = [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.planespotters.net/pub/photos/hex/'.urlencode($icao),
    CURLOPT_USERAGENT => md5(random_bytes(4096)),
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
  $response = json_decode(curl_exec($ch), TRUE);

  /**
   * Error handling.
   */
  if(curl_errno($ch) != 0 OR curl_getinfo($ch, CURLINFO_RESPONSE_CODE) != 200) {
    echo curl_error($ch);
    return FALSE;
  }

  /**
   * Close cURL handle.
   */
  curl_close($ch);

  /**
   * Check if a image is returned.
   */
  if(empty($response['photos'])) {
    return FALSE;
  }

  /**
   * Return array with photo URL and author.
   */
  $response = $response['photos'][0];
  return [
    'url' => $response['thumbnail']['src'],
    'author' => $response['photographer'],
    'linkToPhoto' => $response['link']
  ];
}
?>
