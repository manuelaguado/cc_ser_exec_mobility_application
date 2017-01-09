<?php
class Mobile extends Controlador
{
    public function index()
    {
		$this->se_requiere_logueo(true,'Mobile|index');
		$token_cache = $this->token(5);
		$mvhc = ($_SESSION['id_operador_unidad'] == 'select')?2:1;
		require URL_TEMPLATE_FW7.'index.php';
    }
	public function pusher_auth(){
		$this->se_requiere_logueo(true,'Mobile|index');
		require_once('../vendor/pusher/Pusher.php');
		$pusher = new Pusher(PUSHER_KEY, PUSHER_SECRET, PUSHER_APP_ID);
		$presence_data = array(
			'name' => $_SESSION['usuario'],
			'id_usuario' => $_SESSION['id_usuario']
		);
		echo $pusher->presence_auth(
			$_POST['channel_name'], 
			$_POST['socket_id'], 
			$_SESSION['id_operador'], 
			$presence_data
		);
	}
	public function websockets_control($estado){
		$this->se_requiere_logueo(true,'Mobile|websockets_control');
		
		$state = ($estado == 'true')?'1':'0';
		
		$data['id_site']	= '1';
		$data['descripcion']= 'websockets_control';
		$data['valor']		= $state;
		$data['tmp_val']	= '0';
		$data['data']		= '0';
		
		Controlador::setConfig($data);
		print json_encode(array('resp' => true));
	}
	public function multi(){
		if($_SESSION['id_operador_unidad'] == 'select'){
			
			$mobile = $this->loadModel('Mobile');
			$vehiculos = $mobile->getVehiculosOperador($_SESSION['id_operador']);
			print(json_encode($vehiculos));
			
		}else{
			exit();
		}
	}	
	public function sync()
    {
		header('Access-Control-Allow-Origin: *');
		if(isset($_POST['sync']) && isset($_POST['sid'])){
			$model = $this->loadModel('Mobile');
			$claves = (json_decode($_POST['sync'], true));			
			$operacion = $this->loadModel('Operacion');
			$model->store($claves,$operacion,$_POST['sid']);
			print(json_encode(array('sync'=>'ok')));
		}
    }
	public function gps()
    {
		header('Access-Control-Allow-Origin: *');
		if(isset($_POST['gps']) && isset($_POST['sid'])){
			$model = $this->loadModel('Mobile');
			$claves = (json_decode($_POST['gps'], true));
			$model->storeGps($claves);
			print(json_encode(array('gps'=>'ok')));
		}
    }
	public function pusher_android_auth($token_session,$id_usuario,$id_operador,$id_operador_unidad){
		header('Access-Control-Allow-Origin: *');
		require_once('../vendor/pusher/Pusher.php');
		$pusher = new Pusher(PUSHER_KEY, PUSHER_SECRET, PUSHER_APP_ID);
		$presence_data = array(
			'token_session' => $token_session,
			'id_operador_unidad' => $id_operador_unidad,
			'id_usuario' => $id_usuario
		);
		echo $pusher->presence_auth(
			$_POST['channel_name'], 
			$_POST['socket_id'], 
			$id_operador, 
			$presence_data
		);
	}
	public function multiandroid($id_operador_unidad,$id_operador){
		header('Access-Control-Allow-Origin: *');
		if(($id_operador_unidad) == 'select'){
			
			$mobile = $this->loadModel('Mobile');
			$vehiculos = $mobile->getVehiculosOperador($id_operador);
			print(json_encode($vehiculos));
			
		}else{
			exit();
		}
	}	
	public function setIdOperadorUnidad($id_operador_unidad){	
				header('Access-Control-Allow-Origin: *');
				$operacion = $this->loadModel('Operacion');
				$bases = $this->loadModel('Bases');
				$mobile = $this->loadModel('Mobile');
				
				$idens = $mobile->getAllIdenOperadorUnidad($id_operador_unidad);
				foreach($idens as $iden){
					$tail = $operacion->formadoAnyBase($bases, $iden['id_operador_unidad']);
					if($tail){
						D::bug('Se quitó del cordon 2 > '.$tail);
						$mobile->exitCordonFromLogin($iden['id_usuario'],$iden['id_operador_unidad']);
					}
					$id_operador = $iden['id_operador'];
					$id_usuario = $iden['id_usuario'];
				}
				
				$timebase = $mobile->getIdensOperadorEnC2($id_operador);
				foreach($timebase as $unit){
					$array = array(
						'accurate' => '0',
						'clave' => 'C2',
						'estado1' => 'C2',
						'estado2' => 'NULL',
						'estado3' => 'NULL',
						'estado4' => 'NULL',
						'id' => '0',
						'id_episodio' => '0',
						'id_operador' => $id_operador,
						'id_operador_unidad' => $unit['id_operador_unidad'],
						'id_viaje' => '0',
						'latitud' => '0',
						'longitud' => '0',
						'motivo' => 'AUTO C2 SESION DUPLICADA',
						'serie' => '0',
						'tiempo' => date("Y-m-d H:i:s"),
						'timestamp' => date("Y-m-d H:i:s"),
						'token' => $this->token(62),
						'origen' => 'system',
						'id_usuario' => $id_usuario
					);
					$mobile->storeToSync($array);
				}
		/*if(isset($_POST['session_id'])){
			session_id($_POST['session_id']);
			session_start();
		}*/	
		$_SESSION['multi'] = 1;
		$_SESSION['id_operador_unidad'] = $id_operador_unidad;		
		print json_encode(array('resp' => true ));
	}
}
?>