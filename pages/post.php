<?php
//id de l'utilsateur connecté	
	$id = $_SESSION['login'];
	include 'jaime.php';	
//	recuperer l'id du profil actuel
// 	requete préparée pour eviter les injections SQL, preparation
	$request = "SELECT * FROM user WHERE pseudo = :pseudo";
	$stmt = $db->prepare($request);
	// lie les valeurs et execute la requete
	$stmt->bindParam(":pseudo", $_GET['pseudo']);
	$stmt->execute();
	$idProfil = $stmt->fetch(); 
	$saveIdProfil = $idProfil['id'];
	
	$postreq = "SELECT * FROM post WHERE user_id = '$saveIdProfil'";
	$publications = $db->query($postreq);
	//	afficher les publications
			if(isset($_POST['publications'])){
				echo "<form method='POST' action='profil.php?pseudo=".$idProfil['pseudo']."'>";
				$aucunPost = 0;
				while($rp = $publications->fetch()){
					echo "<div class='post'>";					
					$s = $rp['post_id'];
					$getExtension = explode(".", $rp['publication']);
					$extension = end($getExtension);
					//differencier photo et vidéo
					if($extension == "jpg" OR $extension == "jpeg" OR $extension == "jpe" OR $extension == "jif" OR $extension == "jfif"
					OR $extension == "jfi" OR $extension == "png" OR $extension == "gif" OR $extension == "tiff" OR $extension == "tif" 
					OR $extension == "psd" OR $extension == "raw" OR $extension == "arw" OR $extension == "bmp"){
							echo "<div class='date'>".$rp['date']."</div>";
							echo "<img class='media' src='".$rp['publication']."' alt='post'>";
							//bouton like que ce soit notre profil ou non
							include 'boutonlike.php';
						//pouvoir supprimer la publication en question si c'est celle de l'utilisateur de la session					
						if($monProfil){	
							// enregistrer l'URL de la page pour refresh
							$actualPageUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
							echo "<form method='post' action='$actualPageUrl'>";
							echo "<input type='hidden' name='getPostIdForDelete' value='".$rp['post_id']."'>";		
							echo "<input class='likes' type='submit' name='supprimer' value='supprimer'>";
							echo "</form>";	
						}
					}else{ 
						echo "<div class='date'>".$rp['date']."</div>";
								echo "<video class='media' controls >
									<source src='".$rp['publication']."'>
								  </video>";							
							//bouton like aussi pour les types vidéos
							echo "<div class='post-actions'>";							
							include 'boutonlike.php';
							echo "</div>";						
						//pouvoir supprimer la vidéo aussi
						if($monProfil){	
							// enregistrer l'URL de la page pour refresh
							$actualPageUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
							echo "<form method='post' action='$actualPageUrl'>";
							echo "<input type='hidden' name='getPostIdForDelete' value='".$rp['post_id']."'>";		
							echo "<input class='likes' type='submit' name='supprimer' value='supprimer'>";
							echo "</form>";							
						}
					}
					echo "</div>";
					$aucunPost += 1;
				}
				echo "</form>";
				if($aucunPost == 0){
					if($idProfil['genre'] == "pirate"){
						echo "<p class='noPost'>C'est un pirate solitaire...</p>";
					}else{
						echo "<p class='noPost'>C'est un marine solitaire...</p>";
					}			
				}
			}
			
//afficher les post liker par l'utilisateur du profil
		//	même code que pour les publications du profil
	$postLikedReq = "SELECT * FROM post";
	$publicationsLiked = $db->query($postLikedReq);
			if(isset($_POST['likes'])){
				//variable pour gérer le cas où l'utilisateur du profil n'a aucun like
				$aucunLike = 0;
				echo "<form method='POST' action='profil.php?pseudo=".$idProfil['pseudo']."'>";				
				while($rp = $publicationsLiked->fetch()){
					$postId = $rp['post_id'];
					$userPostId = $rp['user_id'];
					// prendre que les posts aimés par l'utilisateur du profil
					$hasBeenLikedSQL = "SELECT post_id FROM aime WHERE user_id = $saveIdProfil AND post_id = $postId";
					$hasBeenLikedInfo = $db->query($hasBeenLikedSQL);
					$hasBeenLiked = $hasBeenLikedInfo->fetch();
					//trouver l'extension du post
					$getExtension = explode(".", $rp['publication']);
					$extension = end($getExtension);
					  //les pfp et pseudo du détenteur du post en question
					$postInfosRequest ="SELECT user.* FROM post LEFT JOIN user ON user.id = post.user_id WHERE post.user_id = '$userPostId' GROUP BY user.id";		
					$postInfos = $db->query($postInfosRequest);	
					$ri = $postInfos->fetch();
					if($hasBeenLiked){
					echo "<div class='post'>";					
						//differencier photo et vidéo
						if($extension == "jpg" OR $extension == "jpeg" OR $extension == "jpe" OR $extension == "jif" OR $extension == "jfif"
						OR $extension == "jfi" OR $extension == "png" OR $extension == "gif" OR $extension == "tiff" OR $extension == "tif" 
						OR $extension == "psd" OR $extension == "raw" OR $extension == "arw" OR $extension == "bmp"){
							//rajout du pseudo et de la photo de profil
							echo "<a class='pfp' href='profil.php?pseudo=".$ri['pseudo']."'>
									<img class='pfp' width='180' height='180'  src='".$ri['pfp']."' alt='Profile picture'>
								  </a>";
							echo "<a class='pseudo' href='profil.php?pseudo=".$ri['pseudo']."'>".$ri['pseudo']."</a>";
							echo "<div class='date'>".$rp['date']."</div>";
							echo "<img class='media' src='".$rp['publication']."' alt='post'>";
								include 'boutonlike.php';													
						}else{ 
							//rajout du pseudo et de la photo de profil
							echo "<a class='pfp' href='profil.php?pseudo=".$ri['pseudo']."'>
									<img class='pfp' width='180' height='180'  src='".$ri['pfp']."' alt='Profile picture'>
								  </a>";	
							echo "<a class='pseudo' href='profil.php?pseudo=".$ri['pseudo']."'>".$ri['pseudo']."</a>";
							echo "<div class='date'>".$rp['date']."</div>";
								echo "<video class='media' controls >
									<source src='".$rp['publication']."'>
								  </video>";							
								include 'boutonlike.php';
						}
						$aucunLike += 1;
						echo "</div>";
					}
				}
				//affichage si aucun like
				if($aucunLike == 0){
					if($idProfil['genre'] == "pirate"){
						echo "<p class='noPost'>C'est un pirate solitaire...</p>";
					}else{
						echo "<p class='noPost'>C'est un marine solitaire...</p>";
					}
				}
				echo "</form>";
			}
			
	//supprimer le post concerné
	if(isset($_POST['getPostIdForDelete'])){	
		// variable set
		$postId = $_POST['getPostIdForDelete'];
		if(isset($_POST['supprimer'])){
			//supprimer aussi les likes du post en questions !!
			$delLikesPostReq = "DELETE FROM aime WHERE post_id='$postId'";
			$delLikesPost = $db->query($delLikesPostReq);
			//supprimer le post en lui-même
			$delPostReq = "DELETE FROM post WHERE post_id='$postId'";
			$delPost = $db->query($delPostReq);
		}
	}				
?>
