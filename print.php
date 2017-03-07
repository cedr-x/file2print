<?php
// Configure here the IP address of the printer
  $printer="10.10.92.254";

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
  <title>Spoolerfree Printing System</title>
</HEAD>
<BODY>
  <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
     <a class="navbar-brand" href="#">SOS Impression !!!</a>
  </nav>
  <div class="jumbotron">
     <div class="container">
        <H1>Printing service <small>(that just works)</small></H1>
	    <P>Allow printing PDF and plain text files (.TXT) only<BR>
	    <P>without the need of drivers nor admin right on your computer</P>
        <P>The PDF/TXT file is deleted just after been sent to the printer</P>
	  <?php
	  $uploaddir = '/volume1/web/upload/';
	  $uploadfile = $uploaddir . basename($_FILES['fileToPrint']['name']);
	  if (move_uploaded_file($_FILES['fileToPrint']['tmp_name'], $uploadfile)) {
	    // Mise en place d'une connexion basique
	    $conn_id = ftp_connect($printer);
	    // Authent ftp anonyme sur l'imprimante
	    $login_result = ftp_login($conn_id, 'anonymous', '');
	    // Charge un fichier
	    if (ftp_put($conn_id, basename($uploadfile), $uploadfile, FTP_ASCII)) {
	      echo "Your file has been submitted to the printer\n";
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
        Select the PDF to print :
	  <input type="file" name="fileToPrint" accept=".pdf" id="fileToPrint"><br>
	  <input type="submit" class="btn btn-primary" value="Print" name="submit">
    </form>
  </div>
  <div class="container">
    <a class="navbar-brand" href="?s"><small>Source code ?</small></a>
  </div>
  <div class="container" style="background-color: #f9f2f4;">
	<?php if (isset($_GET['s']))  highlight_file ($_SERVER["SCRIPT_FILENAME"]); ?>
  </div>
</BODY>
</HTML>
