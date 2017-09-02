<?php

//Session starten
session_start();

//Login prüfen
if(!isset($_SESSION["ID"])) {
	header("Location: login.php");
	exit();
}


//Datenbankverbindung aufbauen
include("db_conn.php");

//Config inkludieren
include("conf.php");

//Benutzerdaten von ILIAS
include("../../lernstand/classes/ls_class_userData.php");

require('./pdf/fpdf.php');

function get_beste_teilnehmer($heute) {
	
	$output = [];
	
	$team_werte = [];
	$team_werte_3 = [];
	$team_werte_2 = [];
	$team_werte_1 = [];
	
	$result = mysql_query("SELECT * FROM benutzer WHERE qualifiziert = 1");
	while($b = mysql_fetch_assoc($result)) {
		$member = $b["usr_id"];
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
	
	
	/////////////////////////////////////////////////////////// Zu Fuß
	//Plätze anzeigen und sortieren(!)
	$platz = 1;
	$counter = count($team_werte_1);
	$menge = 3;
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
		
		$a = new schueler($best_member);
		$output[1][$platz] = ["name" => $a->getFirstName()." ".$a->getLastName(), "team" => $a->getKlasse(), "punkte" => $best_punkte];
		
		//Bestimmen, wie viele Noch angezeigt werden sollen
		unset($tmp_werte[$best_member]);
		$counter = count($tmp_werte);
		$menge -= 1;
		if($menge <= 0) {
			$counter = 0;
		}
		$platz += 1;
	}
	
	///////////////////////////////////////////////////// Mit dem Rad
	//Plätze anzeigen und sortieren(!)
	$platz = 1;
	$counter = count($team_werte_2);
	$menge = 3;
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
		
		$a = new schueler($best_member);
		$output[2][$platz] = ["name" => $a->getFirstName()." ".$a->getLastName(), "team" => $a->getKlasse(), "punkte" => $best_punkte];
		
		//Bestimmen, wie viele Noch angezeigt werden sollen
		unset($tmp_werte[$best_member]);
		$counter = count($tmp_werte);
		$menge -= 1;
		if($menge <= 0) {
			$counter = 0;
		}
		$platz += 1;
	}
	
	////////////////////////////////////////////////// Per ÖPNV
	//Plätze anzeigen und sortieren(!)
	$platz = 1;
	$counter = count($team_werte_3);
	$menge = 3;
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
		
		$a = new schueler($best_member);
		$output[3][$platz] = ["name" => $a->getFirstName()." ".$a->getLastName(), "team" => $a->getKlasse(), "punkte" => $best_punkte];
		
		//Bestimmen, wie viele Noch angezeigt werden sollen
		unset($tmp_werte[$best_member]);
		$counter = count($tmp_werte);
		$menge -= 1;
		if($menge <= 0) {
			$counter = 0;
		}
		$platz += 1;
	}
	
	return $output;
}

