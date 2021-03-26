<div id="contenu">
  <h2 class="padding-titre">Renseigner ma fiche de frais du mois <?php echo $numMois . "-" . $numAnnee ?></h2>

  <form method="POST" action="index.php?uc=gererFrais&action=validerMajFraisForfait">
    <div class="form-group">
      <legend class="center"><b>Eléments forfaitisés</b>
      </legend>
      <?php
      foreach ($lesFraisForfait as $unFrais) {
        $idFrais = $unFrais['idfrais'];
        $libelle = $unFrais['libelle'];
        if ($unFrais['quantite'] > 0) {
          $quantite = $unFrais['quantite'];
        } else {
          $quantite = 0;
        }
      ?>
        <p>
          <label for="idFrais"><?php echo $libelle ?></label>
          <?php
          if ($libelle == "Puissance véhicule") { ?>
            <select class="form-control" name="nom" size="1">
              <?php
              foreach ($puissance as $unePuissance) {
                $laPuissance = $unePuissance['libelle'];
              ?>
                <option value="<?php echo $laPuissance ?>"><?php echo $laPuissance; ?></option>
              <?php } ?>
            </select>

            <?php
            if (isset($_POST['nom'])) {
              $selected_val = $_POST['nom'];
              $toto = $pdo->getMontant($selected_val);
              $quantite = $toto[0];
            }
            ?>
            <input type="hidden" class="form-control" id="idFrais" name="lesFrais[<?php echo $idFrais ?>]" size="10" maxlength="5" value="<?php echo $quantite ?>"><?php
                                                                                                                                                                  } else { ?>
            <input type="text" class="form-control" id="idFrais" name="lesFrais[<?php echo $idFrais ?>]" size="10" maxlength="5" value="<?php echo $quantite ?>">
        </p>
    <?php
      }
    }
    ?>

    </div>
    <div class="piedForm">
      <p>
        <input id="ok" class="btn btn-primary" type="submit" value="Valider" size="20" />
        <input id="annuler" class="btn btn-secondary" type="reset" value="Effacer" size="20" />
      </p>
    </div>

  </form>