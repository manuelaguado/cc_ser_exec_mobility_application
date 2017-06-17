<?php
require_once( '../reportes/papeleta.php' );
class Ingresosoperador extends Controlador
{
    public function index()
    {
	$this->se_requiere_logueo(true,'Ingresosoperador|index');
       require URL_VISTA.'ingresosoperador/index.php';
    }
    public function pausados()
    {
	$this->se_requiere_logueo(true,'Ingresosoperador|index');
       require URL_VISTA.'ingresosoperador/pausados.php';
    }
    public function ver_papeleta($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           $url = $ingresos->pdfData($id_operador);

           $token = $this->token();
           $destino = $token.'.pdf';
           $tmp = '../public/tmp/';
           copy($url, $tmp.$destino);
           $pdf = $destino;

           require URL_VISTA.'ingresosoperador/pdfview.php';
    }
    public function archivo(){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           require URL_VISTA.'ingresosoperador/archivo.php';
    }
    public function archivo_get(){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           print $ingresos->archivo_get($_POST);
    }
    public function pausadosGroup(){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           print $ingresos->pausadosGroup($_POST);
    }
    public function operadorGroup(){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           print $ingresos->operadorGroup($_POST);
    }
    public function procesados(){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           require URL_VISTA.'ingresosoperador/procesados.php';
    }
    public function procesadosGroup(){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           print $ingresos->procesadosGroup($_POST);
    }
    function viajes_operador($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           require URL_VISTA.'ingresosoperador/viajes_operador.php';
    }
    function viajes_operador_get($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           print $ingresos->viajes_operador($_POST,$id_operador);
    }


    function viajes_pausados($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           require URL_VISTA.'ingresosoperador/viajes_pausados.php';
    }
    function viajes_pausados_get($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           print $ingresos->viajes_pausados($_POST,$id_operador);
    }



    function viajes_procesados($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           require URL_VISTA.'ingresosoperador/viajes_procesados.php';
    }
    function viajes_procesados_get($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           print $ingresos->viajes_procesados($_POST,$id_operador);
    }
    function pausar_viaje($id_viaje){
            $this->se_requiere_logueo(true,'Ingresosoperador|index');
            require URL_VISTA.'modales/ingresosoperador/pausar_viaje.php';
    }
    function pausar_viaje_do($id_viaje){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           print $ingresos->pausar_viaje_do($id_viaje);
    }
    function variantes_viaje($id_viaje){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           $variantes = $ingresos->variantes($id_viaje);
           require URL_VISTA.'modales/ingresosoperador/variantes_viaje.php';
    }
    function proceso249(){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           require URL_VISTA.'modales/ingresosoperador/proceso249.php';
    }
    function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    function proceso249_do(){

           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           ini_set('memory_limit', '2048M');
           ini_set('max_execution_time', 600); //10 minutos
              $ingresos = $this->loadModel('Ingresosoperador');
              $opProcess = $ingresos->opProcess();
              $pdfURLProcess = self::generatePapeletas($opProcess);
              $ingresos->proceso249_do();
              self::sendMailReport($pdfURLProcess);
              $this->transmitir('doit','remoteUpdate');
              print json_encode(array('resp' => true));
    }
    function generarPapeleta($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');


           $token = $this->token(16);
           $head = $ingresos->head_papeleta($id_operador);
           $viajes = $ingresos->desglosePapeleta($id_operador);
           $periodo = $ingresos->periodo($id_operador);
           $ingresos->savePapeleta($viajes,$id_operador,$token);
           D::bug($viajes);

           $pdf = new PAPELETA($orientation='L', $unit='mm', $size='LETTER');
           $pdf->AliasNbPages();
           $pdf->AddPage();
           $pdf->setSourceFile("../resources/plantillas_pdf/papeleta.pdf");
           $tplIdx = $pdf->importPage(1);
           $pdf->useTemplate($tplIdx, 0, 0, 280, 215);
           $pdf->setConfig('head',$head);
           $pdf->setConfig('periodo',$periodo);
           $pdf->setConfig('viajes',$viajes);
           $pdf->Header();
           $pdf->insertarViaje($pdf->cargarViajes($viajes));
           $pdf->Output('../archivo/papeletas/'.$token.'.pdf','F');


           return '../archivo/papeletas/'.$token.'.pdf';
    }
    function generatePapeletas($opProcess){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $num =0;
           $array = array();
           foreach($opProcess as $num=>$cuenta){
                  $array[$num]['url'] = self::generarPapeleta($cuenta['id_operador']);
                  $array[$num]['correo'] = $cuenta['correo'];
                  $array[$num]['num'] = $cuenta['num'];
                  $array[$num]['id_operador'] = $cuenta['id_operador'];
                  $num++;
           }
           return $array;
    }
    function sendMailReport($pdfURLProcess){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           foreach($pdfURLProcess as $num=>$destino){
                 $datamail = array();
                 $datamail['destinatarios'] = array($destino['correo']);
                 $datamail['plantilla'] 	= 'papeleta';
                 $datamail['subject'] 	= 'Estado de cuenta';
                 $datamail['attachment']  = $destino['url'];
                 $datamail['attachment_type'] = 'application/pdf';
                 $datamail['body'] = array('fecha'=>date('Y-m-d h:i:s'),'asunto'=>'Estado de cuenta');
                 $this->sendMail($datamail);
           }
    }
    function marcar_como_pagado($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           require URL_VISTA.'modales/ingresosoperador/marcar_como_pagado.php';
    }
    function marcar_como_pagado_do($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           print $ingresos->marcar_como_pagado_do($id_operador);
    }
    function ver_viajes_archivados($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           require URL_VISTA.'ingresosoperador/viajes_archivados.php';
    }
    function ver_viajes_archivados_get($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           print $ingresos->ver_viajes_archivados($_POST,$id_operador);
    }
}
?>