function get_bestes_team($heute) {
	
	$output = [];
	
	$schule_team_werte = [];
	$schule_team_werte_3 = [];
	$schule_team_werte_2 = [];
	$schule_team_werte_1 = [];
	
	
	//Alle möglichen Teams auswählen
	$result = mysql_query("SELECT DISTINCT value FROM ilias.udf_text WHERE field_id = '1'");
	$teams = [];
	while($row = mysql_fetch_assoc($result)) {
		$teams[] = $row["value"];
	}
	
	//print_r($teams);
	//echo "<br>";
	
	foreach($teams as $team) {
		$team_werte = 0;
		$team_werte_3 = 0;
		$team_werte_2 = 0;
		$team_werte_1 = 0;
		
		//echo "<br>";
		//echo $team;
		//echo "<br>";
		
		$result = mysql_query("SELECT usr_id FROM ilias.udf_text WHERE field_id = '1' AND value = '".$team."'");
		$team_member = [];
		while($row = mysql_fetch_assoc($result)) {
			$team_member[] = $row["usr_id"];
		}
		
		//print_r($team_member);
		//echo "<br>";
		
		foreach($team_member as $member) {
			$result = mysql_query("SELECT * FROM benutzer WHERE usr_id = '".$member."' AND qualifiziert = 1");
			if(mysql_num_rows($result) == 1) {
				$b = mysql_fetch_assoc($result);
				$member_distanz = $b["distanz"];
				
				$member_punkte_3 = 0;
				$member_punkte_2 = 0;
				$member_punkte_1 = 0;
				$resultx = mysql_query("SELECT verkehrsmittel FROM werte WHERE benutzer = '".$member."'  AND tag <= '".$heute."'");
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
					$team_werte += $member_punkte_3 + $member_punkte_2 + $member_punkte_1;
					
					if($member_punkte_3 >= $member_punkte_2 AND $member_punkte_3 >= $member_punkte_1) {
						$team_werte_3 += $member_punkte_3 + $member_punkte_2 + $member_punkte_1;
					}
					elseif($member_punkte_2 >= $member_punkte_3 AND $member_punkte_2 >= $member_punkte_1) {
						$team_werte_2 += $member_punkte_3 + $member_punkte_2 + $member_punkte_1;
					}
					elseif($member_punkte_1 >= $member_punkte_3 AND $member_punkte_1 >= $member_punkte_2) {
						$team_werte_1 += $member_punkte_3 + $member_punkte_2 + $member_punkte_1;
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
		//echo "<br>";
		
		//Nur Teilnehmer mit Werten anzeigen
		if($team_werte_3 != 0 OR $team_wertee_2 != 0 OR $team_werte_1 != 0) {
			$schule_team_werte[$team] += $team_werte_3 + $team_werte_2 + $team_werte_1;
			
			if($team_werte_3 >= $team_werte_2 AND $team_werte_3 >= $team_werte_1) {
				$schule_team_werte_3[$team] += $team_werte_3 + $team_werte_2 + $team_werte_1;
			}
			elseif($team_werte_2 >= $team_werte_3 AND $team_werte_2 >= $team_werte_1) {
				$schule_team_werte_2[$team] += $team_werte_3 + $team_werte_2 + $team_werte_1;
			}
			elseif($team_werte_1 >= $team_werte_3 AND $team_werte_1 >= $team_werte_2) {
				$schule_team_werte_1[$team] += $team_werte_3 + $team_werte_2 + $team_werte_1;
			}
			else {
				$i = 1;
			}
		}
	}
	//echo "<br>";
	//echo "<br>";
	//echo "Schule:";
	//echo "<br>";
	//print_r($schule_team_werte);
	//echo "<br>";
	//print_r($schule_team_werte_3);
	//echo "<br>";
	//print_r($schule_team_werte_2);
	//echo "<br>";
	//print_r($schule_team_werte_1);
	//echo "<br>";
	
	//////////////////////////////////////// Zu Fuß
	//Plätze anzeigen und sortieren(!)
	$platz = 1;
	$counter = count($schule_team_werte_1);
	$menge = 3;
	$tmp_werte = $schule_team_werte_1;
	while($counter > 0) {
		$best_punkte = 0;
		$best_member = 0;
		
		foreach($tmp_werte as $member => $punkte) {
			if($punkte >= $best_punkte) {
				$best_punkte = $punkte;
				$best_member = $member;
			}
		}
		
		$output[1][$platz] = ["team" => $best_member, "punkte" => $best_punkte];
		
		//Bestimmen, wie viele Noch angezeigt werden sollen
		unset($tmp_werte[$best_member]);
		$counter = count($tmp_werte);
		$menge -= 1;
		if($menge <= 0) {
			$counter = 0;
		}
		$platz += 1;
	}
	
	////////////////////////////////////////// Mit dem Rad
	//Plätze anzeigen und sortieren(!)
	$platz = 1;
	$counter = count($schule_team_werte_2);
	$menge = 3;
	$tmp_werte = $schule_team_werte_2;
	while($counter > 0) {
		$best_punkte = 0;
		$best_member = 0;
		
		foreach($tmp_werte as $member => $punkte) {
			if($punkte >= $best_punkte) {
				$best_punkte = $punkte;
				$best_member = $member;
			}
		}
		
		$output[2][$platz] = ["team" => $best_member, "punkte" => $best_punkte];
		
		//Bestimmen, wie viele Noch angezeigt werden sollen
		unset($tmp_werte[$best_member]);
		$counter = count($tmp_werte);
		$menge -= 1;
		if($menge <= 0) {
			$counter = 0;
		}
		$platz += 1;
	}
	
	
	///////////////////////////////////////////////////////////// Per ÖPNV
	//Plätze anzeigen und sortieren(!)
	$platz = 1;
	$counter = count($schule_team_werte_3);
	$menge = 3;
	$tmp_werte = $schule_team_werte_3;
	while($counter > 0) {
		$best_punkte = 0;
		$best_member = 0;
		
		foreach($tmp_werte as $member => $punkte) {
			if($punkte >= $best_punkte) {
				$best_punkte = $punkte;
				$best_member = $member;
			}
		}
		
		$output[3][$platz] = ["team" => $best_member, "punkte" => $best_punkte];
		
		//Bestimmen, wie viele Noch angezeigt werden sollen
		unset($tmp_werte[$best_member]);
		$counter = count($tmp_werte);
		$menge -= 1;
		if($menge <= 0) {
			$counter = 0;
		}
		$platz += 1;
	}
	
	return $output;
}























function create_pdf($beste_teilnehmer, $beste_teams) {
	class PDF extends FPDF {
		function Header() {
			$this->SetFont('Arial', 'B', 40);
			$this->Cell(190, 25, utf8_decode('CO  -frei zur Schule'), 1, 1, 'C');
			$this->Text(61, 33, utf8_decode('²'));
			$this->Ln(10);
		}
		function Footer() {
			$this->SetY(-19);
			$this->SetFont('Arial', '', 10);
			$this->Cell(190, 10, utf8_decode('von-Bülow-Gymnasium - CO2-frei zur Schule - Wettbewerbsverwaltung von © 2017 milchinsel.de Clemens Riese'), 1, 1, 'C');
		}
	}
	
	
	
	$pdf = new PDF();
	$pdf->SetCreator(utf8_decode("CO2-frei zur Schule"));
	$pdf->SetTitle(utf8_decode("C02-frei zur Schule"));
	$pdf->SetAutoPageBreak(true, 25);
	$pdf->AddPage();
	$pdf->SetFont('Arial', 'B', 32);
	$pdf->Cell(190, 20, utf8_decode('Bestenliste vom '.date("d.m.Y G:i")), 0, 1);
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(190, 8, utf8_decode('Beste Teilnehmer - Zu Fuß'), 0, 1);
	$pdf->SetFont('Arial', '', 16);
	$pdf->Cell(190, 8, utf8_decode('1. '.$beste_teilnehmer[1][1]["name"].' ('.$beste_teilnehmer[1][1]["team"].') - '.$beste_teilnehmer[1][1]["punkte"].' Punkte'), 0, 1);
	$pdf->Cell(190, 8, utf8_decode('2. '.$beste_teilnehmer[1][2]["name"].' ('.$beste_teilnehmer[1][2]["team"].') - '.$beste_teilnehmer[1][2]["punkte"].' Punkte'), 0, 1);
	$pdf->Cell(190, 8, utf8_decode('3. '.$beste_teilnehmer[1][3]["name"].' ('.$beste_teilnehmer[1][3]["team"].') - '.$beste_teilnehmer[1][3]["punkte"].' Punkte'), 0, 1);
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(190, 8, utf8_decode('Beste Teilnehmer - Mit dem Rad'), 0, 1);
	$pdf->SetFont('Arial', '', 16);
	$pdf->Cell(190, 8, utf8_decode('1. '.$beste_teilnehmer[2][1]["name"].' ('.$beste_teilnehmer[2][1]["team"].') - '.$beste_teilnehmer[2][1]["punkte"].' Punkte'), 0, 1);
	$pdf->Cell(190, 8, utf8_decode('2. '.$beste_teilnehmer[2][2]["name"].' ('.$beste_teilnehmer[2][2]["team"].') - '.$beste_teilnehmer[2][2]["punkte"].' Punkte'), 0, 1);
	$pdf->Cell(190, 8, utf8_decode('3. '.$beste_teilnehmer[2][3]["name"].' ('.$beste_teilnehmer[2][3]["team"].') - '.$beste_teilnehmer[2][3]["punkte"].' Punkte'), 0, 1);
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(190, 8, utf8_decode('Beste Teilnehmer - Per ÖPNV'), 0, 1);
	$pdf->SetFont('Arial', '', 16);
	$pdf->Cell(190, 8, utf8_decode('1. '.$beste_teilnehmer[3][1]["name"].' ('.$beste_teilnehmer[3][1]["team"].') - '.$beste_teilnehmer[3][1]["punkte"].' Punkte'), 0, 1);
	$pdf->Cell(190, 8, utf8_decode('2. '.$beste_teilnehmer[3][2]["name"].' ('.$beste_teilnehmer[3][2]["team"].') - '.$beste_teilnehmer[3][2]["punkte"].' Punkte'), 0, 1);
	$pdf->Cell(190, 8, utf8_decode('3. '.$beste_teilnehmer[3][3]["name"].' ('.$beste_teilnehmer[3][3]["team"].') - '.$beste_teilnehmer[3][3]["punkte"].' Punkte'), 0, 1);
	$pdf->Ln(10);
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(190, 8, utf8_decode('Beste Teams - Zu Fuß'), 0, 1);
	$pdf->SetFont('Arial', '', 16);
	$pdf->Cell(190, 8, utf8_decode('1. '.$beste_teams[1][1]["team"].' - '.$beste_teams[1][1]["punkte"].' Punkte'), 0, 1);
	$pdf->Cell(190, 8, utf8_decode('2. '.$beste_teams[1][2]["team"].' - '.$beste_teams[1][2]["punkte"].' Punkte'), 0, 1);
	$pdf->Cell(190, 8, utf8_decode('3. '.$beste_teams[1][3]["team"].' - '.$beste_teams[1][3]["punkte"].' Punkte'), 0, 1);
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(190, 8, utf8_decode('Beste Teams - Mit dem Rad'), 0, 1);
	$pdf->SetFont('Arial', '', 16);
	$pdf->Cell(190, 8, utf8_decode('1. '.$beste_teams[2][1]["team"].' - '.$beste_teams[2][1]["punkte"].' Punkte'), 0, 1);
	$pdf->Cell(190, 8, utf8_decode('2. '.$beste_teams[2][2]["team"].' - '.$beste_teams[2][2]["punkte"].' Punkte'), 0, 1);
	$pdf->Cell(190, 8, utf8_decode('3. '.$beste_teams[2][3]["team"].' - '.$beste_teams[2][3]["punkte"].' Punkte'), 0, 1);
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->Cell(190, 8, utf8_decode('Beste Teams - Per ÖPNV'), 0, 1);
	$pdf->SetFont('Arial', '', 16);
	$pdf->Cell(190, 8, utf8_decode('1. '.$beste_teams[3][1]["team"].' - '.$beste_teams[3][1]["punkte"].' Punkte'), 0, 1);
	$pdf->Cell(190, 8, utf8_decode('2. '.$beste_teams[3][2]["team"].' - '.$beste_teams[3][2]["punkte"].' Punkte'), 0, 1);
	$pdf->Cell(190, 8, utf8_decode('3. '.$beste_teams[3][3]["team"].' - '.$beste_teams[3][3]["punkte"].' Punkte'), 0, 1);


	
	$pdf->Output();
	header('Content-Disposition: inline; filename="CO2-frei zur Schule - Bestenliste '.date("Y-m-d-G-i").'.pdf"');
}

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

create_pdf(get_beste_teilnehmer($heute), get_bestes_team($heute));
?>