<?php
require 'database.php';
//test si la connection bien établie	
	if(!isset($_SESSION['login'])){
		header('location:index.php');
		exit();
	}
	
//id de l'utilsateur connecté	
	$id = $_SESSION['login'];
	$table = $db->query('SELECT pseudo FROM user WHERE id = "'.$id.'"');
	$saveTable = $table->fetch();
	$tableAll = $db->query('SELECT * FROM user WHERE id = "'.$id.'"');
	$saveTableAll = $tableAll->fetch();
	$verifAdmin = $db->query('SELECT admin FROM user WHERE id = "'.$id.'"');
	$save = $verifAdmin->fetch();
			
//redirection barre de recherche			
	if(isset($_POST['pseudo'])){
		$request = "SELECT pseudo FROM user WHERE pseudo = :pseudo";
		$stmt = $db->prepare($request);
		$stmt->bindParam(":pseudo", $_POST['pseudo']);
		$stmt->execute();
		$arrayOne = $stmt->fetch();
		if($stmt->rowCount() == 1){
			header('location:profil.php?pseudo='.$arrayOne['pseudo'].'');
		}else $return = "<h2 style='color: red; font-weight: bold; padding-top:5px;'>l'utilisateur est introuvable.</h2>";
	}
   					
?> 
