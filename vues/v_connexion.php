<div id="contenu">
      <h2>Identification utilisateur</h2>
      <form method="POST" action="index.php?uc=connexion&action=valideConnexion">
            <div class="form-group">
                  <p>
                        <label for="nom">Login*</label>
                        <input id="login" type="text" name="login" size="30" maxlength="45" class="form-control" placeholder="Identifiant de connexion">
                  </p>
                  <small id="emailHelp" class="form-text text-muted">Nous ne partagerons jamais vos données personnelles.</small>

            </div>
            <div class="form-group">
                  <p>
                        <label for="mdp">Mot de passe*</label>
                        <input id="mdp" type="password" name="mdp" size="30" maxlength="45" class="form-control" placeholder="Mot de passe">
                  </p>
            </div>
            <input type="submit" class="btn btn-primary" value="Valider" name="valider">
            <input type="reset" class="btn btn-secondary" value="Annuler" name="annuler">
            </p>
      </form>
</div>