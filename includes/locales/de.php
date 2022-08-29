<?php
/**
 * includes/locales/de.php
 * 
 * German locale
 */

/**
 * Global
 */
$lang['exiting'] = "Beende...";
$lang['sendMessage']['ok'] = "Senden erfolgreich.";
$lang['sendMessage']['failed'] = "Senden fehlgeschlagen.";
$lang['done'] = "Fertig.";

/**
 * includes/functions/checkVersion.php
 */
$lang['checkVersion']['error'] = "Es konnte nicht geprüft werden, ob es eine Aktualisierung gab!";
$lang['checkVersion']['updateAvailable'] = "Es steht ein Update zur Verfügung!\n\nNavigiere im Terminal in das Verzeichnis des Notifiers und führe `git pull` aus um ein Update durchzuführen.";
$lang['checkVersion']['versionOk'] = "Version aktuell.";

/**
 * notifier.php
 */
// Versioncheck
$lang['notifier']['checkConfigVersion'] = "Überprüfe Konfigurationsversion.";
$lang['notifier']['updateConfig'] = "Die Konfigurationsdatei muss aktualisiert werden. Verwende die aktuelle Konfigurationsdatei aus dem Repository als Grundlage.";
$lang['notifier']['checkConfigVersionDone'] = "Konfigurationsversion OK.";
$lang['notifier']['checkUpdateAvailable'] = "Prüfung auf Aktualisierungen.";

// previous.json file handling
$lang['notifier']['previousFile'] = "Datei mit vorher gesehenen Flugzeugen: %s";
$lang['notifier']['previousFileNotExists'] = "Existiert noch nicht. Wird später erstellt.";
$lang['notifier']['previousFileEmpty'] = "Datei ist leer. Wird gelöscht und später neu erstellt.";
$lang['notifier']['previousFileReadFailed'] = "Dateiinhalt ungültig. Datei gelöscht.";
$lang['notifier']['previousFileRead'] = "Datei gelesen.";
$lang['notifier']['checkTimeout'] = "Prüfung ob Flugzeuge außerhalb des gesetzten Timeouts (%d Sekunden) liegen.";
$lang['notifier']['deleteAircraft'] = "%s entfernt, zuletzt gesehen am: %s";
$lang['notifier']['deleteAircraftCount'] = "%d Flugzeuge entfernt.";
$lang['notifier']['noDeletedAircrafts'] = "Keine Flugzeuge entfernt.";
$lang['notifier']['previousFileTruncate'] = "Datei mit gesehenen Flugzeugen wird aktualisiert.";

// aircraft.json file handling
$lang['notifier']['aircraftJsonFile'] = "Datei mit aktuellen Flugzeugen vom Decoder: %s";
$lang['notifier']['aircraftJsonFileNotExists'] = "Decoder Daten nicht auffindbar. Pfad und Betrieb des Decoders prüfen!";
$lang['notifier']['aircraftJsonDataFailed'] = "Decoder Daten fehlerhaft!";
$lang['notifier']['aircraftWrongDataSource'] = "%s kommt von einer unerwünschten Datenquelle.";
$lang['notifier']['aircraftOutOfRange'] = "%s außerhalb vom Überwachungsradius.";
$lang['notifier']['aircraftUpdated'] = "%s bereits gesehen, Zeitpunkt der letzten Sichtung aktualisiert.";
$lang['notifier']['newAircraft'] = "%s neu im Überwachungsradius, wird benachrichtigt.";

// Planespotters API
$lang['notifier']['planespottersApiCall'] = "Frage Foto bei planespotters.net an.";
$lang['notifier']['planespottersApiCallSuccessful'] = "Fotoanfrage erfolgreich.";
$lang['notifier']['planespottersApiCallFailed'] = "Fotoanfrage gescheitert.";

// Telegram message templates
$lang['notifier']['newAircraftTelegram'] = EMOJI_AIRCRAFT." *Neues Flugzeug gesichtet!*\n\n";
$lang['notifier']['aircraftLink'] = "Hex: [%s](".$linkToTar1090."/?icao=%s)\n";
$lang['notifier']['aircraftFlight'] = "Flug: `%s`\n";
$lang['notifier']['aircraftRegistration'] = "Registrierung: `%s`\n";
$lang['notifier']['aircraftDesc'] = "\nFlugzeug: `%s`\n";
$lang['notifier']['planespottersNote'] = "\n[Foto](%s) von %s";
?>
