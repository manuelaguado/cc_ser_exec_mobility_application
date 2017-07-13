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
		$operadores = $this->loadModel('Operadores');
		$historia = $operadores->historia($id_operador);
		require URL_VISTA.'modales/operadores/historia.php';
	}
	public function modal_ver_telefonos($id_operador){
		$this->se_requiere_logueo(true,'Operadores|ver_telefonos');
		$operadores = $this->loadModel('Operadores');
		$telefonos = $operadores->listadoTelefonos($id_operador);
		require URL_VISTA.'modales/operadores/ver_telefonos.php';
	}
	public function modal_ver_direcciones($id_operador){
		$this->se_requiere_logueo(true,'Operadores|ver_direcciones');
		$operadores = $this->loadModel('Operadores');
		$domicilios = $operadores->listadoDomicilios($id_operador);
		require URL_VISTA.'modales/operadores/ver_direcciones.php';
	}

	public function modal_domicilios($id_operador){
		$this->se_requiere_logueo(true,'Operadores|gestion_domicilios');
		$operadores = $this->loadModel('Operadores');

		$domicilios = $operadores->queryDomicilio($id_operador);
		$tipodom = $this->selectCatalog('tipodomicilio',null);
		$statusdom = $this->selectCatalog('statusdomicilio',null);

		require URL_VISTA.'modales/operadores/gestion_domicilios.php';
	}
	public function agregar_domicilio(){
		$this->se_requiere_logueo(true,'Operadores|add_domicilio');
		$operadores = $this->loadModel('Operadores');
		$inserta_dom = $operadores->add_domicilio($_POST);
		print json_encode($inserta_dom);
	}
	public function eliminar_domicilio($id_domicilio){
		$this->se_requiere_logueo(true,'Operadores|del_domicilio');
		$operadores = $this->loadModel('Operadores');
		$del_dom = $operadores->status_domicilio($id_domicilio,130);
		print json_encode($del_dom);
	}
	public function activar_domicilio($id_domicilio){
		$this->se_requiere_logueo(true,'Operadores|activar_domicilio');
		$operadores = $this->loadModel('Operadores');
		$act_dom = $operadores->status_domicilio($id_domicilio,128);
		print json_encode($act_dom);
	}
	public function inactivar_domicilio($id_domicilio){
		$this->se_requiere_logueo(true,'Operadores|inactivar_domicilio');
		$operadores = $this->loadModel('Operadores');
		$in_dom = $operadores->status_domicilio($id_domicilio,129);
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
		$operadores = $this->loadModel('Operadores');
		$inserta_tel = $operadores->add_telefono($_POST);
		print json_encode($inserta_tel);
	}
	public function eliminar_telefono($id_telefono){
		$this->se_requiere_logueo(true,'Operadores|del_telefono');
		$operadores = $this->loadModel('Operadores');
		$del_tel = $operadores->status_telefono($id_telefono,110);
		print json_encode($del_tel);
	}
	public function activar_telefono($id_telefono){
		$this->se_requiere_logueo(true,'Operadores|activar_telefono');
		$operadores = $this->loadModel('Operadores');
		$act_tel = $operadores->status_telefono($id_telefono,108);
		print json_encode($act_tel);
	}
	public function inactivar_telefono($id_telefono){
		$this->se_requiere_logueo(true,'Operadores|inactivar_telefono');
		$operadores = $this->loadModel('Operadores');
		$in_tel = $operadores->status_telefono($id_telefono,109);
		print json_encode($in_tel);
	}


	public function listado_vigente()
    {
		$this->se_requiere_logueo(true,'Operadores|listado_vigente');
        require URL_VISTA.'operadores/listado_vigente.php';
    }
	public function listado_vigente_get(){
		$this->se_requiere_logueo(true,'Operadores|listado_vigente');
		$operadores = $this->loadModel('Operadores');
		$listado_vigente_get = $operadores->listado_vigente_get($_POST);
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
		$operadores = $this->loadModel('Operadores');
		$listatarifas = $operadores->obtener_tarifas($_POST,$id_operador);
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
		$operadores = $this->loadModel('Operadores');
		print json_encode($operadores->nueva_tarifa_do($_POST));
	}
	public function tarifas_del($id_tarifa_operador){
		$this->se_requiere_logueo(true,'Operadores|tarifas_del');
		require URL_VISTA.'modales/operadores/eliminar_tarifa.php';
	}
	public function tarifas_del_do($id_tarifa_operador){
		$this->se_requiere_logueo(true,'Operadores|tarifas_del');
		$operadores = $this->loadModel('Operadores');
		print json_encode($operadores->tarifas_del_do($id_tarifa_operador));
	}
	public function obtener_operadores(){
		$this->se_requiere_logueo(true,'Operadores|index');
		$operadores = $this->loadModel('Operadores');
		$listaoperadores = $operadores->obtener_operadores($_POST);
		print $listaoperadores;
	}
	public function activar($id_usuario){
		$this->se_requiere_logueo(true,'Operadores|edita_operador');
		$operadores = $this->loadModel('Operadores');
		$id_operador = $operadores->altaOperador($id_usuario);
		$ok = $operadores->altaOperadornumeq($id_operador,$id_usuario);
		print json_encode($ok);
	}
	public function numero_economico($id_operador){
		$this->se_requiere_logueo(true,'Operadores|numero_economico');
		$operadores = $this->loadModel('Operadores');
		$valores = $operadores->getDataOperador($id_operador);
		$selectNumEq = $operadores->selectNumEq($valores['id_numeq']);
		require URL_VISTA.'modales/operadores/numero_economico.php';
	}
	public function numero_economico_do(){
		$this->se_requiere_logueo(true,'Operadores|edita_operador');
		$operadores = $this->loadModel('Operadores');
		$operadores->insertStateByOper($_POST);
		print json_encode($operadores->setNumEq($_POST));
	}
	public function liberarnumero($num){
		$this->se_requiere_logueo(true,'Operadores|edita_operador');
		$operadores = $this->loadModel('Operadores');
		$operadores->caducarStateByNum($num);
		$ok = $operadores->liberarNumero($num);
		print $ok? json_encode(array('resp' => true)):json_encode(array('resp' => false));
	}
	public function relacionar_autos($id_operador){
		$this->se_requiere_logueo(true,'Operadores|relacionar_autos');
		$operadores = $this->loadModel('Operadores');
			$operador = $operadores->operadorData($id_operador);
		$unidades = $this->loadModel('Unidades');
			$lista_unidades = $unidades->listarUnidades();

		require URL_VISTA.'modales/operadores/relacionar_autos.php';
	}
	public function status_operador($id_operador){
		$this->se_requiere_logueo(true,'Operadores|status_operador');
		$operadores = $this->loadModel('Operadores');

			$valores = $operadores->getOperador($id_operador);
			$selectStatusOperador = $operadores->selectStatusOperador($valores['cat_statusoperador']);

		require URL_VISTA.'modales/operadores/status_operador.php';
	}
	public function status_operador_do(){
		$this->se_requiere_logueo(true,'Operadores|edita_operador');
		$operadores = $this->loadModel('Operadores');
		$num = $operadores->insertStateByStat($_POST);
		if($num == false){$num = 'NULL';}
		if($_POST['cat_statusoperador'] == 10){
			$share = $this->loadModel('Share');
			$operacion = $this->loadModel('Operacion');

			$setStat['id_operador'] = $_POST['id_operador'];
			$setStat['id_operador_unidad'] = 'NULL';
			$setStat['id_episodio'] = 'NULL';
			$setStat['id_viaje'] = 'NULL';
			$setStat['num'] = $num;
			$setStat['state'] = 'F6';
			$setStat['flag1'] = 'F6';
			$setStat['flag2'] = 'NULL';
			$setStat['flag3'] = 'NULL';
			$setStat['flag4'] = 'NULL';
			$setStat['motivo'] = 'NULL';

			$operacion->setF6($_POST['id_operador']);
			$share->setStatOper($setStat);
		}
		print json_encode($operadores->setearstatusoperador($_POST));
	}
	public function comision_operador($id_operador){
		$this->se_requiere_logueo(true,'Operadores|comisiones');
		$operadores = $this->loadModel('Operadores');
		$operacion = $this->loadModel('Operacion');
		$valores = $operadores->getDataOperador($id_operador);
		$comision = $operacion->getComision($id_operador);
		require URL_VISTA.'modales/operadores/comision_operador.php';
	}
	public function comision_operador_do(){
		$this->se_requiere_logueo(true,'Operadores|comisiones');
		$operadores = $this->loadModel('Operadores');
		$operadores->setComision($_POST);
		print json_encode(array('resp' => true));
	}
	public function setComisionDeault($id_operador){
		$this->se_requiere_logueo(true,'Operadores|comisiones');
		$operadores = $this->loadModel('Operadores');
		$operacion = $this->loadModel('Operacion');

		$operadores->setComisionDeault($id_operador);
		$comision = $operacion->getComision($id_operador);
		print json_encode(array('resp' => true,'comision' => $comision));
	}
}
?>
