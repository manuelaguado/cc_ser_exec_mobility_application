<?php
require_once('../vendor/fpdf/fpdf.php');
require_once('../vendor/FPDI/fpdi.php');
class PAPELETA extends FPDI
{
       var $head;
       var $viajes;
       var $periodo;

       function setConfig($var,$val)
       {
           $this->{$var} = $val;
       }
       function Header(){
              $this->SetFont('Courier','',8);
              $this->SetTextColor(0, 0, 0);
              $this->SetXY(86, 8.9);
              $this->Write(0, $this->head['nombre']);
              $this->SetXY(102, 14.9);
              $this->Write(0, $this->head['num']);
              $this->SetXY(83, 20.6);
              $this->Write(0, $this->head['fecha']);

              $this->SetXY(178, 21);
              $this->SetFont('Helvetica','',10);
              $this->SetTextColor(103,101,100);
              $this->MultiCell(100, 5, utf8_decode($this->periodo), 0, 'C', false);
              $this->SetTextColor(0,0,0);
              $this->SetFont('Courier','',8);
              $this->SetXY(10, 37);
       }
       function Footer(){
              $this->SetY(205);
              $this->Cell(205,10,utf8_decode('Página   ').$this->PageNo().'  /  {nb}',0,0,'R');
       }

       function cargarViajes($array)
       {
           $array = $this->viajes;
           $data = array();
           foreach($array as $viaje){
               $viajes[] = $viaje;
           }
           return $viajes;
       }
       function insertarViaje($viajes)
       {
   	    $this->SetXY(10, 37);
           $num = 0;
           $con = 1;
           $km = 0;
           $adicional = 0;
           $monto = 0;
           $neto = 0;

           $this->SetFillColor(248,244,224);
           $fill = false;
           setlocale(LC_MONETARY, 'es_MX.UTF-8');
           foreach($viajes as $viaje)
           {
   			$this->SetFont('Courier','',8);

                     $this->Cell(32, 5, hash( 'adler32', $viaje['id_viaje'].$viaje['monto'].$viaje['geo_origen'].$viaje['geo_destino'].$viaje['cliente'] ), 0,0, 'C', $fill);
                     $this->Cell(11, 5, $con, 0,0, 'C', $fill);
                     $date = date_create($viaje['fecha_requerimiento']);
                     $fecha = date_format($date,('Y-m-d'));
                     $this->Cell(22, 5, $fecha, 0,0, 'C', $fill);
                     $this->Cell(70, 5, utf8_decode($viaje['empresa']), 0,0, 'L', $fill);
                     $this->Cell(20, 5, $viaje['id_viaje'], 0,0, 'C', $fill);
                     $this->Cell(15, 5, utf8_decode($viaje['tipo']), 0,0, 'C', $fill);
                     $this->Cell(15, 5, $viaje['km_max'], 0,0, 'C', $fill);

                     //Variables del sistema
                     $km_cortesia = Controlador::getConfig(1,'km_cortesia');
                     $km_perimetro = Controlador::getConfig(1,'km_perimetro');

                     //$kmsc = km iniciales que los cubre el perimetro
                     //255 corresponde a un viaje de cortesía

                     $kmsc = ($viaje['cat_tipo_tarifa'] == 255)?$km_cortesia['valor']:$km_perimetro['valor'];

                     $perimetro = ($viaje['km_max'] < $kmsc)?'SI':'NO';

                     $this->Cell(10, 5, $perimetro, 0,0, 'C', $fill);
                     $cat_revision = ($viaje['cat_revision'] == 260)?'SI':'NO';
                     $this->Cell(10, 5, $cat_revision, 0,0, 'C', $fill);

                     $ad_cost = $viaje['ad_cgravamen'] + $viaje['ad_sgravamen'];

                     $this->Cell(20, 5, $ad_cost, 0,0, 'R', $fill);
                     $this->Cell(20, 5, $viaje['monto'], 0,0, 'R', $fill);
                     $this->Cell(20, 5, $viaje['neto'], 0,0, 'R', $fill);
                     $fill = !$fill;
                     $this->Ln();
                     $this->SetX(10);

                     if($viaje['adicional_desglose']['empty'] == false ){
                            $this->SetFillColor(244,255,241);
                            $this->SetFont('Courier','I',8);
                            for($i=0;$i < count($viaje['adicional_desglose'])-1;$i++){
                                   $this->Cell(65, 5, '', 0,0, 'C', $fill);
                                   $this->Cell(70, 5, utf8_decode($viaje['adicional_desglose'][$i]['etiqueta']), 0,0, 'R', $fill);

                                   $this->Cell(20, 5, '', 0,0, 'R', $fill);

                                   if($viaje['adicional_desglose'][$i]['descripcion'] == 0){
                                          $this->Cell(50, 5, '', 0,0, 'L', $fill);
                                   }else{
                                          $this->Cell(50, 5, utf8_decode($viaje['adicional_desglose'][$i]['descripcion']), 0,0, 'L', $fill);
                                   }

                                   $this->Cell(20, 5, $viaje['adicional_desglose'][$i]['costo'], 0,0, 'R', $fill);
                                   $this->Cell(40, 5, '', 0,0, 'C', $fill);

                                   $fill = !$fill;
                                   $this->Ln();
                                   $this->SetX(10);
                                   $num++;
                                   if($num == 30){
                                          $num = 0;
                                          $this->AddPage();
                                          $this->setSourceFile("../resources/plantillas_pdf/papeleta.pdf");
                                          $tplIdx = $this->importPage(1);
                                          $this->useTemplate($tplIdx, 0, 0, 280, 215);
                                          $this->SetX(10);
                                   }
                            }
                            $this->SetFillColor(248,244,224);
                            $this->SetFont('Courier','',8);
                     }


                     $this->SetDrawColor(192,177,107);
                     $this->SetLineWidth(.2);
                     $this->Line(41, 31, 41, 195);
                     $km += $viaje['km_max'];
                     $adicional += $ad_cost;
                     $monto += $viaje['monto'];
                     $neto += $viaje['neto'];

                     $con++;
                     $num++;
                     if($num == 30){
                            $num = 0;
                            $this->AddPage();
                            $this->setSourceFile("../resources/plantillas_pdf/papeleta.pdf");
                            $tplIdx = $this->importPage(1);
                            $this->useTemplate($tplIdx, 0, 0, 280, 215);
                            $this->SetX(10);
                     }
           }

           $this->SetFont('Courier','B',8);
           $this->Cell(32, 5, '', 0,0, 'C', $fill);
           $this->Cell(11, 5, '', 0,0, 'C', $fill);
           $this->Cell(22, 5, '', 0,0, 'C', $fill);
           $this->Cell(70, 5, '', 0,0, 'L', $fill);
           $this->Cell(20, 5, 'TOTALES:', 0,0, 'C', $fill);
           $this->Cell(15, 5, '', 0,0, 'C', $fill);
           $this->Cell(15, 5, $km.' km', 0,0, 'C', $fill);
           $this->Cell(10, 5, '', 0,0, 'C', $fill);
           $this->Cell(10, 5, '', 0,0, 'C', $fill);
           $this->Cell(20, 5, '$ '.$adicional, 0,0, 'R', $fill);
           $this->Cell(20, 5, '$ '.$monto, 0,0, 'R', $fill);
           $this->Cell(20, 5, '$ '.$neto, 0,0, 'R', $fill);
           $fill = !$fill;
           $this->Ln();
           $this->SetX(10);

       }
}
