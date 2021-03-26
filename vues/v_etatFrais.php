<h3>Fiche de frais du mois <?php echo $numMois . "-" . $numAnnee ?> :
</h3>
<div>
  <p>
    Etat : <?php echo $libEtat ?> depuis le <?php echo $dateModif ?> <br> Montant validé : <?php echo $montantValide ?>
  </p>
  <table class="table">
    <caption>Eléments forfaitisés </caption>
    <thead>
      <tr>
        <?php
        foreach ($lesFraisForfait as $unFraisForfait) {
          $libelle = $unFraisForfait['libelle'];
          if ($libelle == "Puissance véhicule") {
        ?>
            <th scope="col"> <?php echo "Frais Kilométriques" ?></th>
          <?php } else { ?>
            <th> <?php echo $libelle ?></th>
        <?php
          }
        }
        ?>
      </tr>
    </thead>
    <tr>
      <?php
      foreach ($lesFraisForfait as $unFraisForfait) {
        if ($unFraisForfait['libelle'] == "Kilomètres") {
          $quantite = $unFraisForfait['quantite'];
          $provi = $quantite;
        } else if ($unFraisForfait['libelle'] == "Puissance véhicule") {
          $quantite = $provi * $unFraisForfait['quantite'];
        } else {
          $quantite = $unFraisForfait['quantite'];
        }
      ?>
        <td class="qteForfait"><?php echo $quantite ?> </td>
      <?php
      }
      ?>
    </tr>
  </table>
  <table class="table">
    <caption>Descriptif des éléments hors forfait - <?php echo $nbJustificatifs ?> justificatifs reçus -
    </caption>
    <thead>
      <tr>
        <th scope="col" class="date">Date</th>
        <th scope="col" class="libelle">Libellé</th>
        <th scope="col" class='montant'>Montant</th>
      </tr>
    </thead>
    <?php
    foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
      $date = $unFraisHorsForfait['date'];
      $libelle = $unFraisHorsForfait['libelle'];
      $montant = $unFraisHorsForfait['montant'];
    ?>
      <tr>
        <td><?php echo $date ?></td>
        <td><?php echo $libelle ?></td>
        <td><?php echo $montant ?></td>
      </tr>
    <?php
    }
    ?>
  </table>
  <form action="pdf.php" target="_blank">
</div>
<input class="btn btn-primary" type="submit" value="Imprimer" size="20" />
</div>
</form>