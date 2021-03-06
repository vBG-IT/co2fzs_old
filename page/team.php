<?php
if(!isset($_GET["id"])) {
	header("Location: index.php?p=team&team=".$TEAM);
	exit();
}
?>

<!-- Page Header -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header"><?php if($_GET["id"] == $TEAM) { echo "Mein Team (".$TEAM.")";} else { echo "Team ".$_GET["id"];}?></h1>
	</div>
</div>
<!-- /.row -->

<?php
// Heutigen Tag auslesen
$timestamps = [];
foreach($tage as $tag => $datum) {
	$timestamps[$tag] = strtotime(str_replace(".", "-", $datum));
}
$zeit = time();

$heute = 0;
foreach($timestamps as $tag => $timestamp) {
	if($zeit >= $timestamp AND ($zeit < $timestamps[$tag+1] OR $timestamps[$tag+1] == "")) {
		$heute = $tag;
	}
}




// Werte aus Datenbank laden
$team_werte = [];
$team_werte_3 = [];
$team_werte_2 = [];
$team_werte_1 = [];

$result = mysql_query("SELECT usr_id FROM ilias.udf_text WHERE field_id = '1' AND value = '".$_GET["id"]."'");
$team_member = [];
while($row = mysql_fetch_assoc($result)) {
	$team_member[] = $row["usr_id"];
}

//print_r($team_member);
//echo "<br>";

foreach($team_member as $member) {
	$result = mysql_query("SELECT * FROM benutzer WHERE usr_id = '".$member."'");
	if(mysql_num_rows($result) == 1) {
		$b = mysql_fetch_assoc($result);
		$member_distanz = $b["distanz"];
		
		$member_punkte_3 = 0;
		$member_punkte_2 = 0;
		$member_punkte_1 = 0;
		$resultx = mysql_query("SELECT verkehrsmittel FROM werte WHERE benutzer = '".$member."' AND tag <= '".$heute."'");
		if(mysql_num_rows($resultx) !== 0) {
			while ($row = mysql_fetch_assoc($resultx)) {
			   if($row["verkehrsmittel"] == 3) {
					$member_punkte_3 += round(pow($member_distanz, 1));
				}
				elseif($row["verkehrsmittel"] == 2) {
					$member_punkte_2 += round(pow($member_distanz, 1.8));
				}
				elseif($row["verkehrsmittel"] == 1) {
					$member_punkte_1 += round(pow($member_distanz, 2));
				}
				else {
					$i = 1;
				}
			}
		}
		
		//Nur Teilnehmer mit Werten anzeigen
		if($member_punkte_3 != 0 OR $member_punkte_2 != 0 OR $member_punkte_1 != 0) {
			$team_werte[$member] = $member_punkte_3 + $member_punkte_2 + $member_punkte_1;
			
			if($member_punkte_3 >= $member_punkte_2 AND $member_punkte_3 >= $member_punkte_1) {
				$team_werte_3[$member] = $member_punkte_3 + $member_punkte_2 + $member_punkte_1;
			}
			elseif($member_punkte_2 >= $member_punkte_3 AND $member_punkte_2 >= $member_punkte_1) {
				$team_werte_2[$member] = $member_punkte_3 + $member_punkte_2 + $member_punkte_1;
			}
			elseif($member_punkte_1 >= $member_punkte_3 AND $member_punkte_1 >= $member_punkte_2) {
				$team_werte_1[$member] = $member_punkte_3 + $member_punkte_2 + $member_punkte_1;
			}
			else {
				$i = 1;
			}
		}
	}
}
//print_r($team_werte);
//echo "<br>";
//print_r($team_werte_3);
//echo "<br>";
//print_r($team_werte_2);
//echo "<br>";
//print_r($team_werte_1);
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

Rangliste vom <?php echo $tage[$heute]; ?>

<br>
<br>

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
						<th>Platz</th>
						<th>Name</th>
						<th>Kategorie</th>
						<th>Punkte</th>
					</tr>
				</thead>
				<tbody>
