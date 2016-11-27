<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<?php
require_once( '../vendor/mysql_datatable.php' );
class OperadoresModel
{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
        }
    }
	function historia($id_operador){
		$sql="
			SELECT
				crn.num,
				cm1.etiqueta,
				cm2.valor,
				sync_ride.fecha_alta,
				fwu1.usuario AS solicita,
				fwu2.usuario AS autoriza
			FROM
				cr_sync_ride AS sync_ride
			INNER JOIN cr_operador_unidad AS crou ON sync_ride.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_operador AS crop ON crou.id_operador = crop.id_operador
			INNER JOIN cr_operador_numeq AS cron ON cron.id_operador = crop.id_operador
			INNER JOIN cr_numeq AS crn ON cron.id_numeq = crn.id_numeq
			INNER JOIN cm_catalogo AS cm1 ON sync_ride.cat_cve_store = cm1.id_cat
			INNER JOIN fw_usuarios AS fwu1 ON crop.id_usuario = fwu1.id_usuario
			INNER JOIN cm_catalogo AS cm2 ON cm1.etiqueta = cm2.etiqueta
			AND cm2.catalogo = 'clavesitio'
			INNER JOIN fw_usuarios AS fwu2 ON sync_ride.user_alta = fwu2.id_usuario
			WHERE
				crop.id_operador = $id_operador
			ORDER BY
				sync_ride.id_sync_ride DESC
			LIMIT 0, 100
	";
		$query = $this->db->prepare($sql);
		$query->execute();
		$historia = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			$num=0;
			foreach ($historia as $row) {
				$array[$num]['num'] = $row->num;
				$array[$num]['etiqueta'] = $row->etiqueta;
				$array[$num]['valor'] = $row->valor;
				$array[$num]['fecha_alta'] = $row->fecha_alta;
				$array[$num]['solicita'] = $row->solicita;
				$array[$num]['autoriza'] = $row->autoriza;
				$num++;
			}
		}
		return $array ;	
	}	
	function listadoTelefonos($id_operador){
		$sql="
			SELECT
				cr_telefonos.numero,
				cat1.etiqueta as lab1,
				cat2.etiqueta as lab2
			FROM
				cr_telefonos
			INNER JOIN cm_catalogo AS cat1 ON cr_telefonos.cat_tipotelefono = cat1.id_cat
			INNER JOIN cm_catalogo AS cat2 ON cr_telefonos.cat_statustelefono = cat2.id_cat
			WHERE
				cr_telefonos.id_operador = $id_operador
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$usuarios = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			$num=0;
			foreach ($usuarios as $row) {
				$array[$num]['numero'] = $row->numero;
				$array[$num]['lab1'] = $row->lab1;
				$array[$num]['lab2'] = $row->lab2;
				$num++;
			}
		}
		return $array ;	
	}
	function listadoDomicilios($id_operador){
		$sql="
			SELECT
				dom.domicilio,
				cat1.etiqueta AS lab1,
				cat2.etiqueta AS lab2
			FROM
				cr_domicilios AS dom
			INNER JOIN cm_catalogo AS cat1 ON dom.cat_tipodomicilio = cat1.id_cat
			INNER JOIN cm_catalogo AS cat2 ON dom.cat_statusdomicilio = cat2.id_cat
			WHERE
				dom.id_operador = $id_operador
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$usuarios = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			$num=0;
			foreach ($usuarios as $row) {
				$array[$num]['domicilio'] = $row->domicilio;
				$array[$num]['lab1'] = $row->lab1;
				$array[$num]['lab2'] = $row->lab2;
				$num++;
			}
		}
		return $array ;	
	}
	function queryDomicilio($id_operador){
		$sql_dom="
			SELECT
				cr_od.id_domicilio,
				cr_od.id_operador,
				cr_od.domicilio,
				cr_od.cat_tipodomicilio,
				cr_od.cat_statusdomicilio,				
				cat1.etiqueta as etiqueta1,
				cat2.etiqueta as etiqueta2
			FROM
				cr_domicilios AS cr_od
			INNER JOIN cm_catalogo AS cat1 ON cr_od.cat_tipodomicilio = cat1.id_cat
			INNER JOIN cm_catalogo AS cat2 ON cr_od.cat_statusdomicilio = cat2.id_cat
			WHERE
			cr_od.id_operador = $id_operador
		";
		$query = $this->db->prepare($sql_dom);
		$query->execute();
		$doms =  $query->fetchAll();
		if($query->rowCount()>=1){
			return $doms;
		}
	}
	public function add_domicilio($arreglo){

		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
			
			$sql = "
				INSERT INTO cr_domicilios (
					id_operador,
					domicilio,
					cat_tipodomicilio,
					cat_statusdomicilio,
					user_alta,
					fecha_alta
				) VALUES (
					:id_operador,
					:domicilio,
					:cat_tipodomicilio,
					:cat_statusdomicilio,
					:user_alta,
					:fecha_alta
				)";
			$query = $this->db->prepare($sql);
			$query_resp = $query->execute(
				array(
					':id_operador' => $this->id_operador,
					':domicilio' => $this->domicilio,
					':cat_tipodomicilio' => $this->cat_tipodomicilio,
					':cat_statusdomicilio' => $this->cat_statusdomicilio,
					':user_alta' => $_SESSION['id_usuario'],
					':fecha_alta' => date("Y-m-d H:i:s")
				)
			);
			if($query_resp){
				$respuesta = array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' );
			}else{
				$respuesta = array('resp' => false , 'mensaje' => 'Error en el sistema.' , 'error' => 'Error al insertar registro.', 'query'=>$sql  );
			}

		return $respuesta;
	}
	public function status_domicilio($id_domicilio,$status){
		$sql = "
			UPDATE cr_domicilios
			SET 
			 cat_statusdomicilio = :status,
			 user_mod = :user_mod
			WHERE
				id_domicilio = :id_domicilio
		";
		$query = $this->db->prepare($sql);
		$data = array(
			':status' => $status, 
			':user_mod' => $_SESSION['id_usuario'],
			':id_domicilio' => $id_domicilio
		);
		$query_resp = $query->execute($data);
		if($query_resp){
			$respuesta = array('resp' => true);
		}else{
			$respuesta = array('resp' => false);
		}
		return $respuesta;
	}
	
	function queryTels($id_operador){
		$sql_tel="
			SELECT
				clt.id_telefono,
				clt.id_operador,
				clt.numero,
				clt.cat_tipotelefono,
				clt.cat_statustelefono,				
				cat1.etiqueta as etiqueta1,
				cat2.etiqueta as etiqueta2
			FROM
				cr_telefonos AS clt
			INNER JOIN cm_catalogo AS cat1 ON clt.cat_tipotelefono = cat1.id_cat
			INNER JOIN cm_catalogo AS cat2 ON clt.cat_statustelefono = cat2.id_cat
			WHERE
			clt.id_operador = $id_operador
		";
		$query = $this->db->prepare($sql_tel);
		$query->execute();
		$tels =  $query->fetchAll();
		if($query->rowCount()>=1){
			return $tels;
		}
	}
	public function add_telefono($arreglo){

		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
			
			$sql = "
				INSERT INTO cr_telefonos (
					id_operador,
					numero,
					cat_tipotelefono,
					cat_statustelefono,
					user_alta,
					fecha_alta
				) VALUES (
					:id_operador,
					:numero,
					:cat_tipotelefono,
					:cat_statustelefono,
					:user_alta,
					:fecha_alta
				)";
			$query = $this->db->prepare($sql);
			$query_resp = $query->execute(
				array(
					':id_operador' => $this->id_operador,
					':numero' => $this->numero,
					':cat_tipotelefono' => $this->cat_tipotelefono,
					':cat_statustelefono' => $this->cat_statustelefono,
					':user_alta' => $_SESSION['id_usuario'],
					':fecha_alta' => date("Y-m-d H:i:s")
				)
			);
			if($query_resp){
				$respuesta = array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' );
			}else{
				$respuesta = array('resp' => false , 'mensaje' => 'Error en el sistema.' , 'error' => 'Error al insertar registro.', 'query'=>$sql  );
			}

		return $respuesta;
	}
	public function status_telefono($id_telefono,$status){
		$sql = "
			UPDATE cr_telefonos
			SET 
			 cat_statustelefono = :status,
			 user_mod = :user_mod
			WHERE
				id_telefono = :id_telefono
		";
		$query = $this->db->prepare($sql);
		$data = array(
			':status' => $status, 
			':user_mod' => $_SESSION['id_usuario'],
			':id_telefono' => $id_telefono
		);
		$query_resp = $query->execute($data);
		if($query_resp){
			$respuesta = array('resp' => true);
		}else{
			$respuesta = array('resp' => false);
		}
		return $respuesta;
	}
	
	function tarifas_del_do($id_tarifa_operador){
		$sql = "
			UPDATE fo_tarifas_operadores
			SET eliminado = 1,
				user_mod =  '".$_SESSION['id_usuario']."'
			where
				id_tarifa_operador = ".$id_tarifa_operador."
		";
		$query = $this->db->prepare($sql);
		$result = $query->execute();

		return $result? array('resp' => true):array('resp' => false);	
	}
	function nueva_tarifa_do($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		$sql = "
			INSERT INTO fo_tarifas_operadores (
				id_operador,
				costo_base,
				km_adicional,
				cat_formapago,
				nombre,
				user_alta,
				fecha_alta
			)
			VALUES
				(
					:id_operador,
					:costo_base,
					:km_adicional,
					:cat_formapago,
					:nombre,
					:user_alta,
					:fecha_alta
				)
		";
		$query = $this->db->prepare($sql);
		$result = $query->execute(array(
			':id_operador' => $this->id_operador, 
			':costo_base' => $this->costo_base, 
			':km_adicional' => $this->km_adicional, 
			':cat_formapago' => $this->cat_formapago, 
			':nombre' => $this->nombre,
			':user_alta' => $_SESSION['id_usuario'],
			':fecha_alta' => date("Y-m-d H:i:s")
		));		
		return $result? array('resp' => true, 'id' => $this->db->lastInsertId()):array('resp' => false);
	}
	function setearstatusoperador($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		$sql = "UPDATE cr_operador SET cat_statusoperador = ".$this->cat_statusoperador." where id_operador = ".$this->id_operador."";
		$query = $this->db->prepare($sql);
		$result = $query->execute();
		return $result? array('resp' => true):array('resp' => false);
	}
	function operadorData($id_operador){
		$sql="
			SELECT
				fw_usuarios.nombres,
				fw_usuarios.apellido_paterno,
				fw_usuarios.apellido_materno
			FROM
				cr_operador
			INNER JOIN fw_usuarios ON cr_operador.id_usuario = fw_usuarios.id_usuario
			WHERE
				cr_operador.id_operador = ".$id_operador."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$array =  $query->fetchAll();
		if($query->rowCount()>=1){
			return $array;
		}	
	}
	function altaOperador($id_usuario){
		if(self::noexisteOperador($id_usuario)){
			$sql = "
				INSERT INTO cr_operador (
					id_usuario,
					cat_statusoperador,
					user_alta,
					fecha_alta
				)
				VALUES
					(
						:id_usuario,
						:cat_statusoperador,
						:user_alta,
						:fecha_alta
					)
			";
			$query = $this->db->prepare($sql);
			$result = $query->execute(array(
				':id_usuario' => $id_usuario, 
				':cat_statusoperador' => 8,
				':user_alta' => $_SESSION['id_usuario'],
				':fecha_alta' => date("Y-m-d H:i:s")
			));
			return $this->db->lastInsertId();
		}
	}
	function altaOperadornumeq($id_operador,$id_usuario){
		$sql = "
			INSERT INTO cr_operador_numeq (
				id_operador,
				cat_status_oper_numeq,
				user_alta,
				fecha_alta
			)
			VALUES
				(
					:id_operador,
					:cat_status_oper_numeq,
					:user_alta,
					:fecha_alta
				)
		";
		$query = $this->db->prepare($sql);
		$result = $query->execute(array(
			':id_operador' => $id_operador, 
			':cat_status_oper_numeq' => 12,
			':user_alta' => $_SESSION['id_usuario'],
			':fecha_alta' => date("Y-m-d H:i:s")
		));
		return $this->db->lastInsertId();
	}
	function noexisteOperador($id_usuario){
		$sql="
			SELECT
				id_operador
			FROM
				cr_operador
			WHERE
				id_usuario = ".$id_usuario."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		if($query->rowCount()==0){
			return  true;
		}
	}
	function getDataOperador($id_operador){
		$sql="
			SELECT
				co.id_operador_numeq,
				co.id_numeq,
				cat.etiqueta,
				co.cat_status_oper_numeq
			FROM
				cr_operador_numeq AS co
			INNER JOIN cm_catalogo AS cat ON co.cat_status_oper_numeq = cat.id_cat
			WHERE
				co.id_operador = ".$id_operador."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$usuarios = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($usuarios as $row) {
				$array['id_operador_numeq'] = $row->id_operador_numeq;
				$array['id_numeq'] = $row->id_numeq;
				$array['cat_status_oper_numeq'] = $row->cat_status_oper_numeq;
				$array['etiqueta'] = $row->etiqueta;
			}
		}
		return $array ;	
	}
	function getOperador($id_operador){
		$sql="
			SELECT
				id_usuario,
				cat_statusoperador
			FROM
				cr_operador
			WHERE
				id_operador = ".$id_operador."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$usuarios = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($usuarios as $row) {
				$array['id_usuario'] = $row->id_usuario;
				$array['cat_statusoperador'] = $row->cat_statusoperador;
			}
		}
		return $array ;	
	}
	function selectNumEq($id_numeq){
		$array = array();
		if($id_numeq){$numeq = ' id_numeq = '.$id_numeq.' OR ';}else{$numeq = '';}
		$qry = "SELECT * FROM cr_numeq where ".$numeq." eq_status = 6";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			$cont = 0;
			foreach ($data as $row) {
				$array[$cont]['value']=$row->id_numeq;
				$array[$cont]['valor']=$row->num;
				$cont++;			
			}
		}
		return Controller::setOption($array,$id_numeq);		
	}
	function selectStatusOperador($id_cat){
		$array = array();
		$qry = "SELECT * FROM cm_catalogo where catalogo = 'statusoperador' and activo = 1";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			$cont = 0;
			foreach ($data as $row) {
				$array[$cont]['value']=$row->id_cat;
				$array[$cont]['valor']=$row->etiqueta;
				$cont++;			
			}
		}
		return Controller::setOption($array,$id_cat);		
	}
	function selectStatNumEq($cat_status_oper_numeq){
		$array = array();
		$qry = "SELECT * FROM cm_catalogo where catalogo = 'status_oper_numeq' and activo = 1";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			$cont = 0;
			foreach ($data as $row) {
				$array[$cont]['value']=$row->id_cat;
				$array[$cont]['valor']=$row->etiqueta;
				$cont++;			
			}
		}
		return Controller::setOption($array,$cat_status_oper_numeq);	
	}
	function setStatNumEq($id_numeq,$stat){
		$sql = "
			UPDATE cr_numeq
			SET eq_status = ".$stat.",
			user_mod =  '".$_SESSION['id_usuario']."'
			WHERE
				id_numeq = ".$id_numeq."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	function liberarStatOperNumEq($num){
		$sql = "
			UPDATE cr_operador_numeq
			SET id_numeq = NULL,
			user_mod =  '".$_SESSION['id_usuario']."'
			WHERE
				id_numeq = ".$num."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	function liberarNumero($num){
		self::setStatNumEq($num,6);
		self::liberarStatOperNumEq($num);
	}
	function setNumEq($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		($this->num_eq_old)?self::setStatNumEq($this->num_eq_old,6):NULL;
		$sql = "
			UPDATE cr_operador_numeq
			SET id_numeq = ".$this->id_numeq.",
				cat_status_oper_numeq = '12',
				user_mod =  '".$_SESSION['id_usuario']."'
			where
				id_operador = ".$this->id_operador."
		";
		$query = $this->db->prepare($sql);
		$result = $query->execute();
		self::setStatNumEq($this->id_numeq,7);

		return $result? array('resp' => true):array('resp' => false);
	}
	function obtener_tarifas($array,$id_operador){
		ini_set('memory_limit', '256M');				
		$table = 'fo_tarifas_operadores';
		$primaryKey = 'id_tarifa_operador';
		$columns = array(
			array( 
				'db' => 'id_tarifa_operador',
				'dbj' => 'id_tarifa_operador',
				'real' => 'id_tarifa_operador',
				'typ' => 'int',
				'dt' => 0
			),
			array( 
				'db' => 'nombre AS nombre',
				'dbj' => 'nombre',
				'real' => 'nombre',
				'alias' => 'nombre',
				'typ' => 'int',
				'dt' => 1				
			),
			array( 
				'db' => 'costo_base AS costo_base',
				'dbj' => 'costo_base',	
				'alias' => 'costo_base',
				'real' => 'costo_base',
				'typ' => 'txt',
				'dt' => 2
			),
			array( 
				'db' => 'km_adicional AS km_adicional',
				'dbj' => 'km_adicional',				
				'real' => 'km_adicional',
				'alias' => 'km_adicional',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'cmc.etiqueta AS formapago',
				'dbj' => 'cmc.etiqueta',				
				'real' => 'cmc.etiqueta',
				'alias' => 'formapago',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'id_tarifa_operador',
				'dbj' => 'id_tarifa_operador',
				'real' => 'id_tarifa_operador',
				'typ' => 'int',
				'acciones' => true,
				'id_operador' => $id_operador,
				'dt' => 5			
			)
		);
		$render_table = new acciones_tarifas;
		$inner = '
			INNER JOIN cm_catalogo AS cmc ON cat_formapago = cmc.id_cat
		';
		$where = '
			id_operador = '.$id_operador.'
			AND
			eliminado = 0
		';
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
	public function obtener_operadores($array){
		ini_set('memory_limit', '256M');				
		$table = 'cr_operador AS cro';
		$primaryKey = 'id_operador';
		$columns = array(
			array( 
				'db' => 'cro.id_operador as id_opr',
				'dbj' => 'cro.id_operador',
				'real' => 'cro.id_operador',
				'alias' => 'id_opr',
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
				'db' => 'CONCAT(fwu.nombres, " " ,	fwu.apellido_paterno, " " ,	fwu.apellido_materno) AS nombre',
				'dbj' => 'CONCAT(fwu.nombres, " " ,	fwu.apellido_paterno, " " ,	fwu.apellido_materno)',
				'real' => 'CONCAT(fwu.nombres, " " ,	fwu.apellido_paterno, " " ,	fwu.apellido_materno)',
				'alias' => 'nombre',
				'typ' => 'txt',
				'dt' => 3
			),			
			array( 
				'db' => 'fwu.cat_status AS cat_status',
				'dbj' => 'fwu.cat_status',
				'real' => 'fwu.cat_status',
				'alias' => 'cat_status',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 4				
			),
			array( 
				'db' => 'fwu.id_usuario as id_usuario',
				'dbj' => 'fwu.id_usuario',
				'real' => 'fwu.id_usuario',
				'alias' => 'id_usuario',
				'typ' => 'int',
				'dt' => 5
			)
		);
		$render_table = new acciones_operador;
		$inner = '
			INNER JOIN cr_operador_numeq AS cron ON cron.id_operador = cro.id_operador
			INNER JOIN fw_usuarios AS fwu ON cro.id_usuario = fwu.id_usuario	
		';
		$where = '
			fwu.cat_status = 3
			AND cron.cat_status_oper_numeq = 12
		';
		$where = '';
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
	function listado_vigente_get($array){
		ini_set('memory_limit', '256M');				
		$table = 'cr_operador AS cro';
		$primaryKey = 'id_operador';
		$columns = array(
			array( 
				'db' => 'cro.id_operador as id_opr',
				'dbj' => 'cro.id_operador',
				'real' => 'cro.id_operador',
				'alias' => 'id_opr',
				'typ' => 'int',
				'dt' => 0
			),
			array( 
				'db' => 'crn.num AS num',
				'dbj' => 'crn.num',	
				'real' => 'crn.num',
				'alias' => 'num',
				'typ' => 'int',
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
				'db' => 'crc.numero AS numero',
				'dbj' => 'crc.numero',
				'real' => 'crc.numero',
				'alias' => 'numero',
				'typ' => 'int',
				'dt' => 3				
			),
			array( 
				'db' => 'crc.marcacion_corta AS marcacion_corta',
				'dbj' => 'crc.marcacion_corta',
				'real' => 'crc.marcacion_corta',
				'alias' => 'marcacion_corta',
				'typ' => 'int',
				'dt' => 4				
			),
			array( 
				'db' => 'crm.marca AS marca',
				'dbj' => 'crm.marca',
				'real' => 'crm.marca',
				'alias' => 'marca',
				'typ' => 'txt',
				'dt' => 5				
			),
			array( 
				'db' => 'crmo.modelo AS modelo',
				'dbj' => 'crmo.modelo',
				'real' => 'crmo.modelo',
				'alias' => 'modelo',
				'typ' => 'txt',
				'dt' => 6				
			),
			array( 
				'db' => 'crun.`year` AS agno',
				'dbj' => 'crun.`year`',
				'real' => 'crun.`year`',
				'alias' => 'agno',
				'typ' => 'int',
				'dt' => 7				
			),
			array( 
				'db' => 'crun.placas AS placas',
				'dbj' => 'crun.placas',
				'real' => 'crun.placas',
				'alias' => 'placas',
				'typ' => 'txt',
				'dt' => 8				
			),
			array( 
				'db' => 'crun.color AS color',
				'dbj' => 'crun.color',
				'real' => 'crun.color',
				'alias' => 'color',
				'typ' => 'txt',
				'dt' => 9				
			),
			array( 
				'db' => 'cro.id_usuario AS id_usuario',
				'dbj' => 'cro.id_usuario',
				'real' => 'cro.id_usuario',
				'alias' => 'id_usuario',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 10				
			)
		);
		$render_table = new acciones_lista_opr;
		$inner = '
			INNER JOIN cr_operador_numeq AS cron ON cron.id_operador = cro.id_operador
			INNER JOIN cr_numeq AS crn ON cron.id_numeq = crn.id_numeq
			INNER JOIN fw_usuarios AS fwu ON cro.id_usuario = fwu.id_usuario
			INNER JOIN cr_operador_celular AS croc ON croc.id_operador = cro.id_operador
			INNER JOIN cr_celulares AS crc ON croc.id_celular = crc.id_celular
			INNER JOIN cr_operador_unidad AS crou ON crou.id_operador = cro.id_operador
			INNER JOIN cr_unidades AS crun ON crou.id_unidad = crun.id_unidad
			INNER JOIN cr_marcas AS crm ON crun.id_marca = crm.id_marca
			INNER JOIN cr_modelos AS crmo ON crun.id_modelo = crmo.id_modelo		
		';
		$where = '
			(cro.cat_statusoperador = 8 OR cro.cat_statusoperador = 10) AND
			croc.cat_status_operador_celular = 31 AND
			crun.cat_status_unidad = 14 AND
			fwu.cat_status = 3 AND
			crn.eq_status = 7 AND
			cron.cat_status_oper_numeq = 12
		';
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
}
class acciones_lista_opr extends SSP{
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
					
					$salida = '';
					
					switch ($operador['cat_statusoperador']){
						case 8:
							$salida .= '<i data-rel="tooltip" data-original-title="Activo" class="green tooltip-success ace-icon fa fa-user bigger-130"></i>&nbsp;&nbsp;';
							break;
						case 9:
							$salida .= '<i data-rel="tooltip" data-original-title="Inactivo" class="yellow tooltip-success ace-icon fa fa-user bigger-130"></i>&nbsp;&nbsp;';
							break;
						case 10:
							$salida .= '<i data-rel="tooltip" data-original-title="Suspendido" class="red tooltip-success ace-icon fa fa-user bigger-130"></i>&nbsp;&nbsp;';
							break;
						case 11:
							$salida .= '<i data-rel="tooltip" data-original-title="Baja" class="red tooltip-success ace-icon fa fa-user-times bigger-130"></i>&nbsp;&nbsp;';
							break;
						default:
							$salida .= '';
					}
					if(Controlador::tiene_permiso('Operadores|ver_telefonos')){
						$salida .= '<a data-rel="tooltip" data-original-title="Teléfonos de contacto" class="green tooltip-success" onclick="modal_ver_telefonos('.$id_operador.');"><i class="fa fa-phone bigger-130"></i></a>&nbsp;&nbsp;';
					}
					if(Controlador::tiene_permiso('Operadores|ver_direcciones')){
						$salida .= '<a data-rel="tooltip" data-original-title="Direcciones del operador" class="green tooltip-success" onclick="modal_ver_direcciones('.$id_operador.');"><i class="fa fa-home bigger-130"></i></a>&nbsp;&nbsp;';
					}
					if(Controlador::tiene_permiso('Operadores|historia')){
						$salida .= '<td><a data-rel="tooltip" data-original-title="Historia del operador" class="green tooltip-success" onclick="historia_operador('.$id_operador.')"><i class="ace-icon fa fa-clock-o bigger-130"></i></a></td>';
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
	static function numeq($id_operador,$db){
		$query = "
			SELECT
				crn.num
			FROM
				cr_numeq AS crn
			INNER JOIN cr_operador_numeq AS cron ON cron.id_numeq = crn.id_numeq
			INNER JOIN cr_operador AS cro ON cron.id_operador = cro.id_operador
			WHERE
				cro.id_operador = $id_operador
		";
		
		$query = $db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				return  $row['num'];
			}
		}else{
				return 'NO ASIGNADO';
		}
	}	
	static function getOperador($id_usuario,$db){
		$query = "SELECT `id_operador`,`cat_statusoperador` FROM `cr_operador` WHERE `id_usuario` = '$id_usuario'";
		
		$query = $db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				$array['id_operador'] =  $row['id_operador'];
				$array['cat_statusoperador'] =  $row['cat_statusoperador'];
			}
		}else{
				$array['id_operador'] = 'NO EXISTE';
		}
		return $array;
	}
}
class acciones_operador extends SSP{
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
					$status_usuario 	= $data[$i][ 'cat_status' ];
					
					$operador = self::getOperador($id_usuario,$db);
					$id_operador = $operador['id_operador'];
					
					$salida = '<table id="tb_act_oper"><tr>';
					
						
						switch ($operador['cat_statusoperador']){
							case 8:
								$salida .= '<td><i data-rel="tooltip" data-original-title="Activo" class="green tooltip-success ace-icon fa fa-user bigger-130"></i></td>';
								break;
							case 9:
								$salida .= '<td><i data-rel="tooltip" data-original-title="Inactivo" class="yellow tooltip-success ace-icon fa fa-user bigger-130"></i></td>';
								break;
							case 10:
								$salida .= '<td><i data-rel="tooltip" data-original-title="Suspendido" class="red tooltip-success ace-icon fa fa-user bigger-130"></i></td>';
								break;
							case 11:
								$salida .= '<td><i data-rel="tooltip" data-original-title="Baja" class="red tooltip-success ace-icon fa fa-user-times bigger-130"></i></td>';
								break;
							default:
								$salida .= '';
						}
						if(Controlador::tiene_permiso('Operadores|gestion_telefonos')){
							$salida .= '<td><a data-rel="tooltip" data-original-title="Teléfonos de contacto" class="green tooltip-success" onclick="modal_telefonos('.$id_operador.');"><i class="ace-icon fa fa-phone bigger-130"></i></a></td>';
						}
						if(Controlador::tiene_permiso('Operadores|gestion_domicilios')){
							$salida .= '<td><a data-rel="tooltip" data-original-title="Dómicilios del operador" class="green tooltip-success" onclick="modal_domicilios('.$id_operador.');"><i class="ace-icon fa fa-home bigger-130"></i></a></td>';
						}	
						if(Controlador::tiene_permiso('Operadores|relacionar_autos')){
							$salida .= '<td><a data-rel="tooltip" data-original-title="Autos asignados" class="green tooltip-success" onclick="relacionar_autos('.$id_operador.');"><i class="ace-icon fa fa-car bigger-130"></i></a></td>';
						}
						if(Controlador::tiene_permiso('Operadores|status_operador')){
							$salida .= '<td><a data-rel="tooltip" data-original-title="Status del operador" class="green tooltip-success" onclick="status_operador('.$id_operador.')"><i class="ace-icon fa fa-check-square-o bigger-130"></i></a></td>';
						}
						if(Controlador::tiene_permiso('Operadores|numero_economico')){
							
							$num_eq = self::numeq($id_operador,$db);
							if($num_eq == 'NO ASIGNADO'){$nq = '<span style="color:red;">&nbsp;XX</span>';$color="red";}else{$color="green"; $nq = '<span style="color:#375da8;">&nbsp;'.$num_eq.'</span>';}
							
							$salida .= '<td><a data-rel="tooltip" data-original-title="Número económico" class="'.$color.' tooltip-success" onclick="numero_economico('.$id_operador.')"><i class="ace-icon fa fa-list-ol bigger-130"></i></a></td>';
							$salida .= '<td>'.$nq.'</td>';
						}
						if(Controlador::tiene_permiso('Operadores|historia')){
							$salida .= '<td><a data-rel="tooltip" data-original-title="Historia del operador" class="green tooltip-success" onclick="historia_operador('.$id_operador.')"><i class="ace-icon fa fa-clock-o bigger-130"></i></a></td>';
						}						
						/*if(Controlador::tiene_permiso('Operadores|episodios')){
							$salida .= '<a data-rel="tooltip" data-original-title="Episodios" class="green tooltip-success" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'operadores/episodios/'.$id_operador.'/\');"><i class="ace-icon fa fa-calendar bigger-130"></i></a>&nbsp;&nbsp;';
						}
						if(Controlador::tiene_permiso('Operadores|tarifas')){
							$salida .= '<a data-rel="tooltip" data-original-title="Tarifas" class="green tooltip-success" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'operadores/tarifas/'.$id_operador.'/\');"><i class="ace-icon fa fa-credit-card bigger-130"></i></a>&nbsp;&nbsp;';
						}
						if(Controlador::tiene_permiso('Operadores|ingresos')){
							$salida .= '<a data-rel="tooltip" data-original-title="Ingresos" class="green tooltip-success" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'operadores/ingresos/'.$id_operador.'/\');"><i class="ace-icon fa fa-usd bigger-130 blue"></i></a>&nbsp;&nbsp;';
						}
						if(Controlador::tiene_permiso('Operadores|egresos')){
							$salida .= '<a data-rel="tooltip" data-original-title="Egresos" class="green tooltip-success" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'operadores/egresos/'.$id_operador.'/\');"><i class="ace-icon fa fa-usd bigger-130 red"></i></a>&nbsp;&nbsp;';
						}
						if(Controlador::tiene_permiso('Operadores|favoritos')){
							$salida .= '<a data-rel="tooltip" data-original-title="Favoritos" class="green tooltip-success" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'operadores/favoritos/'.$id_operador.'/\');"><i class="ace-icon fa fa-heart bigger-130"></i></a>&nbsp;&nbsp;';
						}*/
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
	static function numeq($id_operador,$db){
		$query = "
			SELECT
				crn.num
			FROM
				cr_numeq AS crn
			INNER JOIN cr_operador_numeq AS cron ON cron.id_numeq = crn.id_numeq
			INNER JOIN cr_operador AS cro ON cron.id_operador = cro.id_operador
			WHERE
				cro.id_operador = $id_operador
		";
		
		$query = $db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				return  $row['num'];
			}
		}else{
				return 'NO ASIGNADO';
		}
	}	
	static function getOperador($id_usuario,$db){
		$query = "SELECT `id_operador`,`cat_statusoperador` FROM `cr_operador` WHERE `id_usuario` = '$id_usuario'";
		
		$query = $db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				$array['id_operador'] =  $row['id_operador'];
				$array['cat_statusoperador'] =  $row['cat_statusoperador'];
			}
		}else{
				$array['id_operador'] = 'NO EXISTE';
		}
		return $array;
	}
}
class acciones_tarifas extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				if ( isset( $column['acciones'] ) ) {
					$id_tarifa_operador = $data[$i][ 'id_tarifa_operador' ];
					$id_operador = $column['id_operador'];
					
					$salida = '';
						
						if(Controlador::tiene_permiso('Operadores|tarifas_del')){
							$salida .= '<a data-rel="tooltip" data-original-title="Eliminar" class="green tooltip-success" onclick="eliminar_tarifa('.$id_tarifa_operador.')"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>&nbsp;&nbsp;';
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
?>