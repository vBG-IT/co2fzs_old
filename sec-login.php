<?php
//Session starten
session_start();
?>
<?php
include("db_conn.php");
?>
<?php
//Variablen definieren
$benutzername = $_POST["benutzername"];
$passwort = md5($_POST["passwort"]);

//Benutzer validieren
$result = mysql_query("SELECT passwd, usr_id FROM ilias.usr_data WHERE login = '".$benutzername."'");
$benutzer = mysql_fetch_assoc($result);

if($benutzer["passwd"] == $passwort) {
	$_SESSION["ID"] = $benutzer["usr_id"];
	header("Location: index.php");
	exit();
}
else {
	header("Location: login.php?info=denied");
	exit();
}
?>