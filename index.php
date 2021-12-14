<?php
/**
 * Index du projet GSB
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

require_once 'includes/fct.inc.php';
require_once 'includes/class.pdogsb.inc.php';
session_start();
$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();
require 'vues/v_entete.php';
$uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
if ($uc && !$estConnecte && $uc != "mailVerification") {
    $uc = 'connexion';
} elseif (empty($uc)) {
    $uc = 'accueil';
}

verifySession($uc);

switch ($uc) {
case 'connexion':
    include 'controleurs/c_connexion.php';
    break;
case 'accueil':
    include 'controleurs/c_accueil.php';
    break;
case 'gererFrais':
    include 'controleurs/c_gererFrais.php';
    break;
case 'etatFrais':
    include 'controleurs/c_etatFrais.php';
    break;
case 'validerFrais':
    include 'controleurs/c_validerFrais.php';
    break;
case 'etatPayement':
    include 'controleurs/c_etatPayement.php';
    break;
case 'deconnexion':
    include 'controleurs/c_deconnexion.php';
    break;
}
require 'vues/v_pied.php';


function verifySession($uc) {
    if ($uc != 'validerFrais') {
        if (isset($_SESSION['VF-visiteur'])) {
            unset($_SESSION['VF-visiteur']);
        }
        if (isset($_SESSION['VF-mois'])) {
            unset($_SESSION['VF-mois']);
        }
        if (isset($_SESSION['VF-identity'])) {
            unset($_SESSION['VF-identity']);
        }
    }
    if ($uc != 'etatPayement') {
        if (isset($_SESSION['VP-visiteur'])) {
            unset($_SESSION['VP-visiteur']);
        }
        if (isset($_SESSION['VP-mois'])) {
            unset($_SESSION['VP-mois']);
        }
    }
}