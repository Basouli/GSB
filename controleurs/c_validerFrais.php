<?php

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
switch ($action) {
case 'selectionnerFiche':
    $visiteurs = $pdo->getVisiteursFichesEnAttentes();
    require 'vues/v_listeVisiteur.php';
    break;
case 'changerEtats': 
    $values = explode('&', filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING));
    $_SESSION['VF-idVisiteur'] = $values[0];
    $_SESSION['VF-mois'] =  $values[1];
    $visiteur = $pdo->getVisiteurById($_SESSION['VF-idVisiteur']);
    $_SESSION['VF-identity'] = $visiteur['nom'] . ' ' . $visiteur['prenom'];
            
    $idVisiteur = $_SESSION['VF-idVisiteur'];
    $mois = $_SESSION['VF-mois'];
    $identity = $_SESSION['VF-identity'];
    
    $numAnnee = substr($mois, 0, 4);
    $numMois = substr($mois, 4, 2);
    
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
    
    require 'vues/v_listeFraisForfait.php';
    require 'vues/v_listeFraisHorsForfait.php';
    require 'vues/v_validerFrais.php';
    break;
case 'validerMajFraisForfait':
    if (isset($_SESSION['VF-idVisiteur']) && isset($_SESSION['VF-mois'])) {
        $idVisiteur = $_SESSION['VF-idVisiteur'];
        $mois = $_SESSION['VF-mois'];
        $identity = $_SESSION['VF-identity'];
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        }
        
        $ficheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);
        $numAnnee = substr($mois, 0, 4);
        $numMois = substr($mois, 4, 2);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
        require 'vues/v_listeFraisForfait.php';
        require 'vues/v_listeFraisHorsForfait.php';
        require 'vues/v_validerFrais.php';
    } else {
        header('Location: index.php?uc=validerFrais&action=selectionnerFiche');
    }
    break;
case 'refuserFrais':
    if (isset($_SESSION['VF-idVisiteur']) && isset($_SESSION['VF-mois'])) {
        $idVisiteur = $_SESSION['VF-idVisiteur'];
        $mois = $_SESSION['VF-mois'];
        $identity = $_SESSION['VF-identity'];
    
        $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
        $verifyIntegrity = $pdo->getIdFraisHorsForfait($idFrais);
        if ($verifyIntegrity != null) {
            if ($verifyIntegrity['idvisiteur'] == $_SESSION['VF-idVisiteur'] && $verifyIntegrity['mois'] == $_SESSION['VF-mois'] && substr($verifyIntegrity['libelle'], 0, 9) != 'REFUSE : ') {
                $pdo->refuserFrais($idFrais);
                
                $ficheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);
                $numAnnee = substr($mois, 0, 4);
                $numMois = substr($mois, 4, 2);
                $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
                $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
                require 'vues/v_listeFraisForfait.php';
                require 'vues/v_listeFraisHorsForfait.php';
                require 'vues/v_validerFrais.php';
        
                echo "<script>alert('Le frais a été refusé !')</script>";
            }
        }
    } else {
        header('Location: index.php?uc=validerFrais&action=selectionnerFiche');
    }
    break;
case 'accepterFrais':
    if (isset($_SESSION['VF-idVisiteur']) && isset($_SESSION['VF-mois'])) {
        $idVisiteur = $_SESSION['VF-idVisiteur'];
        $mois = $_SESSION['VF-mois'];
        $identity = $_SESSION['VF-identity'];
        
        $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
        $verifyIntegrity = $pdo->getIdFraisHorsForfait($idFrais);
        if ($verifyIntegrity != null) {
            if ($verifyIntegrity['idvisiteur'] == $_SESSION['VF-idVisiteur'] && $verifyIntegrity['mois'] == $_SESSION['VF-mois'] && substr($verifyIntegrity['libelle'], 0, 9) == 'REFUSE : ') {
                $pdo->accepterFrais($idFrais);
                echo "<script>alert('Le frais a été accepté !')</script>";
            }
        }
        
        $ficheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);
        $numAnnee = substr($mois, 0, 4);
        $numMois = substr($mois, 4, 2);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
        require 'vues/v_listeFraisForfait.php';
        require 'vues/v_listeFraisHorsForfait.php';
        require 'vues/v_validerFrais.php';
    } else {
        header('Location: index.php?uc=validerFrais&action=selectionnerFiche');
    }
    break;
case 'validerFiche':
    if (isset($_SESSION['VF-idVisiteur']) && isset($_SESSION['VF-mois'])) {
        $pdo->majEtatFicheFrais($_SESSION['VF-idVisiteur'], $_SESSION['VF-mois'], "VA");
        header('Location: index.php?uc=validerFrais&action=selectionnerFiche');
    } else {
        header('Location: index.php?uc=validerFrais&action=selectionnerFiche');
    }
    break;
case 'reporterFrais':
    $idVisiteur = $_SESSION['VF-idVisiteur'];
    $mois = $_SESSION['VF-mois'];
    $moisSuivant = setMoisSuivant($mois);
    
    $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
    $fraisHorsForfaitAReporter = $pdo->getIdFraisHorsForfait($idFrais);
    
    //Créer une nouvelle fiche pour le mois suivant.
    $pdo->creeNouvellesLignesFrais($idVisiteur, $moisSuivant);
    
    //Dupliquer le frais sur la nouvelle fiche du mois suivant
    if (nbErreurs() != 0) {
        include 'vues/v_erreurs.php';
    } else {
        $pdo->creeNouveauFraisHorsForfait(
            $idVisiteur,
            $moisSuivant,
            $fraisHorsForfaitAReporter['libelle'],
            setDateFrancaise($fraisHorsForfaitAReporter['date']),
            $fraisHorsForfaitAReporter['montant']
        );
    }

    //détruire ce fraishorsforfait
    $pdo->effacerFrais($idFrais);
    
    //Affichage
    $identity = $_SESSION['VF-identity'];
    $numAnnee = substr($mois, 0, 4);
    $numMois = substr($mois, 4, 2);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
    require 'vues/v_listeFraisForfait.php';
    require 'vues/v_listeFraisHorsForfait.php';
    require 'vues/v_validerFrais.php';
    break;
default:
    $visiteurs = $pdo->getVisiteursFichesEnAttentes();
    require 'vues/v_listeVisiteur.php';
    break;
}

function setMoisSuivant($mois) {
    $numAnnee = intval(substr($mois, 0, 4));
    $numMois = intval(substr($mois, 4, 2));
    
    $moisSuivant = $numMois + 1;
    if ($moisSuivant > 12) {
        $numAnnee = $numAnnee + 1;
        $moisSuivant = '01';
    }
    
    $moisSuivant = strval($numAnnee) . strval($moisSuivant);
    return $moisSuivant;
}

function setDateFrancaise($maDate) {
    @list($annee, $mois, $jour) = explode('-', $maDate);
    return date('d/m/Y', mktime(0, 0, 0, $mois, $jour, $annee));
}
