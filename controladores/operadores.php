<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<?php
class Operadores extends Controlador
{
	public function index()
    {
		$this->se_requiere_logueo(true,'Operadores|index');
        require URL_VISTA.'operadores/index.php';
    }
	public function historia_operador($id_operador){
		$this->se_requiere_logueo(true,'Operadores|historia');
		$modelo = $this->loadModel('Operadores');
		$historia = $modelo->historia($id_operador);
		require URL_VISTA.'modales/operadores/historia.php';
	}
	public function modal_ver_telefonos($id_operador){
		$this->se_requiere_logueo(true,'Operadores|ver_telefonos');
		$model = $this->loadModel('Operadores');
		$telefonos = $model->listadoTelefonos($id_operador);
		require URL_VISTA.'modales/operadores/ver_telefonos.php';
	}
	public function modal_ver_direcciones($id_operador){
		$this->se_requiere_logueo(true,'Operadores|ver_direcciones');
		$model = $this->loadModel('Operadores');
		$domicilios = $model->listadoDomicilios($id_operador);
		require URL_VISTA.'modales/operadores/ver_direcciones.php';
	}
	
	public function modal_domicilios($id_operador){
		$this->se_requiere_logueo(true,'Operadores|gestion_domicilios');
		$model = $this->loadModel('Operadores');
		
		$domicilios = $model->queryDomicilio($id_operador);
		$tipodom = $this->selectCatalog('tipodomicilio',null);
		$statusdom = $this->selectCatalog('statusdomicilio',null);
		
		require URL_VISTA.'modales/operadores/gestion_domicilios.php';
	}
	public function agregar_domicilio(){
		$this->se_requiere_logueo(true,'Operadores|add_domicilio');
		$model = $this->loadModel('Operadores');
		$inserta_dom = $model->add_domicilio($_POST);
		print json_encode($inserta_dom);
	}	
	public function eliminar_domicilio($id_domicilio){
		$this->se_requiere_logueo(true,'Operadores|del_domicilio');
		$model = $this->loadModel('Operadores');
		$del_dom = $model->status_domicilio($id_domicilio,130);
		print json_encode($del_dom);
	}
	public function activar_domicilio($id_domicilio){
		$this->se_requiere_logueo(true,'Operadores|activar_domicilio');
		$model = $this->loadModel('Operadores');
		$act_dom = $model->status_domicilio($id_domicilio,128);
		print json_encode($act_dom);
	}
	public function inactivar_domicilio($id_domicilio){
		$this->se_requiere_logueo(true,'Operadores|inactivar_domicilio');
		$model = $this->loadModel('Operadores');
		$in_dom = $model->status_domicilio($id_domicilio,129);
		print json_encode($in_dom);
	}	
	
	
	
	
	
