	<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<?php
class Operacion extends Controlador
{
    public function index()
    {
		$this->se_requiere_logueo(true,'Operacion|index');
        require URL_VISTA.'operacion/index.php';
    }
	public function clearGps(){
		$modelo = $this->loadModel('Operacion');
		$modelo->caducarGps();
	}
	public function cron(){
		
		$mobile = $this->loadModel('Mobile');
		$operacion = $this->loadModel('Operacion');
		
		$relTravel = $operacion->asignar_viajes(1);
		if($relTravel['process']){
			self::asignacion_automatica($relTravel,$mobile);
		}
		
		if($operacion->cordon_hash(1)){$mobile->transmitir('doit','updcrd1');}
		if($operacion->cordon_hash(2)){$mobile->transmitir('doit','updcrd2');}
		
		if($operacion->servicio_hash(170)){$mobile->transmitir('doit','updpendientes');}
		if($operacion->servicio_hash(171)){$mobile->transmitir('doit','updproceso');}
		if($operacion->serv_cve_hash(179)){$mobile->transmitir('doit','updasignados');}
		
		$eventos = $mobile->sync_ride();
		foreach ($eventos as $evento){
			$mobile->broadcast($evento['id_operador_unidad']);
		}
		
	}
	public function getTBUnits(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$model = $this->loadModel('Operacion');
		$operadores = $model->getTBUnits();
		require URL_VISTA.'modales/operacion/getTBUnits.php';
	}
	public function viajeAlAire($id_viaje){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$model = $this->loadModel('Operacion');
		$operadores = $model->getTBUnits();
		require URL_VISTA.'modales/operacion/viajeAlAire.php';
	}
	public function asignarViajeAlAire($id_operador_unidad, $id_operador, $id_viaje){
		
		$operacion = $this->loadModel('Operacion');
		$mobile = $this->loadModel('Mobile');
		
		$operador = $operacion->unidadalAire($id_operador_unidad);
		$operacion->asignar_viaje($id_viaje,$operador);
		
		$relTravel['id_operador_unidad'] = $id_operador_unidad;
		$relTravel['id_viaje'] 	= $id_viaje;
		$relTravel['salida'] 	= 120;
		
		self::asignacion_automatica($relTravel,$mobile);
		
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
	public function asignacion_automatica($array,MobileModel $model){
		$token = 'AAT:'.$this->token(62);
		$model->setCveStore(1,$token,$array['salida'],$array['id_operador_unidad']);
	}
	public function servicios_asignados(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$modelo = $this->loadModel('Operacion');
		print $modelo->servicios_asignados($_POST);
	}
	public function servicios_enProceso(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$modelo = $this->loadModel('Operacion');
		print $modelo->servicios_enProceso($_POST);
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
	
	public function inmediatos_completados(){
		$this->se_requiere_logueo(true,'Operacion|listado_completados');
		$modelo = $this->loadModel('Operacion');
		print $modelo->inmediatos_completados($_POST);
	}
	public function inmediatos_cancelados(){
		$this->se_requiere_logueo(true,'Operacion|listado_cancelados');
		$modelo = $this->loadModel('Operacion');
		print $modelo->inmediatos_cancelados($_POST);
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
		require URL_VISTA.'modales/operacion/costos_adicionales.php';
	}
	public function cambiar_tarifa($id_viaje){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		require URL_VISTA.'modales/operacion/cambiar_tarifa.php';
	}

	
	
	public function activar_cancelacion_do($id_viaje){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$mobile = $this->loadModel('Mobile');
		$operacion = $this->loadModel('Operacion');
		$id_operador_unidad = $operacion->getIdOperadorUnidad($id_viaje);
		$token = 'OP:'.$this->token(62);
		$mobile->setCveStore($_SESSION['id_usuario'],$token,117,$id_operador_unidad);
		$mobile->broadcast($id_operador_unidad);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}
	public function activar_abandono_do($id_viaje){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$mobile = $this->loadModel('Mobile');
		$operacion = $this->loadModel('Operacion');
		$id_operador_unidad = $operacion->getIdOperadorUnidad($id_viaje);
		$token = 'OP:'.$this->token(62);
		$mobile->setCveStore($_SESSION['id_usuario'],$token,185,$id_operador_unidad);
		$mobile->broadcast($id_operador_unidad);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}
	public function costos_adicionales_do(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$modelo = $this->loadModel('Operacion');
		print $modelo->costos_adicionales($_POST);
	}
	public function cambiar_tarifa_do(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$modelo = $this->loadModel('Operacion');
		print $modelo->cambiar_tarifa($_POST);
	}	
	
	
	
	
	
	
	
	
	
	
	
	
	public function solicitud(){
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$tiposServicios = $this->selectCatalog('tipo_servicio',null);
		$formaPago = $this->selectCatalog('formapago',null);
		$tipoSalida = $this->selectCatalog('tipo_salida',null);
		require URL_VISTA.'operacion/solicitud.php';
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
	public function programados_cancelados(){
		$this->se_requiere_logueo(true,'Operacion|programados');
		$modelo = $this->loadModel('Operacion');
		print $modelo->programados_cancelados($_POST);
	}
	public function programados_completados(){
		$this->se_requiere_logueo(true,'Operacion|programados');
		$modelo = $this->loadModel('Operacion');
		print $modelo->programados_completados($_POST);
	}
	public function set_page_remotly($id_operador){
		$this->se_requiere_logueo(true,'Operadores|set_page_remotly');
		require URL_VISTA.'modales/operacion/set_page_remotly.php';
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
		$mobil = $this->loadModel('Mobile');
		$operadores = $this->loadModel('Operadores');
		$login = $this->loadModel('Login');
		print $modelo->setear_status_viaje($_POST, $mobil, $operadores, $login);	
	}
	public function setPageRemotly(){
		$this->se_requiere_logueo(true,'Operadores|set_page_remotly');
		$model = $this->loadModel('Mobile');
		$token = 'OP:'.$this->token(62);
		$id_operador_unidad = $model->getIdOperadorUnidadOp($_POST['id_operador']);
		$model->setCveStore($_SESSION['id_usuario'],$token,153,$id_operador_unidad,true,$_POST['page']);
		print json_encode(array('resp' => true ));
	}
	public function check_standinLine($id_operador){
		$this->se_requiere_logueo(true,'Operacion|check_standinLine');
		$model = $this->loadModel('Operacion');
		$mobile = $this->loadModel('Mobile');
		
		$geotime = $mobile->enGeocercas($id_operador);
		
		$session = $model->getActiveSession($id_operador);
		$connected = $mobile->inLinkedIn($id_operador);
		$engeocerca1 = $geotime['geo1'];
		$engeocerca2 =  $geotime['geo2'];
		$estaEnC1 = $model->c1orc2($id_operador);
		$intime =  $geotime['time'];
		$solicitud = $model->getSolicitudF14Activa($id_operador);
		$encordon1 = $model->formadoenBase($id_operador,1);
		$encordon2 = $model->formadoenBase($id_operador,2);
		
		require URL_VISTA.'modales/operacion/check_standinLine.php';
	}
	public function enlazados(){
		$this->se_requiere_logueo(true,'Operacion|enlazados');
		$model = $this->loadModel('Operacion');
		$mobile = $this->loadModel('Mobile');
		
		$enlazados = array();
		$num = 0;
		$duplicados = array();
		
		if(SOCKET_PROVIDER == 'ABLY'){
			$activos = $mobile->linkedIn();
			foreach($activos->items as $num => $oper){
				$id_operador = substr($oper->clientId, 3);
				$data_enlace = $model->gatDataOperador($id_operador);
				$enlazados[$num]['c1orc2'] = $model->c1orc2($id_operador);
				$enlazados[$num]['id'] = $oper->id;
				$enlazados[$num]['connectionId'] = $oper->connectionId;
				$enlazados[$num]['timestamp'] = $oper->timestamp;
				$enlazados[$num]['clientId'] = $id_operador;
				$enlazados[$num]['num'] = $data_enlace['num'];
				$enlazados[$num]['nombre'] = $data_enlace['nombre'];
				$duplicados[] = $id_operador;
				$neconomico[$id_operador] = $data_enlace['num'];
				$num++;
			}
		}else if(SOCKET_PROVIDER == 'PUSHER'){
			if(PRESENCE_GET == 'CURL'){
				$activos = $mobile->linkedIn();
				foreach($activos['result']['users'] as $num => $item){
					foreach($item as $oper){
						$id_operador = $oper;
						$data_enlace = $model->gatDataOperador($id_operador);
						$enlazados[$num]['c1orc2'] = $model->c1orc2($id_operador);
						$enlazados[$num]['clientId'] = $id_operador;
						$enlazados[$num]['num'] = $data_enlace['num'];
						$enlazados[$num]['nombre'] = $data_enlace['nombre'];
						$duplicados[] = $id_operador;
						$neconomico[$id_operador] = $data_enlace['num'];
						$num++;
					}
				}
				$oper_link = count($activos['result']['users']);
			}else{
				$activos = $mobile->onLinkWebHook();
				foreach($activos as $num => $oper){
					$id_operador = $oper['id_operador'];
					$data_enlace = $model->gatDataOperador($id_operador);
					$enlazados[$num]['c1orc2'] = $model->c1orc2($id_operador);
					$enlazados[$num]['clientId'] = $id_operador;
					$enlazados[$num]['num'] = $data_enlace['num'];
					$enlazados[$num]['nombre'] = $data_enlace['nombre'];
					$duplicados[] = $id_operador;
					$neconomico[$id_operador] = $data_enlace['num'];
					$num++;
				}
				$oper_link = count($activos);
			}
		}else if(SOCKET_PROVIDER == 'PUBNUB'){
			if(PRESENCE_GET == 'CURL'){
				$activos = $mobile->linkedIn();
				foreach($activos['uuids'] as $num => $oper){
					$id_operador = $oper;
					$data_enlace = $model->gatDataOperador($id_operador);
					$enlazados[$num]['c1orc2'] = $model->c1orc2($id_operador);
					$enlazados[$num]['clientId'] = $id_operador;
					$enlazados[$num]['num'] = $data_enlace['num'];
					$enlazados[$num]['nombre'] = $data_enlace['nombre'];
					$duplicados[] = $id_operador;
					$neconomico[$id_operador] = $data_enlace['num'];
					$num++;
				}
				$oper_link = count($activos['uuids']);
			}else{
				$activos = $mobile->onLinkWebHook();
				foreach($activos as $num => $oper){
					$id_operador = $oper['id_operador'];
					$data_enlace = $model->gatDataOperador($id_operador);
					$enlazados[$num]['c1orc2'] = $model->c1orc2($id_operador);
					$enlazados[$num]['clientId'] = $id_operador;
					$enlazados[$num]['num'] = $data_enlace['num'];
					$enlazados[$num]['nombre'] = $data_enlace['nombre'];
					$duplicados[] = $id_operador;
					$neconomico[$id_operador] = $data_enlace['num'];
					$num++;
				}
				$oper_link = count($activos);
			}
		}

		$repeated = array_filter(array_count_values($duplicados), function($count) {
			return $count > 1;
		});
		$duplicado = array();
		$cuantas = array();
		$extras = 0;
		foreach ($repeated as $key => $value) {
			//$repetidos .= "El N° Economico ".$neconomico[$key]." cin id_operador $key tiene $value sesiones iniciadas. <br />";
			$duplicado[] = $key;
			$cuantas[$key] = $value;
			$extras += --$value;
		}
		
		
		if(SOCKET_PROVIDER == 'ABLY'){		
			require URL_VISTA.'operacion/enlace_ably.php';
		}else if(SOCKET_PROVIDER == 'PUSHER'){
			require URL_VISTA.'operacion/enlace_pusher.php';
		}else if(SOCKET_PROVIDER == 'PUBNUB'){
			require URL_VISTA.'operacion/enlace_pubnub.php';
		}
		
		
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
		$model = $this->loadModel('Mobile');
		
		$mensaje = array(
			'mensaje' => $_POST['mensaje']
		);
		$model->transmitir(json_encode($mensaje),'broadcast');
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
		$model = $this->loadModel('Mobile');
		$model->set2enc6($id_base);
		$model->cordonCompletado($_SESSION['id_usuario'],$id_operador_unidad,$id_base);
		$token = 'OP:'.$this->token(62);
		$model->setCveStore($_SESSION['id_usuario'],$token,122,$id_operador_unidad);
		$model->broadcast($id_operador_unidad);
		$model->formarse_directo($token,$id_operador_unidad,$id_base,115);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}
	
	public function modal_activar_c02($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		require URL_VISTA.'modales/operacion/activar_c02.php';
	}	
	public function aut_c02($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		$model = $this->loadModel('Mobile');
		$model->cordonCompletado($_SESSION['id_usuario'],$id_operador_unidad,$id_base);
		$id_operador = $model->getIdOperador($id_operador_unidad);
		$model->ponerEnC2($id_operador_unidad,$id_base,$id_operador);
		$model->cerrarEpisodio($model->getIdEpisodio($id_operador_unidad),$_SESSION['id_usuario']);
		$token = 'OP:'.$this->token(62);
		$model->setCveStore($_SESSION['id_usuario'],$token,153,$id_operador_unidad,true,'inicio');
		$model->broadcast($id_operador_unidad);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}
	
	public function modal_activar_f14($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		require URL_VISTA.'modales/operacion/activar_f14.php';
	}		
	public function aut_f14($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		$model = $this->loadModel('Mobile');
		$model->cordonCompletado($_SESSION['id_usuario'],$id_operador_unidad,$id_base);
		$token = 'OP:'.$this->token(62);
		$model->setCveStore($_SESSION['id_usuario'],$token,122,$id_operador_unidad);
		$model->broadcast($id_operador_unidad);
		$model->formarse_directo($token,$id_operador_unidad,$id_base,113);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}
	
	public function modal_activar_f06($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		require URL_VISTA.'modales/operacion/activar_f06.php';
	}	
	public function aut_f06($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		$model = $this->loadModel('Mobile');
		$model->cordonCompletado($_SESSION['id_usuario'],$id_operador_unidad,$id_base);
		$id_operador = $model->getIdOperador($id_operador_unidad);
		$model->ponerEnC2($id_operador_unidad,$id_base,$id_operador);
		$model2 = $this->loadModel('Operadores');
		$model2->setearstatusoperador(array('cat_statusoperador'=>'10','id_operador'=>$id_operador));
		$token = 'OP:'.$this->token(62);
		$model->setCveStore($_SESSION['id_usuario'],$token,153,$id_operador_unidad,true,'inicio');
		$model->broadcast($id_operador_unidad);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}

	public function modal_activar_out($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		require URL_VISTA.'modales/operacion/activar_out.php';
	}	
	public function aut_out($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		$model = $this->loadModel('Mobile');
		$model->cordonCompletado($_SESSION['id_usuario'],$id_operador_unidad,$id_base);
		$id_operador = $model->getIdOperador($id_operador_unidad);
		$model->broadcast($id_operador_unidad);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}	
	
	
	public function verificar_a10($id_operador_unidad,$id_base){
		$this->se_requiere_logueo(true,'Operacion|activar_a10');
		$model = $this->loadModel('Mobile');
		
		$send = 0;
		for($i=0;$i<=15;$i++){
			
			$turno = $model->turno($id_operador_unidad,$id_base);
			
			if($turno == 'No formado'){
				$state = array('state' => 1);
				$send = 1;
			}else{
				$state = array('state' => 0);
			}
			
			if($send = 1){
				$model->transmitir(json_encode($state),'verify_a10_'.$id_operador_unidad.'');
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
		$model = $this->loadModel('Mobile');
		$token = 'OP:'.$this->token(62);
		$model->setCveStore($_SESSION['id_usuario'],$token,118,$id_operador_unidad);
		$model->broadcast($id_operador_unidad);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}
	
	public function modal_activar_f13($id_operador_unidad){
		$this->se_requiere_logueo(true,'Operacion|activar_f13');
		require URL_VISTA.'modales/operacion/activar_f13.php';
	}
	public function activar_f13($id_operador_unidad){
		$this->se_requiere_logueo(true,'Operacion|activar_f13');
		$model = $this->loadModel('Mobile');
		$token = 'OP:'.$this->token(62);
		$model->setCveStore($_SESSION['id_usuario'],$token,119,$id_operador_unidad);
		$model->broadcast($id_operador_unidad);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}
	
	public function modal_activar_f15($id_operador_unidad){
		$this->se_requiere_logueo(true,'Operacion|activar_f15');
		require URL_VISTA.'modales/operacion/activar_f15.php';
	}
	public function activar_f15($id_operador_unidad){
		$this->se_requiere_logueo(true,'Operacion|activar_f15');
		$model = $this->loadModel('Mobile');
		$token = 'OP:'.$this->token(62);
		$model->setCveStore($_SESSION['id_usuario'],$token,120,$id_operador_unidad);
		$model->broadcast($id_operador_unidad);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}
	
	public function modal_activar_f16($id_operador_unidad){
		$this->se_requiere_logueo(true,'Operacion|activar_f16');
		require URL_VISTA.'modales/operacion/activar_f16.php';
	}
	public function activar_f16($id_operador_unidad){
		$this->se_requiere_logueo(true,'Operacion|activar_f16');
		$model = $this->loadModel('Mobile');
		$token = 'OP:'.$this->token(62);
		$model->setCveStore($_SESSION['id_usuario'],$token,121,$id_operador_unidad);
		$model->broadcast($id_operador_unidad);
		print json_encode(array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' ));
	}
	
	public function panorama($page){
		$this->se_requiere_logueo(true,'Operacion|index');
		require URL_VISTA.'operacion/panorama.php';
	}	
	public function panorama_c1(){
		$this->se_requiere_logueo(true,'Operacion|panorama_c1');
		$unitsIn = self::panorama_get_c1();
		require URL_VISTA.'operacion/panorama_c1.php';
	}
	public function panorama_c8(){
		$this->se_requiere_logueo(true,'Operacion|panorama_c8');
		$unitsIn = self::panorama_get_c8();
		require URL_VISTA.'operacion/panorama_c8.php';
	}
	public function panorama_a11(){
		$this->se_requiere_logueo(true,'Operacion|panorama_a11');
		$unitsIn = self::panorama_get_a11();
		require URL_VISTA.'operacion/panorama_a11.php';
	}
	public function panorama_kpmg(){
		$this->se_requiere_logueo(true,'Operacion|panorama_kpmg');
		$mobile = $this->loadModel('Mobile');
		$unitsIn = self::panorama_get_base($mobile,'B1');
		$geoDraw = self::drawGeocerca($mobile,'B1');
		require URL_VISTA.'operacion/panorama_kpmg.php';
	}
	public function panorama_kpmg_cordon(){
		$this->se_requiere_logueo(true,'Operacion|panorama_kpmg_cordon');
		$mobile = $this->loadModel('Mobile');
		$geoDraw = self::drawGeocerca($mobile,'B1');
		$unitsIn = self::panorama_get_cordon('1');
		require URL_VISTA.'operacion/panorama_kpmg_cordon.php';
	}
	public function panorama_ejnal(){
		$this->se_requiere_logueo(true,'Operacion|panorama_ejnal');
		$mobile = $this->loadModel('Mobile');
		$unitsIn = self::panorama_get_base($mobile,'B2');
		$geoDraw = self::drawGeocerca($mobile,'B2');
		require URL_VISTA.'operacion/panorama_ejnal.php';
	}
	public function panorama_ejnal_cordon(){
		$this->se_requiere_logueo(true,'Operacion|panorama_ejnal_cordon');
		$mobile = $this->loadModel('Mobile');
		$geoDraw = self::drawGeocerca($mobile,'B2');
		$unitsIn = self::panorama_get_cordon('2');
		require URL_VISTA.'operacion/panorama_ejnal_cordon.php';
	}	
	public function panorama_tb(){
		$this->se_requiere_logueo(true,'Operacion|panorama_tb');
		$unitsIn = self::panorama_get_tb();
		require URL_VISTA.'operacion/panorama_tb.php';
	}
	public function panorama_get_tb(){
		$operacion = $this->loadModel('Operacion');
		
		$unitsTB = $operacion->getTBUnits();
		$num = 0;
		$unitState = array();
		foreach ($unitsTB as $tb){
			$unitState[$num]['numeq'] 				= $tb['numeq'];
			$unitState[$num]['id_operador'] 		= $tb['id_operador'];
			$unitState[$num]['id_operador_unidad'] 	= $tb['id_operador_unidad'];
			$unitState[$num]['latitud'] 			= $tb['latitud'];
			$unitState[$num]['longitud'] 			= $tb['longitud'];
			$unitState[$num]['distancia'] 			= round(($tb['distancia']/1000),2).' km';
			$unitState[$num]['min_min'] 			= round(($tb['min_min']/60),0);
			$unitState[$num]['min_max'] 			= round(($tb['min_max'])/60,0);
			$num++;
		}
		return $unitState;
	}	
	public function drawGeocerca(MobileModel $mobile,$base){
		$geocerca = $mobile->getGeocerca($base);
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
	public function panorama_get_cordon($base){
		$operacion = $this->loadModel('Operacion');
		$gps = $this->loadModel('Gps');
		
		$enCordon = $operacion->mapearCordon($base);
		$num = 0;
		$unitState = array();
		foreach ($enCordon as $frm){
			
			$actual_coords = $gps->lastPositionById($frm['id_operador']);
			$coords = json_decode($actual_coords);
			
			$unitState[$num]['numeq'] = $frm['numeq'];
			$unitState[$num]['id_operador'] = $frm['id_operador'];
			$unitState[$num]['id_operador_unidad'] = $frm['id_operador_unidad'];
			$unitState[$num]['latitud'] = @$coords->lat;
			$unitState[$num]['longitud'] = @$coords->lng;
			$unitState[$num]['time'] = $coords->time;
			$num++;
			
		}
		return $unitState;
	}
	public function panorama_get_base(MobileModel $mobile,$base){
		$operacion = $this->loadModel('Operacion');
		$gps = $this->loadModel('Gps');
		
		$enc1 = $operacion->enC1();
		$num = 0;
		$unitState = array();
		foreach ($enc1 as $c1){
			
			$actual_coords = $gps->lastPositionById($c1['id_operador']);
			$coords = json_decode($actual_coords);
			
			$geoVars['latitud_act'] = @$coords->lat;
			$geoVars['longitud_act'] = @$coords->lng;
			
			$in = $mobile->enGeocercaNum($geoVars,$base);
			
			if($in == 'in'){
				$unitState[$num]['numeq'] = $c1['numeq'];
				$unitState[$num]['id_operador'] = $c1['id_operador'];
				$unitState[$num]['id_operador_unidad'] = $c1['id_operador_unidad'];
				$unitState[$num]['latitud'] = $coords->lat;
				$unitState[$num]['longitud'] = $coords->lng;
				$unitState[$num]['time'] = $coords->time;
				$num++;
			}
		}
		return $unitState;
	}
	public function panorama_get_c1(){
		$operacion = $this->loadModel('Operacion');
		$gps = $this->loadModel('Gps');
		
		$enc1 = $operacion->enC1();
		$num = 0;
		$unitState = array();
		foreach ($enc1 as $c1){
			
			$actual_coords = $gps->lastPositionById($c1['id_operador']);
			$coords = json_decode($actual_coords);
			if($coords){
				$unitState[$num]['numeq'] = $c1['numeq'];
				$unitState[$num]['id_operador'] = $c1['id_operador'];
				$unitState[$num]['id_operador_unidad'] = $c1['id_operador_unidad'];
				$unitState[$num]['latitud'] = $coords->lat;
				$unitState[$num]['longitud'] = $coords->lng;
				$unitState[$num]['time'] = $coords->time;
				$num++;
			}
		}
		return $unitState;
	}
	public function panorama_get_c8(){
		$operacion = $this->loadModel('Operacion');
		$gps = $this->loadModel('Gps');
		
		$enc8 = $operacion->enC8();
		$num = 0;
		$unitState = array();
		foreach ($enc8 as $c8){
			
			$actual_coords = $gps->lastPositionById($c8['id_operador']);
			$coords = json_decode($actual_coords);
			if($coords){
				$unitState[$num]['numeq'] = $c8['numeq'];
				$unitState[$num]['id_operador'] = $c8['id_operador'];
				$unitState[$num]['id_operador_unidad'] = $c8['id_operador_unidad'];
				$unitState[$num]['latitud'] = $coords->lat;
				$unitState[$num]['longitud'] = $coords->lng;
				$unitState[$num]['time'] = $coords->time;
				$num++;
			}
		}
		return $unitState;
	}
	public function panorama_get_a11(){
		$operacion = $this->loadModel('Operacion');
		$gps = $this->loadModel('Gps');
		
		$ena11 = $operacion->enA11();
		$num = 0;
		$unitState = array();
		foreach ($ena11 as $a11){
			
			$actual_coords = $gps->lastPositionById($a11['id_operador']);
			$coords = json_decode($actual_coords);
			if($coords){
				$unitState[$num]['numeq'] = $a11['numeq'];
				$unitState[$num]['id_operador'] = $a11['id_operador'];
				$unitState[$num]['id_operador_unidad'] = $a11['id_operador_unidad'];
				$unitState[$num]['latitud'] = $coords->lat;
				$unitState[$num]['longitud'] = $coords->lng;
				$unitState[$num]['time'] = $coords->time;
				$num++;
			}
		}
		return $unitState;
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
		$modelo->adquirirTiemposBase();
		print $modelo->tiempo_base_get($_POST);
	}
	public function procesar_servicio(){
		////////////////////////////////////////////////////////////////////permisos y modelo
		$this->se_requiere_logueo(true,'Operacion|solicitud');
		$cliente= $this->loadModel('Clientes');
		$operacion = $this->loadModel('Operacion');
		$mobile = $this->loadModel('Mobile');
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
				$data['descripcion']= 'turno_apartados';
				$data['valor']		= $turno;
				$data['tmp_val']	= 0;
				$data['data']		= 0;
				
			self::setConfig($data);
			
			$operador = $operacion->unidadalAire($service->id_operador_unidad);
			$operacion->asignar_viaje($service->id_viaje,$operador);
			
			//esto se asigna manualmente por el telefonista de acuerdo a un semaforo de tiempo
			// $relTravel['id_operador_unidad'] = $service->id_operador_unidad;
			// $relTravel['id_viaje'] 	= $service->id_viaje;
			// $relTravel['salida'] 	= 118;
			// self::asignacion_automatica($relTravel,$mobile);
			
		}
		
		////////////////////////////////////////////////////////////////////servicio al aire
		if($service->cat_tipo_salida == 181){
			$operador = $operacion->unidadalAire($service->id_operador_unidad);
			$operacion->asignar_viaje($service->id_viaje,$operador);
			
			$relTravel['id_operador_unidad'] = $service->id_operador_unidad;
			$relTravel['id_viaje'] 	= $service->id_viaje;
			$relTravel['salida'] 	= 120;
			
			self::asignacion_automatica($relTravel,$mobile);
		}
		
		////////////////////////////////////////////////////////////////////salida por sitio
		if($service->cat_tipo_salida == 182){
			$operador = $operacion->unidadenSitio($service->sitio_select_oper,1);
			$operacion->asignar_viaje($service->id_viaje,$operador);

			$relTravel['id_operador_unidad'] = $operador['id_operador_unidad'];
			$relTravel['id_viaje'] 	= $service->id_viaje;
			$relTravel['salida'] 	= 119;		
			
			self::asignacion_automatica($relTravel,$mobile);
		}
		print json_encode(array('resp' => true ));
	}	
}
?>