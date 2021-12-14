<?php 
$pdo = new PDO ('mysql:host=localhost;dbname=gsb_frais', 'root', '');
$req= $pdo->prepare('SELECT * FROM visiteur');
$req->execute();
$visiteurs=$req->fetchAll();
foreach($visiteurs as $visiteur){
	$mdp=password_hash($visiteur['mdp'], PASSWORD_DEFAULT);
	$id=$visiteur['id'];
	$preparedRequest=$pdo->prepare('UPDATE visiteur SET mdp=:mdpH WHERE id=:id');
	$preparedRequest->bindParam(':mdpH', $mdp, PDO::PARAM_STR);
	$preparedRequest->bindParam(':id', $id, PDO::PARAM_STR);
	$preparedRequest->execute();
}