#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Afegir Personatge, entrada de dades</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php 
    include 'funcions.php';     
    iniciaSessio();
    connecta($conn);
    capcalera("Donar d'alta un personatge"); 
 ?>
  <form action="afegirPersonatge_BD.php" method="post">
  <p><label>Despesa mensual: </label><input type="floatval" name="despesaMensual"> </p>
  <p><label>Data de creació: </label><input type="date" name="dataCreacio"> </p>
  <p><label>Usuari propietari: </label>
      <select name="usuari">
      <option value="">--sense especificar--</option>
<?php 
    $usuari = "SELECT alias AS ALIASUSUARI, cognoms || ', ' || nom AS NOM 
                     FROM Usuaris order by NOM";
    $comanda = oci_parse($conn, $usuari);
    $exit=oci_execute($comanda);
    if (!$exit){
        mostraErrorExecucio($comanda);
    }
    while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "      <option value=\"" . $fila['ALIASUSUARI'] . "\">" . $fila['NOM'] . "</option>\n";
    }
    echo "      </select></p>";
 ?>
  <p><label>Tipus de personatge:</label>
      <select name="tipusPersonatge">
      <option value=" ">--sense especificar--</option>
<?php 
    $tipusPersonatges = "SELECT nom AS NOM 
                     FROM TipusPersonatges order by NOM";
    $comanda = oci_parse($conn, $tipusPersonatges);
    $exit=oci_execute($comanda);
    if (!$exit){
        mostraErrorExecucio($comanda);
    }
    while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "      <option value=\"" . $fila['NOM'] . "\">" . $fila['NOM'] . "</option>\n";
    }
    echo "      </select></p>";
  ?>
  
  <p><label>&nbsp;</label><input type = "submit" value="Afegir"></p>
  </form>
<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>