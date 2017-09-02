<?php
$verbindung = mysql_connect ("localhost","user", "pw") or die ("Keine Verbindung zur Datenbank möglich. Bitte informieren Sie den Systemadministrator.");
mysql_set_charset ('utf8');
mysql_select_db("db") or die ("Die Datenbank existiert nicht.");
  


?>
