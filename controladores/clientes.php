<?php
class Clientes extends Controlador
{
    public function index()
    {
		$this->se_requiere_logueo(true,'Clientes|index');
        require URL_VISTA.'clientes/index.php';
    }
	function calendario_historico($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|calendario_historico');
		require URL_VISTA.'clientes/calendario_historico.php';
	}
	function calendario_historico_data($id_client){
		$this->se_requiere_logueo(true,'Clientes|calendario_historico');
		
		require_once('../vendor/dhtmlxScheduler/scheduler_connector.php');

		$scheduler = new schedulerConnector($this->mysqlConnectivity(), DB_TYPE);
		$scheduler->render_table("events","event_id","start_date,end_date,event_name,details");
		
	}
	public function procesar_tarifa(){
		$this->se_requiere_logueo(true,'Clientes|tarifas');
		$model = $this->loadModel('Clientes');
		$result = $model->procesar_tarifa($_POST);
		print json_encode($result);
	}
	public function predeterminarUbicacion($id_datos_fiscales,$id_cliente){
		$this->se_requiere_logueo(true,'Clientes|predeterminarUbicacion');
		$model = $this->loadModel('Clientes');
		$result = $model->predeterminarUbicacion($id_datos_fiscales,$id_cliente);
		print json_encode($result);
	}
	public function eliminarUbicacion($id_datos_fiscales){
		$this->se_requiere_logueo(true,'Clientes|eliminarUbicacion');
		$model = $this->loadModel('Clientes');
		$result = $model->eliminarUbicacion($id_datos_fiscales);
		print json_encode($result);
	}
	public function guardarUbicacion(){
		$this->se_requiere_logueo(true,'Clientes|add_direccion');
		$model = $this->loadModel('Clientes');
		$result = $model->guardarUbicacion($_POST);
		print json_encode($result);
	}
	public function add_direccion($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|add_direccion');
		$model = $this->loadModel('Clientes');
			$cliente = $model->getDataClient($id_cliente);
		require URL_VISTA.'clientes/ubicacion_nueva.php';
	}	
	public function ubicacion($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|ubicacion');
		$model = $this->loadModel('Clientes');
			$cliente = $model->getDataClient($id_cliente);
			$direcciones = $model->direcciones($id_cliente);
		require URL_VISTA.'clientes/ubicacion.php';
	}
	public function allthem(){
		$this->se_requiere_logueo(true,'Clientes|allthem');
		require URL_VISTA.'clientes/allthem.php';
	}
	public function allthem_data(){
		$this->se_requiere_logueo(true,'Clientes|allthem');
		$model = $this->loadModel('Clientes');
		$clientes = $model->allthem($_POST);
		print $clientes;
	}
	public function facturas($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|facturas');
		require URL_VISTA.'clientes/facturas.php';
	}
	public function anticipos($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|anticipos');
		require URL_VISTA.'clientes/anticipos.php';
	}
	public function finanzas($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|finanzas');
		require URL_VISTA.'clientes/finanzas.php';
	}
	public function viajes($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|viajes');
		require URL_VISTA.'clientes/viajes.php';
	}
	public function origenes($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|origenes');
		require URL_VISTA.'clientes/origenes.php';
	}
	public function destinos($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|destinos');
		require URL_VISTA.'clientes/destinos.php';
	}
	public function operador_favorito($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|operador_favorito');
		require URL_VISTA.'clientes/favoritos.php';
	}
	public function tarifas($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|tarifas');
		require URL_VISTA.'clientes/tarifas.php';
	}
	public function listado($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|listado');
		$model = $this->loadModel('Clientes');
		$cliente = $model->getDataClient($id_cliente);
		require URL_VISTA.'clientes/listado.php';
	}
	public function listado_data($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|listado');
		$model = $this->loadModel('Clientes');
		$clientes = $model->listado($_POST,$id_cliente);
		print $clientes;
	}
	public function clientpage($tercio){
		$this->se_requiere_logueo(true,'Clientes|clientpage');
		$elm = explode('|',$tercio);
		$id_cliente = $elm[0];
		$id_superpadre = $elm[1];
		$id_padre = $elm[2];
		
		$model = $this->loadModel('Clientes');
			$cliente = $model->getDataClient($id_cliente);
		
		require URL_VISTA.'clientes/client_page.php';
	}
	public function deleteClient($id_cliente,$padre){
		$this->se_requiere_logueo(true,'Clientes|deleteClient');
		$cliente_model = $this->loadModel('Clientes');
		$delete = $cliente_model->deleteClient($id_cliente,$padre);
		print json_encode($delete);
	}
	public function store_order(){
		$this->se_requiere_logueo(true,'Clientes|ubicacion');
		$cliente_model = $this->loadModel('Clientes');
		$cliente_model->store_order($_POST);
	}
	public function obtener_clientes(){
		$this->se_requiere_logueo(true,'Clientes|index');
		$modelo = $this->loadModel('Clientes');
		$clientes = $modelo->obtenerClientes($_POST);
		print $clientes;
	}
	public function modal_add_cliente(){
		$this->se_requiere_logueo(true,'Clientes|add_cliente');
		$tiposClientes = $this->selectCatalog('tipocliente',null);
		$satatusCliente = $this->selectCatalog('statuscliente',null);
			$modelo = $this->loadModel('Roles');
			$roles = $modelo->selectRolesByTipo(26,$_SESSION['id_rol'],null);
		require URL_VISTA.'modales/clientes/nuevo_cliente.php';
	}
	
	
	

	
	public function modal_establecer_tarifa($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|tarifas');
		$cat_tipo_tarifa = $this->selectCatalog('tipo_tarifa',null);
		require URL_VISTA.'modales/clientes/establecer_tarifa.php';
	}
	public function modal_establecer_tarifa_get($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|tarifas');
		
		$Clientes = $this->loadModel('Clientes');
		print $Clientes->queryTarifas($_POST,$id_cliente);
	}
	
	
	
	
	public function modal_editar_cliente($id_cliente){
		$this->se_requiere_logueo(true,'Clientes|editar');
		$model = $this->loadModel('Clientes');
		$cliente = $model->getDataClient($id_cliente);
		
		$tiposClientes = $this->selectCatalog('tipocliente',$cliente['cat_tipocliente' ]);
		$satatusCliente = $this->selectCatalog('statuscliente',$cliente['cat_statuscliente' ]);
			$model1 = $this->loadModel('Roles');
			$roles = $model1->selectRolesByTipo(26,$_SESSION['id_rol'],$cliente['id_rol' ]);
		
		require URL_VISTA.'modales/clientes/editar_cliente.php';
	}
	public function edit_client(){
		$this->se_requiere_logueo(true,'Clientes|editar');
		$modelo = $this->loadModel('Clientes');
		$edit_client = $modelo->edit_client($_POST);
		print json_encode($edit_client);
	}	
	public function busqueda_de_padre(){
		$this->se_requiere_logueo(true,'Clientes|add_cliente');
		$consulta = $_GET['query'];
		$data = $this->loadModel('Clientes');
		print $data->buscar_padre($consulta);
	}
	public function add_client(){
		$this->se_requiere_logueo(true,'Clientes|add_cliente');
		$modelo = $this->loadModel('Clientes');
		$add_client = $modelo->add_client($_POST);
		print json_encode($add_client);
	}
	public function nestable_client($parent){
		$this->se_requiere_logueo(true,'Clientes|nestable_client');
		$tiposClientes = $this->selectCatalog('tipocliente',null);
		$satatusCliente = $this->selectCatalog('statuscliente',null);
		
		$cliente_model = $this->loadModel('Clientes');
			$cliente = $cliente_model->getDataClient($parent);
			$childrens = $cliente_model->getChildrensClient($parent,1);
		
		$modelo = $this->loadModel('Roles');
			$roles = $modelo->selectRolesByTipo(26,$_SESSION['id_rol'],null);
		
		require URL_VISTA.'clientes/nestable.php';
	}
	public function add_client_children(){
		$this->se_requiere_logueo(true,'Clientes|add_client_children');
		$modelo = $this->loadModel('Clientes');
		print $modelo->add_client_children($_POST);
	}
	public function edit_client_children(){
		$this->se_requiere_logueo(true,'Clientes|add_client_children');
		$modelo = $this->loadModel('Clientes');
		print $modelo->edit_client_children($_POST);
	}
	public function getFormClientEdit($id_cliente, $parent){
		$this->se_requiere_logueo(true,'Clientes|add_client_children');
		
		$cliente_model = $this->loadModel('Clientes');
			$cliente = $cliente_model->getDataClient($id_cliente);
		
		$tiposClientes = $this->selectCatalog('tipocliente',$cliente['cat_tipocliente']);
		$satatusCliente = $this->selectCatalog('statuscliente',$cliente['cat_statuscliente']);
		
		$modelo = $this->loadModel('Roles');
			$roles = $modelo->selectRolesByTipo(26,$_SESSION['id_rol'],$cliente['id_rol']);
		
		require URL_VISTA.'ajax/cliente/editar_cliente.php';
	}
	public function return_form_add($parent){
		$this->se_requiere_logueo(true,'Clientes|add_client_children');
		$tiposClientes = $this->selectCatalog('tipocliente',null);
		$satatusCliente = $this->selectCatalog('statuscliente',null);
			$modelo = $this->loadModel('Roles');
			$roles = $modelo->selectRolesByTipo(26,$_SESSION['id_rol'],null);
		require URL_VISTA.'ajax/cliente/agregar_cliente.php';
	}
}
?>
