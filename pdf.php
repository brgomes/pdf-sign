<?php

/**
Para converter o certificado .pfx em arquivo .crt

openssl pkcs12 -in PFX_FILE.pfx -out CERTIFICATE.crt -nodes

O comando acima precisa da senha do certificado.
**/

ini_set('display_errors', 1);

date_default_timezone_set('America/Araguaina');
setlocale(LC_CTYPE, 'pt_BR');

// set files
define('CERTIFICATE', 'CERTIFICATE.crt');
define('SOURCE_PDF_FILE', 'source.pdf');
define('OUTPUT_PDF_NAME', 'output.pdf');

require 'vendor/autoload.php';
require 'PDFSign.php';

$output     = __DIR__ . '/' . OUTPUT_PDF_NAME;
$source     = __DIR__ . '/' . SOURCE_PDF_FILE;
$command    = __DIR__ . '/gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile="' . $output . '" "' . $source . '"';

exec($command);

$pdf = new PDFSign('P', 'mm', 'A4');
$pages = $pdf->setSourceFile(__DIR__ . '/' . OUTPUT_PDF_NAME);
$pdf->totalPages = $pages;

for ($i=1; $i<=$pages; $i++) {
    $pdf->AddPage();
    $page = $pdf->importPage($i);
    $pdf->useTemplate($page, 0, 0);

    $pdf->currentPage = $i;
}

$pdf->Output(OUTPUT_PDF_NAME, 'D');
