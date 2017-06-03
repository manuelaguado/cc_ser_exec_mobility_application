<?php
class Egresosoperador extends Controlador
{
    public function index()
    {
	$this->se_requiere_logueo(true,'Egresosoperador|index');
       require URL_VISTA.'egresosoperador/index.php';
    }
    public function obtener_conceptos(){
           $this->se_requiere_logueo(true,'Egresosoperador|index');
           $egresos = $this->loadModel('Egresosoperador');
           $lista_conceptos = $egresos->obtenerConceptos($_POST);
           print $lista_conceptos;
    }
    public function add_nuevo_cobro(){
           $this->se_requiere_logueo(true,'Egresosoperador|add_concepto');
              $periodicidad = $this->selectCatalog('periodicidad',null);
           require URL_VISTA.'modales/egresosoperador/add_concepto.php';
    }
    public function add_nuevo_cobro_do(){
           $this->se_requiere_logueo(true,'Egresosoperador|add_concepto');
           $egresos = $this->loadModel('Egresosoperador');
           print json_encode($egresos->add_nuevo_cobro_do($_POST));
    }
    public function eliminar_cobro($id_concepto){
           $this->se_requiere_logueo(true,'Egresosoperador|add_concepto');
           require URL_VISTA.'modales/egresosoperador/eliminar_cobro.php';
    }
    public function eliminar_cobro_do($id_concepto){
           $this->se_requiere_logueo(true,'Egresosoperador|add_concepto');
           $egresos = $this->loadModel('Egresosoperador');
           print json_encode($egresos->eliminar_cobro_do($id_concepto));
    }
    public function editar_cobro($id_concepto){
           $this->se_requiere_logueo(true,'Egresosoperador|add_concepto');
           $egresos = $this->loadModel('Egresosoperador');
           $dataegreso = $egresos->dataegreso($id_concepto);
           $periodicidad = $this->selectCatalog('periodicidad',$dataegreso['cat_periodicidad']);
           require URL_VISTA.'modales/egresosoperador/editar_cobro.php';
    }
    public function editar_cobro_do(){
           $this->se_requiere_logueo(true,'Egresosoperador|add_concepto');
           $egresos = $this->loadModel('Egresosoperador');
           print json_encode($egresos->editar_cobro_do($_POST));
    }
    public function relacionar_cobro($id_concepto){
           $this->se_requiere_logueo(true,'Egresosoperador|add_concepto');
           $egresos = $this->loadModel('Egresosoperador');

           $dataegreso = $egresos->dataegreso($id_concepto);
           $list_operadores = $egresos->getOperadores();

           require URL_VISTA.'egresosoperador/relacionar.php';
    }
    public function establecer_cobro($id_concepto,$id_operador,$estado){
           $this->se_requiere_logueo(true,'Egresosoperador|add_concepto');
           $egresos = $this->loadModel('Egresosoperador');
           $doSet = $egresos->fijar_cobro($id_concepto,$id_operador,$estado);
           print json_encode($doSet);
    }
}
?>
