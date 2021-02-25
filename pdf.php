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

ini_set('display_errors', 0);

date_default_timezone_set('America/Araguaina');
setlocale(LC_CTYPE, 'pt_BR');

// set certificate file
define('CERT_PASSWORD', 'secret');
define('CERT_PUBLIC', 'PUBLIC_CERT.pem');
define('CERT_PRIVATE', 'PRIVATE_KEY.pem');
define('SOURCE_PDF_FILE', 'source.pdf');
define('OUTPUT_PDF_NAME', 'output.pdf');

require 'vendor/autoload.php';

// @codingStandardsIgnoreFile
use setasign\Fpdi\Tcpdf\Fpdi;

class MyPDF extends Fpdi
{
    public $currentPage;
    public $totalPages;

    // Page footer
    public function Footer()
    {
        // Last page
        if ($this->currentPage == $this->totalPages) {
            // Position at 10 mm from bottom
            $this->SetY(-10);
            // Set font
            $this->SetFont('courier', '', 5);
            // Page number
            //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

            $texto = 'Assinado digitalmente por FUNDAÇÃO UNIRG. Data: ' . date('d/m/Y H:i:s') . ' -03:00. Código de autenticação: 1A2B3C4D';
            $this->Cell(0, 10, $texto, 0, false, 'C', 0, '', 0, false, 'T', 'M');

            // set additional information
            $info = array(
                'Location' => 'Núcleo de Tecnologia da Informação - Universidade UnirG',
                'Reason' => 'Documento assinado digitalmente sem interferência humana',
            );

            $certificate = 'file:///' . __DIR__ . '/' . CERT_PUBLIC;
            $signature = 'file:///' . __DIR__ . '/' . CERT_PRIVATE;

            $this->setSignature($certificate, $signature, CERT_PASSWORD, '', 2, $info);
            $this->setSignatureAppearance(10, 290, 190, 4); // X: a partir da coluna 10, largura de 190; Y: a partir da linha 290, altura de 4
        }
    }
}

$output     = __DIR__ . '/' . OUTPUT_PDF_NAME;
$source     = __DIR__ . '/' . SOURCE_PDF_FILE;
$command    = __DIR__ . '/gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile="' . $output . '" "' . $source . '"';
exec($command);

$pdf = new MyPDF('P', 'mm', 'A4');
$pages = $pdf->setSourceFile(__DIR__ . '/' . OUTPUT_PDF_NAME);
$pdf->totalPages = $pages;

for ($i = 1; $i <= $pages; $i++) {
    $pdf->AddPage();
    $page = $pdf->importPage($i);
    $pdf->useTemplate($page, 0, 0);

    $pdf->currentPage = $i;
}

$pdf->Output(OUTPUT_PDF_NAME, 'D');
