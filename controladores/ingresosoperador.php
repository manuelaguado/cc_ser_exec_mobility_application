<?php
class Ingresosoperador extends Controlador
{
    public function index()
    {
	$this->se_requiere_logueo(true,'Ingresosoperador|index');
       require URL_VISTA.'ingresosoperador/index.php';
    }
    public function operadorGroup(){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           $lista_conceptos = $ingresos->operadorGroup($_POST);
           print $lista_conceptos;
    }
    function viajes_operador($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           require URL_VISTA.'ingresosoperador/viajes_operador.php';
    }
    function viajes_operador_get($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           $viajes_operador = $ingresos->viajes_operador($_POST,$id_operador);
           print $viajes_operador;
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
    function mapsroutes($id_viaje){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $ingresos = $this->loadModel('Ingresosoperador');
           $coordenadas = $ingresos->coordenadas($id_viaje);
           require URL_VISTA.'modales/ingresosoperador/mapsroutes.php';
    }
}
?>
