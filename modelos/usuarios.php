<?php
require_once( '../vendor/mysql_datatable.php' );
class UsuariosModel
{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
        }
    }
	function poseer($id_usuario,$password){
		
		$pass = self::datos_usuario($id_usuario);
		$passwd = $pass['password'];
		
		$sql0 = "UPDATE fw_usuarios_config SET password = '".$passwd."', poseido = '1', user_mod = '".$_SESSION['id_usuario']."' where id_usuario = '".$id_usuario."'";
		$query0 = $this->db->prepare($sql0);
		$ok = $query0->execute();
		
		if($ok){
			$sql1 = "UPDATE fw_usuarios SET password = '".md5($password)."', user_mod = '".$_SESSION['id_usuario']."' where id_usuario = '".$id_usuario."'";
			$query1 = $this->db->prepare($sql1);
			$ok2 = $query_resp1 = $query1->execute();
		}
		
		$respuesta = ($ok2)?array('resp' => true ):array('resp' => false );

		return $respuesta;		
		
	}
	function exorcizar($id_usuario){
		
		$passwd = self::get_config_usr($id_usuario,'password');
		
		$sql0 = "UPDATE fw_usuarios_config SET password = NULL, poseido = '0', user_mod = '".$_SESSION['id_usuario']."' where id_usuario = '".$id_usuario."'";
		$query0 = $this->db->prepare($sql0);
		$ok = $query0->execute();
		
		if($ok){
			$sql1 = "UPDATE fw_usuarios SET password = '".$passwd."', user_mod = '".$_SESSION['id_usuario']."' where id_usuario = '".$id_usuario."'";
			$query1 = $this->db->prepare($sql1);
			$ok2 = $query_resp1 = $query1->execute();
		}
		
		$respuesta = ($ok2)?array('resp' => true ):array('resp' => false );

		return $respuesta;		
		
	}
	public function get_config_usr($user_id,$data){
		$user_id = intval($user_id);
		$sql_usr="
			SELECT
				fwuc.".$data."
			FROM
				fw_usuarios_config as fwuc
			WHERE
				fwuc.id_usuario = ".$user_id."		
		";

		$query = $this->db->prepare($sql_usr);
		$query->execute();
		$usuario = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($usuario as $row) {
				return $row->{$data};
			}
		}
	}	
	function set_dac_acl($tercios,$estado,$user){
		if($estado == 'true'){
			$sql = "
				INSERT INTO fw_dac_acl (
					id_usuario, 
					tercio,
					user_alta,
					fecha_alta
				)
				VALUES
				(
					:id_usuario ,
					:tercio,
					:user_alta,
					:fecha_alta
				)
			";
			$stmt = $this->db->prepare($sql);
			foreach($tercios as $tercio) {
				$result = $stmt->execute(
				array(
					':id_usuario' => $user,
					':tercio' => $tercio,
					':user_alta' => $_SESSION['id_usuario'],
					':fecha_alta' => date("Y-m-d H:i:s")
				));
			}
		}else if ($estado == 'false'){
			$clean = "DELETE FROM fw_dac_acl WHERE id_usuario = :id_usuario and tercio = :tercio";
			$stmt = $this->db->prepare($clean);			
			foreach($tercios as $tercio){
				$result = $stmt->execute(
					array(
						':id_usuario' => $user, 
						':tercio' => $tercio
					)
				);
			}
		}
		if($result){
			$respuesta = array('resp' => true , 'mensaje' => 'Se actualizo el permiso de manera satisfactoria.','estado'=>$estado );
		}else{
			$respuesta = array('resp' => false , 'mensaje' => 'Error en el sistema.' , 'error' => 'Ocurrio un error mienras se ejectutaba la query.' );
		}
		return $respuesta;
	}
	public function agregar_usuario($arreglo){

		if( $arreglo['password'] == $arreglo['password2'] ){
			$respuesta = self::guardar_usuario($arreglo);
		}else{
			$respuesta = array('resp' => false , 'mensaje' => 'Error en captura de datos.' , 'error' => 'Las contraseñas ingresadas no son iguales.' );
		}

		return $respuesta;
	}


	public function guardar_usuario($arreglo){

		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}

		$respuesta = self::valida_login_correo($this->usuario,$this->correo);

		if($respuesta['resp'] == true ){
			
			$sql = "
				INSERT INTO fw_usuarios (
					id_ubicacion,
					password, 
					cat_status, 
					usuario, 
					correo, 
					id_rol, 
					nombres, 
					apellido_paterno,
					apellido_materno,
					user_alta,
					fecha_alta
				) VALUES (
					:id_ubicacion,
					:password, 
					:cat_status, 
					:usuario, 
					:correo, 
					:id_rol, 
					:nombres, 
					:apellido_paterno,
					:apellido_materno,
					:user_alta,
					:fecha_alta
				)";
			$query = $this->db->prepare($sql);
			$query_resp = $query->execute(
				array(
					':id_ubicacion' => $this->id_ubicacion,
					':password' => md5($this->password), 
					':cat_status' => $this->cat_status, 
					':usuario' => trim($this->usuario),
					':correo' => $this->correo, 
					':id_rol' => $this->id_rol, 
					':nombres' => $this->nombres, 
					':apellido_paterno' => $this->apellido_paterno,
					':apellido_materno' => $this->apellido_materno,
					':user_alta' => $_SESSION['id_usuario'],
					':fecha_alta' => date("Y-m-d H:i:s")
				)
			);
			
			$id_usuario = $this->db->lastInsertId();
			
			self::crear_perfil($id_usuario);
			self::updateIngreso($this->fecha_ingreso,$id_usuario);
			
			if($query_resp){
				$respuesta = array('resp' => true , 'mensaje' => 'Registro guardado correctamente.', 'id_rol' =>  $this->id_rol, 'id_usuario' => $id_usuario);
			}else{
				$respuesta = array('resp' => false , 'mensaje' => 'Error en el sistema.' , 'error' => 'Error al insertar registro.' );
			}
			
		}

		return $respuesta;
	}

	public function valida_login_correo($usuario,$correo){
		$resp = true;
		$error = "";
		$mensaje = "";
		
		$resp_login = self::consulta_login($usuario);
		$resp_correo = self::consulta_correo($correo);
		if($resp_login['resp'] == true ){
			$resp=false;
			$mensaje = 'Error por duplicidad de datos.';
			$error.= 'Nombre de usuario no disponible.<br />';
		}
		if($resp_correo['resp'] == true ){
			$resp=false;
			$mensaje = 'Error por duplicidad de datos.';
			$error.= 'Cuenta de correo electrónico no disponible.'; 
		}
		return array('resp' => $resp, 'mensaje' => $mensaje, 'error' => $error );
	}
	public function consulta_login($usuario){
		$sql_usr="SELECT usuario FROM fw_usuarios WHERE usuario = '".$usuario."' ;";
		$query = $this->db->prepare($sql_usr);
		$query->execute();
		$query_resp = $query->fetchAll();

		if($query_resp){
			$respuesta = array('resp' => true, 'datos' => $query_resp );
		}else{
			$respuesta = array('resp' => false, 'mensaje' => 'Sin resultados.'  );
		}
		return $respuesta;
	}
	public function consulta_correo($correo){
		$sql_usr="SELECT correo FROM fw_usuarios WHERE correo = '".$correo."' ;";
		$query = $this->db->prepare($sql_usr);
		$query->execute();
		$query_resp = $query->fetchAll();

		if($query_resp){
			$respuesta = array('resp' => true, 'datos' => $query_resp );
		}else{
			$respuesta = array('resp' => false, 'mensaje' => 'Sin resultados en busqueda.'  );
		}
		return $respuesta;
	}
	public function datos_usuario($user_id){
		$user_id = intval($user_id);
		$sql_usr="
			SELECT
				fw_usuarios.id_usuario,
				fw_usuarios.id_area,
				fw_usuarios.`password`,
				fw_usuarios.usuario,
				fw_usuarios.correo,
				fw_usuarios.id_rol,
				fw_usuarios.nombres,
				fw_usuarios.apellido_paterno,
				fw_usuarios.apellido_materno,
				fw_usuarios.id_ubicacion,
				fw_usuarios.cat_status,
				fw_usuarios_config.fecha_ingreso
			FROM
				fw_usuarios
			INNER JOIN fw_usuarios_config ON fw_usuarios_config.id_usuario = fw_usuarios.id_usuario
			WHERE
				fw_usuarios.id_usuario = ".$user_id."		
		";
		$query = $this->db->prepare($sql_usr);
		$query->execute();
		$usuario = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($usuario as $row) {
					$array['id_usuario'] 		= $row->id_usuario;
					$array['usuario'] 			= $row->usuario;
					$array['correo' ]			= $row->correo;
					$array['id_rol'] 			= $row->id_rol;
					$array['nombres'] 			= $row->nombres;
					$array['apellido_paterno'] 	= $row->apellido_paterno;
					$array['apellido_materno'] 	= $row->apellido_materno;
					$array['id_ubicacion'] 		= $row->id_ubicacion;
					$array['password'] 			= $row->password;
					$array['cat_status'] 		= $row->cat_status;
					$array['fecha_ingreso'] 	= $row->fecha_ingreso;
			}
		}
		return $array;
	}
	public function perfil_usuario($user_id){
		$user_id = intval($user_id);
		$sql_usr="SELECT * FROM fw_usuarios_config WHERE id_usuario = ".$user_id."";
		$query = $this->db->prepare($sql_usr);
		$query->execute();
		$perfil = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($perfil as $row) {
					$array['avatar'] 			= $row->avatar;
					$array['paginacion'] 		= $row->paginacion;
					$array['activar_paginado'] 	= $row->activar_paginado;
			}
		}else{
			self::crear_perfil($user_id);
			self::perfil_usuario($user_id);
		}
		return $array;
	}
	public function verifica_token($token){
		$sql="SELECT * FROM fw_lost_password WHERE token = '".$token."'";
		$query = $this->db->prepare($sql);
		$query->execute();
		$lost_pass = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($lost_pass as $row) {
				$array['token'] 		= $row->token;
				$array['id_usuario'] 	= $row->id_usuario;
				$array['correo'] 		= $row->correo;
				$array['valid'] 		= true;
			}
		}
		return $array;
	}
	public function cambiar_password($pass,$id_usuario){
		$sql = "UPDATE fw_usuarios SET password = '".md5($pass)."', user_mod = '".$_SESSION['id_usuario']."' where id_usuario = '".$id_usuario."'";
		$query = $this->db->prepare($sql);
		$query_resp = $query->execute();
		return $query_resp;
	}
	public function eliminar_token($token){
		$clean = "DELETE FROM fw_lost_password WHERE token = :token";
        $query = $this->db->prepare($clean);
        $query_resp = $query->execute(array(':token' => $token));
		return $query_resp;
	}
	public function editar_usuario($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		if(($this->password == $this->password2)&&($this->password)){
			$change_pass = "password='".md5($this->password)."',";
		}else{
			$change_pass = "";
		}
		
				$sql = "UPDATE fw_usuarios SET 
						id_ubicacion 		=	'".$this->id_ubicacion."',
						$change_pass
						cat_status 			=	'".$this->cat_status."',
						correo 				=	'".$this->correo."',
						id_rol 				=	'".$this->id_rol."',
						nombres 			=	'".$this->nombres."',
						apellido_paterno 	=	'".$this->apellido_paterno."',
						apellido_materno 	=	'".$this->apellido_materno."',
						user_mod			=   '".$_SESSION['id_usuario']."'
					where 
						id_usuario 	= 	'".$this->id_usuario."'
				";
				$query = $this->db->prepare($sql);
				$query_resp = $query->execute();	

		if($query_resp){
			self::updateIngreso($this->fecha_ingreso,$this->id_usuario);
			$respuesta = array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' );
		}else{
			$respuesta = array('resp' => false , 'mensaje' => 'Error en el sistema.' , 'error' => 'Error al insertar registro.' );
		}
		return $respuesta;		
	}
	public function editar_perfil($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		if(($this->password == $this->password2)&&($this->password != 'no_seas_miron')){
			$change_pass = "password='".md5($this->password)."',";
		}else{
			$change_pass = "";
		}
		
				$sql = "UPDATE fw_usuarios SET 
						$change_pass
						correo 				=	'".$this->correo."',
						nombres 			=	'".$this->nombres."',
						apellido_paterno 	=	'".$this->apellido_paterno."',
						apellido_materno 	=	'".$this->apellido_materno."',
						user_mod			=   '".$_SESSION['id_usuario']."'
					where 
						id_usuario 	= 	'".$_SESSION['id_usuario']."'
				";
				$query = $this->db->prepare($sql);
				$query_resp = $query->execute();
				
				self::crear_perfil($_SESSION['id_usuario']);
				
				if(self::crear_perfil($_SESSION['id_usuario'])){
					$activar_paginado = (!empty ($this->activar_paginado)) ? 1 : 0;
					$paginacion = $this->paginacion ? $this->paginacion : 0;
					$sql2 = "UPDATE fw_usuarios_config SET 
							paginacion 			=	'".$paginacion."',
							activar_paginado	= 	'".$activar_paginado."',
							user_mod			=   '".$_SESSION['id_usuario']."'
						where 
							id_usuario 	= 	'".$_SESSION['id_usuario']."'
					";
					$query2 = $this->db->prepare($sql2);
					$query_resp2 = $query2->execute();
				}
				
		if(($query_resp)&&($query_resp2)){
			$respuesta = array('resp' => true , 'mensaje' => 'El perfil guardado correctamente.', 'chackbox' => $activar_paginado, 'new_name' => $this->nombres );
		}else{
			$respuesta = array('resp' => false , 'mensaje' => 'Ocurrió un error al actualizar el perfil.' );
		}
		return $respuesta;		
	}
	function updateIngreso($fecha_ingreso,$id_usuario){
		$sql = "
			UPDATE fw_usuarios_config
			SET 
				fecha_ingreso = '".$fecha_ingreso."',
				user_mod = '".$_SESSION['id_usuario']."'
			WHERE
				id_usuario = '".$id_usuario."'			
		";
		$query = $this->db->prepare($sql);
		$result = $query->execute();	
	}	
	function acceptTyc($stat){
		$sql = "
			UPDATE fw_usuarios_config
			SET 
				aceptar_tyc = '".$stat."',
				user_mod = '".$_SESSION['id_usuario']."'
			WHERE
				id_usuario = '".$_SESSION['id_usuario']."'			
		";
		$query = $this->db->prepare($sql);
		$result = $query->execute();
		if($result){
			$_SESSION['tyc'] = 'SI';
			$respuesta = array('resp' => true , 'dispositivo' => $_SESSION['dispositivo'] );
		}else{
			$_SESSION['tyc'] = 'NO';
			$respuesta = array('resp' => false , 'dispositivo' => $_SESSION['dispositivo'] );
		}
		return $respuesta;		
	}
	function set_avatar($avatar){
		$perfil = self::perfil_usuario($_SESSION['id_usuario']);
		$avatar_actual = $perfil['avatar'];
		
			if($avatar_actual){
				unlink('../uploads/perfiles/'.$avatar_actual);
			}
		
			$sql = "
				UPDATE fw_usuarios_config
				SET avatar = '".$avatar."',
				 user_mod = '".$_SESSION['id_usuario']."'
				WHERE
					id_usuario = '".$_SESSION['id_usuario']."'			
			";
			
		$query = $this->db->prepare($sql);
		$query_resp = $query->execute();
	}	
	private function crear_perfil($id_usuario){
		$sql="SELECT count(*) as existe FROM fw_usuarios_config where id_usuario = '".$id_usuario."'";
		$total = $this->db->prepare($sql);
		$total->execute();
		$existe = $total->fetch(PDO::FETCH_OBJ);	
		if($existe->existe == 1){
			return true;
		}else{
			$sql = "
				INSERT INTO fw_usuarios_config (
					id_usuario,
					user_alta,
					fecha_alta
				)
				VALUES
				(
					:id_usuario,
					:user_alta,
					:fecha_alta
				);
			";
			$query = $this->db->prepare($sql);
			$query_resp = $query->execute(array(
				':id_usuario' => $id_usuario,
				':user_alta' => $_SESSION['id_usuario'],
				':fecha_alta' => date("Y-m-d H:i:s") 
			));
			if($query_resp){
				return true;
			}else{
				return false;
			}
		}
	}
	public function baja_usuario($id_usuario){
		$sql = "UPDATE fw_usuarios SET cat_status = '5', user_mod = '".$_SESSION['id_usuario']."' where id_usuario = '".$id_usuario."'";
		$query = $this->db->prepare($sql);
		$query_resp = $query->execute();
		if($query_resp){
			$respuesta = array('resp' => true , 'mensaje' => 'La baja del usuario se realizó de manera correcta.' );
		}else{
			$respuesta = array('resp' => false , 'mensaje' => 'Error en el sistema.' , 'error' => 'Error al dar de baja al usuario.' );
		}
		return $respuesta;
	}
	function getPermisos($user){
		$user = intval($user);
		$sql="SELECT tercio FROM fw_dac_acl where id_usuario = '".$user."'";
		$query = $this->db->prepare($sql);
		$query->execute();
		$usuario = $query->fetchAll();
		
		if($query->rowCount()>=1){
			foreach ($usuario as $row) {
				$array[]=$row->tercio;
			}
		}
		return $array;
	}
	public function logueados_get($array){
		ini_set('memory_limit', '256M');				
		$table = 'fw_login AS fwl';
		$primaryKey = 'fwl.id_usuario';
		$columns = array(
			array( 
				'db' => 'fwl.id_usuario as id_usuario',
				'dbj' => 'fwl.id_usuario',
				'real' => 'fwl.id_usuario',
				'alias' => 'id_usuario',
				'typ' => 'int',
				'dt' => 0
			),
			array( 
				'db' => 'fwu.usuario AS usuario',
				'dbj' => 'fwu.usuario',	
				'real' => 'fwu.usuario',
				'alias' => 'usuario',
				'typ' => 'txt',
				'dt' => 1
			),
			array( 
				'db' => 'CONCAT(fwu.nombres, " " ,	fwu.apellido_paterno, " " ,	fwu.apellido_materno) AS nombre',
				'dbj' => 'CONCAT(fwu.nombres, " " ,	fwu.apellido_paterno, " " ,	fwu.apellido_materno)',
				'real' => 'CONCAT(fwu.nombres, " " ,	fwu.apellido_paterno, " " ,	fwu.apellido_materno)',
				'alias' => 'nombre',
				'typ' => 'txt',
				'dt' => 2
			),
			array( 
				'db' => 'fwl.fecha_login AS fecha_login',
				'dbj' => 'fwl.fecha_login',
				'real' => 'fwl.fecha_login',
				'alias' => 'fecha_login',
				'typ' => 'int',
				'dt' => 3				
			),
			array( 
				'db' => 'fwl.ultima_verificacion AS ultima_verificacion',
				'dbj' => 'fwl.ultima_verificacion',
				'real' => 'fwl.ultima_verificacion',
				'alias' => 'ultima_verificacion',
				'typ' => 'txt',
				'dt' => 4				
			),
			array( 
				'db' => 'fwl.ipv4 AS ipv4',
				'dbj' => 'fwl.ipv4',
				'real' => 'fwl.ipv4',
				'alias' => 'ipv4',
				'typ' => 'txt',
				'dt' => 5				
			),
			array( 
				'db' => 'fwl.session_id AS session_id',
				'dbj' => 'fwl.session_id',
				'real' => 'fwl.session_id',
				'alias' => 'session_id',
				'typ' => 'txt',
				'dt' => 6			
			),
			array( 
				'db' => 'fwl.session_id AS session_idx',
				'dbj' => 'fwl.session_id',
				'real' => 'fwl.session_id',
				'alias' => 'session_idx',
				'typ' => 'txt',
				'acciones' => true,
				'dt' => 7
			)
		);
		$render_table = new acciones_login;
		$inner = '
			INNER JOIN fw_usuarios AS fwu ON fwl.id_usuario = fwu.id_usuario
		';
		$where = '
			fwl.`open` = 1
		';
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
	public function obtener_usuarios($array){
		ini_set('memory_limit', '256M');				
		$table = 'fw_usuarios as fwu';
		$primaryKey = 'id_usuario';
		$columns = array(
			array( 
				'db' => 'fwu.id_usuario as id_usuario',
				'dbj' => 'fwu.id_usuario',
				'real' => 'fwu.id_usuario',
				'alias' => 'id_usuario',
				'typ' => 'int',
				'dt' => 0
			),
			array( 
				'db' => 'fwu.usuario AS usuario',
				'dbj' => 'fwu.usuario',	
				'real' => 'fwu.usuario',
				'alias' => 'usuario',
				'typ' => 'txt',
				'dt' => 1
			),
			array( 
				'db' => 'fwu.correo AS correo',
				'dbj' => 'fwu.correo',				
				'real' => 'fwu.correo',
				'alias' => 'correo',
				'typ' => 'txt',
				'dt' => 2
			),
			array( 
				'db' => 'fwu.nombres AS nombres',
				'dbj' => 'fwu.nombres',
				'real' => 'fwu.nombres',
				'alias' => 'nombres',
				'typ' => 'int',
				'dt' => 3				
			),
			array( 
				'db' => 'fwu.apellido_paterno AS apellido_paterno',
				'dbj' => 'fwu.apellido_paterno',
				'real' => 'fwu.apellido_paterno',
				'alias' => 'apellido_paterno',
				'typ' => 'txt',
				'dt' => 4				
			),
			array( 
				'db' => 'fwu.apellido_materno AS apellido_materno',
				'dbj' => 'fwu.apellido_materno',
				'real' => 'fwu.apellido_materno',
				'alias' => 'apellido_materno',
				'typ' => 'txt',
				'dt' => 5				
			),
			array( 
				'db' => 'fwr.descripcion AS descripcion',
				'dbj' => 'fwr.descripcion',
				'real' => 'fwr.descripcion',
				'alias' => 'descripcion',
				'typ' => 'txt',
				'dt' => 6				
			),
			array( 
				'db' => 'fwuc.poseido AS poseido',
				'dbj' => 'fwuc.poseido',
				'real' => 'fwuc.poseido',
				'alias' => 'poseido',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 7				
			)			
		);
		$render_table = new acciones_usuarios;
		$inner = '
			INNER JOIN fw_roles as fwr ON fwu.id_rol = fwr.id_rol
			INNER JOIN fw_usuarios_config as fwuc ON fwu.id_usuario = fwuc.id_usuario
		';
		$where = '';
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}		
}
class acciones_usuarios extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				
				if ( isset( $column['acciones'] ) ) {
					$id_usuario = $data[$i][ 'id_usuario' ];
					$poseido = $data[$i][ 'poseido' ];
					$color = ($poseido == 1)?'#2a6496':'#ffbf00';
					$salida = '';
						if(Controlador::tiene_permiso('Usuarios|editar_usuario')){
							$salida .= '<a onclick="user_actions('.$id_usuario.');" data-rel="tooltip" data-original-title="Editar usuario">
							<i class="fa fa-pencil" style="font-size:1.6em; color:#80ff00;"></i>
							</a>&nbsp;&nbsp;';
						}			
						if(Controlador::tiene_permiso('Usuarios|posesion')){
							if($poseido == 1){
								$salida .= '<a onclick="liberar_posesion('.$id_usuario.');" data-rel="tooltip" data-original-title="Exorcizar"><i class="fa fa-snapchat-ghost" style="font-size:1.6em; color:'.$color.';"></i></a>&nbsp;&nbsp;';
							}else{
								$salida .= '<a onclick="tomar_posesion('.$id_usuario.');" data-rel="tooltip" data-original-title="Tomar posesión"><i class="fa fa-snapchat-ghost" style="font-size:1.6em; color:'.$color.';"></i></a>&nbsp;&nbsp;';
							}
						}
						
					$row[ $column['dt'] ] = $salida;
				}else{
					$row[ $column['dt'] ] = ( self::detectUTF8($data[$i][$name_column]) )? $data[$i][$name_column] : utf8_encode($data[$i][$name_column]);	
				}
				
			}
			$out[] = $row;
		}
		return $out;
	}
}
class acciones_login extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				if ( isset( $column['acciones'] ) ) {
					