<?php
//Plätze anzeigen und sortieren(!)
$platz = 1;
$counter = count($team_werte);
$menge = 30;
$tmp_werte = $team_werte;
while($counter > 0) {
	$best_punkte = 0;
	$best_member = 0;
	
	foreach($tmp_werte as $member => $punkte) {
		if($punkte >= $best_punkte) {
			$best_punkte = $punkte;
			$best_member = $member;
		}
	}
	
	$benutzer = mysql_fetch_assoc(mysql_query("SELECT * FROM benutzer WHERE usr_id = '".$best_member."'"));
	
	echo "<tr>";
	
	if($benutzer['qualifiziert'] == 0) {
	   echo "<td><span class=\"label label-danger\">D</span></td>";
    }
    else {
       echo "<td>".$platz."</td>";
    }
	/*$result = mysql_query("SELECT firstname, lastname FROM ilias.usr_data WHERE usr_id = '".$best_member."'");
	$info = mysql_fetch_assoc($result);*/
	$a = new schueler($best_member);
	if($benutzer['qualifiziert'] == 0) {
	   echo "<td><span class=\"label label-danger\">Disqualifiziert</span> <a href=\"index.php?p=teilnehmer&id=".$best_member."\">".$a->getFirstName()." ".$a->getLastName()."</a></td>";
    }
    else {
       echo "<td><a href=\"index.php?p=teilnehmer&id=".$best_member."\">".$a->getFirstName()." ".$a->getLastName()."</a></td>";
    }
	if(isset($team_werte_3[$best_member])) {
		$kategorie = "Per ÖPNV";
	}
	elseif(isset($team_werte_2[$best_member])) {
		$kategorie = "Mit dem Rad";
	}
	elseif(isset($team_werte_1[$best_member])) {
		$kategorie = "Zu Fuß";
	}
	else {
		$kategorie = "Gar nicht/Anderes Verkehrsmittel";
	}
	echo "<td>".$kategorie."</td>";
	echo "<td>".$best_punkte."</td>";
	echo "</tr>";
	
	//Bestimmen, wie viele Noch angezeigt werden sollen
	unset($tmp_werte[$best_member]);
	$counter = count($tmp_werte);
	$menge -= 1;
	if($menge <= 0) {
		$counter = 0;
	}
	if($benutzer['qualifiziert'] != 0) {
	   $platz += 1;
    }
}
?>
				</tbody>
			</table>
		</div>
		<!-- /.table-responsive -->
	</div>
	<div class="tab-pane fade" id="fuss">
		<br>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>Kategorie-Platz</th>
						<th>Name</th>
						<th>Punkte</th>
					</tr>
				</thead>
				<tbody>
<?php
//Plätze anzeigen und sortieren(!)
$platz = 1;
$counter = count($team_werte_1);
$menge = 30;
$tmp_werte = $team_werte_1;
while($counter > 0) {
	$best_punkte = 0;
	$best_member = 0;
	
	foreach($tmp_werte as $member => $punkte) {
		if($punkte >= $best_punkte) {
			$best_punkte = $punkte;
			$best_member = $member;
		}
	}
	
	$benutzer = mysql_fetch_assoc(mysql_query("SELECT * FROM benutzer WHERE usr_id = '".$best_member."'"));
	
	echo "<tr>";
	
	if($benutzer['qualifiziert'] == 0) {
	   echo "<td><span class=\"label label-danger\">D</span></td>";
    }
    else {
       echo "<td>".$platz."</td>";
    }
	/*$result = mysql_query("SELECT firstname, lastname FROM ilias.usr_data WHERE usr_id = '".$best_member."'");
	$info = mysql_fetch_assoc($result);*/
	$a = new schueler($best_member);
	if($benutzer['qualifiziert'] == 0) {
	   echo "<td><span class=\"label label-danger\">Disqualifiziert</span> <a href=\"index.php?p=teilnehmer&id=".$best_member."\">".$a->getFirstName()." ".$a->getLastName()."</a></td>";
    }
    else {
       echo "<td><a href=\"index.php?p=teilnehmer&id=".$best_member."\">".$a->getFirstName()." ".$a->getLastName()."</a></td>";
    }
	echo "<td>".$best_punkte."</td>";
	echo "</tr>";
	
	//Bestimmen, wie viele Noch angezeigt werden sollen
	unset($tmp_werte[$best_member]);
	$counter = count($tmp_werte);
	$menge -= 1;
	if($menge <= 0) {
		$counter = 0;
	}
	if($benutzer['qualifiziert'] != 0) {
	   $platz += 1;
    }
}
?>
				</tbody>
			</table>
		</div>
		<!-- /.table-responsive -->
	</div>
	<div class="tab-pane fade" id="rad">
		<br>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>Kategorie-Platz</th>
						<th>Name</th>
						<th>Punkte</th>
					</tr>
				</thead>
				<tbody>
