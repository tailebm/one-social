<?php
//id de l'utilsateur connecté	
	$id = $_SESSION['login'];
	include 'jaime.php';
//test si la connection bien établie	
	if(!isset($_SESSION['login'])){
		header('location:index.php');
		exit();
	}
	
	//	afficher les publications des comptes auxquels l'utilisateur a souscrit
	//  ou afficher toutes les publications pour les admin
	
	//admin ou non
	$verifAdmin = $db->query('SELECT admin FROM user WHERE id = "'.$id.'"');
	$save = $verifAdmin->fetch();
		
		//abonnements de l'utilisateur
		$userFollowReq = "SELECT followed_id FROM follow WHERE following_id = '$id'";
		$userFollow = $db->query($userFollowReq);
		
		
	//admin ou non pour pouvoir supprimer toutes les publications
	if($save['admin'] != "admin"){
		//form en dehors des boucles
		echo "<form method='POST' action='accueil.php'>";
		
		//boucle pour selectionner les posts des abonnements de l'utilisateur
		while($rf = $userFollow->fetch()){
		  $followed_id = $rf['followed_id'];
		  $postreq = "SELECT * FROM post WHERE user_id = '$followed_id'";
		  $publications = $db->query($postreq);
		  //leurs pfp et pseudo
		  $postInfosRequest ="SELECT user.* FROM post LEFT JOIN user ON user.id = post.user_id WHERE post.user_id = '$followed_id' GROUP BY user.id";		
		  $postInfos = $db->query($postInfosRequest);	
		  $ri = $postInfos->fetch();	
		  // $publications et $publicationsUser contiendront les résultats 
		  // de toutes les requêtes SQL exécutées dans leur boucle
		  // elles sont exécutées une fois pour chaque enregistrement trouvé(user) 
				
				//affichage		
				while($rp = $publications->fetch()){
					echo "<div class='post'>";
					$getExtension2 = explode(".", $rp['publication']);
					$extension2 = end($getExtension2);
					//séparer les photos des vidéos
					if($extension2 == "jpg" OR $extension2 == "jpeg" OR $extension2 == "jpe" OR $extension2 == "jif" OR $extension2 == "jfif"
					OR $extension2 == "jfi" OR $extension2 == "png" OR $extension2 == "gif" OR $extension2 == "tiff" OR $extension2 == "tif" 
					OR $extension2 == "psd" OR $extension2 == "raw" OR $extension2 == "arw" OR $extension2 == "bmp"){
						//pfp
						echo "<a class='pfp' href='profil.php?pseudo=".$ri['pseudo']."'>
							<img class='pfp' width='180' height='180'  src='".$ri['pfp']."' alt='Profile picture'>
						</a>";						
						//pseudo
						echo "<a class='pseudo' href='profil.php?pseudo=".$ri['pseudo']."'>".$ri['pseudo']."</a>";
						//date
						echo "<div class='date'>".$rp['date']."</div>";
						//publication
						echo "<img class='media' src='".$rp['publication']."' alt='post'>";
						//bouton like
						include 'boutonlike.php';
					}else{ 
						//pfp
						echo "<a class='pfp' href='profil.php?pseudo=".$ri['pseudo']."'>
							<img class='pfp' width='180' height='180'  src='".$ri['pfp']."' alt='Profile picture'>
						</a>";						
						//pseudo
						echo "<a class='pseudo' href='profil.php?pseudo=".$ri['pseudo']."'>".$ri['pseudo']."</a>";
						//date
						echo "<div class='date'>".$rp['date']."</div>";
						//publication
						echo "<video class='media' controls>
								<source src='".$rp['publication']."'>
							  </video><br>";
						//bouton like
						include 'boutonlike.php';	  
					}
					echo "</div>";	
				}
		}
		echo "</form>";
	}else{
		//form en dehors des boucles
		echo "<form method='POST' action='accueil.php'>";
		//tous les id
		$userReq = "SELECT id FROM user";	
		$user = $db->query($userReq);	
		while($rf = $user->fetch()){
		  $user_id = $rf['id'];
		  //tous les posts
		  $postreq = "SELECT * FROM post WHERE user_id = '$user_id'";
		  $publications = $db->query($postreq);
		  //leurs pfp et pseudo
		  $postInfosRequest ="SELECT user.* FROM post LEFT JOIN user ON user.id = post.user_id WHERE post.user_id = '$user_id' GROUP BY user.id";		
		  $postInfos = $db->query($postInfosRequest);	
		  $ri = $postInfos->fetch();	
		  // $publications et $postInfos contiendront les résultats 
		  // de toutes les requêtes SQL exécutées dans leur boucle
		  // elles sont exécutées une fois pour chaque enregistrement trouvé(user) 				
				
				//affichage		
				while($rp = $publications->fetch()){
					echo "<div class='post'>";			
					$getExtension2 = explode(".", $rp['publication']);
					$extension2 = end($getExtension2);
					//séparer les photos des vidéos
					if($extension2 == "jpg" OR $extension2 == "jpeg" OR $extension2 == "jpe" OR $extension2 == "jif" OR $extension2 == "jfif"
					OR $extension2 == "jfi" OR $extension2 == "png" OR $extension2 == "gif" OR $extension2 == "tiff" OR $extension2 == "tif" 
					OR $extension2 == "psd" OR $extension2 == "raw" OR $extension2 == "arw" OR $extension2 == "bmp"){
						//pfp
						echo "<a class='pfp' href='profil.php?pseudo=".$ri['pseudo']."'>
							<img class='pfp' width='180' height='180'  src='".$ri['pfp']."' alt='Profile picture'>
						</a>";						
						//pseudo
						echo "<a class='pseudo' href='profil.php?pseudo=".$ri['pseudo']."'>".$ri['pseudo']."</a>";
						//date
						echo "<div class='date'>".$rp['date']."</div>";
						//publication
						echo "<img class='media' src='".$rp['publication']."' alt='post'>";
						//bouton like
						include 'boutonlike.php';
						//possibilités de supprimer pour l'admin
						// enregistrer l'URL de la page pour refresh
						$actualPageUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
						echo "<form method='post' action='$actualPageUrl'>";
				        echo "<input type='hidden' name='getPostIdForDelete' value='".$rp['post_id']."'>";		
						echo "<input class='likes' type='submit' name='supprimer' value='supprimer'>";
						echo "</form>";	
					}else{ 
						//pfp
						echo "<a class='pfp' href='profil.php?pseudo=".$ri['pseudo']."'>
							<img class='pfp' width='180' height='180'  src='".$ri['pfp']."' alt='Profile picture'>
						</a>";						
						//pseudo
						echo "<a class='pseudo' href='profil.php?pseudo=".$ri['pseudo']."'>".$ri['pseudo']."</a>";
						//date
						echo "<div class='date'>".$rp['date']."</div>";
						//publication
						echo "<video class='media' controls>
								<source src='".$rp['publication']."'>
							  </video><br>";
						//bouton like
						include 'boutonlike.php';	
						//possibilités de supprimer pour l'admin
						// enregistrer l'URL de la page pour refresh
						$actualPageUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
						echo "<form method='post' action='$actualPageUrl'>";
				        echo "<input type='hidden' name='getPostIdForDelete' value='".$rp['post_id']."'>";		
						echo "<input class='likes' type='submit' name='supprimer' value='supprimer'>";
						echo "</form>";	
					}
					echo"</div>";		
				}
			}
			echo "</form>";			
	}
		
		//le cas où l'utilisateur de follow personne
		if($userFollow->rowCount() < 1 AND $save['admin'] != "admin"){
			echo "<h1>Commencez l'aventure en trouvant de nouveaux nakamas !</h1>";
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
