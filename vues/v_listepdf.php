<?php
ob_get_clean(); // vide le tampon de sortie
$pdf = new FPDF();
$pdf->AddPage();
$pdf->Image('images/logo.png', 10, 10);
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(180, 50, 'Mes fiches de frais!', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(51, 133, 255);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 10, 'Visiteur ' . $nom['prenom'] . " " . $nom['nom'], 0, 1, 'L');
$pdf->Cell(0, 30, 'Fiche de frais du mois ' . $numMois . "/" . $numAnnee, 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 0, 'Etat : ' . utf8_decode($libEtat) . " depuis le " . $datemodif, 0, 1, 'L');
$pdf->Cell(0, 15, utf8_decode('Montant validé : ') . $montantValide, 0, 1, 'L');
$pdf->Cell(0, 30, utf8_decode('Eléments forfaitisés'), 0, 1, 'L');

foreach ($lesFraisForfait as $unFraisForfait) {
    $largeur = 37;
    $libelle = $unFraisForfait['libelle'];
    $pdf->SetFillColor(150, 180, 230);
    if ($libelle == "Puissance véhicule") {
        $pdf->Cell(40, 5, utf8_decode("Frais Kilométriques"), 1, 0, 'C', 1);
    } else if ($libelle == "Repas Restaurant") {
        $pdf->Cell(37, 5, utf8_decode($libelle), 1, 1, 'C', 1);
    } else {
        $pdf->Cell(37, 5, utf8_decode($libelle), 1, 0, 'C', 1);
    }
}
foreach ($lesFraisForfait as $unFraisForfait) {
    if ($unFraisForfait['libelle'] == "Kilomètres") {
        $quantite = $unFraisForfait['quantite'];
        $provi = $quantite;
    } else if ($unFraisForfait['libelle'] == "Puissance véhicule") {
        $quantite = $provi * $unFraisForfait['quantite'];
        $largeur = 40;
    } else {
        $quantite = $unFraisForfait['quantite'];
        $largeur = 37;
    }
    $pdf->Cell($largeur, 5, $quantite, 1, 0, 'L', 0);
}

$pdf->ln(15);
$pdf->Cell(0, 15, utf8_decode('Descriptif des éléments hors forfait -') . $nbJustificatifs . utf8_decode(" justificatifs reçus -"), 0, 1, 'L');
$pdf->Cell(37, 5, "date", 1, 0, 'C', 1);
$pdf->Cell(37, 5, "libelle", 1, 0, 'C', 1);
$pdf->Cell(37, 5, "montant", 1, 1, 'C', 1);

foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
    $date = $unFraisHorsForfait['date'];
    $libelle = $unFraisHorsForfait['libelle'];
    $montant = $unFraisHorsForfait['montant'];

    $pdf->SetFillColor(255, 255, 255);
    $pdf->Cell(37, 5, $date, 1, 0, 'C', 1);
    $pdf->Cell(37, 5, $libelle, 1, 0, 'C', 1);
    $pdf->Cell(37, 5, $montant, 1, 1, 'C', 1);
}

$pdf->Output();
