<?php
//lancer une session
	session_start();
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'data_user');
	define('DB_USERNAME', 'root');
	define('PASSWORD', '');

//connexion à la base de données
	try{
		$db = new PDO("mysql:host=localhost;dbname=data_user", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}catch(Exception $e){
		echo "erreur: ".$e;
	}
?>
