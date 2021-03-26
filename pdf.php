<?php
require_once("include/fct.inc.php");
require_once("include/class.pdogsb.inc.php");

session_start();
$pdo = PdoGsb::getPdoGsb();


require('fpdf/fpdf.php');
include("controleurs/c_pdf.php");
