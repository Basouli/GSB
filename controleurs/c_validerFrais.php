<?php
if ($_SESSION['profil'] != "comptable") {
    header('Location: index.php');
}

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
switch ($action) {
case 'selectionnerFiche':
    $fiches = $pdo->getVisiteursFichesEnAttentes();
    require 'vues/v_listeFiche.php';
    break;
case 'changerEtats': 
    $values = explode('&', filter_input(INPUT_POST, 'lstFiches', FILTER_SANITIZE_STRING));
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
            if ($verifyIntegrity['idVisiteur'] == $_SESSION['VF-idVisiteur'] && $verifyIntegrity['mois'] == $_SESSION['VF-mois'] && substr($verifyIntegrity['libelle'], 0, 9) != 'REFUSE : ') {
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
            if ($verifyIntegrity['idVisiteur'] == $_SESSION['VF-idVisiteur'] && $verifyIntegrity['mois'] == $_SESSION['VF-mois'] && substr($verifyIntegrity['libelle'], 0, 9) == 'REFUSE : ') {
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
        $pdo->setMontantValide($_SESSION['VF-idVisiteur'], $_SESSION['VF-mois']);
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
    
    //Si le mois suivant ne comporte pas de fiche, on la créé.
    $dernierMois = $pdo->dernierMoisSaisi($idVisiteur);
    if ($moisSuivant != $dernierMois) {
        $pdo->creeNouvellesLignesFrais($idVisiteur, $moisSuivant);
    }
    //Ajout du frais hors forfait à la fiche du mois suivant
    $pdo->creeNouveauFraisHorsForfait(
        $idVisiteur,
        $moisSuivant,
        $fraisHorsForfaitAReporter['libelle'],
        dateAnglaisVersFrancais($fraisHorsForfaitAReporter['date']),
        $fraisHorsForfaitAReporter['montant']
    );
    
    //détruire le fraishorsforfait du mois en cours
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
    $fiches = $pdo->getVisiteursFichesEnAttentes();
    require 'vues/v_listeFiche.php';
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
