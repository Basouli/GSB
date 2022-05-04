<?php
/**
 * Vue Entête
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @author    Killian Martin  <killian8342@gmail.com> 
 * @author    Basil Collette <basil.collette@outlook.fr> * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="UTF-8">
        <title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title> 
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./styles/bootstrap/bootstrap.css" rel="stylesheet">
        <link href="./styles/style.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <?php
            $uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
            if ($estConnecte) {
                ?>
                <div class="header">
                    <div class="row vertical-align">
                        <div class="col-md-4">
                            <h1>
                                <img src="./images/logo.jpg" class="img-responsive" 
                                     alt="Laboratoire Galaxy-Swiss Bourdin" 
                                     title="Laboratoire Galaxy-Swiss Bourdin">
                            </h1>
                        </div>
                        <div class="col-md-8">
                            <ul class="nav nav-pills pull-right" role="tablist">

                                <li <?php if (!$uc || $uc == 'accueil') { ?>class="active" <?php } ?>>
                                    <a href="index.php">
                                        <span class="glyphicon glyphicon-home"></span>
                                        Accueil
                                    </a>
                                </li>

                                <?php
                                //COMPTABLE
                                if ($_SESSION['profil'] == "comptable") {
                                    echo '<li ';
                                    if ($uc == 'validerFrais') {
                                        echo 'class="active"';
                                    }
                                    echo '><a href="index.php?uc=validerFrais&action=selectionnerFiche">
                                            <span class="glyphicon glyphicon-ok"></span>
                                            Valider fiche de frais
                                        </a>
                                    </li>
                            
                                <li ';
                                    if ($uc == 'etatPayement') {
                                        echo 'class="active"';
                                    }
                                    echo '><a href="index.php?uc=etatPayement&action=selectionnerFiche">
                                            <span class="glyphicon glyphicon-list-alt"></span>
                                            Suivre le payement des fiches
                                        </a>
                                    </li>';

                                    //VISITEUR
                                } else if ($_SESSION['profil'] == "visiteur") {
                                    echo '<li ';
                                    if ($uc == 'gererFrais') {
                                        echo 'class="active"';
                                    }
                                    echo '><a href="index.php?uc=gererFrais&action=saisirFrais">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                            Renseigner la fiche de frais
                                        </a>
                                    </li>
                            
                                <li ';
                                    if ($uc == 'etatFrais') {
                                        echo 'class="active"';
                                    }
                                    echo '><a href="index.php?uc=etatFrais&action=selectionnerMois">
                                            <span class="glyphicon glyphicon-list-alt"></span>
                                            Afficher mes fiches de frais
                                        </a>
                                    </li>';
                                }
                                ?>

                                <li 
                                    <?php if ($uc == 'deconnexion') { ?>class="active"<?php } ?>>
                                    <a href="index.php?uc=deconnexion&action=demandeDeconnexion">
                                        <span class="glyphicon glyphicon-log-out"></span>
                                        Déconnexion
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>   
                <h1>
                    <img src="./images/logo.jpg"
                         class="img-responsive center-block"
                         alt="Laboratoire Galaxy-Swiss Bourdin"
                         title="Laboratoire Galaxy-Swiss Bourdin">
                </h1>
                <?php
            }
