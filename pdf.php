<?php

/**
Para converter o certificado .pfx em arquivos .pem público e privado

CERTIFICADO .pfx PARA ARQUIVO .pem PRIVADO:
openssl pkcs12 -in filename.pfx -nocerts -out PRIVATE_KEY.pem

CERTIFICADO .pfx PARA ARQUIVO .pem PÚBLICO:
openssl pkcs12 -in filename.pfx -clcerts -nokeys -out PUBLIC_CERT.pem

Ambos os comandos acima precisam da senha do certificado.

Ao converter para arquivo .pem PRIVADO, é necessário informar uma senha,
que é usada no código abaixo através da constante CERT_PASSWORD.

Para usar o código abaixo em produção basta o NTI gerar os arquivos
.pem público e privado do certificado e colocar no servidor. O usuário
que tiver permissão para gerar documentos assinados poderá fazê-lo
independente de ter chave ou não, pois o certificado já estará
guardado no servidor.
**/

ini_set('display_errors', 1);

date_default_timezone_set('America/Araguaina');
setlocale(LC_CTYPE, 'pt_BR');

// set certificate file
define('CERT_PASSWORD', 'secret');
define('CERT_PUBLIC', 'PUBLIC_CERT.pem');
define('CERT_PRIVATE', 'PRIVATE_KEY.pem');
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
