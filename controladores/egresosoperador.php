<?php
class Egresosoperador extends Controlador
{
    public function index()
    {
	$this->se_requiere_logueo(true,'Egresosoperador|index');
       require URL_VISTA.'egresosoperador/index.php';
    }
    public function cron(){
           //exit();
           session_destroy();
           setlocale(LC_TIME,"es_MX.UTF-8");
           $egresos = $this->loadModel('Egresosoperador');

           $datef = new DateTime();
           $datef->modify('first day of this month');
           $datel = new DateTime();
           $datel->modify('last day of this month');

           $firstDay = $datef->format('Y-m-d');
           $lastDay = $datel->format('Y-m-d');
           $actualDay = date("Y-m-d");//2017-02-15
           $dia1 = strftime("%A"); //domingo
           $dia2 = date("d"); // 04
           $dia3 = date("N"); // 1-7

           $trabajos = $egresos->obtener_trabajos();

           foreach($trabajos as $num=>$work){
                  $lastEjecucion = $egresos->lastEjecucion($work['id_concepto']);

                  $execute[0] = ($work['valor'] == $dia3)?true:false;
                  $execute[1] = (($work['valor'] == 'first day of this month')AND($actualDay == $firstDay))?true:false;
                  $execute[2] = (($work['valor'] == 'last day of this month')AND($actualDay == $lastDay))?true:false;
                  $execute[3] = ((($work['valor'] == '15')AND($dia2 == '15'))OR(($work['valor'] == '15')AND($actualDay == $lastDay)))?true:false;

                  if($lastEjecucion != $actualDay){
                         if(in_array(true, $execute)){
                                $deficitarios = $egresos->deficitarios($work['id_concepto']);
                                foreach($deficitarios as $operador){
                                          $egresos->insertDeuda($operador['id_operador_concepto'],$operador['monto'],$actualDay);
                                }
                                $adeudosGenerados = count($deficitarios);
                                $monto = $adeudosGenerados * $work['monto'];
                                $egresos->setAplicacionEjecucion($work['id_concepto'],$actualDay,$adeudosGenerados,$monto);
                         }
                  }
           }
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
    public function ejecucionesCobro($id_concepto){
            $this->se_requiere_logueo(true,'Egresosoperador|add_concepto');
            require URL_VISTA.'egresosoperador/ejecucionesCobro.php';
    }
    public function obtener_ejecucionesCobro($id_concepto){
           $this->se_requiere_logueo(true,'Egresosoperador|index');
           $egresos = $this->loadModel('Egresosoperador');
           $lista_ejecuciones = $egresos->obtener_ejecucionesCobro($_POST,$id_concepto);
           print $lista_ejecuciones;
    }
}
?>