	public function modal_telefonos($id_operador){
		$this->se_requiere_logueo(true,'Operadores|gestion_telefonos');
		$tel_data = $this->loadModel('Operadores');
		
		$telefonos = $tel_data->queryTels($id_operador);
		$tipotel = $this->selectCatalog('tipotelefono',null);
		$statustel = $this->selectCatalog('statustelefono',null);
		
		require URL_VISTA.'modales/operadores/gestion_telefonos.php';
	}
	public function agregar_telefono(){
		$this->se_requiere_logueo(true,'Operadores|add_telefono');
		$model = $this->loadModel('Operadores');
		$inserta_tel = $model->add_telefono($_POST);
		print json_encode($inserta_tel);
	}	
	public function eliminar_telefono($id_telefono){
		$this->se_requiere_logueo(true,'Operadores|del_telefono');
		$model = $this->loadModel('Operadores');
		$del_tel = $model->status_telefono($id_telefono,110);
		print json_encode($del_tel);
	}
	public function activar_telefono($id_telefono){
		$this->se_requiere_logueo(true,'Operadores|activar_telefono');
		$model = $this->loadModel('Operadores');
		$act_tel = $model->status_telefono($id_telefono,108);
		print json_encode($act_tel);
	}
	public function inactivar_telefono($id_telefono){
		$this->se_requiere_logueo(true,'Operadores|inactivar_telefono');
		$model = $this->loadModel('Operadores');
		$in_tel = $model->status_telefono($id_telefono,109);
		print json_encode($in_tel);
	}

	
	public function listado_vigente()
    {
		$this->se_requiere_logueo(true,'Operadores|listado_vigente');
        require URL_VISTA.'operadores/listado_vigente.php';
    }
	public function listado_vigente_get(){
		$this->se_requiere_logueo(true,'Operadores|listado_vigente');
		$modelo = $this->loadModel('Operadores');
		$listado_vigente_get = $modelo->listado_vigente_get($_POST);
		print $listado_vigente_get;
	}
	public function favoritos($id_operador){
		$this->se_requiere_logueo(true,'Operadores|favoritos');
		require URL_VISTA.'operadores/favoritos.php';
	}
	public function ingresos($id_operador){
		$this->se_requiere_logueo(true,'Operadores|ingresos');
		require URL_VISTA.'operadores/ingresos.php';
	}
	public function egresos($id_operador){
		$this->se_requiere_logueo(true,'Operadores|egresos');
		require URL_VISTA.'operadores/egresos.php';
	}
	public function episodios($id_operador){
		$this->se_requiere_logueo(true,'Operadores|episodios');
		require URL_VISTA.'operadores/episodios.php';
	}
	public function tarifas($id_operador){
		$this->se_requiere_logueo(true,'Operadores|tarifas');
		require URL_VISTA.'operadores/tarifas.php';
	}
	public function obtener_tarifas($id_operador){
		$this->se_requiere_logueo(true,'Operadores|tarifas');
		$modelo = $this->loadModel('Operadores');
		$listatarifas = $modelo->obtener_tarifas($_POST,$id_operador);
		print $listatarifas;
	}
	public function nueva_tarifa($id_operador){
		$this->se_requiere_logueo(true,'Operadores|nueva_tarifa');
		$modelo = $this->loadModel('Operadores');
		$formapago = $this->selectCatalog('formapago',null);
		require URL_VISTA.'modales/operadores/nueva_tarifa.php';
	}
	public function nueva_tarifa_do(){
		$this->se_requiere_logueo(true,'Operadores|nueva_tarifa');
		$modelo = $this->loadModel('Operadores');
		print json_encode($modelo->nueva_tarifa_do($_POST));
	}
	public function tarifas_del($id_tarifa_operador){
		$this->se_requiere_logueo(true,'Operadores|tarifas_del');
		require URL_VISTA.'modales/operadores/eliminar_tarifa.php';
	}
	public function tarifas_del_do($id_tarifa_operador){
		$this->se_requiere_logueo(true,'Operadores|tarifas_del');
		$modelo = $this->loadModel('Operadores');
		print json_encode($modelo->tarifas_del_do($id_tarifa_operador));
	}
	public function obtener_operadores(){
		$this->se_requiere_logueo(true,'Operadores|index');
		$modelo = $this->loadModel('Operadores');
		$listaoperadores = $modelo->obtener_operadores($_POST);
		print $listaoperadores;
	}
	public function activar($id_usuario){
		$this->se_requiere_logueo(true,'Operadores|edita_operador');
		$modelo = $this->loadModel('Operadores');
		$id_operador = $modelo->altaOperador($id_usuario);
		$modelo->altaOperadornumeq($id_operador,$id_usuario);
		echo "ok";
	}
	public function numero_economico($id_operador){
		$this->se_requiere_logueo(true,'Operadores|numero_economico');
		$modelo = $this->loadModel('Operadores');
		$valores = $modelo->getDataOperador($id_operador);
		$selectNumEq = $modelo->selectNumEq($valores['id_numeq']);
		require URL_VISTA.'modales/operadores/numero_economico.php';
	}
	public function numero_economico_do(){
		$this->se_requiere_logueo(true,'Operadores|edita_operador');
		$modelo = $this->loadModel('Operadores');
		print json_encode($modelo->setNumEq($_POST));
	}
	public function liberarnumero($num){
		$this->se_requiere_logueo(true,'Operadores|edita_operador');
		$modelo = $this->loadModel('Operadores');
		$modelo->liberarNumero($num);
		echo "ok";
	}
	public function relacionar_autos($id_operador){
		$this->se_requiere_logueo(true,'Operadores|relacionar_autos');
		$model1 = $this->loadModel('Operadores');
			$operador = $model1->operadorData($id_operador);
		$model2 = $this->loadModel('Unidades');
			$unidades = $model2->listarUnidades();
		
		require URL_VISTA.'modales/operadores/relacionar_autos.php';
	}
	public function status_operador($id_operador){
		$this->se_requiere_logueo(true,'Operadores|status_operador');
		$modelo = $this->loadModel('Operadores');
		
			$valores = $modelo->getOperador($id_operador);
			$selectStatusOperador = $modelo->selectStatusOperador($valores['cat_statusoperador']);
		
		require URL_VISTA.'modales/operadores/status_operador.php';
	}
	public function status_operador_do(){
		$this->se_requiere_logueo(true,'Operadores|edita_operador');
		$modelo = $this->loadModel('Operadores');
		print json_encode($modelo->setearstatusoperador($_POST));
	}
}
?>