<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<?php
class Unidades extends Controlador
{
    public function index()
    {
		$this->se_requiere_logueo(true,'Unidades|index');
        require URL_VISTA.'unidades/index.php';
    }
	public function obtener_unidades(){
		$this->se_requiere_logueo(true,'Unidades|index');
		$model = $this->loadModel('Unidades');
		$lista_unidades = $model->obtenerUnidades($_POST);
		print $lista_unidades;
	}
	public function add_unidad(){
		$this->se_requiere_logueo(true,'Unidades|add_unidad');
		$model = $this->loadModel('Unidades');
			$marcas = $model->selectMarca(null);
		$stat_unit = $this->selectCatalog('status_unidad',14);
		require URL_VISTA.'modales/unidades/nueva_unidad.php';
	}
	public function add_unidad_do(){
		$this->se_requiere_logueo(true,'Unidades|add_unidad');
		$model = $this->loadModel('Unidades');
		print json_encode($model->add_unidad_do($_POST));
	}
	public function edita_unidad($id_unidad){
		$this->se_requiere_logueo(true,'Unidades|index');
		$model = $this->loadModel('Unidades');
			$vehiculo = $model->data_unidad($id_unidad);
			$marcas = $model->selectMarca($vehiculo['id_marca']);
			$modelos = $model->selectModelo($vehiculo['id_marca'],$vehiculo['id_modelo']);
		$stat_unit = $this->selectCatalog('status_unidad',$vehiculo['cat_status_unidad']);
		require URL_VISTA.'modales/unidades/editar_unidad.php';
	}
	public function edita_unidad_do(){
		$this->se_requiere_logueo(true,'Unidades|add_unidad');
		$model = $this->loadModel('Unidades');
		print json_encode($model->edita_unidad_do($_POST));
	}
	public function getModelos($id_marca){
		$this->se_requiere_logueo(true,'Unidades|add_unidad');
		$model = $this->loadModel('Unidades');
		print $model->selectModelo($id_marca,null);
	}
	public function asignarAutomovil( $id_operador,$unidad,$estado){
		$this->se_requiere_logueo(true,'Unidades|add_unidad');
		$setear_permiso = $this->loadModel('Unidades');
		$doSet = $setear_permiso->asignarAutomovil( $id_operador,$unidad,$estado);
		print json_encode($doSet);
	}
	
}
?>