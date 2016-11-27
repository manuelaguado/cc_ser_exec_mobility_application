<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<?php
class Telefonia extends Controlador
{
    public function index()
    {
		$this->se_requiere_logueo(true,'Telefonia|index');
        require URL_VISTA.'telefonia/index.php';
    }
	public function obtener_celulares()
    {
		$this->se_requiere_logueo(true,'Telefonia|index');
        $modelo = $this->loadModel('Telefonia');
		$listaCelulares = $modelo->listaCelulares($_POST);
		print $listaCelulares;
    }
	public function nuevo_cel()
    {
		$this->se_requiere_logueo(true,'Telefonia|nuevo_cel');
        require URL_VISTA.'modales/telefonia/nuevo_cel.php';
    }
	public function nuevo_cel_do(){
		$this->se_requiere_logueo(true,'Telefonia|nuevo_cel');
		$modelo = $this->loadModel('Telefonia');
		print json_encode($modelo->insertCell($_POST));
	}
	public function editar($id_celular)
    {
		$this->se_requiere_logueo(true,'Telefonia|editar');
		$modelo = $this->loadModel('Telefonia');
		$dataCell = $modelo->dataCell($id_celular);
		$selectEstado = $this->selectCatalog('status_celular',$dataCell['cat_status_celular']);
        require URL_VISTA.'modales/telefonia/editar.php';
    }
	public function editar_cel_do(){
		$this->se_requiere_logueo(true,'Telefonia|editar');
		$modelo = $this->loadModel('Telefonia');
		print json_encode($modelo->editCell($_POST));
	}
	public function asignar($id_celular)
    {
		$this->se_requiere_logueo(true,'Telefonia|asignar');
		$modelo = $this->loadModel('Telefonia');
		$lista_operadores = $modelo->operadoresUnassign();
        require URL_VISTA.'modales/telefonia/asignar.php';
    }
	public function asignar_celular_do(){
		$this->se_requiere_logueo(true,'Telefonia|asignar');
		$modelo = $this->loadModel('Telefonia');
		print json_encode($modelo->asignarCell($_POST));
	}
}
?>