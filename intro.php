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
                        <h3 class="panel-title">Vorab: Die Regeln</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" action="exe-intro.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <p>Schön, dass du bei <b>CO<sub>2</sub>-frei zur Schule mitmachst</b>,<br>
                                    hier in der Wettbewerbsverwaltung trägst du ein, mit welchem Verkehrsmittel du zur Schule gekommen bist und mit welchem wieder nach Hause.<br>
                                    Das Eintragen geschieht auf vertrauensbasis, jedoch kann man die Werte von jedem in den Ranglisten nach plausibilität untersuchen. <i>Und im Ernst - durch betrügen, schont man nicht die Umwelt.</i></p>
                                    <p>Bei den Verkehrsmitteln kannst du zwischen folgenden Kategorien wählen: <b>Zu Fuß</b> (z.B.: Laufen, Longboard), <b>Mit dem Rad</b> (z.B.: Fahrrad, E-Bike), <b>Per ÖPNV</b> (z.B.: Bus, Zug, Straßenbahn), <b>Gar nicht/Anderes Verkehrsmittel</b> (z.B.: Auto, Mofa oder wenn du nicht in die Schule gegegangen bist). Solltest du verschiene Verkehrsmittel mischen, gibt du die Kategorie an, in der das Verkehrsmittel ist, mit dem du den größten Teil der Strecken bewältigst hast.<br>
                                    Für jede Kategorie bekommst du unterschiedlich viele Punkte, abhängig von deiner Strecke. In der Kategorie <b>Zu Fuß</b> bekommt man am meisten, für <b>Gar nicht/Anderes Verkehrsmittel</b> keine.</p>
                                    <p>Noch ein kleiner Tipp am Rande:<br>
                                    <b>Füge diese Homepage deinem Startbildschirm hinzu</b>, ab dann funktioniert sie wie eine App und du kannst das Eintragen nie mehr vergessen.</p>
                                    <p>Viele Grüße ENVIRO</p>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Fertig</button>
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
