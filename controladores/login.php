<?php
class Login extends Controlador
{
    public function index()
    {	
		$this->se_requiere_logueo(false);
		setcookie("PHPSESSID",$this->token(32),time()+86400);
		require_once '../vendor/MobileDetect/Mobile_Detect.php';
		$detect = new Mobile_Detect;
		if($detect->isMobile()){
			if($detect->isTablet()){
				include (URL_VISTA.'login/index.php');
			}else{
				/*Celular*/
				include (URL_VISTA.'material/login.php');
			}
		}else{
			include (URL_VISTA.'login/index.php');
		}
    }
	
	
	public function modal_all_sign_out(){
		$this->se_requiere_logueo(true,'Login|force_all_sign_out');
		require URL_VISTA.'modales/login/sign-all-out.php';
	}
	public function sign_all_out(){
		$this->se_requiere_logueo(true,'Login|force_sign_out');
		
		$login = $this->loadModel('Login');
		$whosLogin = $login->whoisLogged();
		
		$mobile = $this->loadModel('Mobile');
		
		foreach ($whosLogin as $logged){
			$token = 'LGN:'.$this->token(62);
			$id_operador_unidad = $mobile->getIdOperadorUnidad($logged['id_usuario']);
			$mobile->setCveStore($_SESSION['id_usuario'],$token,154,$id_operador_unidad,false);
			$login->signout($logged['id_usuario']);
		}
	}
	public function switch_login_op($estado){
		$this->se_requiere_logueo(true,'Login|switch_login_op');
		
		$state = ($estado == 'true')?'1':'0';
		
		$data['id_site']	= '1';
		$data['descripcion']= 'login_operadores';
		$data['valor']		= $state;
		$data['tmp_val']	= '0';
		$data['data']		= '0';
		
		Controlador::setConfig($data);
		print json_encode(array('resp' => true));
	}
	
	public function modal_sign_out($id_usuario){
		$this->se_requiere_logueo(true,'Login|force_sign_out');
		require URL_VISTA.'modales/login/sign-out.php';
	}
	public function sign_out($id_usuario){
		$this->se_requiere_logueo(true,'Login|force_sign_out');
		
		$mobile = $this->loadModel('Mobile');
		$token = 'LGN:'.$this->token(62);
		$id_operador_unidad = $mobile->getIdOperadorUnidad($id_usuario);
		$mobile->setCveStore($_SESSION['id_usuario'],$token,154,$id_operador_unidad,false);		
		
		$model = $this->loadModel('Login');
		print $model->signout($id_usuario);
	}
	public function logear()
    {
		$this->se_requiere_logueo(false);
		$obtener_modelo = $this->loadModel('Login');
		$mobile = $this->loadModel('Mobile');
		$loguear = $obtener_modelo->logear($mobile);
			
			if(($loguear[1]['dispositivo'] == 'celular')&&($loguear[2]['via'] == 'correcta')){
				$operacion = $this->loadModel('Operacion');
				$bases = $this->loadModel('Bases');
				$tail = $operacion->formadoAnyBase($bases, $_SESSION['id_operador_unidad']);
				if($tail){
					D::bug('Se quitó del cordon '.$tail);
					$mobile->exitCordonFromLogin($_SESSION['id_usuario'],$_SESSION['id_operador_unidad']);
				}
			}
			
        print json_encode($loguear);
    }
	public function verifica_session()
    {
		/*se_requiere_logueo no se llama por que este reconstruye la sesion cuando es verdadero, y cuando es falso redirige al estar la sesion iniciada*/
		$obtener_modelo = $this->loadModel('Login');
		$verificar = $obtener_modelo->verificarSession();
        return $verificar;
    }
	public function salir()
    {
		$this->se_requiere_logueo(true,'Login|salir');
		$obtener_modelo = $this->loadModel('Login');
		$salir = $obtener_modelo->salir();
        return $salir;
    }
	public function salirAlternativo()
    {
		$obtener_modelo = $this->loadModel('Login');
		$salir = $obtener_modelo->salir();
        return $salir;
    }
	public function recuperar_datos()
    {
		$this->se_requiere_logueo(false);
		$obtener_modelo = $this->loadModel('Login');
		$token = $this->token(62);
		$recuperar = $obtener_modelo->recuperar_datos($_POST['correo'],$token);
		
		require( '../vendor/mail.php' );
		$send = new Email();
		$send->recuperar_cuenta($_POST['correo'], $token, $recuperar[0]['usuario']);
			
        print json_encode($recuperar);
    }
}
?>
