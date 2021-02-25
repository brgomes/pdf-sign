<?php

// @codingStandardsIgnoreFile
use setasign\Fpdi\Tcpdf\Fpdi;

class PDFSign extends Fpdi
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
