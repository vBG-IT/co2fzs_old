<!-- Page Header -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Start</h1>
	</div>
</div>
<!-- /.row -->

<!--
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
-->

<?php
$timestamps = [];
foreach($tage as $tag => $datum) {
	$timestamps[$tag] = strtotime(str_replace(".", "-", $datum));
}
$zeit = time();

$heute = 0;
foreach($timestamps as $tag => $timestamp) {
	if($zeit >= $timestamp AND $zeit <= $timestamp+((60*60*24)-1)) {
		$heute = $tag;
	}
}

//print_r($timestamps);
//echo "<br>";
//echo "---";
//print_r($heute);
//echo "---";
?>

<?php
$hinweg == false;
if(mysql_num_rows(mysql_query("SELECT * FROM werte WHERE benutzer = '".$_SESSION["ID"]."' AND tag = '".$heute."' AND richtung = '0'")) >= 1) {
	$hinweg = true;
}

$rueckweg == false;
if(mysql_num_rows(mysql_query("SELECT * FROM werte WHERE benutzer = '".$_SESSION["ID"]."' AND tag = '".$heute."' AND richtung = '1'")) >= 1) {
	$rueckweg = true;
}
?>

<?php if($heute == 0): ?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Keine Eintragung möglich
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				Für heute kannst du leider keine Werte eintragen. Ältere Werte kannst du jedoch <a href="index.php?p=uebersicht">bearbeiten</a>.
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
</div>
<?php elseif($hinweg == true AND $rueckweg == true): ?>
<h2>Tag <?php echo $heute; ?> (<?php echo $tage[$heute]; ?>)</h2>
<br>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Alle Werte schon eingetragen
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				Du hast alle Werte für heute schon eingetragen. Hast du dich verklickt? Du kannst deine Werte natürlich auch nachträglich <a href="index.php?p=uebersicht">bearbeiten</a>.
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
</div>
<?php else: ?>
<?php
if($hinweg == false) {
	$richtung = 0;
}
else {
	$richtung = 1;
}
?>
<h2>Tag <?php echo $heute; ?> (<?php echo $tage[$heute]; ?>) <?php if($hinweg == false) { echo "Hinweg"; } else { echo "Rückweg"; } ?></h2>
<label>Wie bist du heute <?php if($hinweg == false) { echo "zur Schule"; } else { echo "nach Hause"; } ?> gekommen?</label>
<br>
<div class="row">
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-green">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-male fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"></div>
						<div>Zu Fuß</div>
					</div>
				</div>
			</div>
			<a href="exe-p-eintragen.php?tag=<?php echo $heute; ?>&richtung=<?php echo $richtung; ?>&verkehrsmittel=1&via=main">
				<div class="panel-footer">
					<span class="pull-left">Wählen</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-yellow">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-bicycle fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"></div>
						<div>Mit dem Rad</div>
					</div>
				</div>
			</div>
			<a href="exe-p-eintragen.php?tag=<?php echo $heute; ?>&richtung=<?php echo $richtung; ?>&verkehrsmittel=2&via=main">
				<div class="panel-footer">
					<span class="pull-left">Wählen</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-red">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-bus fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"></div>
						<div>Per ÖPNV</div>
					</div>
				</div>
			</div>
			<a href="exe-p-eintragen.php?tag=<?php echo $heute; ?>&richtung=<?php echo $richtung; ?>&verkehrsmittel=3&via=main">
				<div class="panel-footer">
					<span class="pull-left">Wählen</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-times fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"></div>
						<div>Gar nicht/Anderes Verkehrsmittel</div>
					</div>
				</div>
			</div>
			<a href="exe-p-eintragen.php?tag=<?php echo $heute; ?>&richtung=<?php echo $richtung; ?>&verkehrsmittel=0&via=main">
				<div class="panel-footer">
					<span class="pull-left">Wählen</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
</div>
<!-- /.row -->
<?php endif; ?>

<pre><?php echo $changetext; ?></pre>