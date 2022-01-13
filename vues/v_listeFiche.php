
<?php 
if ($uc == 'validerFrais') {
    ?><h2>Valider Fiche de Frais</h2><?php
} else if ($uc == 'etatPayement') {
    ?><h2>Etat Payement des Fiches</h2><?php
}
?>      

<div class="row">
    <?php
    if ($fiches != null) {
        ?>
        <div class="col-md-4">
            <h3>Sélectionner une fiche : </h3>
        </div>
        <div class="col-md-4">
            <form action=
            <?php 
                if ($uc == 'validerFrais') {
                    ?>"index.php?uc=validerFrais&action=changerEtats"<?php
                } else if ($uc == 'etatPayement') {
                    ?>"index.php?uc=etatPayement&action=etatFiche"<?php
                }
            ?> method="post" role="form">
                <div class="form-group">
                    <label for="lstFiche" accesskey="n">Fiches : </label>
                    <select id="lstFiches" name="lstFiches" class="form-control">
                        <?php
                        foreach ($fiches as $uneFiche) {
                            $numAnnee = substr($uneFiche['mois'], 0, 4);
                            $numMois = substr($uneFiche['mois'], 4, 2);
                            echo '<option selected value="' . $uneFiche['id'] . '&' . $numAnnee . $numMois . '">' . $uneFiche['nom'] . ' ' . $uneFiche['prenom'] . ' - ' . $numMois . '/' . $numAnnee . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <input id="ok" type="submit" value="Valider" class="btn btn-success"
                       role="button">
            </form>
        </div>
    <?php
    } else {
         echo '<p class="col-md-4">Aucune fiche validées</p>';
    }
    ?>
    
</div>