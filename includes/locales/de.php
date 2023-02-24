<?php
/**
 * includes/locales/de.php
 * 
 * German locale
 */

/**
 * Global
 */
$lang['exiting'] = 'Beende...';
$lang['sendMessage']['ok'] = 'Senden erfolgreich.';
$lang['sendMessage']['failed'] = 'Senden fehlgeschlagen.';
$lang['sendMessage']['testMessage'] = 'Testnachricht!';
$lang['done'] = 'Fertig.';
$lang['decimalSeparator'] = ',';
$lang['thousandsSeparator'] = '.';

/**
 * includes/functions/checkVersion.php
 */
$lang['checkVersion']['skipped'] = 'Prüfung auf Aktualisierungen übersprungen. Wird nur alle 15 Minuten geprüft!';
$lang['checkVersion']['error'] = 'Es konnte nicht geprüft werden, ob es eine Aktualisierung gab!';
$lang['checkVersion']['notifyAgain'] = 'Letzte Aktualisierungsbenachrichtigung vor 3 Tagen. Benachrichtige erneut.';
$lang['checkVersion']['updateAvailable'] = "Es steht eine Aktualisierung zur Verfügung!\n\nNavigiere im Terminal in das Verzeichnis des Notifiers und führe `git pull` aus um eine Aktualisierung durchzuführen.";
$lang['checkVersion']['versionOk'] = 'Version aktuell.';

/**
 * notifier.php
 */
// Versioncheck
$lang['notifier']['checkConfigVersion'] = 'Überprüfe Konfigurationsversion.';
$lang['notifier']['updateConfig'] = 'Die Konfigurationsdatei muss aktualisiert werden. Verwende die aktuelle Konfigurationsdatei aus dem Repository als Grundlage.';
$lang['notifier']['checkConfigVersionDone'] = 'Konfigurationsversion OK.';
$lang['notifier']['checkUpdateAvailable'] = 'Prüfung auf Aktualisierungen.';

// Send test message to telegram
$lang['notifier']['sendTestMessage'] = 'Sende Telegram Testnachricht und beende.';

// previous.json file handling
$lang['notifier']['previousFile'] = 'Datei mit vorher gesehenen Flugzeugen: %s';
$lang['notifier']['previousFileNotExists'] = 'Existiert noch nicht. Wird später erstellt.';
$lang['notifier']['previousFileEmpty'] = 'Datei ist leer. Wird gelöscht und später neu erstellt.';
$lang['notifier']['previousFileReadFailed'] = 'Dateiinhalt ungültig. Datei gelöscht.';
$lang['notifier']['previousFileRead'] = 'Datei gelesen.';
$lang['notifier']['checkTimeout'] = 'Prüfung ob Flugzeuge außerhalb des gesetzten Timeouts (%d Sekunden) liegen.';
$lang['notifier']['deleteAircraft'] = '%s entfernt, zuletzt gesehen am: %s';
$lang['notifier']['deleteAircraftCount'] = '%d Flugzeuge entfernt.';
$lang['notifier']['noDeletedAircrafts'] = 'Keine Flugzeuge entfernt.';
$lang['notifier']['previousFileTruncate'] = 'Datei mit gesehenen Flugzeugen wird aktualisiert.';

// aircraft.json file handling
$lang['notifier']['aircraftJsonFile'] = 'Datei mit aktuellen Flugzeugen vom Decoder: %s';
$lang['notifier']['aircraftJsonFileNotExists'] = 'Decoder Daten nicht auffindbar. Pfad und Betrieb des Decoders prüfen!';
$lang['notifier']['aircraftJsonDataFailed'] = 'Decoder Daten fehlerhaft!';
$lang['notifier']['aircraftWrongDataSource'] = '%s kommt von einer unerwünschten Datenquelle.';
$lang['notifier']['aircraftOutOfRange'] = '%s außerhalb vom Überwachungsradius oder hat einen unbekannten Standort.';
$lang['notifier']['aircraftBelowMinAlt'] = '%s unterhalb der Mindesthöhe.';
$lang['notifier']['aircraftAboveMaxAlt'] = '%s über der Maximalhöhe.';
$lang['notifier']['aircraftUpdated'] = '%s bereits gesehen, Zeitpunkt der letzten Sichtung aktualisiert.';
$lang['notifier']['aircraftWrongDbFlag'] = '%s hat ein unerwünschtes dbFlag.';
$lang['notifier']['aircraftSkipWithoutReg'] = '%s wurde übersprungen, da keine Registrierung ermittelt werden konnte.';
$lang['notifier']['newAircraft'] = '%s neu im Überwachungsradius, wird benachrichtigt.';

// Planespotters API
$lang['notifier']['planespottersApiCall'] = 'Frage Foto bei planespotters.net an.';
$lang['notifier']['planespottersApiCallSuccessful'] = 'Fotoanfrage erfolgreich.';
$lang['notifier']['planespottersApiCallFailed'] = 'Fotoanfrage gescheitert.';
$lang['notifier']['planespottersSkipNoPhoto'] = 'Überspringe Flugzeug, da kein Foto verfügbar ist.';

// Telegram message templates
$lang['notifier']['newAircraftTelegram'] = "*Neues Flugzeug gesichtet!*\n\n";
$lang['notifier']['aircraftHexLink'] = 'Hex: [%s]('.$linkToTar1090.")\n";
$lang['notifier']['aircraftFlightLink'] = 'Flug: [%s]('.$linkToFlightStats.")\n";
$lang['notifier']['aircraftRegistration'] = "Registrierung: `%s`\n";
$lang['notifier']['aircraftDesc'] = "\nFlugzeug:\n`%s`\n";
$lang['notifier']['aircraftDbFlag'] = "\ndbFlag: `%s`\n";
$lang['notifier']['aircraftCoordinates'] = "Koordinaten: %s, %s\n";
$lang['notifier']['aircraftCoordinatesLinked'] = "Koordinaten: [%s, %s](%s)\n";
$lang['notifier']['aircraftAlt'] = "Höhe: %s%s\n";
$lang['notifier']['planespottersNote'] = "\n[Foto](%s) von %s";

/**
 * Execution time
 */
$lang['notifier']['executionTime'] = 'Ausführungszeit: ';
?>