<?php
//Plätze anzeigen und sortieren(!)
$platz = 1;
$counter = count($team_werte_2);
$menge = 30;
$tmp_werte = $team_werte_2;
while($counter > 0) {
	$best_punkte = 0;
	$best_member = 0;
	
	foreach($tmp_werte as $member => $punkte) {
		if($punkte >= $best_punkte) {
			$best_punkte = $punkte;
			$best_member = $member;
		}
	}
	
	$benutzer = mysql_fetch_assoc(mysql_query("SELECT * FROM benutzer WHERE usr_id = '".$best_member."'"));
	
	echo "<tr>";
	
	if($benutzer['qualifiziert'] == 0) {
	   echo "<td><span class=\"label label-danger\">D</span></td>";
    }
    else {
       echo "<td>".$platz."</td>";
    }
	/*$result = mysql_query("SELECT firstname, lastname FROM ilias.usr_data WHERE usr_id = '".$best_member."'");
	$info = mysql_fetch_assoc($result);*/
	$a = new schueler($best_member);
	if($benutzer['qualifiziert'] == 0) {
	   echo "<td><span class=\"label label-danger\">Disqualifiziert</span> <a href=\"index.php?p=teilnehmer&id=".$best_member."\">".$a->getFirstName()." ".$a->getLastName()."</a></td>";
    }
    else {
       echo "<td><a href=\"index.php?p=teilnehmer&id=".$best_member."\">".$a->getFirstName()." ".$a->getLastName()."</a></td>";
    }
	echo "<td>".$best_punkte."</td>";
	echo "</tr>";
	
	//Bestimmen, wie viele Noch angezeigt werden sollen
	unset($tmp_werte[$best_member]);
	$counter = count($tmp_werte);
	$menge -= 1;
	if($menge <= 0) {
		$counter = 0;
	}
	if($benutzer['qualifiziert'] != 0) {
	   $platz += 1;
    }
}
?>
				</tbody>
			</table>
		</div>
		<!-- /.table-responsive -->
	</div>
	<div class="tab-pane fade" id="oepnv">
		<br>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>Kategorie-Platz</th>
						<th>Name</th>
						<th>Punkte</th>
					</tr>
				</thead>
				<tbody>
<?php
//Plätze anzeigen und sortieren(!)
$platz = 1;
$counter = count($team_werte_3);
$menge = 30;
$tmp_werte = $team_werte_3;
while($counter > 0) {
	$best_punkte = 0;
	$best_member = 0;
	
	foreach($tmp_werte as $member => $punkte) {
		if($punkte >= $best_punkte) {
			$best_punkte = $punkte;
			$best_member = $member;
		}
	}
	
	$benutzer = mysql_fetch_assoc(mysql_query("SELECT * FROM benutzer WHERE usr_id = '".$best_member."'"));
	
	echo "<tr>";
	
	if($benutzer['qualifiziert'] == 0) {
	   echo "<td><span class=\"label label-danger\">D</span></td>";
    }
    else {
       echo "<td>".$platz."</td>";
    }
	/*$result = mysql_query("SELECT firstname, lastname FROM ilias.usr_data WHERE usr_id = '".$best_member."'");
	$info = mysql_fetch_assoc($result);*/
	$a = new schueler($best_member);
	if($benutzer['qualifiziert'] == 0) {
	   echo "<td><span class=\"label label-danger\">Disqualifiziert</span> <a href=\"index.php?p=teilnehmer&id=".$best_member."\">".$a->getFirstName()." ".$a->getLastName()."</a></td>";
    }
    else {
       echo "<td><a href=\"index.php?p=teilnehmer&id=".$best_member."\">".$a->getFirstName()." ".$a->getLastName()."</a></td>";
    }
	echo "<td>".$best_punkte."</td>";
	echo "</tr>";
	
	//Bestimmen, wie viele Noch angezeigt werden sollen
	unset($tmp_werte[$best_member]);
	$counter = count($tmp_werte);
	$menge -= 1;
	if($menge <= 0) {
		$counter = 0;
	}
	if($benutzer['qualifiziert'] != 0) {
	   $platz += 1;
    }
}
?>
				</tbody>
			</table>
		</div>
		<!-- /.table-responsive -->
	</div>
</div>

