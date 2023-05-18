<?php require 'setting.back.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Paramètres</title>
	<meta charset="utf-8">	
	<?php if($saveTable['sombre'] == "non"){ ?>
	<link rel="stylesheet" href="../css/setting.css"> 
	<?php }else{ ?>
	<link rel="stylesheet" href="../css/settingBlack.css"> 
	<?php }?>
</head>

<body>
<!-- stacklima.com pour aider sur le css du navigateur-->
	<!-- barre de navigateur -->
	<div class="navigateur">
		<a href='accueil.php'>Accueil</a>
		<a href='profil.php?pseudo=<?php echo $saveTable['pseudo'] ?>'>Profil</a>
		<a href='setting.php'>Paramètres</a>
		<a href='deconnexion.php'>Déconnexion</a>
		<?php 
			if($save['admin'] == "admin"){
				echo "<p>Administrateur</p>";
			}
		?>
		<!-- barre de recherche -->
		<div class="search">
			<form method='POST' action="">
				<input id="barre" onkeyup="search_User()" type="text" name="pseudo" placeholder="  Rechercher d'autres utilisateurs !">
				<button onclick="onButton()"><i class="button" style="font-size: 18px;"></i></button> 
			</form>
		</div>
	</div>
	<!-- stacklima.com/barre-de-recherche-utilisant-html-css-et-javascript/ -->
	<script src="../javascript/fonctions.js"></script>
	<!-- nom du reseau social  -->
	<div class="container">
		<h1 style="color: #84DBF0; font-weight: bold; padding-top:50px;">One Social</h1>
			<?php 
						if(isset($_POST['pseudo']) AND isset($return)){
							echo $return;
						}
					?>
	</div>	
	
<!-- Partie Parametres  -->	
		<div id="mid"><h1>Editer votre profil</h1></div>
	<form  enctype="multipart/form-data" action="setting.php" method='POST'>

	<!-- changer sa pfp -->	
	<div class="edit">	
			<label for="lienImage">changer de photo de profil</label> 
			<input type="file" accept="image/*" name="lienImage" id="file"> 
			<input type="submit" name="validePfp" value="valider"><br>		
			
	<!-- changer son pseudo -->
			<input type="submit" name="nextPseudo" value="changer de pseudo">
	<?php
		if(isset($_POST['nextPseudo']) AND !isset($_POST['annulerPseudo'])){ ?>
			<input type="text" name="newPseudo" placeholder="pseudo">
			<input type="submit" name="sauvegarderPseudo" value="sauvegarder">
			<input type="submit" name="annulerPseudo" value="annuler"><br>             
	<?php	} ?>
	
	<!-- changer sa description -->		
			<br><input type="submit" name="bio" value="voulez-vous changer votre bio ?">
	<?php
		if(isset($_POST['bio']) AND !isset($_POST['annuler'])){ ?>
			<input type="text" name="description" size="40" placeholder="remplissez votre bio !">
			<input type="submit" name="sauvegarder" value="sauvegarder">
			<input type="submit" name="annuler" value="annuler"><br>
	<?php	}else{ ?>
			<p>bio : <?php echo $saveTable['description']; ?></p>	
	<?php }	?>

		<!-- passer en mode nuit (changer le css) -->
		<p>Mode sombre</p>
		<select name="modeSombre" onchange="setModeSombre(this)">
		  <option value="Activé" <?php if(isset($_COOKIE['modeSombre']) && $_COOKIE['modeSombre'] == 'Activé') echo 'selected'; ?>>Activé</option>
		  <option value="Désactivé" <?php if(!isset($_COOKIE['modeSombre']) || $_COOKIE['modeSombre'] == 'Désactivé') echo 'selected'; ?>>Désactivé</option>
		</select>
		<input type="submit" name="sombre" value="Enregistrer">
<!-- setModeSombre est appelée lorsqu'un nouvel élément est sélectionné dans le menu déroulant 
	Cette fonction stocke la valeur sélectionnée dans un cookie nommé "modeSombre" qui est valide pour l'ensemble du site (path=/)
	Lorsque la page est chargée, les options du menu déroulant sont générées avec PHP et la valeur sélectionnée est rétablie
	à partir du cookie si elle correspond à la valeur stockée dans le cookie.-->
		<script>
		function setModeSombre(selectElem) {
		  var selectedValue = selectElem.value;
		  document.cookie = "modeSombre=" + selectedValue + "; path=/";
		}
		</script>
				

		<!-- changer son mot de passe -->
			<br><input type="submit" name="nextPasseword" value="changer de mot de passe">
	<?php
		if(isset($_POST['nextPasseword']) AND !isset($_POST['annulerPasseword'])){ ?>
			<input type="password" name="oldPasseword" placeholder="ancien mot de passe">
			<input type="password" name="newPasseword" placeholder="nouveau mot de passe">
			<input type="submit" name="sauvegarderPasseword" value="sauvegarder">
			<input type="submit" name="annulerPasseword" value="annuler">             
	<?php } 
	echo "<p>".$error."</p>";
	?>
	<!-- supprimer son compte -->
			<input type="submit" name="delete" value="Supprimer mon compte">
	<?php	if(isset($_POST['delete'])){ ?>
				<p>Êtes-vous sûr ?</p>
				<input type="submit" name="oui" value="oui">
				<input type="submit" name="non" value="non">
	<?php	} ?>
	</div>
	</form>
</body>
</html>
