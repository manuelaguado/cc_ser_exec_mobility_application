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

		$share = $this->loadModel('Share');

		foreach ($whosLogin as $logged){
			$token = 'LGN:'.$this->token(60);
			$id_operador_unidad = $share->getIdOperadorUnidadEpisode($logged['id_usuario'],'user_alta');

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

		$share = $this->loadModel('Share');
		$token = 'LGN:'.$this->token(60);
		$id_operador_unidad = $share->getIdOperadorUnidadEpisode($id_usuario,'user_alta');

		$model = $this->loadModel('Login');
		print $model->signout($id_usuario);
	}
	public function verifica_session()
       {
		/*se_requiere_logueo no se llama por que este reconstruye la sesion cuando es verdadero, y cuando es falso redirige al estar la sesion iniciada*/
		$obtener_modelo = $this->loadModel('Login');
		$obtener_modelo->verificarSession();
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
		$token = $this->token(60);
		$recuperar = $obtener_modelo->recuperar_datos($_POST['correo'],$token);

		require( '../vendor/mail.php' );
		$send = new Email();
		$send->recuperar_cuenta($_POST['correo'], $token, $recuperar[0]['usuario']);

              print json_encode($recuperar);
       }
       public function salirAndroid()
       {
		header('Access-Control-Allow-Origin: *');
		$obtener_modelo = $this->loadModel('Login');
		$salir = $obtener_modelo->salirAndroid($_POST['id_usuario']);
               return $salir;
       }
       public function logear()
       {
              header('Access-Control-Allow-Origin: *');
              $this->se_requiere_logueo(false);
              $obtener_modelo = $this->loadModel('Login');
              $share = $this->loadModel('Share');
              $loguear = $obtener_modelo->logear();

              	if(($loguear[1]['dispositivo'] == 'celular')&&($loguear[2]['via'] == 'correcta')&&($_SESSION['id_operador_unidad'])!= 'select'){
              		$operacion = $this->loadModel('Operacion');
              		$bases = $this->loadModel('Bases');
              		$tail = $operacion->formadoAnyBase($bases, $_SESSION['id_operador_unidad']);
              		if($tail){
              			D::bug('Se quitó del cordon '.$tail);
              			$share->exitCordonFromLogin($_SESSION['id_usuario'],$_SESSION['id_operador_unidad']);
              		}
              	}

              print json_encode($loguear);
       }
}
?>
