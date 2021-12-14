<h2>Valider Fiche de Frais</h2>
<div class="row">
    <?php
    if ($visiteurs != null) {
        echo '<div class="col-md-4">
            <h3>Sélectionner un visiteur : </h3>
        </div>
        <div class="col-md-4">
            <form action="index.php?uc=validerFrais&action=changerEtats"
                  method="post" role="form">
                <div class="form-group">
                    <label for="lstVisiteurs" accesskey="n">Visiteurs : </label>
                    <select id="lstVisiteurs" name="lstVisiteurs" class="form-control">';
                        foreach ($visiteurs as $unVisiteur) {
                            $numAnnee = substr($unVisiteur['mois'], 0, 4);
                            $numMois = substr($unVisiteur['mois'], 4, 2);
    
                            echo '<option selected value="' .  $unVisiteur['id'] . '&' . $unVisiteur['mois'] . '"> ' . $unVisiteur['prenom'] . ' ' . $unVisiteur['nom'] . ' - ' . $numMois . '/' . $numAnnee . '</option>';
                        }
                    echo '</select>
                </div>
                <input id="ok" type="submit" value="Valider" class="btn btn-success" role="button">
            </form>
        </div>';
    } else {
        echo '<p class="col-md-4">Aucune fiche en attente de validation</p>';
    }
    ?>
</div>