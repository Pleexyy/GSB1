<!-- Division pour le sommaire -->
<div id="menuGauche">
   <div id="infosUtil">
   </div>
   <ul id="menuList">
      <li>
         <?php echo $_SESSION['role']; ?>
         <br>
         <?php echo $_SESSION['prenom'] . "  " . $_SESSION['nom'] ?>
      </li>
      <?php
      if ($_SESSION['role'] == "comptable") { ?>
         <li class="">
            <a title="Frais">Frais</a>
            <ul>
               <li>
                  <a href="index.php?uc=consulterComptable&action=suivreFiche" title="Saisie fiche de frais ">Suivre</a>
               </li>
               <li>
                  <a href="index.php?uc=consulterComptable&action=saisirUtilisateur" title="Consultation de mes fiches de frais">Consulter</a>
               </li>
            </ul>
         </li>
      <?php } else { ?>
         <li class="smenu">
            <a href="index.php?uc=gererFrais&action=saisirFrais" title="Saisie fiche de frais ">Saisie fiche de frais</a>
         </li>
         <li class="smenu">
            <a href="index.php?uc=etatFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
         </li>
      <?php } ?>
      <li class="smenu">
         <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
      </li>
   </ul>
</div>