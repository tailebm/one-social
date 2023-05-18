<?php 
require 'database.php' ;
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
?>
<?php //vérification des informations entrées pour inscription
	if(isset($_POST['inscription'])){
		$email = Securité($_POST['email']);
		$pseudo = Securité($_POST['pseudo']);
		$passeword = Securité($_POST['passeword']);
		$secondpasseword = Securité($_POST['secondpasseword']);
		$genre = Securité($_POST['genre']);
		$age = Securité($_POST['age']);
		$date = date('d/m/Y');
		$description = Securité($_POST['description']);
		if(!empty($email) && !empty($pseudo) && !empty($passeword) && !empty($secondpasseword)&& !empty($genre) && !empty($age)){
			if(filter_var($email, FILTER_VALIDATE_EMAIL)){
				if($passeword == $secondpasseword){
//					if(strlen($pseudo) == 100){
						if($age>=0){
							$verifEmail = $db->query('SELECT id FROM user WHERE email = "'.$email.'"');
							if($verifEmail->rowCount() < 1){
								$passeword = PassewordCache($passeword);
								if($genre == "marine"){
									$pfp = "../pfp/randomMarine.png";
								}else{
									$pfp = "../pfp/randomPirate.jpg";
								}
								$db->query('INSERT INTO user (genre, age, email, pseudo, passeword, date, description, pfp) VALUES ("'.$genre.'", "'.$age.'", "'.$email.'", "'.$pseudo.'", "'.$passeword.'", "'.$date.'","'.$description.'","'.$pfp.'")');
								//on redirige
								$newIdentity = $db->query('SELECT id FROM user WHERE pseudo = "'.$pseudo.'" AND passeword = "'.$passeword.'"');
								$newSave = $newIdentity->fetch();
								$_SESSION['login'] = $newSave['id'];
								header('location:accueil.php');
							}else $return = "l'adresse email est déja associée à un compte.";
						}else $return = "l'âge est incorrect";
//					}else $return = "votre pseudo dépasse la limite de caractères";
				}else $return = "le mot de passe n'est pas identique.";
			}else $return = "l'email n'est pas valide.";
		}else $return = "Veuillez remplir tous les champs.";
	}
?>

<?php //vérification des informations entrées pour connection
	if(isset($_POST['connexion'])){
		$pseudo = Securité($_POST['pseudo']);
		$passeword = Securité($_POST['passeword']);
		if(!empty($pseudo) && !empty($passeword)){
			$passeword = PassewordCache($passeword);
			$verifIdentity = $db->query('SELECT id FROM user WHERE pseudo = "'.$pseudo.'" AND passeword = "'.$passeword.'"');
			$save = $verifIdentity->fetch();
			if($verifIdentity->rowCount() == 1){
				$_SESSION['login'] = $save['id'];
				header('location:accueil.php');
			}else $return = "le pseudo ou le mot de passe est invalide.";
		}else $return = "Veuillez remplir tous les champs.";
	}
?>
