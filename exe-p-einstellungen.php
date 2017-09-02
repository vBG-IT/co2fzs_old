<?php
//Session starten
session_start();
?>
<?php
//Login prüfen
if(!isset($_SESSION["ID"])) {
	header("Location: login.php");
	exit();
}
?>
<?php
//Datenbankverbundung aufbauen
include("db_conn.php");
?>
<?php
$distanz = $_POST["distanz"];

if(!isset($distanz)) {
	header("Location: index.php?p=einstellungen");
	exit();
}

mysql_query("UPDATE benutzer SET distanz = '".$distanz."' WHERE usr_id = '".$_SESSION["ID"]."'");

header("Location: index.php?p=einstellungen");
exit();
?>