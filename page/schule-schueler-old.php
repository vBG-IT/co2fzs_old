<!-- Page Header -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Beste Schüler</h1>
	</div>
</div>
<!-- /.row -->

<?php
$team_werte = [];
$result = mysql_query("SELECT * FROM benutzer WHERE 1");
while($b = mysql_fetch_assoc($result)) {
	$member = $b["usr_id"];
	$member_distanz = $b["distanz"];
	
	$member_punkte = 0;
	$resultx = mysql_query("SELECT verkehrsmittel FROM werte WHERE benutzer = '".$member."'");
	if(mysql_num_rows($resultx) !== 0) {
		while ($row = mysql_fetch_assoc($resultx)) {
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
				Teilnehmer: <?php echo count($team_werte); ?><br>
				Gesammt Punkte: <?php $p = 0; foreach($team_werte as $w) {$p += $w;} echo $p;?>
			</div>
		</div>
	</div>
</div>

<!-- Nav tabs -->
<ul class="nav nav-tabs">
	<li class="active">
		<a href="#alle" data-toggle="tab">Alle</a>
	</li>
	<li>
		<a href="#fuss" data-toggle="tab">Zu Fuß</a>
	</li>
	<li>
		<a href="#rad" data-toggle="tab">Mit dem Rad</a>
	</li>
	<li>
		<a href="#oepnv" data-toggle="tab">Per ÖPNV</a>
	</li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
	<div class="tab-pane fade in active" id="alle">
		<br>
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
	</div>
	<div class="tab-pane fade" id="fuss">
		<br>
		<div class="panel panel-red">
			<div class="panel-heading">
				Coming Soon
			</div>
			<div class="panel-body">
				<p class="text-danger text-center"><i class="fa fa-exclamation-triangle fa-3x" aria-hidden="true"></i></p>
				<p>Diese Funktion ist noch in Arbeit.</p>
			</div>
			<div class="panel-footer">
				~Clemens
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="rad">
		<br>
		<div class="panel panel-red">
			<div class="panel-heading">
				Coming Soon
			</div>
			<div class="panel-body">
				<p class="text-danger text-center"><i class="fa fa-exclamation-triangle fa-3x" aria-hidden="true"></i></p>
				<p>Diese Funktion ist noch in Arbeit.</p>
			</div>
			<div class="panel-footer">
				~Clemens
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="oepnv">
		<br>
		<div class="panel panel-red">
			<div class="panel-heading">
				Coming Soon
			</div>
			<div class="panel-body">
				<p class="text-danger text-center"><i class="fa fa-exclamation-triangle fa-3x" aria-hidden="true"></i></p>
				<p>Diese Funktion ist noch in Arbeit.</p>
			</div>
			<div class="panel-footer">
				~Clemens
			</div>
		</div>
	</div>
</div>

