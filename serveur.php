<?php       
include_once("php/init.php") ;
$db = Connexion() ;

//--- demande et contr�le d'identification ---
if (isset($_GET["txtLogin"])) {
  $login = $_GET["txtLogin"] ;
  $mdp = $_GET["pwdMdp"] ;
   

  $curseur = "select * from client where login=:pLogin and mdp=:pwMdp" ;
  $kappa = $db->prepare($curseur);
  $kappa->bindValue(':pLogin', $login, PDO::PARAM_STR);
  $kappa->bindValue(':pwMdp', $mdp, PDO::PARAM_STR);
  $kappa->execute(); 
  while ($client = $kappa->fetch(PDO::FETCH_OBJ)) {
    $_SESSION["login"] = $login ; 
    $_SESSION["id"] = $client->numclient;
    setcookie("login", $_SESSION["id"] * 353 - 27, time()+60*60*24*3600) ;
    echo $login ;
  }

//--- enregistrement de la couleur du tshirt ---
}elseif (isset($_POST["couleur"])) {
  $_SESSION["couleur"] = $_POST["couleur"] ;

//--- controle si une couleur de tshirt a �j� �t� s�lectionn�e ---
}elseif (isset($_GET["tshirt"])) {
  if (isset($_SESSION["couleur"])) {
    echo $_SESSION["couleur"] ;
  }else{
    echo "" ;
  }
  
//--- supprimer l'enregistrement de la couleur du tshirt ---
}elseif (isset($_POST["supprtshirt"])) {
  session_unregister("couleur") ;

//--- insertion d'un article dans le panier (si la personne est identifi�e) ---
}elseif (isset($_POST["panierplus"])) {
  if (isset($_SESSION["id"])) {
    mysqli_query("insert into panier values( ".$_SESSION["id"].", ".$_POST["panierplus"].")") ;
  }

//--- suppression d'un article du panier (si la personne est identifi�e) ---
}elseif (isset($_POST["paniermoins"])) {
  if (isset($_SESSION["id"])) {
    mysqli_query("delete from panier where idclient=".$_SESSION["id"]." and idarticle=".$_POST["paniermoins"]) ;
  }

//--- contr�le si le login saisi n'existe pas d�j� ---
}elseif (isset($_GET["controle"])) {
  $login = $_GET["controle"] ;
  $curseur = mysql_query("select * from client where login='".$login."'") ;
  if (mysqli_num_rows($curseur)==0) {
    echo "faux" ;
  }elseif (!isset($_SESSION["id"])) {
    echo "vrai" ;
  }elseif ($_SESSION["id"]!=mysql_result($curseur, 0, "numclient")) {
    echo "vrai" ;
  }else{
    echo "faux" ;
  }

//--- enregistrement des informations de la personne ---
}elseif (isset($_GET["login"])) {
  // r�cup�ration de toutes les informations
  $nom = $_GET["nom"] ;
  $prenom = $_GET["prenom"] ;
  $adr1 = $_GET["adr1"] ;
  $adr2 = $_GET["adr2"] ;
  $cp = $_GET["cp"] ;
  $ville = $_GET["ville"] ;
  $infoslivraison = $_GET["infoslivraison"] ;
  $tel = $_GET["tel"] ;
  $mail = $_GET["mail"] ;
  $login = $_GET["login"] ;
  $mdp = $_GET["mdp"] ;

  // ajoute ou modifie (suivant si la personne existe ou non)
  if (isset($_SESSION["id"])) {
    $id = $_SESSION["id"] ; 
    $requete = 'update client set nom="'.$nom.'", prenom="'.$prenom.'", adr1="'.$adr1.'", adr2="'.$adr2.'", cp="'.$cp.'", ville="'.$ville.'", infoslivraison="'.$infoslivraison.'", tel="'.$tel.'", mail="'.$mail.'", login="'.$login.'", mdp="'.$mdp.'" where numclient='.$id ;
    mysqli_query($requete) ;
  }else{
    $requete = 'insert into client values ("", "'.$nom.'", "'.$prenom.'", "'.$adr1.'", "'.$adr2.'", "'.$cp.'", "'.$ville.'", "'.$infoslivraison.'", "'.$tel.'", "'.$mail.'", "'.$login.'", "'.$mdp.'")' ;
    mysqli_query($requete) ;
    $id = mysql_insert_id() ;
  }

  // met � jour les variables de session et le cookie
  $_SESSION["login"] = $login ;
  $_SESSION["id"] = $id ;
  setcookie("login", $id * 353 - 27, time()+60*60*24*3600) ;

}else{
  // demande de d�connexion
  session_destroy();
  setcookie("login", "", time() - 3600) ;
}

//header("location:index.php") ;
?>