					$id_usuario 		= $data[$i][ 'id_usuario' ];
					$operador = self::getOperador($id_usuario,$db);
					$id_operador = $operador['id_operador'];
					
					$salida = '<table><tr>';
					
					if(Controlador::tiene_permiso('Usuarios|logueados')){
						
						if($id_operador != 'N/A'){
							if($operador['estado'] == 'C1'){
								$salida .= '<td width="30px;"><span style="color:#69aa46 !important">'.$operador['estado'].'</span></td>';
							}else{
								$salida .= '<td width="30px;"><span style="color:red !important">'.$operador['estado'].'</span></td>';
							}
						}else{
							$salida .= '<td width="30px;"><a data-rel="tooltip" data-original-title="No es operador" class="green tooltip-success">N/A</a></td>';
						}
					}
					
					if(Controlador::tiene_permiso('Usuarios|logueados')){
						if($id_operador != 'N/A'){
							$salida .= '<td width="30px;"><a data-rel="tooltip" data-original-title="Número económico" class="green tooltip-success">'.$operador['num'].'</a></td>';
						}else{
							$salida .= '<td width="30px;"><a data-rel="tooltip" data-original-title="No es operador" class="green tooltip-success">N/A</a></td>';
						}
					}
					
					if(Controlador::tiene_permiso('Login|force_sign_out')){
						$salida .= '
							<td><div class="hidden-sm hidden-xs btn-group">
								<a onclick = "modal_sign_out('.$id_usuario.');" class="btn btn-xs btn-warning tooltip-warning" data-rel="tooltip" data-original-title="Des-loguear usuario">
									<i class="ace-icon fa fa-sign-out bigger-120"></i>
								</a>
							</div></td>				
						';
					}
					$salida .= '</tr></table>';
					
