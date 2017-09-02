<?php
//Session starten
session_start();
?>
<?php
//Session beenden
session_destroy();

//Auf Login Seite weiterleiten
header("Location: login.php");
exit();

?>