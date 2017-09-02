<!-- Page Header -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Übersicht</h1>
	</div>
</div>
<!-- /.row -->

<?php
$bearbeiten == false;
$zeit = time();
$erster_tag = strtotime(str_replace(".", "-", $eintragen_erster_tag));
$letzter_tag = strtotime(str_replace(".", "-", $eintragen_letzter_tag));
if($zeit >= $erster_tag AND $zeit <= $letzter_tag+((60*60*24)-1)) {
	$bearbeiten = true;
}
?>

<div class="panel-group" id="accordion">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Infos <span class="fa arrow"></span></a>
			</h4>
		</div>
		<div id="collapseOne" class="panel-collapse collapse">
			<div class="panel-body">
				Hier kannst du vom <?php echo $eintragen_erster_tag; ?> bis zum <?php echo $eintragen_letzter_tag; ?> deine zuvor eingetragenen Werte bearbeiten. Auch kannst du in diesem Zeitraum Werte schon vorher eintagen, falls du dies sonst nicht rechtzeitig hin bekommst. 
			</div>
		</div>
	</div>
</div>

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Tag</th>
				<th>Datum</th>
				<th>Hinweg</th>
				<th>Rückweg</th>
				<th>Bearbeiten</th>
			</tr>
		</thead>
		<tbody>
<?php
foreach($tage as $nummer => $datum) {
	echo "<tr>";
	echo "<td>".$nummer."</td>";
	echo "<td>".$datum."</td>";
	$result = mysql_query("SELECT * FROM werte WHERE benutzer = '".$_SESSION["ID"]."' AND tag = '".$nummer."' AND richtung = '0'");
	if (mysql_num_rows($result) !== 0) {
		$info = mysql_fetch_assoc($result);
		
		if($info["verkehrsmittel"] == 3) {
			$hinweg = "Per ÖPNV (+".round(pow($DISTANZ, 1))."P)";
		}
		elseif($info["verkehrsmittel"] == 2) {
			$hinweg = "Mit dem Rad (+".round(pow($DISTANZ, 1.8))."P)";
		}
		elseif($info["verkehrsmittel"] == 1) {
			$hinweg = "Zu Fuß (+".round(pow($DISTANZ, 2))."P)";
		}
		else {
			$hinweg = "Gar nicht/Anderes Verkehrsmittel (+0P)";
		}
		
	}
	else {
		$hinweg = "-";
	}
	echo "<td>".$hinweg."</td>";
	$result = mysql_query("SELECT * FROM werte WHERE benutzer = '".$_SESSION["ID"]."' AND tag = '".$nummer."' AND richtung = '1'");
	if (mysql_num_rows($result) !== 0) {
		$info = mysql_fetch_assoc($result);
		
		if($info["verkehrsmittel"] == 3) {
			$rueckweg = "Per ÖPNV (+".round(pow($DISTANZ, 1))."P)";
		}
		elseif($info["verkehrsmittel"] == 2) {
			$rueckweg = "Mit dem Rad (+".round(pow($DISTANZ, 1.8))."P)";
		}
		elseif($info["verkehrsmittel"] == 1) {
			$rueckweg = "Zu Fuß (+".round(pow($DISTANZ, 2))."P)";
		}
		else {
			$rueckweg = "Gar nicht/Anderes Verkehrsmittel (+0P)";
		}
		
	}
	else {
		$rueckweg = "-";
	}
	echo "<td>".$rueckweg."</td>";
	if($bearbeiten == true) {
		echo "<td>"."<a href=\"index.php?p=eintragen&tag=".$nummer."\" class=\"btn btn-default\">Bearbeiten</a>"."</td>";
	}
	else {
		echo "<td>"."Bearbeiten nicht möglich."."</td>";
	}
	echo "</tr>";
}

?>
		</tbody>
	</table>
</div>
<!-- /.table-responsive -->