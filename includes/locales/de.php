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

/**
 * includes/functions/checkVersion.php
 */
$lang['checkVersion']['error'] = "Es konnte nicht geprüft werden, ob es eine Aktualisierung gab!";
$lang['checkVersion']['updateAvailable'] = "Es steht ein Update zur Verfügung!\n\nNavigiere im Terminal in das Verzeichnis des Notifiers und führe `git pull` aus um ein Update durchzuführen.";
$lang['checkVersion']['versionOk'] = "Version aktuell.";

/**
 * notifier.php
 */
$lang['notifier']['checkConfigVersion'] = "Überprüfe Konfigurationsversion.";
$lang['notifier']['updateConfig'] = "Die Konfigurationsdatei muss aktualisiert werden. Verwende die aktuelle Konfigurationsdatei aus dem Repository als Grundlage.";
$lang['notifier']['checkConfigVersionDone'] = "Konfigurationsversion OK.";
$lang['notifier']['checkUpdateAvailable'] = "Prüfung auf Aktualisierungen.";

$lang['notifier']['previousFile'] = "Datei mit vorher gesehenen Flugzeugen: %s";
$lang['notifier']['previousFileNotExists'] = "Existiert noch nicht. Wird später erstellt.";
$lang['notifier']['previousFileEmpty'] = "Datei ist leer. Wird gelöscht und später neu erstellt.";
$lang['notifier']['previousFileReadFailed'] = "Dateiinhalt ungültig. Datei gelöscht.";
$lang['notifier']['previousFileRead'] = "Datei gelesen.";
$lang['notifier']['checkTimeout'] = "Prüfung ob Flugzeuge außerhalb des gesetzten Timeouts (%d Sekunden) liegen.";
$lang['notifier']['deleteAircraft'] = "%s entfernt, zuletzt gesehen am: %s";

?>
