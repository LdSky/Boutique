<?php

/**
 * Connexion � la base de donn�es � partir de variables globales
 */     

		function Connexion(){
                try {
                    $db = new PDO("mysql:host=localhost;dbname=boutique;charset=utf8", "root", "");
                    return $db;
                } catch (PDOException $e) {
                    echo "Error: " . $e;
                }
		}

?>