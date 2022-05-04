<?php
/**
 * Vue Liste des frais au forfait
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
<div class="row">
    <?php
    if ($uc == 'gererFrais') {
        echo '<h2>Renseigner ma fiche de frais du mois '
        . $numMois . '-' . $numAnnee .
        '</h2>';
    } else if ($uc == 'validerFrais') {
        echo '<h2>Valider fiche de frais du mois '
        . $numMois . '-' . $numAnnee . ' du visiteur <u>' . $identity .
        '</u></h2>';
    }
    ?>
    <hr>
    <h3>Eléments forfaitisés</h3>

    <!-- AFFICHAGE DU FRAIS EN COURS -->
    <div class="panel panel-info">
        <div class="panel-heading">Eléments forfaitisés</div>
        <table class="table table-bordered table-responsive">
            <tr>
                <?php
                foreach ($lesFraisForfait as $unFraisForfait) {
                    $libelle = $unFraisForfait['libelle'];
                    ?>
                    <th> <?php echo htmlspecialchars($libelle) ?></th>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <?php
                foreach ($lesFraisForfait as $unFraisForfait) {
                    $quantite = $unFraisForfait['quantite'];
                    ?>
                    <td class="qteForfait"><?php echo $quantite ?> </td>
                    <?php
                }
                ?>
            </tr>
        </table>
    </div>

    <!-- INPUT FRAIS FORFAIT -->
    <div class="col-md-4">
        <form method="post" 
              action="index.php?<?php if ($uc == 'gererFrais') {
                    echo 'uc=gererFrais&action=validerMajFraisForfait';
                } else if ($uc == 'validerFrais') {
                    echo 'uc=validerFrais&action=validerMajFraisForfait&idFrais=3';
                } ?>"
              role="form">
            <fieldset>       
<?php
foreach ($lesFraisForfait as $unFrais) {
    $idFrais = $unFrais['idfrais'];
    $libelle = htmlspecialchars($unFrais['libelle']);
    $quantite = $unFrais['quantite'];
    ?>
                    <div class="form-group">
                        <label for="idFrais"><?php echo $libelle ?></label>
                        <input type="text" id="idFrais" 
                               name="lesFrais[<?php echo $idFrais ?>]"
                               size="10" maxlength="5" 
                               value="<?php echo $quantite ?>" 
                               class="form-control">
                    </div>
    <?php
}
?>
                <button class="btn btn-success" type="submit">Modifier</button>
                <button class="btn btn-danger" type="reset">Effacer</button>
            </fieldset>
        </form>
    </div>
</div>
