<?php
class Mobile extends Controlador
{
    public function index()
    {
		$this->se_requiere_logueo(true,'Mobile|index');
		$token_cache = $this->token(5);
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
}
?>