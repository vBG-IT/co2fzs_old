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
header("Location: index.php?p=main");
exit();
?>