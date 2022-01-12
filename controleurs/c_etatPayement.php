<?php
if ($_SESSION['profil'] != "comptable") {
    header('Location: index.php');
}

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
switch ($action) {
case 'selectionnerFiche':
    $fiches = $pdo->getFichesValidees();
    require 'vues/v_listeFiche.php';
    break;
case 'etatFiche':
    $fiches = $pdo->getFichesValidees();
    
    $postValues = explode('&', filter_input(INPUT_POST, 'lstFiche', FILTER_SANITIZE_STRING));
    $idVisiteur = $postValues[0];
    $mois = $postValues[1];
    
    $_SESSION['VP-idVisiteur'] = $idVisiteur;
    $_SESSION['VP-mois'] = $mois;
    
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
    require 'vues/v_listeFiche.php';
    include 'vues/v_fraisForfait.php';
    include 'vues/v_validerPayement.php';
    break;
case 'validerPayement':
    if (isset($_SESSION['VP-idVisiteur']) && isset($_SESSION['VP-mois'])) {
        $pdo->majEtatFicheFrais($_SESSION['VP-idVisiteur'], $_SESSION['VP-mois'], "RB");
        $fiches = $pdo->getFichesValidees();
        require 'vues/v_listeFiche.php';
        echo "<script>alert('Le frais a été mis en paiement !')</script>";
    } else {
        header('Location: ?uc=etatPayement&action=selectionnerFiche');
    }
    break;
default:
    $fiches = $pdo->getFichesValidees();
    require 'vues/v_listeFiche.php';
    break;
}

