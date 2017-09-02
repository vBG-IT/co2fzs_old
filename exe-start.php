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
	header("Location: start.php");
	exit();
}

mysql_query("INSERT INTO benutzer (usr_id, distanz) VALUES ('".$_SESSION["ID"]."', '".$distanz."')");

header("Location: intro.php");
exit();
?>