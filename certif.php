<?php
//============================================================+
// File name   : example_051.php
// Begin       : 2009-04-16
// Last Update : 2013-05-14
//
// Description : Example 051 for TCPDF class
//               Full page background
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Full page background
 * @author Nicola Asuni
 * @since 2009-04-16
 */
 
 /**
 * Emissão de certificados
 * @package com.tecnick.tcpdf
 * @abstract Implementação de script básico para emissão de certificados em PDF para I Conferência Municipal de Saúde Mental de Osório e Tramandaí, Prefeitura Municipal de Osório, Secretaria Municipal de Osório, CAPS Casa Aberta
 * @author Tiago Floriano <tiago.floriano@acad.ufsm.br>
 * @since 2022-03-14
 */
 
//recebe do sistema externo as variáveis nome, emit (que informa que está sendo emitido algum certificado), e letras de "a" à "g" informando o tipo de certificado
if($_GET["emit"] != 1){
    exit;
}

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/examples/tcpdf_include.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        //$img_file = K_PATH_IMAGES.'image_demo.jpg';
        $img_file = "certif.jpg";
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tiago Floriano');
$pdf->SetTitle('Certificado');
$pdf->SetSubject('I Conferencia Municipal de Saude Mental de Osorio e Tramandai');
$pdf->SetKeywords('certificado, saude mental, caps');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

//captura dados da url
$dados = parse_url($_SERVER['REQUEST_URI']);
//separa variáveis
$vars = explode("&",$dados['query']);
//separa o nome do participante
for($i=0;$vars[$i]!="";$i++){
    $vars2 = explode("=",$vars[$i]);
    if($vars2[0] == "nome"){
        $nome = urldecode($vars2[1]);
    }
}
//gera as páginas dos certificados
for($i=0;$vars[$i]!="";$i++){
    $vars2 = explode("=",$vars[$i]);
    if($vars2[0] != "emit" && $vars2[0] != "nome"){
        if($vars2[0] == "a"){ $img_file = "participacao"; }
        if($vars2[0] == "b"){ $img_file = "organizacao"; }
        if($vars2[0] == "c"){ $img_file = "delegado"; }
        if($vars2[0] == "d"){ $img_file = "palestrante"; }
        if($vars2[0] == "e"){ $img_file = "coordgrupo"; }
        if($vars2[0] == "f"){ $img_file = "relator"; }
        if($vars2[0] == "g"){ $img_file = "convidado"; }
        // set font
        $pdf->SetFont('helveticaB', '', 14);
        // add a page
        $pdf->AddPage('L', 'A4');
        // -- set new background ---
        // get the current page break margin
        $bMargin = $pdf->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $pdf->getAutoPageBreak();
        // disable auto-page-break
        $pdf->SetAutoPageBreak(false, 0);
        // set bacground image
        #$img_file = K_PATH_IMAGES.'image_demo.jpg';
        $img_file = "images/cert{$img_file}.jpeg";
        $pdf->Image($img_file, 0, 0, 297, 210, '', '', '', false, 300, '', false, false, 0);
        // Print a text
        $html = '<div style="color: #000;"><br><br><br><br><br><br><br><br><br><br><br><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nome.'</div>';
        $pdf->writeHTML($html, true, false, true, false, '');
    }
}

// restore auto-page-break status
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);
// set the starting point for the page content
$pdf->setPageMark();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('certificado.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
exit;