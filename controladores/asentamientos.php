<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<?php
class Asentamientos extends Controlador
{
    public function index(){
		$this->se_requiere_logueo(true,'Asentamientos|index');
        require URL_TEMPLATE.'404.php';
    }
	public function form_general($identificador,$id_asentamiento){
		$this->se_requiere_logueo(true,'Asentamientos|form_general');
		$_SESSION['busqueda_ciudad'] = '102';
		require URL_VISTA.'modales/asentamientos/formulario_general.php';
	}
	public function busqueda_cp(){
		$this->se_requiere_logueo(true,'Asentamientos|form_general');
		$consulta = $_GET['query'];
		$model = $this->loadModel('Asentamientos');
		print $model->busqueda_cp($consulta);
	}
	public function busqueda_colonia(){
		$this->se_requiere_logueo(true,'Asentamientos|form_general');
		$consulta = $_GET['query'];
		$model = $this->loadModel('Asentamientos');
		print $model->busqueda_colonia($consulta);
	}
	public function busqueda_ciudad(){
		$this->se_requiere_logueo(true,'Asentamientos|form_general');
		$consulta = $_GET['query'];
		$model = $this->loadModel('Asentamientos');
		print $model->busqueda_ciudad($consulta);
	}
	public function set_busqueda_ciudad($id_ciudad){
		$this->se_requiere_logueo(true,'Asentamientos|form_general');
		$_SESSION['busqueda_ciudad'] = $id_ciudad;
		print "SET CITY: ".$id_ciudad;
	}
}
?>