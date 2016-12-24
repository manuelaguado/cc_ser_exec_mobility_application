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
	public function sync()
    {
		$this->se_requiere_logueo(true,'Mobile|index');
		$model = $this->loadModel('Mobile');
		$operacion = $this->loadModel('Operacion');
		$claves = (json_decode($_POST['sync'], true));
		$model->store($claves,$operacion);
		print(json_encode(array('sync'=>'ok')));
    }
	public function gps()
    {
		$this->se_requiere_logueo(true,'Mobile|index');
		$model = $this->loadModel('Mobile');
		$claves = (json_decode($_POST['gps'], true));
		$model->storeGps($claves);
		print(json_encode(array('gps'=>'ok')));
    }
	public function pusher_auth(){
		$this->se_requiere_logueo(true,'Mobile|index');
		require_once('../vendor/pusher/Pusher.php');
		$pusher = new Pusher(PUSHER_KEY, PUSHER_SECRET, PUSHER_APP_ID);
		$presence_data = array(
			'name' => $_SESSION['usuario'],
			'xxx' => $_SESSION['id_usuario']
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
	public function setIdOperadorUnidad($id_operador_unidad){
		$_SESSION['multi'] = 1;
		$_SESSION['id_operador_unidad'] = $id_operador_unidad;
		
				$operacion = $this->loadModel('Operacion');
				$bases = $this->loadModel('Bases');
				$mobile = $this->loadModel('Mobile');
				$tail = $operacion->formadoAnyBase($bases, $_SESSION['id_operador_unidad']);
				if($tail){
					D::bug('Se quitó del cordon 2'.$tail);
					$mobile->exitCordonFromLogin($_SESSION['id_usuario'],$_SESSION['id_operador_unidad']);
				}
		print json_encode(array('resp' => true ));
	}
}
?>