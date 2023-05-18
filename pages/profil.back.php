<?php
require 'database.php';

	function Securité($st){
		//est-ce un entier ?
		if(is_int($st)){
			$st = intval($st);
		}else{
			$st = htmlentities($st, ENT_QUOTES, "UTF-8");
		}
		return $st;
	}

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
//admin ou non
	$verifAdmin = $db->query('SELECT admin FROM user WHERE id = "'.$id.'"');
	$save = $verifAdmin->fetch();

//	recuperer l'id du profil actuel
// 	requete préparée pour eviter les injections SQL, preparation
	$request = "SELECT * FROM user WHERE pseudo = :pseudo";
	$stmt = $db->prepare($request);
	// lie les valeurs et execute la requete
	$stmt->bindParam(":pseudo", $_GET['pseudo']);
	$stmt->execute();
	$idProfil = $stmt->fetch(); 

//barre de recherche sur la page profil également			
	if(isset($_POST['pseudo'])){
		$request2 = "SELECT pseudo FROM user WHERE pseudo = :pseudo";
		$stmt2 = $db->prepare($request2);
		$stmt2->bindParam(":pseudo", $_POST['pseudo']);
		$stmt2->execute();
		$arrayOne = $stmt2->fetch();
		if($stmt2->rowCount() == 1){
			header('location:profil.php?pseudo='.$arrayOne['pseudo'].'');
		}else $return = "<h2 style='color: red; font-weight: bold; padding-top:5px;'>l'utilisateur est introuvable.</h2>";
	}

//	trouver les followers du profil actuel
		$saveIdProfil = $idProfil['id'];
		$find="SELECT user.* FROM follow LEFT JOIN user ON user.id = follow.following_id WHERE follow.followed_id = '$saveIdProfil' GROUP BY user.id";
		$prepareFind = $db->query($find); 
		// les compter pour la value du submit
		$c = $prepareFind->rowCount();
		if($c > 1){
			$nombreFollowers = $c." abonnés";
		}else{
			$nombreFollowers = $c." abonné";
		} 				
//	trouver les follows du profil actuel
		$find2="SELECT user.* FROM follow LEFT JOIN user ON user.id = follow.followed_id WHERE follow.following_id = '$saveIdProfil' GROUP BY user.id";
		$prepareFind2 = $db->query($find2);
		// les compter pour la value du submit
		$c2 = $prepareFind2->rowCount();
		if($c2 > 1){
			$nombreFollows = $c2." abonnements";
		}else{
			$nombreFollows = $c2." abonnement";
		}
//leurs affichage est dans follow.php !!

		
	// Differencier le profil de l'utilisateur connecté des autres profils
	$monProfil = true;
	if (isset($_GET['pseudo'])) {
		if($_GET['pseudo'] != $saveTable['pseudo']){
			$monProfil = false;
		}
	}
	
	// savoir si on suit déjà l'utilisateur ou non
	$suivi = false;
/*	$userFollowingRequest = "SELECT follow.followed_id, user.id as user_followed_id, user.pseudo as user_pseudo FROM follow JOIN user ON user.id=follow.followed_id WHERE following_id = '$saveIdProfil'";
	$getFollowing = $db->query($userFollowingRequest);

	$userFollowedRequest = "SELECT follow.following_id, user.id as user_follow, user.pseudo as user_pseudo FROM follow JOIN user ON user.id=follow.following_id WHERE followed_id = '$saveIdProfil'";
	$getFollowed = $db->query($userFollowedRequest);
*/
	$checkFollowSql = "SELECT * FROM follow WHERE followed_id = $saveIdProfil";
	$checkFolloQuery = $db->query($checkFollowSql);

	while($isFollowed = $checkFolloQuery->fetch()){
		if($isFollowed['followed_id'] == $saveIdProfil && $isFollowed['following_id'] == $id){
			$suivi = true;
		}
	}
	// s'abonner ou se désabonner 			
		if(isset($_POST['follow'])){
			//requete SQL avec IGNORE pour ignorer les doublons
			$addRequest = "INSERT IGNORE INTO follow(following_id, followed_id) VALUES ('$id', '$saveIdProfil')";
			$add = $db->query($addRequest);
			//changer la valeur du boolean
			$suivi = true;
			header('location:profil.php?pseudo='.$_GET['pseudo'].'');
		}
		if(isset($_POST['unfollow'])){
			//requete SQL
			$delRequest = "DELETE FROM follow WHERE follow.following_id='$id' AND follow.followed_id='$saveIdProfil'";			
			$del = $db->query($delRequest);
			//changer la valeur du boolean
			$suivi = false; 
			header('location:profil.php?pseudo='.$_GET['pseudo'].'');		
		}
		
	// poster une publication si c'est le profil de l'utilisateur connecté
	$date = date('d/m/Y');
	if(isset($_POST['validePost'])){
		$nomPost = $_FILES['lienPost']['name'];
		$tmpPost = $_FILES['lienPost']['tmp_name'];
		
		// avoir le type du fichier avec la fonction explode, retourne array separateur(.)
		// donc le dernier élément est le type du fichier
		$getExtension = explode(".", $nomPost);
		$extension = end($getExtension);
		$newNomPost = uniqid('',true);
		$nom = $newNomPost.".".$extension; 	
	
		// déplacer l'image dans le répertoire post
		move_uploaded_file($tmpPost, '../post/'.$nom);
					
		//lien stocker dans la bdd colonne post
		$rep = "../post/".$nom;
		$reqPost = "INSERT IGNORE INTO post(user_id, publication, date) VALUES('$id', '$rep', '$date')";
		$post = $db->query($reqPost);
	}
	

?>
