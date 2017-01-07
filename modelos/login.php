<?php
class LoginModel
{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
        }
    }
	public function whoisLogged(){
		$sql = '
			SELECT SQL_CALC_FOUND_ROWS
				fwl.id_usuario AS id_usuario,
				fwl.session_id AS session_id
			FROM
				fw_login AS fwl
			INNER JOIN fw_usuarios AS fwu ON fwl.id_usuario = fwu.id_usuario
			WHERE
				fwl.`open` = 1
			ORDER BY
				id_usuario ASC			
		';
		$query = $this->db->prepare($sql);
		$query->execute();
		$result = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			$num=0;
			foreach ($result as $num => $row) {
				$array[$num]['id_usuario'] = $row->id_usuario;
				$array[$num]['session_id'] = $row->session_id;
				$num++;
			}
		}
		return $array;
	}
	public function signout($id_usuario){
		$id_login = self::getId_login($id_usuario);
		if($id_login){
			$fecha_login = self::initLogin($id_login);
			$fin = date("Y-m-d H:i:s");
			$tiempo = Controller::diferenciaFechasD($fecha_login , $fin);
			
			$sql = "
				UPDATE `fw_login`
				SET
				 `open` = '0',
				 `fecha_logout` = '".$fin."',
				 `tiempo_session` = '".$tiempo."',
				 `user_mod` = '".$id_usuario."',
				 `fecha_mod` = '".$fin."'
				WHERE
					(`id_login` = '".$id_login."');
			";
			
			$session_id = self::getSession_id($id_login);
			
			if(file_exists(session_save_path().'/sess_'.$session_id)){
				unlink(session_save_path().'/sess_'.$session_id);
			}
			
			$query = $this->db->prepare($sql);
			$query->execute();
		}
		return json_encode(array('resp' => true )); 
	}
	public function session_duplicada($id_usuario, MobileModel $mobile){
		$sql = "
			SELECT
				fwl.id_login,
				fwl.session_id,
				fwl.fecha_login
			FROM
				fw_login as fwl
			WHERE
				fwl.id_usuario = ".$id_usuario." AND
				fwl.`open` = 1
		";
				
		$query = $this->db->prepare($sql);
		$query->execute();
		$result = $query->fetchAll();
		if($query->rowCount()>=1){
			foreach ($result as $num => $row) {
				$id_operador_unidad = self::getIdOperadorUnidadBySession($row->session_id);				
				$token = 'DUP:'.Controlador::token(60);
				$mobile->storeToSyncRide($id_usuario,$token,155,$id_operador_unidad);
				self::signout($id_usuario);
			}
		}
	}
	private function getAllIdenOperadorUnidad($id_operador_unidad){
		$qry = "
			SELECT
				base.id_operador_unidad
			FROM
				cr_operador_unidad AS iden
			INNER JOIN cr_operador_unidad AS base ON iden.id_operador = base.id_operador
			WHERE
				iden.id_operador_unidad = $id_operador_unidad
				AND base.status_operador_unidad = 198
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$ids = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$ids[$num]['id_operador_unidad'] = $row->id_operador_unidad;
				$num++;
			}
		}
		return $ids;
	}	
	private function getIdOperadorUnidadBySession($session_id){
		$id_operador_unidad = '';
		if(file_exists(session_save_path().'/sess_'.$session_id)){
			$fp = fopen(session_save_path().'/sess_'.$session_id, "r");
			$content = '';
			while(!feof($fp)) {
				$content .= fgets($fp);
			}
				$regex = '#.*(id_operador_unidad\|).{5}#';
				$replacement = '';
				$result = preg_replace($regex, $replacement, $content);
				
				$regex = '#(";cat_statusoperador).*#';
				$replacement = '';
				$id_operador_unidad = preg_replace($regex, $replacement, $result);
				
			fclose($fp);
		}
		return $id_operador_unidad;
	}
	public function logear(MobileModel $mobile){
		
		$stat = self::getStatusUser($_POST['usuario']);
		if($stat == 131){
			$array[]=array('resp'=>"inhabilitado");
			print json_encode($array);
			exit();
		}
		
		$password_md5=md5($_POST['password']);
        $sql = "
		SELECT fwu.id_usuario, fwu.id_rol, fwu.usuario, fwu.id_ubicacion, fwu.correo, fwc.aceptar_tyc FROM fw_usuarios as fwu
		INNER JOIN fw_usuarios_config as fwc ON fwc.id_usuario = fwu.id_usuario
		WHERE fwu.usuario='{$_POST['usuario']}' and fwu.password = '{$password_md5}' and cat_status = '3'";
		$query = $this->db->prepare($sql);
        $query->execute();
        $usuario = $query->fetchAll();
		if($query->rowCount()>=1){
			
			foreach ($usuario as $row) {
				self::session_duplicada($row->id_usuario,$mobile);

				session_name(SITE_NAME);
				$_SESSION['id_usuario']=$row->id_usuario;
				$_SESSION['id_rol']=$row->id_rol;
				$_SESSION['hora_acceso']= time();
				$_SESSION['usuario']=$row->usuario;
				$_SESSION['id_ubicacion']=$row->id_ubicacion;
				$_SESSION['correo']=$row->correo;
				$_SESSION['tyc']=$row->aceptar_tyc;
				$_SESSION['token'] = Controlador::token(60);
				$array[0]=array('resp'=>"acceso_correcto");
				$array[3]=array('tyc'=>$_SESSION['tyc']);
			}
				self::MobileDetect();
				$array[1] = array('dispositivo'=>$_SESSION['dispositivo']);	

				if(($_SESSION['id_rol']==2)&&($_SESSION['dispositivo'] == 'pc')){
					session_unset();
					unset($_SESSION);
					session_destroy();
					$array[2] = array('via'=>"incorrecta");
					
				}else if(($_SESSION['id_rol']==2)&&($_SESSION['dispositivo'] == 'celular')){
					$acceso = Controlador::getConfig(1,'login_operadores');
					if($acceso['valor'] == 1){
						
						$sess_oper = self::setIDOperadorSessions($_SESSION['id_usuario']);
						$_SESSION['id_operador'] = $sess_oper['id_operador'];
						
						/*no separar getIdOperadorUnidadBySession*/
						$_SESSION['id_operador_unidad'] = $sess_oper['id_operador_unidad'];
						$_SESSION['cat_statusoperador'] = $sess_oper['cat_statusoperador'];
						/*no separar getIdOperadorUnidadBySession*/
						
						if($sess_oper['multi'] > 1){
							$_SESSION['id_operador_unidad'] = 'select';
						}
						
						$_SESSION['serie'] = self::getSerie($_SESSION['id_usuario']);
						self::permisos($_SESSION['id_rol']);
						self::permisos_acl($_SESSION['id_usuario']);
						$episodio = self::openEpisodio($_SESSION['id_operador']);
						$_SESSION['id_episodio'] = ($episodio)?$episodio:'';
						if(
							($_SESSION['id_operador'] == '') OR
							($_SESSION['id_operador_unidad'] == '') OR
							($_SESSION['serie'] == '')
						){
							session_unset();
							unset($_SESSION);
							session_destroy();
							$array[2] = array('via'=>"incompleto");	
						}else{
							$array[2] = array('via'=>"correcta");
						}
						self::storeSession($_SESSION['id_usuario']);
						
					}else{
						session_unset();
						unset($_SESSION);
						session_destroy();
						$array[2] = array('via'=>"disabled");
					}
					
				}else if(($_SESSION['id_rol']!=2)&&($_SESSION['dispositivo'] == 'pc')){	
					self::permisos($_SESSION['id_rol']);
					self::permisos_acl($_SESSION['id_usuario']);
					$array[2] = array('via'=>"correcta");
					self::storeSession($_SESSION['id_usuario']);
				
				}else{	
					session_unset();
					unset($_SESSION);
					session_destroy();
					$array[2] = array('via'=>"disabled");
					
				}
		}else{
			self::putLoggerLogin($_POST['usuario']);
			$array[]=array('resp'=>"acceso_incorrecto");
		}
		return $array;
	}
	private function openEpisodio($id_operador){
		$qry = "
			SELECT
				cre.id_episodio
			FROM
				cr_episodios AS cre
			WHERE
				cre.id_operador = $id_operador
			AND cre.fin IS NULL
			ORDER BY
				cre.id_episodio DESC
			LIMIT 0,
			 1
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$data = $query->fetchAll();
		$id_episodio = '';
		if($query->rowCount()>=1){
			foreach ($data as $row) {
				$id_episodio = $row->id_episodio;
			}
		}
		return $id_episodio;
	}
	public function getStatusUser($usuario){
		$sql = "
			SELECT
				fw_usuarios.cat_status
			FROM
				fw_usuarios
			WHERE
				fw_usuarios.usuario = '".$usuario."'
		";
				
		$query = $this->db->prepare($sql);
		$query->execute();
		$result = $query->fetchAll();
		if($query->rowCount()>=1){
			foreach ($result as $num => $row) {
				return $row->cat_status;
			}
		}
	}
	public function putLoggerLogin($usuario){
		$id_usuario = self::getIdUsuario($usuario);
		if($id_usuario){
			$ahora = date("Y-m-d H:i:s");
			$logger = self::selectLoggerLogin($id_usuario);
			if($logger['id_login_log'] !== NULL){
				if($logger['intentos'] <= 4){
					$segundos = Controller::diferenciaSegundos($logger['fecha'],$ahora);
					($segundos <= 600)?self::updateLoggerLogin($logger['id_login_log']):self::insertLoggerLogin($id_usuario);
				}else{
					self::inhabilitarUsuario($id_usuario);
				}
			}else{
				self::insertLoggerLogin($id_usuario);
			}
		}
	}
	public function inhabilitarUsuario($id_usuario){
		$ahora = date("Y-m-d H:i:s");
		$sql = "
			UPDATE `fw_usuarios`
			SET
			 `cat_status` = '131',
			 `user_mod` = '".$id_usuario."'
			WHERE
				(`id_usuario` = '".$id_usuario."');
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	public function insertLoggerLogin($id_usuario){
		$ahora = date("Y-m-d H:i:s");
		$sql = "
			INSERT INTO `fw_login_log` (
				`id_usuario`,
				`ip`,
				`fecha`,
				`intentos`
			)
			VALUES
				(
					'".$id_usuario."',
					'".$_SERVER['REMOTE_ADDR']."',
					'".$ahora."',
					'1'
				);
		";
		$query = $this->db->prepare($sql);
		$query->execute();		
	}
	public function updateLoggerLogin($id_login_log){
		$ahora = date("Y-m-d H:i:s");
		$sql = "
			UPDATE `fw_login_log`
			SET 
			 `ip` = '".$_SERVER['REMOTE_ADDR']."',
			 `fecha` = '".$ahora."',
			 `intentos` = intentos + 1
			WHERE
				(`id_login_log` = '".$id_login_log."');
		";
		$query = $this->db->prepare($sql);
		$query->execute();		
	}	
	public function selectLoggerLogin($id_usuario){
		$sql = "
			SELECT
				fwlg.id_login_log,
				fwlg.ip,
				fwlg.fecha,
				fwlg.intentos
			FROM
				fw_login_log as fwlg
			WHERE
				fwlg.id_usuario = '".$id_usuario."'
			ORDER BY
				fwlg.id_login_log DESC
			LIMIT 0,
			 1
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$result = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($result as $num => $row) {
				$array['id_login_log'] 	= $row->id_login_log;
				$array['ip'] 			= $row->ip;
				$array['fecha'] 		= $row->fecha;
				$array['intentos'] 		= $row->intentos;
			}
			return $array;
		}else{
			return array('id_login_log' => NULL);
		}
	}	
	public function getIdUsuario($usuario){
		$sql = "
			SELECT
				fw_usuarios.id_usuario
			FROM
				fw_usuarios
			WHERE
				fw_usuarios.usuario = '".$usuario."'
		";
				
		$query = $this->db->prepare($sql);
		$query->execute();
		$result = $query->fetchAll();
		if($query->rowCount()>=1){
			foreach ($result as $num => $row) {
				return $row->id_usuario;
			}
		}
	}	
	public function storeSession($id_usuario){
		$init = date("Y-m-d H:i:s");
		$sql = "
			INSERT INTO `fw_login` (
				`id_usuario`,
				`session_id`,
				`open`,
				`fecha_login`,
				`ipv4`,
				`ipv6`,
				`user_alta`,
				`fecha_alta`
			)
			VALUES
				(
					'".$id_usuario."',
					'".session_id()."',
					'1',
					'".$init."',
					'".$_SERVER['REMOTE_ADDR']."',
					'".Controller::ipv4to6()."',
					'".$_SESSION['id_usuario']."',
					'".$init."'
				);
		";
		
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	public function getId_login($id_usuario = NULL){
		if((!isset($_SESSION['id_usuario']))&&($id_usuario == NULL)){
			//si ya no existe la sesion no tiene caso continuar
			//solo en caso de que no llegue del login
			$array[]=array('resp'=>"violacion_c2",'stat'=>"Sesion inexistente, se envian variables de curso para login");
			print json_encode($array);
			exit();
		}
		$id_usuario =($id_usuario === NULL)?$_SESSION['id_usuario']:$id_usuario;
		$sql = "
			SELECT
				fwl.id_login
			FROM
				fw_login as fwl
			WHERE
				fwl.id_usuario = ".$id_usuario." AND 
				fwl.open = 1
		";
				
		$query = $this->db->prepare($sql);
		$query->execute();
		$result = $query->fetchAll();
		if($query->rowCount()>=1){
			foreach ($result as $num => $row) {
				return $row->id_login;
			}
		}
	}
	public function getSession_id($id_login){
		if($id_login){
			$sql = "
				SELECT
					fwl.session_id
				FROM
					fw_login as fwl
				WHERE
					fwl.id_login = ".$id_login."
			";
					
			$query = $this->db->prepare($sql);
			$query->execute();
			$result = $query->fetchAll();
			if($query->rowCount()>=1){
				foreach ($result as $num => $row) {
					return $row->session_id;
				}
			}
		}else{
			/*Se limpio la db mientras el usuario estaba logueado?*/
			return '2015-06-13 18:00:00';/* :) mi fecha especial*/
		}
	}
	public function initLogin($id_login){
		if($id_login){
			$sql = "
				SELECT
					fwl.fecha_login
				FROM
					fw_login as fwl
				WHERE
					fwl.id_login = ".$id_login."
			";
					
			$query = $this->db->prepare($sql);
			$query->execute();
			$result = $query->fetchAll();
			if($query->rowCount()>=1){
				foreach ($result as $num => $row) {
					return $row->fecha_login;
				}
			}
		}else{
			/*Se limpio la db mientras el usuario estaba logueado?*/
			return '2014-03-17 19:44:00';/* :) mi fecha especial*/
		}
	}
	public function getSerie($id_usuario){
		$sql = "
			SELECT
				crc.serie
			FROM
				cr_operador_celular AS croc
			INNER JOIN cr_operador AS cro ON croc.id_operador = cro.id_operador
			INNER JOIN cr_celulares AS crc ON croc.id_celular = crc.id_celular
			INNER JOIN fw_usuarios AS fwu ON cro.id_usuario = fwu.id_usuario
			WHERE
				fwu.id_usuario = ".$id_usuario." AND
				croc.cat_status_operador_celular = 31
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$result = $query->fetchAll();
		if($query->rowCount()>=1){
			foreach ($result as $num => $row) {
				return $row->serie;
			}
		}
	}
	public function setIDOperadorSessions($id_usuario){
		$sql = "
			SELECT
				crou.id_operador,
				crou.id_operador_unidad,
				cro.cat_statusoperador,
				count(
					DISTINCT crou.id_operador_unidad
				) AS multi
			FROM
				cr_operador_unidad AS crou
			INNER JOIN cr_operador AS cro ON crou.id_operador = cro.id_operador
			INNER JOIN fw_usuarios AS fwu ON cro.id_usuario = fwu.id_usuario
			INNER JOIN cr_unidades ON cr_unidades.id_unidad = crou.id_unidad
			WHERE
				fwu.id_usuario = ".$id_usuario."
			AND cr_unidades.cat_status_unidad = 14
			AND crou.status_operador_unidad = 198
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$result = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($result as $num => $row) {
				$array['id_operador'] = $row->id_operador;
				$array['id_operador_unidad'] = $row->id_operador_unidad;
				$array['cat_statusoperador'] = $row->cat_statusoperador;
				$array['multi'] = $row->multi;
			}
		}
		return $array;
	}
	public function MobileDetect(){
		require_once '../vendor/MobileDetect/Mobile_Detect.php';
		$detect = new Mobile_Detect;
		$array = array();
		if($detect->isMobile()){
			if($detect->isTablet()){
				$_SESSION['dispositivo'] = 'tableta';
			}else{
				$_SESSION['dispositivo'] = 'celular';
			}
		}else{
			$_SESSION['dispositivo'] = 'pc';
		}
	}
	public function credenciales(){
		$data = array();
		if(isset($_SESSION['id_rol'])){
			
			$id_ubicacion = isset($_SESSION['id_ubicacion']) ? $_SESSION['id_ubicacion'] : '';
			$id_rol = isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : '';
			$sql = "
					SELECT
						fw_ubicacion.descripcion_ubicacion,
						fw_roles.descripcion as rol
					FROM
						fw_ubicacion,
						fw_roles
					WHERE
						fw_ubicacion.id_ubicacion = ".$id_ubicacion."
					AND 
						fw_roles.id_rol = ".$id_rol."
			";
			$query = $this->db->prepare($sql);
			$query->execute();
			$result = $query->fetchAll();
			if($query->rowCount()>=1){
				foreach ($result as $num => $row) {
					$data['descripcion'] = $row->descripcion_ubicacion;
					$data['rol'] = $row->rol;
				}
			}else{
					$data['descripcion'] = '';
					$data['rol'] = '';
			}
		}
		return $data;
	}
	private function permisos_acl($user){
		$sql = "SELECT tercio FROM fw_dac_acl where id_usuario = '".$user."'";
		$query = $this->db->prepare($sql);
		$query->execute();
		$permisos = $query->fetchAll();
		$accesos = '';
		if($query->rowCount()>=1){
			foreach ($permisos as $num => $row) {
				$accesos[$num] = $row->tercio;
			}
			$_SESSION['permisos_acl'] = $accesos;
		}else{
			$_SESSION['permisos_acl'] = '';
		}
	}	
	private function permisos($rol){
		$sql = "SELECT	fw_metodos.controlador, fw_metodos.metodo	FROM fw_permisos INNER JOIN fw_metodos ON fw_permisos.id_metodo = fw_metodos.id_metodo where fw_permisos.id_rol = $rol ";
		$query = $this->db->prepare($sql);
		$query->execute();
		$permisos = $query->fetchAll();
		$accesos = '';
		if($query->rowCount()>=1){
			foreach ($permisos as $num => $row) {
				$accesos[$num] = $row->controlador .'|'. $row->metodo;
			}
			$_SESSION['permisos'] = $accesos;
		}else{
			$_SESSION['permisos'] = '';
		}
	}
	public function verificarSession(){
		
		if(!isset($_SESSION['hora_acceso'])){
			$array[]=array('resp'=>"timeout");
		}else{
			$resta = time()-$_SESSION['hora_acceso'];
			/*1800 = 30 minutos*/
			/*3600 = 1 hr*/
			/*tiempo en segundos*/
			if(isset($_SESSION['hora_acceso']) && ($resta>3600)){
				$id_login = self::getId_login();
				self::signout($_SESSION['id_usuario']);
				session_destroy();
				session_unset();
				$array[]=array('resp'=>"timeout",'tiempo'=>$resta);
			}else{
				$array[]=array('resp'=>"intime",'tiempo'=>$resta);
				Controlador::updateLogin();
			}
		}
		print json_encode($array); 			 
	}
	public function salir(){
		$id_login = self::getId_login();
		self::signout($_SESSION['id_usuario']);
		session_unset();
		unset($_SESSION);
		if(session_destroy()){
			$array[]=array('resp'=>"correcto");
		}else{
			$array[]=array('resp'=>"incorrecto");
		}
		print json_encode($array);
	}
	public function recuperar_datos($correo,$token){
        $sql = "SELECT id_usuario, usuario FROM fw_usuarios WHERE correo='{$correo}'";
        $query = $this->db->prepare($sql);
        $query->execute();
        $usuario = $query->fetchAll();
		if($query->rowCount()>=1){
			self::insert_lost_password($token,$usuario[0]->id_usuario,$correo);
			$array[]=array('resp'=>"enviado",'usuario'=>$usuario[0]->usuario);
		}else{
			$array[]=array('resp'=>"no_existe");
		}
		return $array;
	}
	private function insert_lost_password($token,$id,$correo){
		$clean = "DELETE FROM fw_lost_password WHERE correo = :correo";
        $query = $this->db->prepare($clean);
        $query->execute(array(':correo' => $correo));
		
		$sql = "INSERT INTO fw_lost_password (	token, 	id_usuario, correo) VALUES (:token, :id_usuario, :correo)";
		$query = $this->db->prepare($sql);
		$query->execute(array(':token' => $token, ':id_usuario' => $id, ':correo' => $correo));	
	}
}
