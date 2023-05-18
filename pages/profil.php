<?php include 'profil.back.php'; ?>

<!DOCTYPE>
<html lang="fr">

<head>
	<title><?php echo $_GET['pseudo']?></title>
	<meta charset="utf-8">
	<?php if($saveTableAll['sombre'] == "non"){ ?>
	<link rel="stylesheet" href="../css/profilOP.css"> 
	<?php }else{ ?>
	<link rel="stylesheet" href="../css/profilSombre.css"> 
	<?php }?>
</head>
<body>	
	<!-- barre de navigateur -->
	<div class="navigateur">
		<a href='accueil.php'>Accueil</a>
		<a href='profil.php?pseudo=<?php echo $saveTable['pseudo'] ?>'>Profil</a>
		<a href='setting.php'>Paramètres</a>
		<a href='deconnexion.php'>Déconnexion</a>
		<?php 
			$id = $_SESSION['login'];
			$verifAdmin = $db->query('SELECT admin FROM user WHERE id = "'.$id.'"');
			$save = $verifAdmin->fetch();
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

	<!-- fonction javascript à l'aide de stacklima.com/barre-de-recherche-utilisant-html-css-et-javascript/ -->
	<script src="../javascript/fonctions.js"></script> 

	<div class="container">
		<h1 style="color: #84DBF0; font-weight: bolder; padding-top:80px;">One Social</h1>
		<?php 
						if(isset($_POST['pseudo']) AND isset($return)){
							echo $return;
						}
					?>
	</div>

<div class="number">
	<!-- afficher/cacher la liste d'abonnés/abonnemments -->	
		<form enctype="multipart/form-data" method='POST' action="profil.php?pseudo=<?php echo $_GET['pseudo']; ?>" >
			<input class="follows" type="submit" value="<?php echo $nombreFollowers?>" name="abonnés">
			<input class="follows" type="submit" value="<?php echo $nombreFollows?>" name="abonnements">
	 <!-- afficher suivre/se désabonner selon le boolean -->
                    <?php
                    if($monProfil == false){
                        if($suivi == true){ ?>
							<form action="profil.php?pseudo=<?php echo $_GET['pseudo']; ?>" method='POST'>
								<input class="suivi" type="submit" value="se désabonner" name="unfollow">
							</form>
                    <?php }else{ ?>
							<form action="profil.php?pseudo=<?php echo $_GET['pseudo']; ?>" method='POST'>
								<input class="suivi" type="submit" value="suivre" name="follow">
							</form>
                        <?php }
                    }else{ ?>
						<input type="submit" name="post" value="nouvelle publication">
						<?php if(isset($_POST['post'])){ ?>
							<label for="lienPost">video ou image</label>							
							<input type="file" accept="video/*,image/*" name="lienPost" id="file">
							<input type="submit" name="annulerPost" value="annuler">							
							<input type="submit" name="validePost" value="valider">
						<?php } 
						} ?>
			<?php 	include 'follow.php'; ?>

		</form> 
	
	<!-- informations de l'utilisateur du profil actuel -->
		<div class="userDetails">
			<!-- photo de profil -->
			<div class="userpfp">
			<img class="profilPicture" width="180" height="180"  src="<?php echo $idProfil['pfp'];?>" alt="Profile picture">
			</div>
			<br>
			<!-- pseudo et bio -->	
						<?php echo "<h3>".$_GET['pseudo']."</h3>"; ?>
						<?php if($idProfil['age'] > 1){ 
								echo "<h3>".$idProfil['age']." ans</h3>"; 
							}else{ echo "<3>".$idProfil['age']." an</3>"; } ?>
						<?php echo "<h3>A rejoint l'aventure en tant que ".$idProfil['genre']." depuis le ".$idProfil['date']."</h3>"; ?>
						<?php echo "<h3>".$idProfil['description']."</h3>"; ?>
					<br>
		</div>
	<!-- mur de l'utilisateur du profil (publications, likes, commentaires) -->
	<div class="mur">
		<form method='POST' action="profil.php?pseudo=<?php echo $_GET['pseudo']; ?>">
			<!-- <div id="pictureContainer" class="w-30 h-30 rounded-full"> -->
			<input type="submit" name="publications" value="Publications">				
			<input type="submit" name="likes" value="Likes">				
	</div>		
			<?php include 'post.php'; ?>						
		</form>
</div>	
</body>
</html>
