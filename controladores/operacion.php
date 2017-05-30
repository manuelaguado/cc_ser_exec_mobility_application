<?php
class Operacion extends Controlador
{
    public function index()
    {
	$this->se_requiere_logueo(true,'Operacion|index');
       require URL_VISTA.'operacion/index.php';
    }
	public function cron(){
              //exit();
              session_destroy();

		$share = $this->loadModel('Share');
		$operacion = $this->loadModel('Operacion');

		if($operacion->cordon_hash(1)){$share->transmitir('doit','updcrd1');}
		if($operacion->serv_cve_hash(179)){$share->transmitir('doit','updasignados');}
		if($operacion->servicio_hash(170)){$share->transmitir('doit','updpendientes');}
		if($operacion->servicio_hash(188)){$share->transmitir('doit','updpendientes');}

              $operacion->asignar_viajes(1,$share);

	}
	public function cron10(){
		session_destroy();
		$share = $this->loadModel('Share');
		$operacion = $this->loadModel('Operacion');
		$notificaciones = $operacion->notificacionesApartados();
		$share->transmitir(json_encode($notificaciones),'notificarApartados');
	}
       public function modal_activar_c1($id_operador, $num){
		$this->se_requiere_logueo(true,'Operacion|activar_c1');
		$model = $this->loadModel('Operacion');
		$vehiculos = $model->elegirVehiculo($id_operador);
		require URL_VISTA.'modales/operacion/activar_c01.php';
	}
	public function activar_c1($id_operador_unidad,$id_operador,$num){
		$this->se_requiere_logueo(true,'Operacion|activar_c1');
		$share = $this->loadModel('Share');
              $id_episodio = $share->getEpisodio($id_operador,$_SESSION['id_usuario'],$id_operador_unidad);

              $setStat['id_operador'] = $id_operador;
              $setStat['id_operador_unidad'] = $id_operador_unidad;
              $setStat['id_episodio'] = $id_episodio;
              $setStat['id_viaje'] = 'NULL';
              $setStat['num'] = $num;
              $setStat['state'] = 'C1';
              $setStat['flag1'] = 'C1';
              $setStat['flag2'] = 'F11';
              $setStat['flag3'] = 'NULL';
              $setStat['flag4'] = 'NULL';
              $setStat['motivo'] = 'NULL';

		$share->setStatOper($setStat);
		print json_encode(array('resp' => true , 'mensaje' => 'El operador inició operaciones correctamente.' ));
	}
       public function historia_viaje($id_viaje){
              $this->se_requiere_logueo(true,'Operacion|solicitud');
		$modelo = $this->loadModel('Operacion');
		$historia = $modelo->historia_viaje($id_viaje);
		require URL_VISTA.'modales/operacion/historia.php';
       }
       public function modal_activar_f6($id_operador, $num){
		$this->se_requiere_logueo(true,'Operacion|activar_f6');
		require URL_VISTA.'modales/operacion/activar_f06.php';
	}
	public function activar_f6($id_operador,$num){
		$this->se_requiere_logueo(true,'Operacion|activar_f6');
		$share = $this->loadModel('Share');
              $operacion = $this->loadModel('Operacion');

              $setStat['id_operador'] = $id_operador;
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

              $operacion->setF6($id_operador);
		$share->setStatOper($setStat);
		print json_encode(array('resp' => true , 'mensaje' => 'El operador se suspendió correctamente.' ));
	}
       public function modal_desactivar_f06($id_operador, $num){
		$this->se_requiere_logueo(true,'Operacion|desactivar_f06');
		require URL_VISTA.'modales/operacion/desactivar_f06.php';
	}
	public function desactivar_f06_do($id_operador,$num){
		$this->se_requiere_logueo(true,'Operacion|desactivar_f06');
		$share = $this->loadModel('Share');
              $operacion = $this->loadModel('Operacion');

              $setStat['id_operador'] = $id_operador;
              $setStat['id_operador_unidad'] = 'NULL';
              $setStat['id_episodio'] = 'NULL';
              $setStat['id_viaje'] = 'NULL';
              $setStat['num'] = $num;
              $setStat['state'] = 'C2';
              $setStat['flag1'] = 'C2';
              $setStat['flag2'] = 'NULL';
              $setStat['flag3'] = 'NULL';
              $setStat['flag4'] = 'NULL';
              $setStat['motivo'] = 'NULL';

              $operacion->unSetF6($id_operador);
		$share->setStatOper($setStat);
		print json_encode(array('resp' => true , 'mensaje' => 'El operador se suspendió correctamente.' ));
	}
	public function getTBUnits(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$model = $this->loadModel('Operacion');
		$operadores = $model->getTBUnits();
		require URL_VISTA.'modales/operacion/getTBUnits.php';
	}
       public function intoCordon(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$model = $this->loadModel('Operacion');
		$operadores = $model->getTBUnits();
		require URL_VISTA.'modales/operacion/intoCordon.php';
	}
       public function meteralCordon($id_episodio,$id_operador_unidad,$id_base,$statuscordon){
              $this->se_requiere_logueo(true,'Operacion|solicitud');
              $share = $this->loadModel('Share');
              $share->formarse_directo($id_episodio,$id_operador_unidad,$id_base,$statuscordon);
              print json_encode(array('resp' => true , 'mensaje' => 'El operador se formo correctamente.' ));
       }
	public function viajeAlAire($id_viaje){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$model = $this->loadModel('Operacion');
		$operadores = $model->getTBUnits();
		require URL_VISTA.'modales/operacion/viajeAlAire.php';
	}
	public function asignarViajeAlAire($id_operador_unidad, $id_operador, $id_viaje){

		$operacion = $this->loadModel('Operacion');
		$share = $this->loadModel('Share');

		$operador = $operacion->unidadalAire($id_operador_unidad);
		$operacion->asignar_viaje($id_viaje,$operador);

              $id_episodio = $share->getIdEpisodio($id_operador_unidad);
              $share->setstatlocal($id_operador,$id_operador_unidad,$id_episodio,'F15','C1','F15','NULL','NULL','Servicio al aire desde pendientes',$id_viaje);

		print json_encode(array('resp' => true ));
	}
	public function pulledApart(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$model = $this->loadModel('Operacion');
		$operadores = $model->pulledApart();
		$anterior = self::getConfig(1,'turno_apartados');
		$actual = $model->turnoApart($anterior['valor']);
		require URL_VISTA.'modales/operacion/pulledApart.php';
	}
	public function elegirVehiculo($id_operador){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$model = $this->loadModel('Operacion');
		$vehiculos = $model->elegirVehiculo($id_operador);
		require URL_VISTA.'modales/operacion/elegirVehiculo.php';
	}
	public function mensajeriaSettings(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');

		$model = $this->loadModel('Operacion');
		$operadores = $model->pulledApart();
		$anterior = self::getConfig(1,'turno_apartados');
		$actual = $model->turnoApart($anterior['valor']);

		require URL_VISTA.'modales/operacion/mensajeriaSettings.php';
	}
	public function paqueteriaSettings(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');

		$model = $this->loadModel('Operacion');
		$operadores = $model->pulledApart();
		$anterior = self::getConfig(1,'turno_apartados');
		$actual = $model->turnoApart($anterior['valor']);

		require URL_VISTA.'modales/operacion/paqueteriaSettings.php';
	}
	public function servicios_asignados(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$modelo = $this->loadModel('Operacion');
		print $modelo->servicios_asignados($_POST);
	}
	public function servicios_pendientes(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$modelo = $this->loadModel('Operacion');
		print $modelo->servicios_pendientes($_POST);
	}
	public function listado_completados(){
		$this->se_requiere_logueo(true,'Operacion|listado_completados');
		require URL_VISTA.'operacion/listado_completados.php';
	}
	public function listado_cancelados(){
		$this->se_requiere_logueo(true,'Operacion|listado_cancelados');
		require URL_VISTA.'operacion/listado_cancelados.php';
	}

