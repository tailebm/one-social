<?php require 'accueil.back.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">	
	<?php if($saveTableAll['sombre'] == "non"){ ?>
	<link rel="stylesheet" href="../css/accueilOP.css"> 
	<?php }else{ ?>
	<link rel="stylesheet" href="../css/accueilSombre.css"> 
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

	<div class="container">
		<h1 style="color: #84DBF0; font-weight: bolder; padding-top:80px;">One Social</h1>
			<?php 
						if(isset($_POST['pseudo']) AND isset($return)){
							echo $return;
						}
					?>
	</div>
	
		<!-- afficher les publications des comptes auxquels l'utilisateur a souscrit-->
	<?php include 'post.accueil.php';?>
</body>
</html>
