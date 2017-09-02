<?php
$changetext = "
=== Teamzuordnung ===

Bist du im falschen Team gelandet?
Wende dich bitte an deinen Klassenlehrer(in),
diese(r) hat von der Schulleitung die Informationen
zum umsortieren bekommen.

Alle Lehrer bilden ein eigenes Team ;-)


=== Preise ===

Bis jetzt sollen die Preise geheim gehalten werden,
doch so viel sei schon gesagt:

Team Gesammtwertung:
 - 2 Exkursionen für das ganze Team

Teilnehmer Kategoriewertung:
 - Hier wird es je nach Kategorie entsprechende Preise
   für die besten geben


=== Changelog ===

08.05.2017
 - Teilnehmer können ab sofort als disqualifiziert gekennzeichnet werden

27.04.2017
 - Anmerkungen hinzugefügt
 - Montag den 01.05.2017 als Wettberwerbstag entfernt
 - Algorithmus zur Berechnung der Ranglisten verbessert:
   - nun werden nur noch Werte bis zum aktuellen Tag in
     den Ranglisten gewertet
 - Verlaufsübersicht bei Benutzern hinzugefügt
";

//Erster Tag, an dem Werte bearbeitet (in der Übersicht der Benutzer) werden dürfen
$eintragen_erster_tag = "24.04.2017";

//Letzter Tag, an dem Werte bearbeitet (in der Übersicht der Benutzer) werden dürfen
$eintragen_letzter_tag = "14.05.2017";

//Alle Tage, an denen der Wettbewerb stattfindet
$tage = [
	1  => "24.04.2017",
	2  => "25.04.2017",
	3  => "26.04.2017",
	4  => "27.04.2017",
	5  => "28.04.2017",
	6  => "02.05.2017",
	7  => "03.05.2017",
	8  => "04.05.2017",
	9  => "05.05.2017",
	10 => "08.05.2017",
	11 => "09.05.2017",
	12 => "10.05.2017",
	13 => "11.05.2017",
	14 => "12.05.2017",
]
?>