<table class="table">
  <caption>Descriptif des éléments hors forfait
  </caption>
  <thead>
    <tr>
      <th scope="col" class="date">Date</th>
      <th scope="col" class="libelle">Libellé</th>
      <th scope="col" class="montant">Montant</th>
      <th scope="col" class="action">Supprimer</th>
    </tr>
  </thead>

  <?php
  foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
    $libelle = $unFraisHorsForfait['libelle'];
    $date = $unFraisHorsForfait['date'];
    $montant = $unFraisHorsForfait['montant'];
    $id = $unFraisHorsForfait['id'];
  ?>
    <tr>
      <th scope="row"><?php echo $date ?></td>
      <td><?php echo $libelle ?></td>
      <td><?php echo $montant ?></td>

      <form method="POST" action="index.php?uc=gererFrais&action=supprimerLeFrais">
        <input type="hidden" value=" <?php echo $id ?>" name="id" />
        <td><input type="submit" value="supprimer" /></td>
      </form>
    </tr>
  <?php
  }
  ?>

</table>
<form action="index.php?uc=gererFrais&action=validerCreationFrais" method="post">
  <div class="form-group">

    <legend class="center"><b>Nouvel élément hors forfait</b></legend>
    <p>
      <label for="txtDateHF">Date (jj/mm/aaaa): </label>
      <input type="text" class="form-control" id="txtDateHF" name="dateFrais" size="10" maxlength="10" value="" />
    </p>
    <p>
      <label for="txtLibelleHF">Libellé</label>
      <input type="text" class="form-control" id="txtLibelleHF" name="libelle" size="70" maxlength="256" value="" />
    </p>
    <p>
      <label for="txtMontantHF">Montant : </label>
      <input type="text" class="form-control" id="txtMontantHF" name="montant" size="10" maxlength="10" value="" />
    </p>
  </div>
  <div class="piedForm">
    <p>
      <input id="ajouter" class="btn btn-primary" type="submit" value="Ajouter" size="20" />
      <input id="effacer" class="btn btn-secondary" type="reset" value="Effacer" size="20" />
    </p>
  </div>

</form>
</div>