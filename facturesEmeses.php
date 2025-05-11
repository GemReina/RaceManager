#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Factures emeses d'un usuari</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>

<?php 
    include 'funcions.php';     
    iniciaSessio();
    connecta($conn);
    capcalera("Factures emeses per un usuari"); 
?>

  <form action="facturesEmesesUsuari.php" method="post">
  <p><label>Usuari:</label>
      <select name="usuari">
	  <option value="">--sense especificar--</option>

<?php 
    $cursa = "SELECT alias AS ALIAS, cognoms || ', ' || nom AS NOM 
                     FROM Usuaris
					 ORDER BY NOM";
    $comanda = oci_parse($conn, $cursa);
    $exit=oci_execute($comanda);
    if (!$exit){
        mostraErrorExecucio($comanda);
    }
    while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "      <option value=\"" . $fila['ALIAS'] . "\">" . $fila['NOM'] . "</option>\n";
    }
    echo "      </select></p>";
?>
 
<p><label>&nbsp;</label><input type = "submit" value="Veure factures"></p>
</form>
<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>
