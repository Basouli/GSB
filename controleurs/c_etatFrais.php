<?php
if ($_SESSION['profil'] != "visiteur") {
    header('Location: index.php');
}

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$idVisiteur = $_SESSION['idUtilisateur'];

switch ($action) {
case 'selectionnerMois':
    $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
    $lesCles = array_keys($lesMois);
    $moisASelectionner = $lesCles[0];
    include 'vues/v_listeMois.php';
    break;
case 'voirEtatFrais':
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
    $moisASelectionner = $leMois;
    include 'vues/v_listeMois.php';
    
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
    
    $numAnnee = substr($leMois, 0, 4);
    $numMois = substr($leMois, 4, 2);
    
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    include 'vues/v_etatFrais.php';
    break;
default:
    $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
    $lesCles = array_keys($lesMois);
    $moisASelectionner = $lesCles[0];
    include 'vues/v_listeMois.php';
    break;
}
