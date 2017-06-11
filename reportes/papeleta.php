<?php
require_once('../vendor/fpdf/fpdf.php');
require_once('../vendor/FPDI/fpdi.php');

class PAPELETA extends FPDI
{
       var $head;

       function setConfig($var,$val)
       {
           $this->{$var} = $val;
       }
       function putHead(){
              $this->SetFont('Courier','',10);
              $this->SetTextColor(0, 0, 0);
              $this->SetXY(86, 9);
              $this->Write(0, $this->head['nombre']);
              $this->SetXY(102, 15);
              $this->Write(0, $this->head['num']);
              $this->SetXY(83, 21);
              $this->Write(0, $this->head['fecha']);
       }
}

$pdf = new PAPELETA($orientation='L', $unit='mm', $size='LETTER');
$pdf->AddPage();
$pdf->setSourceFile("../resources/plantillas_pdf/papeleta.pdf");
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 0, 0, 280, 215);

$pdf->setConfig('head',$head);

$pdf->putHead();

$pdf->Output('../public/tmp/'.$token.'.pdf','I');
