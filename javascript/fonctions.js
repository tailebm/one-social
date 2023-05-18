//appeler une fonction PHP sur le clic d'un bouton
function onButton(){
	//variable detenant le code de la barre de recherche
	//il n'est pas dans une fonction car j'ai rencontrer une erreur concernant la base de données
	var click = "<?php if(isset($_POST['pseudo'])){ $request = "+"'SELECT pseudo FROM user WHERE pseudo = ?'"+"; $stmt = $db->prepare($request); $stmt->execute([$_POST['pseudo']]); $arrayOne = $stmt->fetch();"+" if($stmt->rowCount() == 1){ header('location:profil.php?pseudo='.$arrayOne['pseudo'].''); }else $return = '<h2>l'utilisateur est introuvable.</h2>'; ?>";
	document.write($click);
}
//filtre de recherche
function search_User(){
	let input = document.getElementById('barre').value
	input = input.toLowerCase();
	let x = document.getElementsByClassName('allUsers');
			
	for(i=0;<x.length; i++){
		if(!x[i].innerHTML.toLowerCase().includes(input)) {
			x[i].style.display="none";
		}else{
			x[i].style.display="list-item";                 
		}
	}
}
