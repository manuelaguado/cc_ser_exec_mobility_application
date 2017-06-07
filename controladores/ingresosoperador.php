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
           $egresos = $this->loadModel('Ingresosoperador');
           $lista_conceptos = $egresos->operadorGroup($_POST);
           print $lista_conceptos;
    }
    function viajes_operador($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           require URL_VISTA.'ingresosoperador/viajes_operador.php';
    }
    function viajes_operador_get($id_operador){
           $this->se_requiere_logueo(true,'Ingresosoperador|index');
           $egresos = $this->loadModel('Ingresosoperador');
           $viajes_operador = $egresos->viajes_operador($_POST,$id_operador);
           print $viajes_operador;
    }
}
?>
