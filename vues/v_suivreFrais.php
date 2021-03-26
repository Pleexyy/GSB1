<div id="contenu">
    <h2>Gestion des Frais</h2>
    <form method="POST" action="index.php?uc=consulterComptable&action=listeFiches">
        <div class="form-group">
            <h1 class="center">Suivi des fiches de frais</h1>
            <fieldset class="center">
                <legend class="center">Veuillez sélectionner un visiteur</legend>
                <select class="form-control" name="values" size="1" size="1" id="select-size2">
                    <?php
                    foreach ($fiche as $lesfiches) {
                        $login = $lesfiches['login'];
                    ?>
                        <option value="<?php echo $lesfiches[0]; ?>"><?php echo $login; ?></option>
                    <?php } ?>
                    <input type="submit" value="Valider" class="btn btn-primary" id="esp-valider">
                </select>
            </fieldset>
        </div>
    </form>
</div>