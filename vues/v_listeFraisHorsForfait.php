<?php
/**
 * Vue Liste des frais hors forfait
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
<hr>
<h3>Eléments non forfaitisés</h3>
<div class="row">
    <div class="panel panel-info">
        <div class="panel-heading">Descriptif des éléments hors forfait</div>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th class="date">Date</th>
                    <th class="libelle">Libellé</th>  
                    <th class="montant">Montant</th>  
                    <th class="action">&nbsp;</th> 
                </tr>
            </thead>  
            <tbody>
            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $libelleColor = "";
                if (!strcmp(substr($libelle, 0, 9), 'REFUSE : ')) {
                    $libelleColor = "bg-danger";
                }
                $date = $unFraisHorsForfait['date'];
                $montant = $unFraisHorsForfait['montant'];
                $id = $unFraisHorsForfait['id'];
                ?>
                <tr>
                    <td class="<?php echo $libelleColor; ?>"><?php echo $date ?></td>
                    <td class="<?php echo $libelleColor; ?>"><?php echo $libelle ?></td>
                    <td class="<?php echo $libelleColor; ?>"><?php echo $montant ?></td>
                    <td class="<?php echo $libelleColor; ?>">
                    <?php 
                    if ($_SESSION['profil'] == "visiteur") {
                        echo '<a class="btn btn-danger" href="index.php?uc=gererFrais&action=supprimerFrais&idFrais=' . $id . '" 
                           onclick="return confirm(\'Voulez-vous vraiment supprimer ce frais?\');">Supprimer ce frais</a>';
                    } else if ($_SESSION['profil'] == "comptable") {
                        //ACCEPTER OU REFUSER SELON SON ETAT
                        if (strcmp(substr($libelle, 0, 9), 'REFUSE : ')) {
                            echo '<a class="btn btn-danger" href="index.php?uc=validerFrais&action=refuserFrais&idFrais=' . $id . '" 
                                onclick="return confirm(\'Voulez-vous vraiment refuser ce frais?\');">Refuser ce frais</a>';
                        } else {
                            echo '<a class="btn btn-success" href="index.php?uc=validerFrais&action=accepterFrais&idFrais=' . $id . '" 
                                onclick="return confirm(\'Voulez-vous vraiment accepter ce frais?\');">Accepter ce frais</a>';
                        }
                        //Reporter frais
                        echo '<a class="btn btn-danger" href="index.php?uc=validerFrais&action=reporterFrais&idFrais=' . $id . '" 
                                onclick="return confirm(\'Voulez-vous vraiment reporter ce frais?\');" style="margin-left:8px;">Reporter</a>';
                    }
                    ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>  
        </table>
    </div>
</div>


<?php
if ($uc == 'gererFrais') {
    //AFFICHE LE FORMULAIRE QUI AJOUTE UN FRAIS HORS FORFAIT
    echo '<div class="row">
        <h3>Nouvel élément hors forfait</h3>
        <div class="col-md-4">
            <form action="index.php?uc=gererFrais&action=validerCreationFrais" 
                  method="post" role="form">
                <div class="form-group">
                    <label for="txtDateHF">Date (jj/mm/aaaa): </label>
                    <input type="text" id="txtDateHF" name="dateFrais" 
                           class="form-control" id="text">
                </div>
                <div class="form-group">
                    <label for="txtLibelleHF">Libellé</label>             
                    <input type="text" id="txtLibelleHF" maxlength="90" name="libelle" 
                           class="form-control" id="text">
                </div> 
                <div class="form-group">
                    <label for="txtMontantHF">Montant : </label>
                    <div class="input-group">
                        <span class="input-group-addon">€</span>
                        <input type="text" id="txtMontantHF" name="montant" 
                               class="form-control" value="">
                    </div>
                </div>
                <button class="btn btn-success" type="submit">Ajouter</button>
                <button class="btn btn-danger" type="reset">Effacer</button>
            </form>
        </div>
    </div>';
}
?>
<hr>
