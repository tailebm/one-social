<?php
		//supprimer le post concerné
		//tous les id
		$userReq = "SELECT id FROM user";	
		$user = $db->query($userReq);
		while($rf = $user->fetch()){
		    $user_id = $rf['id'];
		    //tous les posts
		    $postreq = "SELECT * FROM post WHERE user_id = '$user_id'";
		    $publications = $db->query($postreq);
			while($rs = $publications->fetch()){
				$s = $rs['post_id'];
				if(isset($_POST["$s"])){
					//supprimer aussi les likes du post en questions !!
					$delLikesPostReq = "DELETE FROM aime WHERE post_id='$s'";
					$delLikesPost = $db->query($delLikesPostReq);
					//supprimer le post en lui-même
					$delPostReq = "DELETE FROM post WHERE post_id='$s'";
					$delPost = $db->query($delPostReq);
				}
			}
		}
?>
