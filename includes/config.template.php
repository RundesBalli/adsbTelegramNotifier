<?php
/**
 * includes/config.php
 * 
 * Configuration file
 */

/**
 * 
 * BASIC CONFIGURATION
 * 
 */

/**
 * Timezone
 * 
 * The timezone to be used in the notifications. Use the "TZ database name" from the list linked below:
 * @see https://en.wikipedia.org/wiki/List_of_tz_database_time_zones#List
 * e.g. Europe/Berlin
 * 
 * @var string
 */
$timezone = "Europe/Berlin";

/**
 * Locale
 * 
 * The selected language file in which all messages should be displayed.
 * Has to be a filename in /includes/locales
 * e.g. de, en
 * 
 * @var string
 */
$locale = "de";


/**
 * 
 * ADSB CONFIGURATION
 * 
 */

/**
 * aircraft.json file
 * 
 * Location of the aircraft.json file
 * For readsb installations, this file is usually located at:
 * /run/readsb/aircraft.json
 * 
 * @var string
 */
$aircraftJsonFile = "/run/readsb/aircraft.json";

/**
 * Link to tar1090
 * 
 * Link to tar1090 or other map services with ICAO hex selection without last forward slash.
 * e.g. http://your-decoder-ip/tar1090 or https://globe.adsbexchange.com
 * 
 * @var string
 */
$linkToTar1090 = "https://globe.adsbexchange.com";

/**
 * Use planespotters.net API for photographs.
 * 
 * @var bool
 */
$planespotters = TRUE;

/**
 * Skip aircraft when no photograph from planespotters.net is available.
 * 
 * @var bool
 */
$skipNoPhoto = TRUE;

/**
 * Use Metric
 * 
 * Setting whether metric (TRUE) or aeronautical (FALSE) distances should be displayed and calculated.
 * 
 * @var bool
 */
$useMetric = TRUE;

/**
 * Radius to be monitored
 * 
 * The radius around the location set in the decoder configuration that should be monitored. Whether
 * kilometers or nautical miles are used depends on the $useMetric setting.
 * 
 * @var int/float
 */
$radius = 7.5;

/**
 * Minimum flight altitude
 * 
 * If you don't want to be notified about small gliders, the minimum flight altitude can set here. If you want
 * to be notified about every aircraft, set it to 0.
 * 
 * @var int
 */
$minAlt = 0;

/**
 * Data sources
 * 
 * Only data from these data sources is used.
 * e.g. adsb_icao, mode_s, mlat
 * Or use all to use all data sources.
 * 
 * @var array
 */
$dataSources = [
  'all'
];

/**
 * dbFlags
 * 
 * Only data with these dbFlags is used.
 * 0: All non military and non special flights.
 * 1: Military
 * 2: Interesting
 * 4: PIA
 * 8: LADD
 * 
 * Note: This data selection is only available, if the readsb decoder has the db-file included (see ReadMe).
 * 
 * @see https://github.com/wiedehopf/readsb/blob/dev/README-json.md
 * @see https://nbaa.org/aircraft-operations/security/privacy/privacy-icao-address-pia/
 * @see https://nbaa.org/aircraft-operations/security/privacy/limiting-aircraft-data-displayed-ladd/
 * 
 * @var array
 */
$dbFlags = [
  0,
  1,
  2,
  4,
  8
];

/**
 * Skip aircraft without registration
 * 
 * Some aircraft are not registered in the database. For these aircraft no assignment "ICAO" => "Registration"
 * can be made. Only if such an assignment exists, "dbFlags" can be checked. If you use the dbFlags function
 * from above, it is possible that e.g. small gliders without assignment "ICAO" => "Registration" are still
 * reported via telegram. To prevent these false messages, enter TRUE here.
 * 
 * Note: This data selection is only available, if the readsb decoder has the db-file included (see ReadMe).
 * If you set this to TRUE without using the db-file, no aircraft will ever be reported.
 */
$skipWithoutReg = FALSE;

/**
 * Timeout
 * 
 * This value represents the amount of time (in seconds) that an aircraft must be outside of the observation
 * radius before its re-entry will be reported again.
 * e.g. 3600 (1 hour)
 */
$timeout = 3600;


/**
 * 
 * TELEGRAM CONFIGURATION
 * 
 */

/**
 * Bot token
 * 
 * The bot token from the telegram botfather.
 * 
 * @var string
 */
$botToken = "";

/**
 * Chat-ID
 * 
 * The chat ID to send the text to.
 * Note: The chat ID may have a negative sign in front of it.
 * 
 * @var string
 */
$chatId = "";

/**
 * Disable notification
 * 
 * True if the recipient should not be notified about the new message.
 * 
 * @var bool
 */
$disableNotification = FALSE;

/**
 * Network interface
 * 
 * The name of the network interface, e.g. eth0
 * Find out by typing the following command to the terminal: ip a l
 * Leave empty if you are not sure.
 * 
 * @var string
 */
$networkInterface = "";


/**
 * 
 * DO NOT CHANGE
 * 
 */

/**
 * Config version
 * 
 * @var int
 */
$configVersion = 4;
?>