					$row[ $column['dt'] ] = $salida;
				}else{
					$row[ $column['dt'] ] = ( self::detectUTF8($data[$i][$name_column]) )? $data[$i][$name_column] : utf8_encode($data[$i][$name_column]);	
				}
			}
			$out[] = $row;
		}
		return $out;
	}
	static function getOperador($id_usuario,$db){
		$query = "
			SELECT
				cr_operador.id_operador,
				cr_numeq.num,
				cr_sync.estado1,
				count(cr_sync.estado1) as tot,
				count(DISTINCT cr_sync.estado1) as C1				
			FROM
				cr_operador
			INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = cr_operador.id_operador
			INNER JOIN cr_numeq ON cr_operador_numeq.id_numeq = cr_numeq.id_numeq
			INNER JOIN cr_operador_unidad ON cr_operador_unidad.id_operador = cr_operador.id_operador
			INNER JOIN cr_sync ON cr_operador_unidad.sync_token = cr_sync.token
			WHERE
				`id_usuario` = $id_usuario
		";
		
		$query = $db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				if(($row['C1'] >= 2)&&($row['tot'] >= 2)){
					$stat = 'C1';
				}else if(($row['C1'] >= 1)&&($row['tot'] >= 2)){
					$stat = 'C2';
				}else if(($row['C1'] == 1)&&($row['tot'] == 1)&&($row['estado1'] == 'C1')){
					$stat = 'C1';
				}else if(($row['C1'] == 1)&&($row['tot'] == 1)&&($row['estado1'] == 'C2')){
					$stat = 'C1';
				}else{
					$stat = 'C2';
				}				
				
				if($row['C1'] == 0){
					$stat = 'N/A';
					$array['id_operador'] =  'N/A';
				}else{
					$array['id_operador'] =  $row['id_operador'];
				}
				$array['num'] =  $row['num'];
				$array['estado'] =  $stat;
			}
		}
		return $array;
	}
}
?>