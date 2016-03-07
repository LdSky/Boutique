<?php


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


$db = Connexion() ;


// r�cup�ration de l'�ventuel cookie
if (isset($_COOKIE["login"])) {
  $leId = ($_COOKIE["login"] + 27) / 353 ;
  $curseur = "select * from client where numclient=:id" ;
  $kappa = $db->prepare($curseur);
  $kappa->bindValue(':id', $leId, PDO::PARAM_INT); 
  $kappa->execute();
  
  while($client = $kappa->fetch(PDO::FETCH_OBJ)) {    
 
    $_SESSION["login"] = $client->login;
    $_SESSION["id"] = $client->numclient ;
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