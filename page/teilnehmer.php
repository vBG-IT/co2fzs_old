<?php
$member = $_GET["id"];

if(!isset($member)) {
	header("Location: index.php?p=main");
	exit();
}
?>
<!-- Page Header -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
		  <?php
		      $benutzer = mysql_fetch_assoc(mysql_query("SELECT * FROM benutzer WHERE usr_id = '".$member."'"));
		      if($benutzer['qualifiziert'] == 0) {
	              echo "<span class=\"label label-danger\">Disqualifiziert</span> ";
              }
		      $a = new schueler($member);
		      echo $a->getFirstName()." ".$a->getLastName();
		  ?></h1>
	</div>
</div>
<!-- /.row -->

<?php
$result = mysql_query("SELECT * FROM benutzer WHERE usr_id = '".$member."'");
$b = mysql_fetch_assoc($result);

$distanz = $b["distanz"];



$member_punkte_3 = 0;
$member_punkte_2 = 0;
$member_punkte_1 = 0;
$member_punkte_0 = 0;
$resultx = mysql_query("SELECT verkehrsmittel FROM werte WHERE benutzer = '".$member."'");
if(mysql_num_rows($resultx) !== 0) {
	while ($row = mysql_fetch_assoc($resultx)) {
	   if($row["verkehrsmittel"] == 3) {
			$member_punkte_3 += 1;
		}
		elseif($row["verkehrsmittel"] == 2) {
			$member_punkte_2 += 1;
		}
		elseif($row["verkehrsmittel"] == 1) {
			$member_punkte_1 += 1;
		}
		else {
			$member_punkte_0 += 1;
		}
	}
}

$member_punkte_0 = (count($tage) * 2)-$member_punkte_3-$member_punkte_2-$member_punkte_1;

$member_team = mysql_fetch_assoc(mysql_query("SELECT value FROM ilias.udf_text WHERE field_id = '1' AND usr_id = '".$member."'"));
$member_team = $member_team["value"];

?>

<!--<div class="panel panel-red">
	<div class="panel-heading">
		Wartungsarbeiten
	</div>
	<div class="panel-body">
		<p class="text-danger text-center"><i class="fa fa-exclamation-triangle fa-3x" aria-hidden="true"></i></p>
		<p>Diese Funktion wird gerade überarbeitet und kann deshalb fehlerhaft sein.</p>
	</div>
	<div class="panel-footer">
		~Clemens
	</div>
</div>-->


<div class="row">
	<div class="col-lg-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				Genutze Verkehrsmittel in Wegen
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<div id="kategorien" style="width: 100%;"></div>
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
		<script type="text/javascript">
		Morris.Donut({
			element: 'kategorien',
			colors: [
				"#e6e6e6",
				"#5cb85c",
				"#f0ad4e",
				"#d9534f"
			],
			data: [
				{label: "Gar nicht/Anderes Verkehrsmittel", value: <?php echo $member_punkte_0; ?>},
				{label: "Zu Fuß", value: <?php echo $member_punkte_1; ?>},
				{label: "Mit dem Rad", value: <?php echo $member_punkte_2; ?>},
				{label: "Per ÖPNV", value: <?php echo $member_punkte_3; ?>}
			]
		});
		</script>
	</div>
	<div class="col-lg-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				Statistiken
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover">
						<tbody>
							<tr><th>Team</th><td><a href="index.php?p=team&id=<?php echo $member_team; ?>"><?php echo $member_team; ?></a></td></tr>
							<tr><th>Punkte</th><td><?php echo ($member_punkte_3 * round(pow($distanz, 1))) + ($member_punkte_2 * round(pow($distanz, 1.8))) + ($member_punkte_1 * round(pow($distanz, 2))); ?></td></tr>
							<tr><th>Schulweglänge</th><td><?php echo $distanz; ?>km</td></tr>
							<tr><th>Distanz zu Fuß</th><td><?php echo $member_punkte_1 * $distanz; ?>km</td></tr>
							<tr><th>Distanz mit dem Rad</th><td><?php echo $member_punkte_2 * $distanz; ?>km</td></tr>
							<tr><th>Distanz per ÖPNV</th><td><?php echo $member_punkte_3 * $distanz; ?>km</td></tr>
						</tbody>
					</table>
				</div>
				<!-- /.table-responsive -->
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
</div>
<!-- /.row -->

<div class="row">
    <div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Verlauf
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<div class="table-responsive">
				    <table class="table table-striped table-bordered table-hover">
					   <thead>
                			<tr>
                				<th>Tag</th>
                				<th>Datum</th>
                				<th>Hinweg</th>
                				<th>Rückweg</th>
                			</tr>
                		</thead>
						<tbody>
<?php
foreach($tage as $nummer => $datum) {
	echo "<tr>";
	echo "<td>".$nummer."</td>";
	echo "<td>".$datum."</td>";
	$result = mysql_query("SELECT * FROM werte WHERE benutzer = '".$_GET["id"]."' AND tag = '".$nummer."' AND richtung = '0'");
	if (mysql_num_rows($result) !== 0) {
		$info = mysql_fetch_assoc($result);
		
		if($info["verkehrsmittel"] == 3) {
			$hinweg = "Per ÖPNV (+".round(pow($distanz, 1))."P)";
		}
		elseif($info["verkehrsmittel"] == 2) {
			$hinweg = "Mit dem Rad (+".round(pow($distanz, 1.8))."P)";
		}
		elseif($info["verkehrsmittel"] == 1) {
			$hinweg = "Zu Fuß (+".round(pow($distanz, 2))."P)";
		}
		else {
			$hinweg = "Gar nicht/Anderes Verkehrsmittel (+0P)";
		}
		
	}
	else {
		$hinweg = "-";
	}
	echo "<td>".$hinweg."</td>";
	$result = mysql_query("SELECT * FROM werte WHERE benutzer = '".$_GET["id"]."' AND tag = '".$nummer."' AND richtung = '1'");
	if (mysql_num_rows($result) !== 0) {
		$info = mysql_fetch_assoc($result);
		
		if($info["verkehrsmittel"] == 3) {
			$rueckweg = "Per ÖPNV (+".round(pow($distanz, 1))."P)";
		}
		elseif($info["verkehrsmittel"] == 2) {
			$rueckweg = "Mit dem Rad (+".round(pow($distanz, 1.8))."P)";
		}
		elseif($info["verkehrsmittel"] == 1) {
			$rueckweg = "Zu Fuß (+".round(pow($distanz, 2))."P)";
		}
		else {
			$rueckweg = "Gar nicht/Anderes Verkehrsmittel (+0P)";
		}
		
	}
	else {
		$rueckweg = "-";
	}
	echo "<td>".$rueckweg."</td>";
	echo "</tr>";
}

?>
						</tbody>
					</table>
				</div>
				<!-- /.table-responsive -->
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
</div>
<!-- /.row -->