	public function completados(){
		$this->se_requiere_logueo(true,'Operacion|listado_completados');
		$modelo = $this->loadModel('Operacion');
		print $modelo->completados($_POST);
	}
	public function cancelados(){
		$this->se_requiere_logueo(true,'Operacion|listado_cancelados');
		$modelo = $this->loadModel('Operacion');
		print $modelo->cancelados($_POST);
	}
	public function iframeFullModalD(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$_SESSION['busqueda_ciudad'] = '102';
		require URL_VISTA.'modales/operacion/iframeFullModalD.php';
	}
	public function iframeSetReferenceD(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		require URL_VISTA.'modales/operacion/iframeSetReferenceD.php';
	}
	public function iframeFullModal(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$_SESSION['busqueda_ciudad'] = '102';
		require URL_VISTA.'modales/operacion/iframeFullModal.php';
	}
	public function iframeSetReference(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		require URL_VISTA.'modales/operacion/iframeSetReference.php';
	}
	public function mapCoordSelect_origen(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		require URL_VISTA.'modales/operacion/mapCoordSelect_origen.php';
	}
	public function mapCoordSelect_destino(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		require URL_VISTA.'modales/operacion/mapCoordSelect_destino.php';
	}
	public function activar_cancelacion($id_viaje){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		require URL_VISTA.'modales/operacion/activar_cancelacion.php';
	}
	public function activar_abandono($id_viaje){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		require URL_VISTA.'modales/operacion/activar_abandono.php';
	}
	public function costos_adicionales($id_viaje){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$cat_concepto = $this->selectCatalog('costos_adicionales',null);
		require URL_VISTA.'modales/operacion/costos_adicionales.php';
	}
	public function costos_adicionales_get($id_viaje){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$operacion = $this->loadModel('Operacion');
		print $operacion->queryCostosAdicionales($_POST,$id_viaje);
	}
	public function eliminar_costoAdicional($id_costos_adicionales){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$operacion = $this->loadModel('Operacion');
		$ok = $operacion->eliminar_costoAdicional($id_costos_adicionales);
		print json_encode($ok);
	}
	public function cambiar_tarifa($id_viaje){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$operacion = $this->loadModel('Operacion');
		$id_cliente = $operacion->getIdCliente($id_viaje);
		$current_tarifa = $operacion->currentTarifa($id_viaje);
		$id_company = $operacion->id_company($id_cliente);
		$tarifas = $operacion->queryTarifas($id_company);

		require URL_VISTA.'modales/operacion/cambiar_tarifa.php';
	}
	public function activar_cancelacion_do($id_viaje){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$share = $this->loadModel('Share');
		$operacion = $this->loadModel('Operacion');
		$id_operador_unidad = $operacion->getIdOperadorUnidadViaje($id_viaje);
		$token = 'OP:'.$this->token(62);
		$share->storeToSyncRide($_SESSION['id_usuario'],$token,117,$id_operador_unidad);
		// no existe el broadcast en version noMobile $share->broadcast($id_operador_unidad);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}
	public function activar_abandono_do($id_viaje){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$share = $this->loadModel('Share');
		$operacion = $this->loadModel('Operacion');
		$id_operador_unidad = $operacion->getIdOperadorUnidadViaje($id_viaje);
		$token = 'OP:'.$this->token(62);
		$share->storeToSyncRide($_SESSION['id_usuario'],$token,185,$id_operador_unidad);
		// no existe el broadcast en version noMobile $share->broadcast($id_operador_unidad);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}
	public function costos_adicionales_do(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$modelo = $this->loadModel('Operacion');
		print json_encode($modelo->addCostoAdicional($_POST));
	}
	public function cambiar_tarifa_do($id_tarifa_cliente,$id_viaje){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$modelo = $this->loadModel('Operacion');
		$modelo->cambiar_tarifa($id_tarifa_cliente,$id_viaje);
	}
	public function solicitud(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$tiposServicios = $this->selectCatalog('tipo_servicio',null);
		$formaPago = $this->selectCatalog('formapago',null);
		$tipoSalida = $this->selectCatalog('tipo_salida',null);
		require URL_VISTA.'operacion/solicitud.php';
	}
	public function addClienteUsuario(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$tiposClientes = $this->selectCatalog('tipocliente',200);
		$satatusCliente = $this->selectCatalog('statuscliente',21);
			$modelo = $this->loadModel('Roles');
			$roles = $modelo->selectRolesByTipo(26,$_SESSION['id_rol'],5);
		require URL_VISTA.'modales/clientes/add_usuario_desde_solicitud.php';
	}
	public function listadoEmpresas(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$consulta = $_GET['query'];
		$model = $this->loadModel('Clientes');
		print $model->listadoEmpresas($consulta);
	}
	public function add_user_client(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$modelo = $this->loadModel('Clientes');
		print $modelo->add_client_children($_POST);
	}
	public function paqMsgSelect_salida(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		require URL_VISTA.'modales/operacion/paqMsgSelect_salida.php';
	}
	public function selectOrigenes($id_cliente){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$model = $this->loadModel('Clientes');
		print $model->selectOrigenes($id_cliente);
	}
	public function selectDestinos($id_cliente){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$model = $this->loadModel('Clientes');
		print $model->selectDestinos($id_cliente);
	}
	public function modal_extra_origen(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		require URL_VISTA.'modales/operacion/extra_origen.php';
	}
	public function modal_extra_destino(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		require URL_VISTA.'modales/operacion/extra_destino.php';
	}
	public function busqueda_usuario(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$consulta = $_GET['query'];
		$model = $this->loadModel('Operacion');
		print $model->busqueda_usuario($consulta);
	}
	public function programados(){
		$this->se_requiere_logueo(true,'Operacion|programados');
		require URL_VISTA.'operacion/programados.php';
	}
	public function programados_rojo(){
		$this->se_requiere_logueo(true,'Operacion|programados');
		$modelo = $this->loadModel('Operacion');
		print $modelo->programados_rojo($_POST);
	}
	public function programados_naranja(){
		$this->se_requiere_logueo(true,'Operacion|programados');
		$modelo = $this->loadModel('Operacion');
		print $modelo->programados_naranja($_POST);
	}
	public function programados_amarillo(){
		$this->se_requiere_logueo(true,'Operacion|programados');
		$modelo = $this->loadModel('Operacion');
		print $modelo->programados_amarillo($_POST);
	}
	public function programados_verde(){
		$this->se_requiere_logueo(true,'Operacion|programados');
		$modelo = $this->loadModel('Operacion');
		print $modelo->programados_verde($_POST);
	}
	public function programados_gris(){
		$this->se_requiere_logueo(true,'Operacion|programados');
		$modelo = $this->loadModel('Operacion');
		print $modelo->programados_gris($_POST);
	}
	public function set_status_viaje($id_viaje,$stat,$origen){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		switch($stat){
			case '170':/*Solicitado - Pendiente*/
				$write['title'] = 'Poner servicio en solicitado';
				$write['accion'] = 'retornará a pendientes';
				$write['boton'] = 'retornar a pendientes el';
				break;
			case '171':/*En proceso*/
				$write['title'] = 'Poner servicio en proceso';
				$write['accion'] = 'pondrá en proceso';
				$write['boton'] = 'procesar';
				break;
			case '172':/*Completado*/
				$write['title'] = 'Completar servicio';
				$write['accion'] = 'completará';
				$write['boton'] = 'completar';
				break;
			case '173':/*Cancelado*/
				$write['title'] = 'Cancelar servicio';
				$write['accion'] = 'cancelará';
				$write['boton'] = 'cancelar';
				$razones_cancelacion = $this->selectCatalog('cancelaciones',null);
				break;
			case '179':/*Asignado*/
				$write['title'] = 'Poner servicio en asignado';
				$write['accion'] = 'asignará';
				$write['boton'] = 'asignar';
				break;
			default:
				exit('variable incorrecta');
				break;
		}
		require URL_VISTA.'modales/operacion/set_status_viaje.php';
	}
	public function setear_status_viaje(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$modelo = $this->loadModel('Operacion');
		$share = $this->loadModel('Share');
		$operadores = $this->loadModel('Operadores');
		print $modelo->setear_status_viaje($_POST, $share, $operadores);
	}
	public function cancel_apartado($id_viaje,$origen){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$razones_cancelacion = $this->selectCatalog('cancelaciones',null);
		require URL_VISTA.'modales/operacion/cancel_apartado.php';
	}







	public function apartado2pendientes($id_viaje,$origen){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$model = $this->loadModel('Operacion');
		$vigente = $model->viajeVigente($id_viaje);
		$alAire = true;
		$formado = false;

		if($vigente){
			require URL_VISTA.'modales/operacion/apartado2pendientes.php';
		}else{
			require URL_VISTA.'modales/operacion/procesarException.php';
		}
	}
	public function apartado2pendientesDo(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$modelo = $this->loadModel('Operacion');
		print $modelo->apartado2pendientesDo($_POST);
	}


	public function apartadoAlAire($id_viaje,$origen){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$model = $this->loadModel('Operacion');
		$operadores = $model->getTBUnits();

		$vigente = $model->viajeVigente($id_viaje);
		$alAire = true;
		$formado = false;

		if($vigente){
			require URL_VISTA.'modales/operacion/apartadoAlAire.php';
		}else{
			require URL_VISTA.'modales/operacion/procesarException.php';
		}
	}
	public function asignarApartadoAlAire($id_operador_unidad, $id_operador, $id_viaje){

		$operacion = $this->loadModel('Operacion');
		$share = $this->loadModel('Share');

		$operador = $operacion->unidadalAire($id_operador_unidad);
		$operacion->asignarApartadoAlAire($id_viaje,$operador);

              $setStat['id_operador'] = $operador['id_operador'];
              $setStat['id_operador_unidad'] = $id_operador_unidad;
              $setStat['id_episodio'] = $operador['id_episodio'];
              $setStat['id_viaje'] = $id_viaje;
              $setStat['num'] = $operador['num'];
              $setStat['state'] = 'T2';
              $setStat['flag1'] = 'C1';
              $setStat['flag2'] = 'T2';
              $setStat['flag3'] = 'NULL';
              $setStat['flag4'] = 'NULL';
              $setStat['motivo'] = 'Se asignó el apartado al aire';

		$share->setStatOper($setStat);

		print json_encode(array('resp' => true ));
	}
	public function procesarNormal($id_viaje,$origen){
		$this->se_requiere_logueo(true,'Operacion|solicitud');

		$operacion = $this->loadModel('Operacion');
		$bases = $this->loadModel('Bases');

		$id_operador_unidad = $operacion->getIdOperadorUnidadViaje($id_viaje);
		$alAire = $operacion->alAire($id_operador_unidad);
		$formado = $operacion->formadoAnyBase($bases, $id_operador_unidad);
		$vigente = $operacion->viajeVigente($id_viaje);


		if(($alAire)&&(!$formado)&&($vigente)){
			require URL_VISTA.'modales/operacion/procesarNormal.php';
		}else{
			require URL_VISTA.'modales/operacion/procesarException.php';
		}
	}
	public function procesarNormalDo(){
		$operacion = $this->loadModel('Operacion');
		$share = $this->loadModel('Share');
		$id_viaje = $_POST['id_viaje'];
		$id_operador_unidad = $operacion->getIdOperadorUnidadViaje($id_viaje);

		$operador = $operacion->unidadalAire($id_operador_unidad);
		$operacion->activarApartado($id_viaje,$operador);

              $setStat['id_operador'] = $operador['id_operador'];
              $setStat['id_operador_unidad'] = $id_operador_unidad;
              $setStat['id_episodio'] = $operador['id_episodio'];
              $setStat['id_viaje'] = $_POST['id_viaje'];
              $setStat['num'] = $operador['num'];
              $setStat['state'] = 'T1';
              $setStat['flag1'] = 'C1';
              $setStat['flag2'] = 'T1';
              $setStat['flag3'] = 'NULL';
              $setStat['flag4'] = 'NULL';
              $setStat['motivo'] = 'Se asignó el apartado de forma normal';

		$share->setStatOper($setStat);

		print json_encode(array('resp' => true ));
	}
	public function cancel_apartado_set(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$operacion = $this->loadModel('Operacion');
		$share = $this->loadModel('Share');

		print $operacion->cancel_apartado_set($_POST, $share);
	}
	public function setPageRemotly(){
		$this->se_requiere_logueo(true,'Operadores|set_page_remotly');
		$share = $this->loadModel('Share');
		$token = 'OP:'.$this->token(62);
		$id_operador_unidad = $share->getIdOperadorUnidadEpisode($_POST['id_operador'],'id_operador');
		$share->storeToSyncRide($_SESSION['id_usuario'],$token,153,$id_operador_unidad,true,$_POST['page']);
		print json_encode(array('resp' => true ));
	}
	public function set_page_remotly($id_operador){
		$this->se_requiere_logueo(true,'Operadores|set_page_remotly');
		require URL_VISTA.'modales/operacion/set_page_remotly.php';
	}
	public function check_standinLine($id_operador){
		$this->se_requiere_logueo(true,'Operacion|check_standinLine');
		$model = $this->loadModel('Operacion');
		$share = $this->loadModel('Share');

		$geotime = $share->enGeocercas($id_operador);

		$session = $model->getActiveSession($id_operador);
		$connected = $share->inLinkedIn($id_operador);
		$engeocerca1 = $geotime['geo1'];
		$engeocerca2 =  $geotime['geo2'];
		$estaEnC1 = $model->c1orc2($id_operador);
		$intime =  $geotime['time'];
		$solicitud = $model->getSolicitudF14Activa($id_operador);
		$encordon1 = $model->formadoenBase($id_operador,1);
		$encordon2 = $model->formadoenBase($id_operador,2);

		require URL_VISTA.'modales/operacion/check_standinLine.php';
	}
	public function modal_mensajeria($id_operador){
		$this->se_requiere_logueo(true,'Operacion|mensajeria');
		require URL_VISTA.'modales/operacion/mensajeria.php';
	}
	public function guardar_mensaje(){
		$this->se_requiere_logueo(true,'Operacion|mensajeria');
		$model = $this->loadModel('Operacion');
		$model->guardar_mensaje($_POST);
	}
	public function broadcast_all(){
		$this->se_requiere_logueo(true,'Operacion|broadcast_all');
		require URL_VISTA.'modales/operacion/broadcast_all.php';
	}
	public function enviar_emision(){
		$this->se_requiere_logueo(true,'Operacion|broadcast_all');
		$share = $this->loadModel('Share');

		$mensaje = array(
			'mensaje' => $_POST['mensaje']
		);
		$share->transmitir(json_encode($mensaje),'broadcast');
		print json_encode(array('resp'=>true));
	}
	public function delivery_stat($id_mensaje){
		$this->se_requiere_logueo(true,'Operacion|mensajeria');
		$model = $this->loadModel('Operacion');
		$model->delivery_stat($id_mensaje);
	}

	public function modal_activar_c06($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		require URL_VISTA.'modales/operacion/activar_c06.php';
	}
	public function aut_c06($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		$share = $this->loadModel('Share');
		$share->set2enc6($id_base);
		$share->cordonCompletado($_SESSION['id_usuario'],$id_operador_unidad,$id_base);
		$token = 'OP:'.$this->token(62);
		$share->storeToSyncRide($_SESSION['id_usuario'],$token,122,$id_operador_unidad);
		// no disponible en la version noMobile $share->broadcast($id_operador_unidad);
		//$share->formarse_directo($id_episodio,$id_operador_unidad,$id_base,115);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}

	public function modal_activar_c02($id_operador, $num, $id_operador_unidad){
              $this->se_requiere_logueo(true,'Operacion|activar_c2');
		require URL_VISTA.'modales/operacion/activar_c02.php';
	}
	public function aut_c02($id_operador_unidad,$id_operador,$num){
		$this->se_requiere_logueo(true,'Operacion|activar_c2');
              $share = $this->loadModel('Share');
              $share->cordonCompletado($_SESSION['id_usuario'],$id_operador_unidad,1);
              $id_episodio = $share->getIdEpisodio($id_operador_unidad);
              $share->cerrarEpisodio($id_episodio,$_SESSION['id_usuario']);

              $setStat['id_operador'] = $id_operador;
              $setStat['id_operador_unidad'] = $id_operador_unidad;
              $setStat['id_episodio'] = $id_episodio;
              $setStat['id_viaje'] = 'NULL';
              $setStat['num'] = $num;
              $setStat['state'] = 'C2';
              $setStat['flag1'] = 'C2';
              $setStat['flag2'] = 'NULL';
              $setStat['flag3'] = 'NULL';
              $setStat['flag4'] = 'NULL';
              $setStat['motivo'] = 'NULL';

		$share->setStatOper($setStat);
		print json_encode(array('resp' => true , 'mensaje' => 'El operador inició operaciones correctamente.' ));
	}

	public function modal_activar_f14($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		require URL_VISTA.'modales/operacion/activar_f14.php';
	}
	public function aut_f14($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		$share = $this->loadModel('Share');
		$share->cordonCompletado($_SESSION['id_usuario'],$id_operador_unidad,$id_base);
		$token = 'OP:'.$this->token(62);
		$share->storeToSyncRide($_SESSION['id_usuario'],$token,122,$id_operador_unidad);
		// no disponible en la version noMobile $share->broadcast($id_operador_unidad);
		//$share->formarse_directo($id_episodio,$id_operador_unidad,$id_base,113);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}

	public function modal_activar_f06($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		require URL_VISTA.'modales/operacion/activar_f06.php';
	}
	public function aut_f06($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		$share = $this->loadModel('Share');
		$share->cordonCompletado($_SESSION['id_usuario'],$id_operador_unidad,$id_base);
		$id_operador = $share->getIdOperador($id_operador_unidad);
		$model2 = $this->loadModel('Operadores');
		$model2->setearstatusoperador(array('cat_statusoperador'=>'10','id_operador'=>$id_operador));
		$token = 'OP:'.$this->token(62);
		$share->storeToSyncRide($_SESSION['id_usuario'],$token,153,$id_operador_unidad,true,'inicio');
		// no disponible en la version noMobile $share->broadcast($id_operador_unidad);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}

	public function modal_activar_out($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		require URL_VISTA.'modales/operacion/activar_out.php';
	}
	public function aut_out($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$share = $this->loadModel('Share');
		$share->cordonCompletado($_SESSION['id_usuario'],$id_operador_unidad,$id_base);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}


	public function verificar_a10($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		$share = $this->loadModel('Share');

		$send = 0;
		for($i=0;$i<=15;$i++){

			$turno = $share->turno($id_operador_unidad,$id_base);

			if($turno == 'No formado'){
				$state = array('state' => 1);
				$send = 1;
			}else{
				$state = array('state' => 0);
			}

			if($send = 1){
				$share->transmitir(json_encode($state),'verify_a10_'.$id_operador_unidad.'');
				exit();
			}

			sleep(2);
		}
		print json_encode(array('resp' => true ));
	}
	public function modal_activar_a10($id_operador_unidad,$base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		require URL_VISTA.'modales/operacion/activar_a10.php';
	}
	public function activar_a10($id_operador_unidad,$base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		$share = $this->loadModel('Share');
		$token = 'OP:'.$this->token(62);
		$share->storeToSyncRide($_SESSION['id_usuario'],$token,118,$id_operador_unidad);
		// no disponible en la version noMobile $share->broadcast($id_operador_unidad);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}

	public function modal_activar_f13($id_operador_unidad){
		$this->se_requiere_logueo(true,'Operacion|activar_f13');
		require URL_VISTA.'modales/operacion/activar_f13.php';
	}
	public function activar_f13($id_operador_unidad){
		$this->se_requiere_logueo(true,'Operacion|activar_f13');
		$share = $this->loadModel('Share');
		$token = 'OP:'.$this->token(62);
		$share->storeToSyncRide($_SESSION['id_usuario'],$token,119,$id_operador_unidad);
		// no disponible en la version noMobile $share->broadcast($id_operador_unidad);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}
	public function dataGeocerca($geocerca){
		$geoarray = explode(' ',$geocerca);
		$num = 0;
		foreach($geoarray as $group){
			$angulares = explode(',',$group);
			$coords[$num]['lat'] = $angulares[1];
			$coords[$num]['lng'] = $angulares[0];
			$num++;
		}
		return $coords;
	}
	public function cordon_kpmg(){
		$this->se_requiere_logueo(true,'Operacion|cordon_kpmg');
		$modelo = $this->loadModel('Operacion');
		require URL_VISTA.'operacion/cordon_kpmg.php';
	}
	public function cordon_kpmg_get(){
		$this->se_requiere_logueo(true,'Operacion|cordon_kpmg');
		$modelo = $this->loadModel('Operacion');
		print $modelo->cordon_get($_POST,1);
	}
	public function dataViaje($id_viaje){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$operacion = $this->loadModel('Operacion');
		$data = $operacion->dataViaje($id_viaje);
		require URL_VISTA.'modales/operacion/dataViaje.php';
	}
       public function selectClave($id_viaje){
              $this->se_requiere_logueo(true,'Operacion|solicitud');
              $operacion = $this->loadModel('Operacion');

		$claves = $operacion->selectClave();

		require URL_VISTA.'modales/operacion/selectClave.php';
       }
       public function setClaveNum($id_viaje,$clave){
              $this->se_requiere_logueo(true,'Operacion|solicitud');
              require URL_VISTA.'modales/operacion/setClaveNumConfirm.php';
       }
       public function setClaveOk($id_viaje,$clave){
              $this->se_requiere_logueo(true,'Operacion|solicitud');
              $operacion = $this->loadModel('Operacion');
              $share = $this->loadModel('Share');
              $operacion->setClaveOk($id_viaje,$clave,$share);
              print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
       }
	public function cordon_ejnal(){
		$this->se_requiere_logueo(true,'Operacion|cordon_ejnal');
		$modelo = $this->loadModel('Operacion');
		require URL_VISTA.'operacion/cordon_ejnal.php';
	}
	public function cordon_ejnal_get(){
		$this->se_requiere_logueo(true,'Operacion|cordon_ejnal');
		$modelo = $this->loadModel('Operacion');
		print $modelo->cordon_get($_POST,2);
	}

	public function cordon_hash($base){
		$this->se_requiere_logueo(true,'Operacion|cordon_kpmg');
		$modelo = $this->loadModel('Operacion');
		print $modelo->cordon_hash($base);
	}
	public function suspendidas(){
		$this->se_requiere_logueo(true,'Operacion|suspendidas');
		require URL_VISTA.'operacion/suspendidas.php';
	}
	public function suspendidas_get(){
		$this->se_requiere_logueo(true,'Operacion|suspendidas');
		$modelo = $this->loadModel('Operacion');
		print $modelo->suspendidas_get($_POST);
	}
	public function activos(){//c1
		$this->se_requiere_logueo(true,'Operacion|activos');
		require URL_VISTA.'operacion/activos.php';
	}
	public function activos_get(){
		$this->se_requiere_logueo(true,'Operacion|activos');
		$modelo = $this->loadModel('Operacion');
		print $modelo->activos_get($_POST);
	}
	public function inactivos(){//c2
		$this->se_requiere_logueo(true,'Operacion|inactivos');
		require URL_VISTA.'operacion/inactivos.php';
	}
	public function inactivos_get(){
		$this->se_requiere_logueo(true,'Operacion|inactivos');
		$modelo = $this->loadModel('Operacion');
		print $modelo->inactivos_get($_POST);
	}
	public function tiempo_base(){
		$this->se_requiere_logueo(true,'Operacion|tiempo_base');
		require URL_VISTA.'operacion/tiempo_base.php';
	}
	public function tiempo_base_get(){
		$this->se_requiere_logueo(true,'Operacion|inactivos');
		$modelo = $this->loadModel('Operacion');
		print $modelo->tiempo_base_get($_POST);
	}
	public function getTarifa($id_cliente){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$operacion = $this->loadModel('Operacion');
		$tarifa = $operacion->id_tarifa_cliente($id_cliente);
		print json_encode(array('resp' => true, 'tarifa' => $tarifa ));
	}
	public function procesar_servicio(){
		////////////////////////////////////////////////////////////////////permisos y modelo
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$cliente= $this->loadModel('Clientes');
		$operacion = $this->loadModel('Operacion');
		$share = $this->loadModel('Share');
		////////////////////////////////////////////////////////////////////variables del form
		$num = 0;
		$service = new stdClass;
		foreach ($_POST as $key => $value) {
			$service->$key = $value;
			if(substr($key,0, 8) == 'usuario_'){
				$clientes[$num] = $value;
				if($num == 0){$service->id_cliente = $value;}
				$num++;
			}
		}

		////////////////////////////////////////////////////////////////////crear directorio
		if(empty($_POST['id_cliente_origen'])) {
			$service->id_cliente_origen = $cliente->insertOrigen($service);
		}
		if(empty($_POST['id_cliente_destino'])) {
			$service->id_cliente_destino = $cliente->insertDestino($service);
		}

		////////////////////////////////////////////////////////////////////inserta viaje
		$service->id_viaje = $operacion->insert_viaje($service);
		$operacion->insert_detallesViaje($service);
		$operacion->insert_formaPago($service);
		$operacion->insert_viajeDestino($service);

		////////////////////////////////////////////////////////////////////inserta clientes
		foreach($clientes as $key => $id_cliente){
			$operacion->insert_viajeClientes($service->id_viaje,$id_cliente);
		}
		////////////////////////////////////////////////////////////////////servicio de apartado
		if($service->temporicidad == 162){
			$turno = $service->turno_apartado;
			$num_eq = $service->numero_economico;
			$hit = ($num_eq == $turno)?true:false;
			$operacion->countApart($service->id_operador,$hit,$service->id_operador_turno);

				$data['id_site']	= 1;
				$data['descripcion'] = 'turno_apartados';
				$data['valor']	= $turno;
				$data['tmp_val']	= 0;
				$data['data']		= 0;

			self::setConfig($data);
                     
			$operacion->asignar_apartado($service->id_viaje,$service->id_operador_unidad);

		}
		////////////////////////////////////////////////////////////////////servicio al aire
		if($service->cat_tipo_salida == 181){
                     $id_episodio = $share->getIdEpisodio($service->id_operador_unidad);
                     $operador['id_operador_unidad']=$service->id_operador_unidad;
                     $operador['id_episodio']=$id_episodio;
                     $operador['id_cordon']='';
			$operacion->asignar_viaje($service->id_viaje,$operador);

                     $share->setstatlocal($service->id_operador,$service->id_operador_unidad,$id_episodio,'F15','C1','F15','NULL','NULL','Servicio al aire desde solicitud',$service->id_viaje);
		}

		////////////////////////////////////////////////////////////////////salida por sitio
		if($service->cat_tipo_salida == 182){
			$operador = $operacion->unidadenSitio($service->sitio_select_oper,1);
			$operacion->asignar_viaje($service->id_viaje,$operador);

                     $share->cordonFinishSuccess($_SESSION['id_usuario'],$operador['id_operador_unidad'],$service->id_viaje);

                     $share->setstatlocal($operador['id_operador'],$operador['id_operador_unidad'],$operador['id_episodio'],'F13','C1','F13','NULL','NULL','Salida por sitio',$service->id_viaje);

		}
		print json_encode(array('resp' => true ));
	}

}
?>
