<?php
// inicia sessions per poder compartir dades
function iniciaSessio(){
  $dirSessions = exec("pwd") . "/tmp";
  ini_set('session.save_path', $dirSessions);
  session_start();
}

//obra una connexió amb ORACLE i la retorna a $connexio
function connecta(&$connexio){
  $connexio = oci_connect($_SESSION['usuari'], 
                          $_SESSION['password'], 'ORCLCDB');
  if (!$connexio) {
    header('Location: errorLogin.php');
  }
}

// escriu el que es mostra a dalt de la pàgina web
function capcalera($text){
  echo '<div id="textbox">'."\n";
  echo '<p class="capcaleraTitol">'.$text."</p>\n";
  echo '<p class="capcaleraUsuari">usuari actiu: <b>'. 
        $_SESSION['usuari'] . "</b></p>\n"; 
  echo "</div>\n";
  echo '<div style="clear: both;"></div>'."\n";
  echo "<hr>\n";
}
  
// escriu el peu de la pàgina web
function peu($text,$pagina){
  echo '<hr><p class="peu"><a class="menu" href="'.
       $pagina.'">'.$text."</a></p>\n";
}

// mostra un error provocat per oci_execute
function mostraErrorExecucio($comanda){
  $error = oci_error($comanda);
  $_SESSION['ErrorSentencia'] = $error['sqltext'];
  $_SESSION['ErrorCodi'] = $error['code'];
  $_SESSION['ErrorMissatge'] = $error['message'];
  $_SESSION['ErrorOffset'] = $error['offset'];
  header('Location: errorExecucio.php');
}

// mostra un error provocat per oci_parse
function mostraErrorParser($sentenciaSQL){
  $_SESSION['ErrorParser'] = $sentenciaSQL;
  header('Location: errorParser.php');
}

?>
