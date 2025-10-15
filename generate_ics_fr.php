<?php
/* ============================================================
   GÉNÉRATEUR ICS DYNAMIQUE — ALTAROC.PE
   Événements Livestorm / HubSpot — Version Nils Poirier 2025
   ============================================================ */

// --- CONFIG FIXE (ne change pas d’un événement à l’autre) ---
$CONFIG = [
  'domain'       => 'altaroc.pe',                           // Domaine maison
  'default_file' => 'webinar.ics',                          // Nom par défaut du fichier
  'desc_footer'  => "🔗 Votre lien de connexion personnel : {{link}}", // Pied dans la description
  'default_alarm_minutes' => 0                              // Pas de rappel par défaut
];

// --- Fonction pratique pour lire les paramètres GET ---
function p($key, $default = null) { return isset($_GET[$key]) ? trim($_GET[$key]) : $default; }

// --- Paramètres dynamiques venant de l’URL ---
$title    = p('title', 'Webinar Altaroc');
$desc     = p('desc',  'Découvrez les solutions d’investissement Altaroc');
$link     = p('link',  'https://www.altaroc.pe');
$start    = p('start', '20250101T090000Z');
$end      = p('end',   '20250101T100000Z');
$filename = p('filename', $CONFIG['default_file']);
$alarmMin = (int) p('reminder', $CONFIG['default_alarm_minutes']);

// --- Validation minimale ---
if (!preg_match('/^\d{8}T\d{6}Z$/', $start)) { http_response_code(400); exit('Invalid start'); }
if (!preg_match('/^\d{8}T\d{6}Z$/', $end))   { http_response_code(400); exit('Invalid end');   }

// --- Construit la description complète ---
$descFull = $desc;
if ($CONFIG['desc_footer']) {
  $descFull .= "\n\n" . str_replace('{{link}}', $link, $CONFIG['desc_footer']);
}

// --- UID unique pour l’événement ---
$uid = uniqid('', true) . '@' . $CONFIG['domain'];

// --- Bloc VALARM (si reminder > 0) ---
$valarm = '';
if ($alarmMin > 0) {
  $valarm = "BEGIN:VALARM
TRIGGER:-PT" . intval($alarmMin) . "M
ACTION:DISPLAY
DESCRIPTION:Rappel - $title
END:VALARM
";
}

// --- Construction du contenu ICS ---
$ics = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//" . $CONFIG['domain'] . "//Webinar//FR
CALSCALE:GREGORIAN
BEGIN:VEVENT
UID:$uid
DTSTAMP:" . gmdate('Ymd\THis\Z') . "
DTSTART:$start
DTEND:$end
SUMMARY:$title
DESCRIPTION:" . str_replace(["\r\n","\r","\n"], "\\n", $descFull) . "
LOCATION:$link
URL:$link
$valarm" . "END:VEVENT
END:VCALENDAR";

// --- Envoi du fichier ---
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '.ics"');
echo $ics;
?>
