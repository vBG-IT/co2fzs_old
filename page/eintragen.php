<?php
$tag = $_GET["tag"];

if(!isset($tag)) {
	header("Location: index.php?p=uebersicht");
	exit();
}
?>

<!-- Page Header -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Eintragen</h1>
	</div>
</div>
<!-- /.row -->
<h2>Tag <?php echo $tag; ?> (<?php echo $tage[$tag]; ?>)</h2>
<br>

<?php
$result = mysql_query("SELECT * FROM werte WHERE benutzer = '".$_SESSION["ID"]."' AND tag = '".$tag."' AND richtung = '0'");
$hinweg = mysql_fetch_assoc($result);
//print_r($hinweg);
$hinweg = $hinweg["verkehrsmittel"];
$result = mysql_query("SELECT * FROM werte WHERE benutzer = '".$_SESSION["ID"]."' AND tag = '".$tag."' AND richtung = '1'");
$rueckweg = mysql_fetch_assoc($result);
//print_r($rueckweg);
$rueckweg = $rueckweg["verkehrsmittel"];
//print_r($hinweg);
//print_r($rueckweg);
?>

<form action="exe-p-eintragen.php?tag=<?php echo $tag; ?>&via=uebersicht" method="post">
	<label>Wie bist du zur Schule gekommen?</label>
	<select class="form-control" name="hinweg">
	<option value="0" <?php if($hinweg == 0) {echo "selected";} ?>>Gar nicht/Anderes Verkehrsmittel</option>
	<option value="1" <?php if($hinweg == 1) {echo "selected";} ?>>Zu Fuß</option>
	<option value="2" <?php if($hinweg == 2) {echo "selected";} ?>>Mit dem Rad</option>
	<option value="3" <?php if($hinweg == 3) {echo "selected";} ?>>Per ÖPNV</option>
	</select>
	<br>
	<label>Und wie wieder nach Hause?</label>
	<select class="form-control" name="rueckweg">
	<option value="0" <?php if($rueckweg == 0) {echo "selected";} ?>>Gar nicht/Anderes Verkehrsmittel</option>
	<option value="1" <?php if($rueckweg == 1) {echo "selected";} ?>>Zu Fuß</option>
	<option value="2" <?php if($rueckweg == 2) {echo "selected";} ?>>Mit dem Rad</option>
	<option value="3" <?php if($rueckweg == 3) {echo "selected";} ?>>Per ÖPNV</option>
	</select>
	<br>
	<button type="submit" class="btn btn-default">Speichern</button>
</form>