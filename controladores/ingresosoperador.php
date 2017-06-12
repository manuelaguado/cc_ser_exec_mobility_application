<?php
class Ingresosoperador extends Controlador
{
    public function index()
    {
	$this->se_requiere_logueo(true,'Ingresosoperador|index');
       require URL_VISTA.'ingresosoperador/index.php';
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
    function proceso249_do(){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           $opProcess = $ingresos->proceso249_do();
           //$pdfURLProcess = self::generatePapeletas($opProcess);
           //self::sendMailReport($pdfURLProcess);
           return json_encode(array('resp' => true));
    }
    function generarPapeleta($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');

           $head = $ingresos->head_papeleta($id_operador);
           $viajes = $ingresos->desglosePapeleta($id_operador);
           $periodo = $ingresos->periodo($id_operador);

           $token = $this->token(16);
           require '../reportes/papeleta.php';
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
