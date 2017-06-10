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
           self::sendMailReport();
           print $ingresos->proceso249_do();
    }
    function sendMailReport(){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           /*$datamail = array();
           $datamail['destinatarios'] = array(
                  'manuelaguado@gmail.com'
           );
           $datamail['plantilla'] 	= 'contrast';
           $datamail['subject'] 	= 'Informe';
           $datamail['body'] = array(
                                          'fecha'	=>	'México D.F. a 16 de Noviembre de 2015',
                                          'asunto'	=>	'Se le informa la finalización del plan',
                                          'firma'	=>	'Ing Pocoyó',
                                          'hospital'	=>	'Belisario Domíguez'
                                   );
           $this->sendMail($datamail);*/
           echo 'ok';

           // El mensaje
           $mensaje = "Línea 1\r\nLínea 2\r\nLínea 3";

           // Si cualquier línea es más larga de 70 caracteres, se debería usar wordwrap()
           $mensaje = wordwrap($mensaje, 70, "\r\n");

           // Enviarlo
           mail('manuelaguado@gmail.com', 'Mi título', $mensaje);

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
