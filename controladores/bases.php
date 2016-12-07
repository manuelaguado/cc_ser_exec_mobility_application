<?php
class Bases extends Controlador
{
    public function index()
    {
		$this->se_requiere_logueo(true,'Bases|index');
        require URL_VISTA.'bases/index.php';
    }
	public function obtener_bases(){
		$this->se_requiere_logueo(true,'Bases|index');
		$modelo = $this->loadModel('Bases');
		$bases = $modelo->obtenerBases($_POST);
		print $bases;
	}
	public function nueva_base(){
		$this->se_requiere_logueo(true,'Bases|nueva_base');
		$selecTipos = $this->selectCatalog('tipobase',null);
		require URL_VISTA.'modales/bases/nueva_base.php';
	}
	public function edita_base($id_base){
		$this->se_requiere_logueo(true,'Bases|edita_base');
		$modelo = $this->loadModel('Bases');
		$base = $modelo->getBaseData($id_base);
		$selecTipos = $this->selectCatalog('tipobase',$base['cat_tipobase']);
		require URL_VISTA.'modales/bases/editar_base.php';
	}
	public function edita_base_do(){
		$this->se_requiere_logueo(true,'Bases|edita_base');
		$modelo = $this->loadModel('Bases');
		print json_encode($modelo->editaBase($_POST));
	}
	public function nueva_base_do(){
		$this->se_requiere_logueo(true,'Bases|edita_base');
		$modelo = $this->loadModel('Bases');
		print json_encode($modelo->insertBase($_POST));
	}
	public function asignar_bases($operador_unidad){
		$this->se_requiere_logueo(true,'Bases|asignar_bases');
		$modelo = $this->loadModel('Bases');
		$user_movil = $modelo->user_movil($operador_unidad);
		$listaBases = $modelo->listarBases();
		require URL_VISTA.'modales/bases/asignar_bases.php';
	}
	public function asignarBase($operador_unidad,$id_base,$estado){
		$this->se_requiere_logueo(true,'Bases|asignar_bases');
		$setear_permiso = $this->loadModel('Bases');
		$doSet = $setear_permiso->asignarBase($operador_unidad,$id_base,$estado);
		print json_encode($doSet);
	}
}
?>
