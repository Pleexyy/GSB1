 <div id="contenu">
   <h2 class="padding-titre">Mes fiches de frais</h2>
   <h3>Mois à sélectionner : </h3>
   <form action="index.php?uc=etatFrais&action=voirEtatFrais" method="post">
     <div class="form-group">
       <p>
         <label for="lstMois" accesskey="n">Mois : </label>
         <select id="lstMois" class="form-control" name="lstMois">
           <?php
            foreach ($lesMois as $unMois) {
              $mois = $unMois['mois'];
              $numAnnee =  $unMois['numAnnee'];
              $numMois =  $unMois['numMois'];
              if ($mois == $moisASelectionner) {
            ?>
               <option selected value="<?php echo $mois ?>"><?php echo  $numMois . "/" . $numAnnee ?> </option>
             <?php
              } else { ?>
               <option value="<?php echo $mois ?>"><?php echo  $numMois . "/" . $numAnnee ?> </option>
           <?php
              }
            }
            ?>
         </select>
       </p>
     </div>
     <div class="piedForm">
       <p>
         <input id="ok" class="btn btn-primary" type="submit" value="Valider" size="20" />
       </p>
     </div>
   </form>