#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Genera les factures d'una cursa</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>

<?php 
    include 'funcions.php';     
    iniciaSessio();
    connecta($conn);
    capcalera("Generar factures"); 
?>

  <form action="generarFactures_BD.php" method="post">
  <p><label>Cursa:</label>
      <select name="cursa">
	  <option value="">--sense especificar--</option>

<?php 
    $cursa = "SELECT codi AS CODI, nom AS NOM 
                     FROM Curses
					 WHERE millorTemps IS NOT NULL AND codi NOT IN(
						SELECT cursa
							FROM Factures
					 )
					 ORDER BY NOM";
    $comanda = oci_parse($conn, $cursa);
    $exit=oci_execute($comanda);
    if (!$exit){
        mostraErrorExecucio($comanda);
    }
    while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "      <option value=\"" . $fila['CODI'] . "\">" . $fila['NOM'] . "</option>\n";
    }
    echo "      </select></p>";
?>
 
<p><label>&nbsp;</label><input type = "submit" value="Generar factures"></p>
</form>
<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>
