<?php 

// id de la publication
$postId = $rp['post_id'];

//vérifier le genre de l'user qui a posté la publication en question
$postInfoRequest ="SELECT user.* FROM post LEFT JOIN user ON user.id = post.user_id WHERE post.post_id = '$postId' GROUP BY user.id";		
$getPostInfo = $db->query($postInfoRequest);	
$postInfo = $getPostInfo->fetch();

// j'aimes de la publication
$getPostLikesRequest = "SELECT COUNT(post_id) as compter FROM aime WHERE post_id = $postId" ;
$getPostLikes = $db->query($getPostLikesRequest);
$postLikes = $getPostLikes->fetch();

// nombre de like
$otherLikes = intval($postLikes['compter']) - 1;
$totalLikes = $postLikes['compter'];


// verifier si le post a deja été like par l'utilisateur
$hasBeenLikedSQL = "SELECT post_id FROM aime WHERE user_id = $id AND post_id = $postId";
$hasBeenLikedInfo = $db->query($hasBeenLikedSQL);
$hasBeenLiked = $hasBeenLikedInfo->fetch();

// enregistrer l'URL de la page pour refresh
$actualPageUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


	if($hasBeenLiked){ ?>
    <div id="likes" class="flex flex-wrap space-between items-center space-x-20 pt-5">
        <div id="members-liking" class="italic text-xs">Vous <?php 
            if($otherLikes > 1){
				if($postInfo['genre'] == "pirate"){
					echo 'et '.$otherLikes ?> autres pirates ont aimé cette publication
	 <?php		}else{
					echo 'et '.$otherLikes ?> autres marines ont aimé cette publication
     <?php      }
			}else if($otherLikes == 1){
				if($postInfo['genre'] == "pirate"){
					echo 'et '.$otherLikes ?> autre pirate ont aimé cette publication
     <?php 		}else{
					echo 'et '.$otherLikes ?> autre marine ont aimé cette publication
	 <?php		}
            }else{ ?>
                avez aimé cette publication
     <?php  } ?>
        </div>
        <form method="post" action='<?php echo $actualPageUrl; ?>'>
            <input type="hidden" name="getPostId" value='<?php echo $postId; ?>'>
            <input class="likes" type="submit" name="unlike" value="♥ <?php echo $postLikes['compter']; ?>"
            class="font-extrabold cursor-pointer hover:text-orange-600">
        </form>
    </div>

<?php }else{ ?>
    <div id="likes" class="flex flex-wrap space-between items-center space-x-20 pt-5">
        <div id="members-liking" class="italic text-xs">
            <?php	//toutes les possibilités
				  if($totalLikes > 1){
					if($postInfo['genre'] == "pirate"){
						echo $totalLikes; ?> pirates ont aimé cette publication
			<?php	}else{
						echo $totalLikes; ?> marines ont aimé cette publication						
            <?php   }
				  }else if($totalLikes == 0){
					if($postInfo['genre'] == "pirate"){ ?>
						aucun pirate n'a aimé cette publication
			<?php	}else{ ?>
						aucun marine n'a aimé cette publication						
			<?php   }
				  }else{
					if($postInfo['genre'] == "pirate"){
						echo $totalLikes; ?> pirate a aimé cette publication
			<?php	}else{
						echo $totalLikes; ?> marine a aimé cette publication						
			<?php   }
				  } ?>
        </div>
        <form method='POST' action='<?php echo $actualPageUrl ?>'>
            <input type="hidden" name="getPostId" value='<?php echo $postId; ?>'>
            <input type="submit" name="like" value="♥ <?php echo $postLikes['compter']; ?>"
            class="likes">
        </form>
    </div>
<?php } ?>
