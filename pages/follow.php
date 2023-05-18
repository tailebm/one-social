<?php
//	recuperer l'id du profil actuel
// 	requete préparée pour eviter les injections SQL, preparation
	$request = "SELECT * FROM user WHERE pseudo = :pseudo";
	$stmt = $db->prepare($request);
	// lie les valeurs et execute la requete
	$stmt->bindParam(":pseudo", $_GET['pseudo']);
	$stmt->execute();
	$idProfil = $stmt->fetch();
	
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
			if(isset($_POST['abonnés'])){
				//	les afficher à l'aide d'une boucle dans un div 'abonnés'
				echo "<div class='abonnés'><ul id='list'>";
				while($r = $prepareFind->fetch()){
					echo "<a href='profil.php?pseudo=".$r['pseudo']."'>";
					echo "<li>".$r['pseudo'];
					echo "</li></a>";
				}
				echo "</ul></div>";
			}
			if(isset($_POST['abonnements'])){
				//	les afficher à l'aide d'une boucle dans un div 'abonnements'
				echo "<div class='abonnements'><ul id='list'>";
				while($r2 = $prepareFind2->fetch()){ 
					echo "<a href='profil.php?pseudo=".$r2['pseudo']."'><li>".$r2['pseudo']."</li></a>";
				}
				echo "</ul></div>";
			} 		 
?>
