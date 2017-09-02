<!-- Page Header -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Mein Team (<?php echo $TEAM; ?>)</h1>
	</div>
</div>
<!-- /.row -->

<?php
$result = mysql_query("SELECT usr_id FROM ilias.udf_text WHERE field_id = '1' AND value = '".$TEAM."'");
$team_member = [];
while($row = mysql_fetch_assoc($result)) {
	$team_member[] = $row["usr_id"];
}

//print_r($team_member);

$team_werte = [];
foreach($team_member as $member) {
	$result = mysql_query("SELECT * FROM benutzer WHERE usr_id = '".$member."'");
	if(mysql_num_rows($result) == 1) {
		$b = mysql_fetch_assoc($result);
		$member_distanz = $b["distanz"];
		
		$member_punkte = 0;
		$result = mysql_query("SELECT verkehrsmittel FROM werte WHERE benutzer = '".$member."'");
		if(mysql_num_rows($result) !== 0) {
			while ($row = mysql_fetch_assoc($result)) {
			   if($row["verkehrsmittel"] == 3) {
					$member_punkte += round(pow($member_distanz, 1));
				}
				elseif($row["verkehrsmittel"] == 2) {
					$member_punkte += round(pow($member_distanz, 1.8));
				}
				elseif($row["verkehrsmittel"] == 1) {
					$member_punkte += round(pow($member_distanz, 2));
				}
				else {
					$member_punkte += 0;
				}
			}
		}
		$team_werte[$member] = $member_punkte;
	}
}

//print_r($team_werte);
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
				Anzahl Mitglieder: <?php echo count($team_member); ?><br>
				Teilnehmende Mitglieder: <?php echo count($team_werte); ?><br>
				Team gesammt Punkte: <?php $p = 0; foreach($team_werte as $w) {$p += $w;} echo $p;?>
			</div>
		</div>
	</div>
</div>

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Team-Platz</th>
				<th>Name</th>
				<th>Punkte</th>
			</tr>
		</thead>
		<tbody>
<?php
//Plätze anzeigen und sortieren(!)
$platz = 1;
$counter = count($team_werte);
$menge = 30;
while($counter > 0) {
	$best_punkte = 0;
	$best_member = 0;
	
	foreach($team_werte as $member => $punkte) {
		if($punkte >= $best_punkte) {
			$best_punkte = $punkte;
			$best_member = $member;
		}
	}
	
	echo "<tr>";
	echo "<td>".$platz."</td>";
	/*$result = mysql_query("SELECT firstname, lastname FROM ilias.usr_data WHERE usr_id = '".$best_member."'");
	$info = mysql_fetch_assoc($result);*/
	$a = new schueler($best_member);
	echo "<td>".$a->getFirstName()." ".$a->getLastName()." (".$best_member.")</td>";
	echo "<td>".$best_punkte."</td>";
	echo "</tr>";
	
	//Bestimmen, wie viele Noch angezeigt werden sollen
	unset($team_werte[$best_member]);
	$counter = count($team_werte);
	$menge -= 1;
	if($menge <= 0) {
		$counter = 0;
	}
	$platz += 1;
}
?>
		</tbody>
	</table>
</div>
<!-- /.table-responsive -->