<?php
//Session starten
session_start();
?>
<?php
//Login prüfen
if(!isset($_SESSION["ID"])) {
	header("Location: login.php");
	exit();
}
?>
<?php
//Datenbankverbundung aufbauen
include("db_conn.php");
?>
<?php
//Testen, ob Benutzer bereits vorhanden
if (mysql_num_rows(mysql_query("SELECT * FROM benutzer WHERE usr_id = '".$_SESSION["ID"]."'")) !== 0) {
	header("Location: index.php");
	exit();
}
?>
<!DOCTYPE html>
<html lang="de">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="Clemens" >

    <title>CO&#8322;-frei zur Schule</title>
    
    <!-- Homepage als App-->
        <!-- Allgemein -->
        <link rel="icon" href="./media/logo50x50.png" />
        
        <!-- Apple -->
        <link rel="apple-touch-icon" href="./media/logo500x500.png" />
        <link rel="apple-touch-icon-precomposed" href="./media/logo500x500.png" />
        <link rel="apple-touch-startup-image" href="./media/hintergrund.png">
        <meta name="apple-mobile-web-app-capable" content="yes">
        
        <!-- Microsoft -->
        <meta name="application-name" content="CO&#8322;-frei zur Schule">
        <meta name="msapplication-starturl" content="http://kunstkombinat5.org/clerie/enviro/index.php">
        <!-- <meta name="msapplication-square150x150logo" content="images/logo.png"> -->
        <!-- <meta name="msapplication-square310x310logo" content="images/largelogo.png"> -->
        <!-- <meta name="msapplication-square70x70logo" content="images/tinylogo.png"> -->
        <!-- <meta name="msapplication-wide310x150logo" content="images/widelogo.png"> -->
        <!-- <meta name="msapplication-TileColor" content="#FF3300"> -->
        <meta name="msapplication-tooltip" content="CO&#8322;-frei zur Schule - von-Bülow-Gymnasium Neudietendorf">
        <meta name="msapplication-TileImage" content="./media/logo500x500.png" />
        
        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary" />
        <!--<meta name="twitter:site" content="@flickr" />-->
        <meta name="twitter:title" content="CO&#8322;-frei zur Schule" />
        <meta name="twitter:description" content="Mach mit und reduziere deine CO&#8322;-Emmisionen!" />
        <meta name="twitter:image" content="./media/logo500x500.png" />

    <!-- Bootstrap Core CSS -->
    <link href="./vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="./vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="./dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="./vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Start: Entfernung von der Schule</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" action="exe-start.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <label>Wie weit ist dein Schulweg? Runde auf einen Kilometer auf.<br>
                                    Wenn du weniger als ein Kilometer von der Schule weg wohnst, gibt genau einen an.<br>
                                    Du kannst als Schulweg maximal 30km angeben.</label>
                                    <div class="form-group input-group">
                                        <input class="form-control" placeholder="Entfernung (km)" name="distanz" type="number" min="1" max="30" step="1" value="1" autofocus>
                                        <span class="input-group-addon">km</span>
                                    </div>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Speichern</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="./vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="./vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="./vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="./dist/js/sb-admin-2.js"></script>

</body>

</html>
