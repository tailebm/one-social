<?php

//id de l'utilsateur connecté	
	$id = $_SESSION['login'];
	 
if(isset($_POST['getPostId'])){

    // variable set
    $postId = $_POST['getPostId'];

    if(isset($_POST["like"])){
            $insertLikeRequest = "INSERT INTO aime(user_id, post_id) VALUES('$id', '$postId') ";
            $ok = $db->query($insertLikeRequest);
    }

    if(isset($_POST["unlike"])){
            $removeLikeRequest = "DELETE FROM aime WHERE user_id = '$id' AND post_id = '$postId'";
            var_dump($removeLikeRequest);
            $ok = $db->query($removeLikeRequest);
    }
        
    ?>

<?php } ?>
