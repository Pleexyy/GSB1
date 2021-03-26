<div id="contenu">
    <h2>Suivi des Frais</h2>
    <div class="form-group">
        <form method="POST" action="index.php?uc=consulterComptable&action=voirPdf" target="_blank">
            <h1 class="center">Liste des fiches de l'utilisateur</h1>
            <fieldset class="center">
                <?php
                foreach ($lesInfos as $lesInfos) {
                    $id = $lesInfos['id'];
                    $nom = $lesInfos['nom'];
                    $date = $lesInfos['datemodif'];
                ?>
                    <input type="hidden" name="infoDate" value="<?php echo $date; ?>">
                    <input type="hidden" name="infoId" value="<?php echo $id; ?>">
                    <input type="submit" class="btn btn-light" value="<?php echo $id . " " . $nom . " " . $date; ?>"></input>
                <?php } ?>
            </fieldset>
        </form>
    </div>
</div>