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
$bearbeiten == false;
$zeit = time();
$erster_tag = strtotime(str_replace(".", "-", $eintragen_erster_tag));
$letzter_tag = strtotime(str_replace(".", "-", $eintragen_letzter_tag));
if($zeit >= $erster_tag AND $zeit <= $letzter_tag+((60*60*24)-1)) {
	$bearbeiten = true;
}
?>
<?php
$tag = $_GET["tag"];
$via = $_GET["via"];

if($via == "uebersicht") {
	if($bearbeiten = true) {
		$hinweg = $_POST["hinweg"];
		$rueckweg = $_POST["rueckweg"];
		
		if(!isset($tag)) {
			header("Location: index.php?p=uebersicht");
			exit();
		}
		
		if(!isset($hinweg) || !isset($rueckweg)) {
			header("Location: index.php?p=eintragen&tag=".$tag);
			exit();
		}
		
		if (mysql_num_rows(mysql_query("SELECT * FROM werte WHERE benutzer = '".$_SESSION["ID"]."' AND tag = '".$tag."' AND richtung = '0'")) == 0) {
			mysql_query("INSERT INTO werte (benutzer, tag, richtung, verkehrsmittel) VALUES ('".$_SESSION["ID"]."', '".$tag."', '0', '".$hinweg."')");
		}
		else {
			mysql_query("UPDATE werte SET verkehrsmittel='".$hinweg."' WHERE benutzer = '".$_SESSION["ID"]."' AND tag = '".$tag."' AND richtung = '0'");
		}
		
		if (mysql_num_rows(mysql_query("SELECT * FROM werte WHERE benutzer = '".$_SESSION["ID"]."' AND tag = '".$tag."' AND richtung = '1'")) == 0) {
			mysql_query("INSERT INTO werte (benutzer, tag, richtung, verkehrsmittel) VALUES ('".$_SESSION["ID"]."', '".$tag."', '1', '".$rueckweg."')");
		}
		else {
			mysql_query("UPDATE werte SET verkehrsmittel = '".$rueckweg."' WHERE benutzer = '".$_SESSION["ID"]."' AND tag = '".$tag."' AND richtung = '1'");
		}
	}
	
	header("Location: index.php?p=uebersicht");
	exit();
}
elseif($via = "main") {
	if($bearbeiten = true) {
		$richtung = $_GET["richtung"];
		$verkehrsmittel = $_GET["verkehrsmittel"];
		
		if($richtung == 0) {
			if (mysql_num_rows(mysql_query("SELECT * FROM werte WHERE benutzer = '".$_SESSION["ID"]."' AND tag = '".$tag."' AND richtung = '0'")) == 0) {
				mysql_query("INSERT INTO werte (benutzer, tag, richtung, verkehrsmittel) VALUES ('".$_SESSION["ID"]."', '".$tag."', '0', '".$verkehrsmittel."')");
			}
			else {
				mysql_query("UPDATE werte SET verkehrsmittel='".$verkehrsmittel."' WHERE benutzer = '".$_SESSION["ID"]."' AND tag = '".$tag."' AND richtung = '0'");
			}
			
			header("Location: index.php?p=main");
			exit();
		}
		elseif($richtung == 1) {
			if (mysql_num_rows(mysql_query("SELECT * FROM werte WHERE benutzer = '".$_SESSION["ID"]."' AND tag = '".$tag."' AND richtung = '1'")) == 0) {
				mysql_query("INSERT INTO werte (benutzer, tag, richtung, verkehrsmittel) VALUES ('".$_SESSION["ID"]."', '".$tag."', '1', '".$verkehrsmittel."')");
			}
			else {
				mysql_query("UPDATE werte SET verkehrsmittel='".$verkehrsmittel."' WHERE benutzer = '".$_SESSION["ID"]."' AND tag = '".$tag."' AND richtung = '1'");
			}
			
			header("Location: index.php?p=main");
			exit();
		}
		else {
			header("Location: index.php?p=main");
			exit();
		}
	}
	
	header("Location: index.php?p=main");
	exit();
}
else {
	header("Location: index.php?p=main");
	exit();
}
?>