<?php require 'index.back.php';?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/indexOP.css">
</head>

<body>
	<h1 class="social" style="color: #84DBF0; font-weight: bolder;">One Social</h1>
<!-- formulaire d'inscription -->
	<div class="container">
		<form method='POST' action="#">
			<div class="formulaire">
				<h1>Inscription</h1>
				<input type="text" name="email" placeholder="email"><br/>
				<input type="number" name="age" placeholder="age"><br/><br/>
				<select name="genre">
					<option value="marine">Marine</option>
					<option value="pirate">Pirate</option>
				</select>
				<br>
				<input type="text" name="pseudo" placeholder="pseudo"><br/>
				<input type="password" name="passeword" placeholder="mot de passe"><br/><br/>
				<input type="password" name="secondpasseword" placeholder="réecrivez le mot de passe"><br/><br/>
				<input type="text" name="description" size="40" placeholder="remplissez votre bio !"><br/><br/>
				<input type="submit" value="S'inscrire !" name="inscription">
			</div>
		</form>
	<!-- formulaire de connexion -->
		<form method='POST' action="#">
			<div class="formulaire2">
				<h1 class="connection">Connexion</h1>
				<?php  //afficher le message d'erreur
					if(isset($_POST['inscription']) && isset($return)){ 
						echo "<h2 class="."erreur".">".$return."</h2>";
					}
					if(isset($_POST['connexion']) && isset($return)){ 
						echo "<h2 class="."erreur".">".$return."</h2>";
					}
				?>
				<input type="text" name="pseudo" placeholder="pseudo"><br/>
				<input type="password" name="passeword" placeholder="mot de passe"><br/><br/>
				<input type="submit" value="Se connecter !" name="connexion">
			</div>
		</form>	
	</div>
</body>
</html>
