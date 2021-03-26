<div id="contenu">
    <form method="POST" action="index.php?uc=consulterComptable&action=validerEtat">
        <fieldset class="center">
            <h2><?php echo "Etat de la fiche : " . $etat[0]; ?></h2>
            <legend style="text-align: center;">Validation des frais</legend>
            <table style="width:60%; margin-left:20%; margin-right:20%;">
                <caption class="center">Frais au forfait</caption>
                <tr>
                    <th scope="col" class="center">Repas midi</th>
                    <th scope="col" class="center">Nuitée</th>
                    <th scope="col" class="center">Etape</th>
                    <th scope="col" class="center">Km</th>
                    <th scope="col" class="center">Situation</th>
                </tr>

                <tr class="center">
                    <?php $montantKm = $prixkm['montantKm'] * $lapuissance['montantPuissance']; ?>
                    <td style="width: 25%;"><?php echo $prixrepasmidi['montantRep']; ?></td>
                    <td style="width: 25%;"><?php echo $prixnuitee['montantNuitee']; ?></td>
                    <td style="width: 25%;"><?php echo $prixetape['montantEtape']; ?></td>
                    <td style="width: 25%;"><?php echo $montantKm; ?></td>
                    <td>
                        <select class="form-control" name="choix" multiple size="2">
                            <option value="valider" selected>Validé</option>
                            <option value="refuser">Refusé</option>
                        </select>
                    </td>
                </tr>
            </table>

            <br> <br>
            <table style="width:60%; margin-left:20%; margin-right:20%;">
                <caption class="center">Frais hors forfait</caption>
                <tr class="center">
                    <th scope="col">Date</th>
                    <th scope="col">Libellé</th>
                    <th scope="col">Montant</th>
                    <th scope="col">Situation</th>
                </tr>
                <?php
                $i = 0;
                foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                ?>
                    <tr class="center">
                        <td class="center"><?php echo $unFraisHorsForfait['date'] ?></td>
                        <td class="center"><?php echo $unFraisHorsForfait['libelle']; ?></td>
                        <td class="center"><?php echo $unFraisHorsForfait['montant']; ?></td>
                        <td>
                            <select class="form-control" name="etat<?php echo $i ?>" multiple size="2">
                                <option value="valider" selected>Validé</option>
                                <option value="refuser">Refusé</option>
                            </select>
                        </td>
                    </tr>
                <?php
                    $i += 1;
                }
                $_SESSION['compteur'] = $i;
                ?>
            </table>
            <br>
            <div class="center">
                <input type="submit" class="btn btn-primary" value="Valider la fiche de frais" />
            </div>
        </fieldset>
    </form>
</div>