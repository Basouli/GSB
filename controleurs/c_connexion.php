<?php
/**
 * Gestion de la connexion
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if (!$uc) {
    $uc = 'demandeconnexion';
}

switch ($action) {
case 'demandeConnexion':
    include 'vues/v_connexion.php';
    break;
case 'verifyConnexion':
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING);
    //Récupération visiteur avec login saisit
    $visiteur = $pdo->getInfosUtilisateur($login);
    //Vérification que le mot de passe de l'utilisateur corresponde avec celui saisit
    if(password_verify($mdp, $pdo->getMdpUtilisateur($login))) {
        //Stockage du login en variable de session pour être réutilisé après l'identification double facteur
        $_SESSION["login"] = $login;
        
        //Création d'un code aléatoire et stockage de celui-ci en variable de session
        $code = rand(1000, 9999);
        $_SESSION["mailCode"] = $code;
        //Envois mail
        mail(
            $visiteur['email'],
            "code",
            "Code de connexion : " . $code . "."
        );
        include 'vues/v_mailVerification.php';
    } else {
        ajouterErreur('Login ou mot de passe incorrect');
        include 'vues/v_erreurs.php';
        include 'vues/v_connexion.php';
    }
    break;
case 'validerConnexion':
    //Vérification code entré avec code envoyé
    $code = filter_input(INPUT_POST, 'inputCode', FILTER_SANITIZE_STRING);
    if ($code == $_SESSION["mailCode"]) {
        $visiteur = $pdo->getInfosUtilisateur($_SESSION["login"]);
        connecter($visiteur['id'], $visiteur['nom'], $visiteur['prenom'], $visiteur['profil']);
        //Supression des variables dessession inutiles
        unset($_SESSION["login"]);
        unset($_SESSION["mailCode"]);
        //Redirection vers l'acceuil, après l'authentification à double facteur
        header('Location: index.php');
    } else {
        ajouterErreur('Code de vérification incorrect');
        include 'vues/v_erreurs.php';
        include 'vues/v_mailVerification.php';
    }
    break;
default:
    include 'vues/v_connexion.php';
    break;
}
