<?php
// Configure here the IP address of the printer
  $imprimante="10.10.92.254";

// Restrict access to local networks if needed
if (substr($_SERVER['REMOTE_ADDR'],0,3) != "10." && substr($_SERVER['REMOTE_ADDR'],0,8) != "192.168.") { 
  header("HTTP/1.0 404 Not Found");
  exit("Page not found");
}
?>
<!DOCTYPE html>
<HTML>
<HEAD>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<title>Impression de documents PDF</title>
</HEAD>
<BODY>
   <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
       <a class="navbar-brand" href="#">SOS Impression !!!</a>
   </nav>
   <div class="jumbotron">
      <div class="container">
        <H1>Service d'impression <small>(qui juste marche)</small></H1>
	<P>Impression de documents PDF sans passer par le gestionnaire d'impression</P>
	<P><B>Veuillez ne charger QUE des PDF !!</B></P>
        <P>Le fichier PDF upload&eacute; est supprim&eacute; juste apr&egrave;s son impression</P>
	<?php
	  $uploaddir = '/volume1/web/upload/';
	  $uploadfile = $uploaddir . basename($_FILES['fileToPrint']['name']);
	  if (move_uploaded_file($_FILES['fileToPrint']['tmp_name'], $uploadfile)) {
	    // Mise en place d'une connexion basique
	    $conn_id = ftp_connect($imprimante);
	    // Authent ftp anonyme sur l'imprimante
	    $login_result = ftp_login($conn_id, 'anonymous', '');
	    // Charge un fichier
	    if (ftp_put($conn_id, basename($uploadfile), $uploadfile, FTP_ASCII)) {
	      echo "Le fichier &agrave; &eacute;t&eacute; imprim&eacute; avec succ&egrave;s\n";
	    }
	    // Fermeture de la connexion
	    ftp_close($conn_id);                             
	    // Suppression du PDF
	    unlink($uploadfile);
	  }
	?>
   </div><!-- /.container -->
  </div>
    <div class="container">                                                                       
      <form action="print.php" method="post" enctype="multipart/form-data">
        Selectionnez le PDF a imprimer:
	<input type="file" name="fileToPrint" accept=".pdf" id="fileToPrint"><br>
	<input type="submit" class="btn btn-primary" value="Imprimer" name="submit">
      </form>
    </div>
      <div class="container">
       <a class="navbar-brand" href="?s"><small>Code source ?</small></a>
      </div>
      <div class="container" style="background-color: #f9f2f4;">
	<?php if (isset($_GET['s']))  highlight_file ($_SERVER["SCRIPT_FILENAME"]); ?>
      </div>
  </BODY>
</HTML>
