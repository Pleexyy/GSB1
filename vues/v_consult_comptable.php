<div id="contenu">
    <h2 class="padding-titre">Gestion des Frais</h2>
    <form method="POST" action="index.php?uc=consulterComptable&action=validationFrais">
        <div class="form-group">
            <h1 style="text-align: center;">Suivi de remboursement des frais</h1>
            <fieldset style="text-align:center;">
                <legend style="text-align: center;">Veuillez sélectionner un visiteur et la date du forfait concerné</legend>
                <select class="form-control" name="value" size="1" id="select-size2">
                    <?php
                    foreach ($infos as $lesInfos) {
                        $id = $lesInfos['id'];
                        $nom = $lesInfos['nom'];
                        $datemodif = $lesInfos['datemodif'];
                    ?>
                        <option value="<?php echo $lesInfos[0]; ?>"><?php echo $id . " " . $nom . " " . $datemodif; ?></option>
                    <?php } ?>
                    <input type="submit" value="Valider" class="btn btn-primary" id="esp-valider">
                </select>
            </fieldset>
        </div>
    </form>
</div>