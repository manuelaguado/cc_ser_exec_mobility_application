<?php
require_once('../vendor/fpdf/fpdf.php');
require_once('../vendor/FPDI/fpdi.php');

class RESGUARDO extends FPDI
{
	var $firma;
	var $resguardo;
	var $autoriza;
	var $tiempo;
	var $accesorios;
	var $domicilio;
	var $opciones;
		
    function setConfig($var,$val)
    {
        $this->{$var} = $val;
    }
    function SetDash($black=null, $white=null)
    {
        if($black!==null)
            $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }	
	function insertFolio(){
		$this->SetFont('Helvetica','',10);
		$this->SetTextColor(255, 0, 0);
		$this->SetXY(150, 24.5);
		$this->Write(0, $this->resguardo['id_operador_celular']);
		$this->SetXY(150, 24.5+134.5);
		$this->Write(0, $this->resguardo['id_operador_celular']);
	}
	function insertFecha(){
		$this->SetFont('Helvetica','',10);
		$this->SetTextColor(0, 0, 0);
		$this->SetXY(120, 28.5);
		setlocale(LC_TIME, 'es_MX.UTF-8');
		$this->Write(0, utf8_decode(strftime('%A %d de %B de %Y', time())));
		$this->SetXY(120, 28.5+136);
		$this->Write(0, utf8_decode(strftime('%A %d de %B de %Y', time())));
	}
	function insertNombre(){
		$this->SetFont('Helvetica','',10);
		$this->SetTextColor(0, 0, 0);
		$this->SetXY(40, 40);
		$this->Write(0, utf8_decode(utf8_decode($this->resguardo['nombre'])));
		$this->SetXY(40, 40+136);
		$this->Write(0, utf8_decode(utf8_decode($this->resguardo['nombre'])));
	}
	function insertDomicilio(){
		$this->SetFont('Helvetica','',10);
		$this->SetTextColor(0, 0, 0);
		$this->SetXY(40, 46);
		$this->Write(0, $this->domicilio);
		$this->SetXY(40, 46+137);
		$this->Write(0, $this->domicilio);
	}
	function insertnameSign(){
		$this->SetFont('Helvetica','',10);
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(255,0,0);
		$this->SetXY(18, 113);
		$this->Cell(91,4,strtoupper(utf8_decode(utf8_decode($this->resguardo['nombre']))),0,0,'C');
		$this->SetXY(18, 115+135.5);
		$this->Cell(91,4,strtoupper(utf8_decode(utf8_decode($this->resguardo['nombre']))),0,0,'C');
	}
	function insertDigitalSign(){
		$this->SetFont('Helvetica','',7);
		$this->SetTextColor(0, 0, 0);
		$this->SetXY(14, 132);
		$this->Write(5, 'Firma digital: '.$this->firma);
		$this->SetXY(14, 143);
		$this->Write(5, 'Firma digital: '.$this->firma);
	}
	function insertDataCel(){
		$this->SetFont('Helvetica','',7);
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(255,0,0);
		$this->SetXY(18, 58);
		$this->Cell(18,4,$this->resguardo['marca'],0,0,'C');
		$this->Cell(26,4,$this->resguardo['modelo'],0,0,'C');
		$this->Cell(25,4,$this->resguardo['imei'],0,0,'C');
		$this->Cell(23,4,$this->resguardo['numero'],0,0,'C');
		$this->Cell(22,4,$this->resguardo['marcacion_corta'],0,0,'C');
		$this->Cell(36,4,$this->resguardo['sim'],0,0,'C');
		$this->Cell(36,4,'$ '.$this->resguardo['valor'],0,0,'C');
		
		$this->SetXY(18, 58+136.5);
		$this->Cell(18,4,$this->resguardo['marca'],0,0,'C');
		$this->Cell(26,4,$this->resguardo['modelo'],0,0,'C');
		$this->Cell(25,4,$this->resguardo['imei'],0,0,'C');
		$this->Cell(23,4,$this->resguardo['numero'],0,0,'C');
		$this->Cell(22,4,$this->resguardo['marcacion_corta'],0,0,'C');
		$this->Cell(36,4,$this->resguardo['sim'],0,0,'C');
		$this->Cell(36,4,'$ '.$this->resguardo['valor'],0,0,'C');
	}
	function cut(){
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(0.1);
		$this->SetDash(2,2);
		$this->Line(2,140,215,140);		
	}
	function insertAutoriza(){
		$this->SetFont('Helvetica','',10);
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(255,0,0);
		$this->SetXY(110, 113);
		$this->Cell(87,4,strtoupper($this->autoriza),0,0,'C');
		$this->SetXY(110, 113+137.5);
		$this->Cell(87,4,strtoupper($this->autoriza),0,0,'C');
	}
	function insertTiempo(){
		$this->SetFont('Helvetica','',10);
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(255,0,0);
		$this->SetXY(115, 31);
		$this->Cell(40,4,strtoupper($this->tiempo),0,0,'C');
		$this->SetXY(115, 32+136);
		$this->Cell(40,4,strtoupper($this->tiempo),0,0,'C');
	}
    function cargarAccesorios($array)
    {
        $array = $this->accesorios;
        $data = array();
        foreach($array as $accesorio){
            $accesorios[] = $accesorio;
		}
        return $accesorios;
    }
    function insertarAccesorios($accesorios)
    {  
		$this->SetXY(20, 78.5);
		$legend = 0;
        foreach($accesorios as $accesorio)
        {
			$this->SetFont('Helvetica');
			if($legend == 0){
				$this->Write(5, 'Se entrega con: ');
				$legend = 1;
			}
			$this->Write(5, $accesorio . ' - ');
        }
		
		$this->SetXY(20, 216);
		$legend = 0;
        foreach($accesorios as $accesorio)
        {
			$this->SetFont('Helvetica');
			if($legend == 0){
				$this->Write(5, 'Se entrega con: ');
				$legend = 1;
			}
			$this->Write(5, $accesorio . ' - ');
        }
		
    }
	function cargarOpciones($array)
    {
        $array = $this->opciones;
        $data = array();
        foreach($array as $opcion){
            $opciones[] = $opcion;
		}
        return $opciones;
    }
	function insertarOpciones($opciones)
    {  
		$this->SetFont('Helvetica','',8);
		$this->SetXY(110, 97.5);
        foreach($opciones as $opcion)
        {
			$this->Cell(87,4,strtoupper($opcion),0,0,'C');
        }
		$this->SetXY(110, 235);
        foreach($opciones as $opcion)
        {
			$this->Cell(87,4,strtoupper($opcion),0,0,'C');
        }
    }
	
}

$pdf = new RESGUARDO($orientation='P', $unit='mm', $size='LETTER');
$pdf->AddPage();
$pdf->setSourceFile("../resources/plantillas_pdf/resguardo_telefonico.pdf");
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 1, -7, 210, 300);



// variables

$pdf->setConfig('firma',$firma);
$pdf->setConfig('resguardo',$resguardo);
$pdf->setConfig('autoriza',$autoriza);
$pdf->setConfig('tiempo',$tiempo);
$pdf->setConfig('domicilio',$domicilio);
$pdf->insertFolio();
$pdf->insertFecha();
$pdf->insertNombre();
$pdf->insertDomicilio();
$pdf->insertnameSign();
$pdf->insertDigitalSign();
$pdf->insertDataCel();
$pdf->insertAutoriza();
$pdf->insertTiempo();
$pdf->cut();

$pdf->setConfig('accesorios',$accesorios);
$pdf->insertarAccesorios($pdf->cargarAccesorios($accesorios));

$pdf->setConfig('opciones',$opciones);
$pdf->insertarOpciones($pdf->cargarOpciones($opciones));

$pdf->Output('../public/tmp/'.$token.'.pdf','F');