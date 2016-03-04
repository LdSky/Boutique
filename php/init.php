<?php

session_start () ;

// variables globales
$bdServeur = "localhost" ;
$bdUser = "root" ;
$bdMdp = "" ;
$bdBase = "boutique" ; 
$prixTshirt = 25 ;

// inclusion des autres fichiers
include_once ("chaines.php") ;
include_once ("curseurs.php") ;
include_once ("outils.php") ;
$link = mysqli_connect("localhost", "root", "", "boutique");

Connexion() ;

function mysqli_result($result,$row,$field=0) {
    if ($result===false) { return false; }
    if ($row>=mysqli_num_rows($result)) { return false;}
    if (is_string($field) && !(strpos($field,".")===false)) {
        $t_field=explode(".",$field);
        $field=-1;
        $t_fields=mysqli_fetch_fields($result);
        for ($id=0;$id<mysqli_num_fields($result);$id++) {
            if ($t_fields[$id]->table==$t_field[0] && $t_fields[$id]->name==$t_field[1]) {
                $field=$id;
                break;
            }
        }
        if ($field==-1) { return false;}
    }
    mysqli_data_seek($result,$row);
    $line=mysqli_fetch_array($result);
    return isset($line[$field])?$line[$field]:false;
}



// r�cup�ration de l'�ventuel cookie
if (isset($_COOKIE["login"])) {
  $leId = ($_COOKIE["login"] + 27) / 353 ;
  $curseur = mysqli_query($link, "select * from client where numclient=".$leId) ;
  if (mysqli_num_rows($curseur)!=0) {
    $_SESSION["login"] = mysqli_result($curseur, 0, "login") ;
    $_SESSION["id"] = mysqli_result($curseur, 0, "numclient") ;
  }
}

// r�cup�ration des variables de session �ventuelles
if (isset($_SESSION["login"])) {
  $login = $_SESSION["login"] ;
  $id = $_SESSION["id"] ;  
}else{
  $login = "" ;
  $id = "" ;
}

?>