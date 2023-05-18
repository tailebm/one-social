<?php
	require 'database.php';
	//fonctions pour + de sécurité
	function Securité($st){
		//est-ce un entier ?
		if(is_int($st)){
			$st = intval($st);
		}else{
			$st = htmlentities($st, ENT_QUOTES, "UTF-8");
		}
		return $st;
	}
	function PassewordCache($st){
		$st = sha1(md5($st));
		return $st;
	}
	//test si la connection bien établie	
		if(!isset($_SESSION['login'])){
			header('location:index.php');
			exit();
		}
	//id de l'utilsateur connecté	
		$id = $_SESSION['login'];	
	//pseudo de l'utilisateur connecté	
		$table = $db->query('SELECT * FROM user WHERE id = "'.$id.'"');
		$saveTable = $table->fetch();
	//admin ou non
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
	
	//modifier le mode sombre
	if(isset($_POST['sombre'])){
		$value = $_POST['modeSombre'];
		if($value == "Activé"){
			$reqSombre = "UPDATE user SET sombre = 'oui' WHERE id='$id'";
			$sombre = $db->query($reqSombre);
		}else{
			$reqSombre = "UPDATE user SET sombre = 'non' WHERE id='$id'";
			$sombre = $db->query($reqSombre);	
		}
	}

	// choisir la photo de profil
	if(isset($_POST['validePfp'])){
		$nomPfp = $_FILES['lienImage']['name'];
		$tmpPfp = $_FILES['lienImage']['tmp_name'];

			// avoir le type du fichier avec la fonction explode, retourne array separateur(.)
			// donc le dernier élément est le type du fichier
/*			$getExtension = explode(".", $nomPfp);
			$extension = end($getExtension);
			$nom = $nomPfp.".".$extension; */
			
		//lien stocker dans la bdd colonne pfp
		$repertoire = "../pfp/".$nomPfp;
		// move image to folder
		move_uploaded_file($tmpPfp, '../pfp/'.$nomPfp);
		$reqPfp = "UPDATE user SET pfp = '$repertoire' WHERE id='$id'";
		$pfp = $db->query($reqPfp);
	}

	//changer de pseudo 
	if(isset($_POST['sauvegarderPseudo'])){
		$pseudo = Securité($_POST['newPseudo']);
		$requestPseudo = "UPDATE user SET pseudo='$pseudo' WHERE id='$id'";
		$changePseudo = $db->query($requestPseudo);
	}
	//changer la description	
		if(isset($_POST['sauvegarder'])){ 
			$description = Securité($_POST['description']);
			$requestBio = "UPDATE user SET description='$description' WHERE id='$id'";
			$changeBio = $db->query($requestBio);
		}
		
	//changer de mot de passe 
	$error = "";
	if(isset($_POST['sauvegarderPasseword'])){
		if(isset($_POST['oldPasseword']) AND isset($_POST['newPasseword'])){
			$oldMdp = Securité($_POST['oldPasseword']);
			$oldMdpCache = PassewordCache($oldMdp);
			if($oldMdpCache == $saveTable['passeword']){
				$newMdp = Securité($_POST['newPasseword']);
				$newMdpCache = PassewordCache($newMdp);
				$requestMdp = "UPDATE user SET passeword='$newMdpCache' WHERE id='$id'";
				$changeMdp = $db->query($requestMdp);
				$error = "changement réussi";
			}else $error = "le mot de passe ne correspond pas.";
		}else $error = "veuillez remplir tout les champs.";
	}
	//supprimer son compte
		if(isset($_POST['oui'])){
			$supLikeReq= "DELETE FROM aime WHERE user_id='$id'";
			$supLike = $db->query($supLikeReq);
			$supPostReq= "DELETE FROM post WHERE user_id='$id'";
			$supPost = $db->query($supPostReq);
			$supFollowReq= "DELETE FROM follow WHERE follow.followed_id ='$id' OR follow.following_id ='$id'";
			$supFollow = $db->query($supFollowReq);
			$supUserReq= "DELETE FROM user WHERE id='$id'";
			$supUser = $db->query($supUserReq);
			header('location:deconnexion.php');
		} 
?>
