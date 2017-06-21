<?php
require_once( '../vendor/mysql_datatable.php' );
class ClientesModel
{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
        }
    }
	function insertDireccion($service,$tipo){
		$calle 		= ($tipo == 'origen')?$service->origen_calle:$service->destino_calle;
		$num_ext 	= ($tipo == 'origen')?$service->origen_num_ext:$service->destino_num_ext;
		$num_int 	= ($tipo == 'origen')?$service->origen_num_int:$service->destino_num_int;
		$telefono 	= ($tipo == 'origen')?$service->origen_telefono:$service->destino_telefono;
		$celular 	= ($tipo == 'origen')?$service->origen_celular:$service->destino_celular;
		$referencia = ($tipo == 'origen')?$service->origen_referencia:$service->destino_referencia;

		$geocodificacion_inversa 	= ($tipo == 'origen')?$service->geocodificacion_inversa_origen:$service->geocodificacion_inversa_destino;
		$geocoordenadas 			= ($tipo == 'origen')?$service->geocoordenadas_origen:$service->geocoordenadas_destino;

		$sql = "
			INSERT INTO `it_direcciones` (
				`calle`,
				`num_ext`,
				`num_int`,
				`telefono`,
				`celular`,
				`referencia`,
				`geocodificacion_inversa`,
				`geocoordenadas`,
				`user_alta`,
				`fecha_alta`
			)
			VALUES
				(
					'".$calle."',
					'".$num_ext."',
					'".$num_int."',
					'".$telefono."',
					'".$celular."',
					'".$referencia."',
					'".$geocodificacion_inversa."',
					'".$geocoordenadas."',
					'".$_SESSION['id_usuario']."',
					'".date("Y-m-d H:i:s")."'
				);
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		return $this->db->lastInsertId();
	}
	function insertOrigenes($id_direccion){
		$sql = "
			INSERT INTO `it_origenes` (
				`id_direccion`,
				`descripcion`,
				`user_alta`,
				`fecha_alta`
			)
			VALUES
				(
					'".$id_direccion."',
					'Inserción automática',
					'".$_SESSION['id_usuario']."',
					'".date("Y-m-d H:i:s")."'
				);
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		return $this->db->lastInsertId();
	}
	function insertClienteOrigen($id_origen,$id_cliente){
		$sql = "
			INSERT INTO `it_cliente_origen` (
				`id_cliente`,
				`id_origen`,
				`user_alta`,
				`fecha_alta`
			)
			VALUES
				(
					'".$id_cliente."',
					'".$id_origen."',
					'".$_SESSION['id_usuario']."',
					'".date("Y-m-d H:i:s")."'
				);
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		return $this->db->lastInsertId();
	}
	function insertDestinos($id_direccion){
		$sql = "
			INSERT INTO `it_destinos` (
				`id_direccion`,
				`descripcion`,
				`user_alta`,
				`fecha_alta`
			)
			VALUES
				(
					'".$id_direccion."',
					'Inserción automática',
					'".$_SESSION['id_usuario']."',
					'".date("Y-m-d H:i:s")."'
				);
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		return $this->db->lastInsertId();
	}
	function insertClienteDestinos($id_destino,$id_cliente){
		$sql = "
			INSERT INTO `it_cliente_destino` (
				`id_cliente`,
				`id_destino`,
				`user_alta`,
				`fecha_alta`
			)
			VALUES
				(
					'".$id_cliente."',
					'".$id_destino."',
					'".$_SESSION['id_usuario']."',
					'".date("Y-m-d H:i:s")."'
				);
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		return $this->db->lastInsertId();
	}

	function insertOrigen($service){
		$id_direccion = self::insertDireccion($service,'origen');
		$id_origen = self::insertOrigenes($id_direccion);
		return self::insertClienteOrigen($id_origen,$service->id_cliente);
	}
	function insertDestino($service){
		$id_direccion = self::insertDireccion($service,'destino');
		$id_destino = self::insertDestinos($id_direccion);
		return self::insertClienteDestinos($id_destino,$service->id_cliente);
	}
	function selectOrigenes($id_cliente){
		$array = array();

		$qry = "
			SELECT
				itd.geocodificacion_inversa,
				itco.id_cliente_origen
			FROM
				it_cliente_origen AS itco
			INNER JOIN it_origenes AS ito ON itco.id_origen = ito.id_origen
			INNER JOIN it_direcciones AS itd ON ito.id_direccion = itd.id_direccion
			WHERE
				itco.id_cliente = ".$id_cliente."
			ORDER BY
				itco.id_cliente_origen DESC
		";

		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			$cont = 0;
			foreach ($data as $row) {
				$array[$cont]['valor']=$row->geocodificacion_inversa;
				$array[$cont]['value']=$row->id_cliente_origen;
				$cont++;
			}
		}
		return Controller::setOption($array,null);
	}
	function selectDestinos($id_cliente){
		$array = array();

		$qry = "
			SELECT
				itd.geocodificacion_inversa,
				itcd.id_cliente_destino
			FROM
				it_cliente_destino AS itcd
			INNER JOIN it_destinos AS itt ON itcd.id_destino = itt.id_destino
			INNER JOIN it_direcciones AS itd ON itt.id_direccion = itd.id_direccion
			WHERE
				itcd.id_cliente = ".$id_cliente."
			ORDER BY
				itcd.id_cliente_destino DESC
		";

		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			$cont = 0;
			foreach ($data as $row) {
				$array[$cont]['valor']=$row->geocodificacion_inversa;
				$array[$cont]['value']=$row->id_cliente_destino;
				$cont++;
			}
		}
		return Controller::setOption($array,null);
	}
	function caducarTarifa($id_cliente,$cat_tipo_tarifa){
		$sql = "
		UPDATE cl_tarifas_clientes SET
			fin_vigencia 		= :fin_vigencia,
			cat_statustarifa	= :cat_statustarifa,
			user_mod 			= :user_mod
		WHERE
			id_cliente = :id_cliente
			AND
			cat_statustarifa = :cat_tarifa_old
			AND
			cat_tipo_tarifa = :cat_tipo_tarifa
		";
		$query = $this->db->prepare($sql);
		$data = array(
			':fin_vigencia'		=> date("Y-m-d H:i:s"),
			':cat_statustarifa'	=> 169,
			':cat_tarifa_old'	=> 168,
			':cat_tipo_tarifa'	=> $cat_tipo_tarifa,
			':id_cliente'		=> $id_cliente,
			':user_mod'			=> $_SESSION['id_usuario']
		);
		$query->execute($data);
	}
	function caducar_tarifa($id_tarifa_cliente){
		$sql = "
		UPDATE cl_tarifas_clientes SET
			fin_vigencia 		= :fin_vigencia,
			cat_statustarifa	= :cat_statustarifa,
			user_mod 			= :user_mod
		WHERE
			id_tarifa_cliente = :id_tarifa_cliente
		";
		$query = $this->db->prepare($sql);
		$data = array(
			':fin_vigencia'		=> date("Y-m-d H:i:s"),
			':cat_statustarifa'	=> 169,
			':id_tarifa_cliente'=> $id_tarifa_cliente,
			':user_mod'			=> $_SESSION['id_usuario']
		);
		$query_resp = $query->execute($data);
		if($query_resp){
			$respuesta = array('resp' => true);
		}else{
			$respuesta = array('resp' => false);
		}
		return $respuesta;
	}
	function procesar_tarifa($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}

		if($this->tabular == 0){
			self::caducarTarifa($this->id_cliente,$this->cat_tipo_tarifa);
		}

		$sql = "
			INSERT INTO cl_tarifas_clientes (
				id_cliente,
                            costo_base,
				km_adicional,
                            costo_base_venta,
				km_adicional_venta,
				descripcion,
				nombre,
				inicio_vigencia,
				cat_statustarifa,
				cat_tipo_tarifa,
				tabulado,
				user_alta,
				fecha_alta
			) VALUES (
				:id_cliente,
                            :costo_base,
				:km_adicional,
                            :costo_base_venta,
				:km_adicional_venta,
				:descripcion,
				:nombre,
				:inicio_vigencia,
				:cat_statustarifa,
				:cat_tipo_tarifa,
				:tabulado,
				:user_alta,
				:fecha_alta
			)";
		$query = $this->db->prepare($sql);
		$query_resp = $query->execute(
			array(
				':id_cliente' => $this->id_cliente,
                            ':costo_base' => substr($this->costo_base, 2),
				':km_adicional' => substr($this->km_adicional, 2),
                            ':costo_base_venta' => substr($this->costo_base_venta, 2),
				':km_adicional_venta' => substr($this->km_adicional_venta, 2),
				':descripcion' => $this->descripcion,
				':nombre' => $this->nombre,
				':inicio_vigencia' => date("Y-m-d H:i:s"),
				':cat_statustarifa' => 168,
				':cat_tipo_tarifa' => $this->cat_tipo_tarifa,
				':tabulado' => $this->tabular,
				':user_alta' => $_SESSION['id_usuario'],
				':fecha_alta' => date("Y-m-d H:i:s")
			)
		);
		if($query_resp){
			$respuesta = array('resp' => true);
		}else{
			$respuesta = array('resp' => false);
		}
		return $respuesta;
	}
	function edit_client($client){
		$sql = "
		UPDATE cl_clientes SET
			cat_statuscliente 	= :cat_statuscliente,
			cat_tipocliente		= :cat_tipocliente,
			id_rol				= :id_rol,
			nombre				= :nombre,
			user_mod 			= :user_mod
		WHERE
			id_cliente = :id_cliente
		";
		$query = $this->db->prepare($sql);
		$data = array(
			':cat_statuscliente'=> $client['cat_statuscliente'],
			':cat_tipocliente'	=> $client['cat_tipocliente'],
			':id_rol'			=> $client['id_rol'],
			':nombre'			=> $client['nombre'],
			':id_cliente' 		=> $client['id_cliente'],
			':user_mod'			=> $_SESSION['id_usuario']
		);
		$query_resp = $query->execute($data);
		if($query_resp){
			$respuesta = array('resp' => true);
		}else{
			$respuesta = array('resp' => false);
		}
		return $respuesta;
	}
	function predeterminarUbicacion($id_datos_fiscales,$id_cliente){
		self::setZeroPredUbic($id_cliente);
		$sql = "
			UPDATE cl_datos_fiscales
			SET
			 predeterminar = :predeterminar,
			 user_mod = :user_mod
			WHERE
				id_datos_fiscales = :id_datos_fiscales
		";
		$query = $this->db->prepare($sql);
		$data = array(
			':predeterminar' => 1,
			':user_mod' => $_SESSION['id_usuario'],
			':id_datos_fiscales' => $id_datos_fiscales
		);
		$query_resp = $query->execute($data);
		if($query_resp){
			$respuesta = array('resp' => true);
		}else{
			$respuesta = array('resp' => false);
		}
		return $respuesta;
	}
	function setZeroPredUbic($id_cliente){
		$sql = "
			UPDATE cl_datos_fiscales
			SET
			 predeterminar = :predeterminar,
			 user_mod = :user_mod
			WHERE
				id_cliente = :id_cliente
		";
		$query = $this->db->prepare($sql);
		$data = array(
			':predeterminar' => 0,
			':user_mod' => $_SESSION['id_usuario'],
			':id_cliente' => $id_cliente
		);
		$query->execute($data);
	}
	function eliminarUbicacion($id_datos_fiscales){
		$sql = "
			UPDATE cl_datos_fiscales
			SET
			 eliminado = :eliminado,
			 user_mod = :user_mod
			WHERE
				id_datos_fiscales = :id_datos_fiscales
		";
		$query = $this->db->prepare($sql);
		$data = array(
			':eliminado' => 1,
			':user_mod' => $_SESSION['id_usuario'],
			':id_datos_fiscales' => $id_datos_fiscales
		);
		$query_resp = $query->execute($data);
		if($query_resp){
			$respuesta = array('resp' => true);
		}else{
			$respuesta = array('resp' => false);
		}
		return $respuesta;
	}
	function direcciones($id_cliente){
		$sql="
			SELECT
				cdf.id_datos_fiscales,
				cdf.id_cliente,
				cdf.predeterminar,
				cdf.eliminado,
				cdf.rfc,
				cdf.calle,
				cdf.num_ext,
				cdf.num_int,
				cdf.telefono,
				cdf.celular,
				cdf.correo,
				asn.id_asentamiento as id_asn,
				asn.asentamiento,
				cp.codigo_postal,
				ta.d_tipo_asenta,
				mu.municipio,
				es.estado,
				cd.ciudad
			FROM
				cl_datos_fiscales AS cdf
			INNER JOIN it_asentamientos AS asn ON cdf.id_asentamiento = asn.id_asentamiento
			INNER JOIN it_codigos_postales AS cp ON asn.id_codigo_postal = cp.id_codigo_postal
			INNER JOIN it_tipo_asentamientos AS ta ON asn.id_tipo_asenta = ta.id_tipo_asenta
			INNER JOIN it_municipios AS mu ON asn.id_municipio = mu.id_municipio
			INNER JOIN it_estados AS es ON asn.id_estado = es.id_estado
			INNER JOIN it_ciudades AS cd ON asn.id_ciudad = cd.id_ciudad
			WHERE
				cdf.id_cliente = ".$id_cliente."
				AND
				cdf.eliminado = 0
			ORDER BY
				cdf.id_asentamiento ASC
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$cliente = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			$num = 0;
			foreach ($cliente as $row) {
				$array[$num]['id_datos_fiscales'] 	= $row->id_datos_fiscales;
				$array[$num]['id_cliente'] 			= $row->id_cliente;
				$array[$num]['predeterminar'] 		= $row->predeterminar;
				$array[$num]['eliminado'] 			= $row->eliminado;
				$array[$num]['rfc'] 				= $row->rfc;
				$array[$num]['calle'] 				= $row->calle;
				$array[$num]['num_ext'] 			= $row->num_ext;
				$array[$num]['num_int'] 			= $row->num_int;
				$array[$num]['telefono'] 			= $row->telefono;
				$array[$num]['celular'] 			= $row->celular;
				$array[$num]['correo'] 				= $row->correo;
				$array[$num]['id_asentamiento'] 	= $row->id_asn;
				$array[$num]['asentamiento'] 		= $row->asentamiento;
				$array[$num]['codigo_postal'] 		= $row->codigo_postal;
				$array[$num]['d_tipo_asenta'] 		= $row->d_tipo_asenta;
				$array[$num]['municipio'] 			= $row->municipio;
				$array[$num]['estado'] 				= $row->estado;
				$array[$num]['ciudad'] 				= $row->ciudad;
				$num++;
			}
		}
		return $array;
	}
	function guardarUbicacion($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		$sql = "
			INSERT INTO cl_datos_fiscales (
				id_cliente,
				id_asentamiento,
				predeterminar,
				eliminado,
				rfc,
				calle,
				num_ext,
				num_int,
				telefono,
				celular,
				correo,
				user_alta,
				fecha_alta
			) VALUES (
				:id_cliente,
				:id_asentamiento,
				:predeterminar,
				:eliminado,
				:rfc,
				:calle,
				:num_ext,
				:num_int,
				:telefono,
				:celular,
				:correo,
				:user_alta,
				:fecha_alta
			)";
		$query = $this->db->prepare($sql);
		$query_resp = $query->execute(
			array(
				':id_cliente' => $this->id_cliente,
				':id_asentamiento' => $this->id_asentamiento,
				':predeterminar' => 0,
				':eliminado' => 0,
				':rfc' => $this->rfc,
				':calle' => $this->calle,
				':num_ext' => $this->num_ext,
				':num_int' => $this->num_int,
				':telefono' => $this->telefono,
				':celular' => $this->celular,
				':correo' => strtolower($this->correo),
				':user_alta' => $_SESSION['id_usuario'],
				':fecha_alta' => date("Y-m-d H:i:s")
			)
		);
		if($query_resp){
			$respuesta = array('resp' => true);
		}else{
			$respuesta = array('resp' => false);
		}
		return $respuesta;
	}
	function getChildrensClient($id_cliente,$level,$id_origen=null){

		$origen='';
		if($id_origen){$n = $level - 1; $origen = 'AND fwn.n'.$n.' = '.$id_origen.'';}
		$sql="
			SELECT
				clc.id_cliente,
				clc.nombre,
				clc.parent,
				fwn.nivel,
				fwn.n0,
				fwn.n1
			FROM
				cl_clientes AS clc
			INNER JOIN fw_nivel AS fwn ON clc.id_cliente = fwn.id_origen
			WHERE
				clc.parent = ".$id_cliente."
				AND clc.cat_statuscliente <> 24
			AND fwn.nivel = ".$level."
			".$origen."
			ORDER BY
				fwn.nivel ASC
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$cliente = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			$num = 0;
			foreach ($cliente as $row) {
				$array[$num]['id_cliente'] 	= $row->id_cliente;
				$array[$num]['nombre'] 		= $row->nombre;
				$array[$num]['parent'] 		= $row->parent;
				$array[$num]['padre'] 		= self::getPadreNivel($row->id_cliente, $row->nivel);
				$array[$num]['nivel'] 		= $row->nivel;
				$array[$num]['childrens']   = self::getChildrensClient($id_cliente,($row->nivel + 1),$row->id_cliente);
				$num++;
			}
		}
		return $array;
	}
	function getPadreNivel($id_cliente, $nivel){
		$n = $nivel-1;
		$n2 = 'n'.$n;
		$sql="
			SELECT
				fw_nivel.n".$n."
			FROM
				fw_nivel
			WHERE
				fw_nivel.origen = 'cl_clientes'
			AND fw_nivel.id_origen = ".$id_cliente."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$cliente = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($cliente as $row) {
				$nivel_padre = $row->$n2;
				return $nivel_padre;
			}
		}
	}
	function store_order($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		$clientes =  json_decode($this->newjson);
		self::recursive_record($this->parent, $clientes);

	}
	function recursive_record($padre, $clientes){
		foreach($clientes as $cliente){
			self::insertOrUpdateNivelClient($padre, $cliente->id);
			if(isset($cliente->children)){
				if($cliente->children){
					self::recursive_record($cliente->id, $cliente->children);
				}
			}
		}
	}
	function insertOrUpdateNivelClient($padre, $id_cliente){
		$nivel_cliente = self::getDataClientNivel($id_cliente);
		$nivel_padre = self::getDataClientNivel($padre);
		if($nivel_cliente['no_existe']){
			self::insertNivelClient($nivel_padre, $id_cliente);
		}else{
			self::updateNivelClient($nivel_padre, $id_cliente);
		}
	}
	function getDataClientNivel($id_cliente){
		$sql="SELECT * FROM fw_nivel WHERE origen = 'cl_clientes' and id_origen = '".$id_cliente."'";
		$query = $this->db->prepare($sql);
		$query->execute();
		$cliente = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($cliente as $row) {
				$array['no_existe'] = false;
				$array['id_nivel'] 	= $row->id_nivel;
				$array['nivel'] 	= $row->nivel;
				$array['n0'] 		= $row->n0;
				$array['n1'] 		= $row->n1;
				$array['n2'] 		= $row->n2;
				$array['n3'] 		= $row->n3;
				$array['n4'] 		= $row->n4;
				$array['n5'] 		= $row->n5;
				$array['n6'] 		= $row->n6;
				$array['n7'] 		= $row->n7;
				$array['n8'] 		= $row->n8;
				$array['n9'] 		= $row->n9;
			}
		}else{
			$array['no_existe'] = true;
		}
		return $array;
	}
	function getDataClient($id_cliente){
		$id_cliente = intval($id_cliente);
		$sql="SELECT * FROM cl_clientes WHERE id_cliente = ".$id_cliente."";
		$query = $this->db->prepare($sql);
		$query->execute();
		$cliente = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($cliente as $row) {
				$array['parent'] 			= $row->parent;
				$array['id_rol'] 			= $row->id_rol;
				$array['cat_tipocliente' ]	= $row->cat_tipocliente;
				$array['cat_statuscliente'] = $row->cat_statuscliente;
				$array['nombre'] 			= $row->nombre;
			}
		}
		return $array;
	}
	function newClienteNivelChildren($id_cliente, $padre){
			$sql = "
				INSERT INTO fw_nivel (
					id_origen,
					origen,
					nivel,
					n0,
					n1,
					user_alta,
					fecha_alta
				) VALUES (
					:id_origen,
					:origen,
					:nivel,
					:n0,
					:n1,
					:user_alta,
					:fecha_alta
				)";
			$query = $this->db->prepare($sql);
			$query_resp = $query->execute(
				array(
					':id_origen' => $id_cliente,
					':origen' => 'cl_clientes',
					':nivel' => '1',
					':n0' => $padre,
					':n1' => $id_cliente,
					':user_alta' => $_SESSION['id_usuario'],
					':fecha_alta' => date("Y-m-d H:i:s")
				)
			);
	}
	function deleteClient($id_cliente, $padre){
		$dependientes = self::existenDependientes($id_cliente);

		if($dependientes == 0){
			self::deleteClientDo($id_cliente);
			self::rootClientNivel($id_cliente);
			$hermanos = self::existenDependientes($padre);
			$output = array(
				'resp'=> true,
				'dependientes'=> $dependientes,
				'eliminado'=> true,
				'hermanos'=> $hermanos
			);
		}else{
			$output = array(
				'resp'=> true,
				'dependientes'=> $dependientes,
				'eliminado'=> false
			);
		}
		return $output;
	}
	function rootClientNivel($id_cliente){
		$sql = "
			UPDATE fw_nivel
			SET
			 nivel = :nivel,
			 n1 = :n1,
			 n2 = :n2,
			 n3 = :n3,
			 n4 = :n4,
			 n5 = :n5,
			 n6 = :n6,
			 n7 = :n7,
			 n8 = :n8,
			 n9 = :n9,
			 user_mod = :user_mod
			WHERE
				origen = :origen
			AND id_origen = :id_origen
		";
		$query = $this->db->prepare($sql);
		$data = array(
			':nivel' => 1,
			':n1' => $id_cliente,
			':n2' => 0,
			':n3' => 0,
			':n4' => 0,
			':n5' => 0,
			':n6' => 0,
			':n7' => 0,
			':n8' => 0,
			':n9' => 0,
			':origen' => 'cl_clientes',
			':id_origen' => $id_cliente,
			':user_mod' => $_SESSION['id_usuario']
		);
		$query->execute($data);
	}
	function deleteClientDo($id_cliente){
		$sql = "
		UPDATE cl_clientes SET
			cat_statuscliente 	= :cat_statuscliente,
			user_mod = :user_mod
		WHERE
			id_cliente = :id_cliente
		";
		$query = $this->db->prepare($sql);
		$data = array(
			':cat_statuscliente'=> 24,
			':id_cliente' 		=> $id_cliente,
			':user_mod'	=> $_SESSION['id_usuario']
		);
		$query->execute($data);
	}
	function existenDependientes($id_cliente){
		$cliente = self::getDataClientNivel($id_cliente);
		return self::totalDependientes($id_cliente, $cliente['nivel']);
	}
	function totalDependientes($id_cliente, $nivel){
		$query = "
			SELECT
				count(fw_nivel.n".$nivel.") AS total
			FROM
				fw_nivel
			WHERE
				fw_nivel.n".$nivel." = ".$id_cliente."
		";
		$query = $this->db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		$output = array('suggestions'=>array());
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				$dependientes = (($row->total) - 1);
				return $dependientes;
			}
		}
	}
	function listadoEmpresas($search){
		$query = "
			SELECT
				cl_clientes.id_cliente as padre,
				cl_clientes.nombre as empresa
			FROM
				cl_clientes
			WHERE
				cl_clientes.parent = 0
			AND cl_clientes.cat_statuscliente = 21
			AND cl_clientes.nombre LIKE lower('%".$search."%')
			ORDER BY
				cl_clientes.id_cliente ASC
		";
		$query = $this->db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		$output = array('suggestions'=>array());
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				$output['suggestions'][] = array(
					'value'	=> 	$row->empresa,
					'data'		=>	$row->padre
				);
			}
		}
		return json_encode($output);
	}
	function add_client_children($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
			$sql = "
				INSERT INTO cl_clientes (
					parent,
					id_rol,
					cat_tipocliente,
					cat_statuscliente,
					nombre,
					user_alta,
					fecha_alta
				) VALUES (
					:parent,
					:id_rol,
					:cat_tipocliente,
					:cat_statuscliente,
					:nombre,
					:user_alta,
					:fecha_alta
				)";
			$query = $this->db->prepare($sql);
			$query_resp = $query->execute(
				array(
					':parent' => $this->padre,
					':id_rol' => $this->id_rol,
					':cat_tipocliente' => $this->cat_tipocliente,
					':cat_statuscliente' => $this->cat_statuscliente,
					':nombre' => $this->nombre,
					':user_alta' => $_SESSION['id_usuario'],
					':fecha_alta' => date("Y-m-d H:i:s")
				)
			);
			$id_cliente = $this->db->lastInsertId();
			self::newClienteNivelChildren($id_cliente, $this->padre);
			$html = '
				<li class="dd-item" data-id="'.$id_cliente.'" id="dataClientNestable_'.$id_cliente.'">
					<div class="dd-handle">
						<div id="nombre_nestable_'.$id_cliente.'">'.$this->nombre.'</div>
					</div>
					<div class="pull-right action-buttons" style="position:relative; top:-33px; left:-13px; z-index:500">
						<i id="spinnerClient_'.$id_cliente.'" class="fa fa-circle-o-notch fa-spin fa-fw blue hidden"></i>
						<a class="blue" href="#" onclick="getFormClientEdit('.$id_cliente.','.$this->padre.')">
							<i class="ace-icon fa fa-pencil bigger-130"></i>
						</a>
						<a class="red" href="#" onclick="deleteClient('.$id_cliente.','.$this->padre.')">
							<i class="ace-icon fa fa-trash-o bigger-130"></i>
						</a>
					</div>
				</li>
			';
			return $html;
	}
	function edit_client_children($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		$sql = "
		UPDATE cl_clientes SET
			id_rol 				= :id_rol,
			cat_tipocliente 	= :cat_tipocliente,
			cat_statuscliente 	= :cat_statuscliente,
			nombre 				= :nombre,
			user_mod 			= :user_mod
		WHERE
			id_cliente = :id_cliente
		";
		$query = $this->db->prepare($sql);
		$data = array(
			':id_rol' 			=> $this->id_rol,
			':cat_tipocliente' 	=> $this->cat_tipocliente,
			':cat_statuscliente'=> $this->cat_statuscliente,
			':nombre' 			=> $this->nombre,
			':id_cliente' 		=> $this->id_cliente,
			':user_mod'			=> $_SESSION['id_usuario']
		);
		$query->execute($data);
		return $this->nombre;
	}
	function add_client($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
			$sql = "
				INSERT INTO cl_clientes (
					parent,
					id_rol,
					cat_tipocliente,
					cat_statuscliente,
					nombre,
					user_alta,
					fecha_alta
				) VALUES (
					:parent,
					:id_rol,
					:cat_tipocliente,
					:cat_statuscliente,
					:nombre,
					:user_alta,
					:fecha_alta
				)";
			$query = $this->db->prepare($sql);
			$query_resp = $query->execute(
				array(
					':parent' => 0,
					':id_rol' => $this->id_rol,
					':cat_tipocliente' => $this->cat_tipocliente,
					':cat_statuscliente' => $this->cat_statuscliente,
					':nombre' => $this->nombre,
					':user_alta' => $_SESSION['id_usuario'],
					':fecha_alta' => date("Y-m-d H:i:s")
				)
			);
			$id_cliente = $this->db->lastInsertId();
			self::newClienteNivel($id_cliente);
			if($query_resp){
				$respuesta = array('resp' => true);
			}else{
				$respuesta = array('resp' => false);
			}
		return $respuesta;
	}
	function newClienteNivel($id_cliente){
			$sql = "
				INSERT INTO fw_nivel (
					id_origen,
					origen,
					nivel,
					n0,
					user_alta,
					fecha_alta
				) VALUES (
					:id_origen,
					:origen,
					:nivel,
					:n0,
					:user_alta,
					:fecha_alta
				)";
			$query = $this->db->prepare($sql);
			$query_resp = $query->execute(
				array(
					':id_origen' => $id_cliente,
					':origen' => 'cl_clientes',
					':nivel' => '0',
					':n0' => $id_cliente,
					':user_alta' => $_SESSION['id_usuario'],
					':fecha_alta' => date("Y-m-d H:i:s")
				)
			);
	}
	function buscar_padre($search){
		$query = "
			SELECT
				cl_clientes.id_cliente,
				cl_clientes.parent,
				cl_clientes.nombre
			FROM
				cl_clientes
			WHERE
			   ((id_cliente LIKE lower('%".$search."%')) OR (parent LIKE '%".$search."%') OR (nombre LIKE '%".$search."%'))
		";
		$query = $this->db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		$output = array('suggestions'=>array());
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				$output['suggestions'][] = array('value'=> '['.$row->parent.'] - ['.$row->id_cliente.'] '.$row->nombre,'data'=>$row->id_cliente);
			}
		}


		return json_encode($output);
	}
	function obtenerClientes($array){
		ini_set('memory_limit', '256M');
		$table = 'cl_clientes AS cli';
		$primaryKey = 'id_cliente';
		$columns = array(
			array(
				'db' => 'id_cliente',
				'dbj' => 'id_cliente',
				'real' => 'id_cliente',
				'alias' => 'id_cliente',
				'typ' => 'int',
				'dt' => 0
			),
			array(
				'db' => 'nombre',
				'dbj' => 'nombre',
				'real' => 'nombre',
				'alias' => 'nombre',
				'typ' => 'txt',
				'dt' => 1
			),
			array(
				'db' => 'cat.etiqueta AS etiqueta',
				'dbj' => 'cat.etiqueta',
				'real' => 'cat.etiqueta',
				'alias' => 'etiqueta',
				'typ' => 'txt',
				'dt' => 2
			),
			array(
				'db' => 'cat2.etiqueta AS etiqueta2',
				'dbj' => 'cat2.etiqueta',
				'real' => 'cat2.etiqueta',
				'alias' => 'etiqueta2',
				'typ' => 'txt',
				'dt' => 3
			),
			array(
				'db' => 'rol.descripcion AS rol',
				'dbj' => 'rol.descripcion',
				'real' => 'rol.descripcion',
				'alias' => 'rol',
				'typ' => 'txt',
				'dt' => 4
			),
			array(
				'db' => 'id_cliente',
				'dbj' => 'id_cliente',
				'alias' => 'id_cliente',
				'real' => 'id_cliente',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 5
			)
		);
		$render_table = new acciones_cliente;
		$inner = '
			INNER JOIN cm_catalogo AS cat ON cli.cat_tipocliente = cat.id_cat
			INNER JOIN cm_catalogo AS cat2 ON cli.cat_statuscliente = cat2.id_cat
			INNER JOIN fw_roles AS rol ON cli.id_rol = rol.id_rol
		';
		$where = '
			parent = 0
		';
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
	function insertNivelClient($dataPadre, $id_cliente){
			switch ($dataPadre['nivel']) {
				case 0:
					$nivel = 1;
					$n0 = $dataPadre['n0'];
					$n1 = $id_cliente; $n2 = 0; $n3 = 0; $n4 = 0; $n5 = 0; $n6 = 0; $n7 = 0; $n8 = 0; $n9 = 0;
					break;
				case 1:
					$nivel = 2;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $id_cliente; $n3 = 0; $n4 = 0; $n5 = 0; $n6 = 0; $n7 = 0; $n8 = 0; $n9 = 0;
					break;
				case 2:
					$nivel = 3;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $id_cliente; $n4 = 0; $n5 = 0; $n6 = 0; $n7 = 0; $n8 = 0; $n9 = 0;
					break;
				case 3:
					$nivel = 4;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $dataPadre['n3'];
					$n4 = $id_cliente; $n5 = 0; $n6 = 0; $n7 = 0; $n8 = 0; $n9 = 0;
					break;
				case 4:
					$nivel = 5;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $dataPadre['n3'];
					$n4 = $dataPadre['n4'];
					$n5 = $id_cliente; $n6 = 0; $n7 = 0; $n8 = 0; $n9 = 0;
					break;
				case 5:
					$nivel = 6;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $dataPadre['n3'];
					$n4 = $dataPadre['n4'];
					$n5 = $dataPadre['n5'];
					$n6 = $id_cliente; $n7 = 0; $n8 = 0; $n9 = 0;
					break;
				case 6:
					$nivel = 7;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $dataPadre['n3'];
					$n4 = $dataPadre['n4'];
					$n5 = $dataPadre['n5'];
					$n6 = $dataPadre['n6'];
					$n7 = $id_cliente; $n8 = 0; $n9 = 0;
					break;
				case 7:
					$nivel = 8;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $dataPadre['n3'];
					$n4 = $dataPadre['n4'];
					$n5 = $dataPadre['n5'];
					$n6 = $dataPadre['n6'];
					$n7 = $dataPadre['n7'];
					$n8 = $id_cliente; $n9 = 0;
					break;
				case 8:
					$nivel = 9;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $dataPadre['n3'];
					$n4 = $dataPadre['n4'];
					$n5 = $dataPadre['n5'];
					$n6 = $dataPadre['n6'];
					$n7 = $dataPadre['n7'];
					$n8 = $dataPadre['n8'];
					$n9 = $id_cliente;
					break;
				case 9:
					$nivel = 10;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $dataPadre['n3'];
					$n4 = $dataPadre['n4'];
					$n5 = $dataPadre['n5'];
					$n6 = $dataPadre['n6'];
					$n7 = $dataPadre['n7'];
					$n8 = $dataPadre['n8'];
					$n9 = $dataPadre['n9'];
					break;
			}
			$sql = "
				INSERT INTO fw_nivel (
					id_origen,
					origen,
					nivel,
					n0,
					n1,
					n2,
					n3,
					n4,
					n5,
					n6,
					n7,
					n8,
					n9,
					user_alta,
					fecha_alta
				)
				VALUES
					(
						:id_origen ,
						:origen ,
						:nivel ,
						:n0 ,
						:n1 ,
						:n2 ,
						:n3 ,
						:n4 ,
						:n5 ,
						:n6 ,
						:n7 ,
						:n8 ,
						:n9,
						:user_alta,
						:fecha_alta
					)
			";
			$query = $this->db->prepare($sql);
			$query_resp = $query->execute(
				array(
					':id_origen' => $id_cliente,
					':origen' => 'cl_clientes',
					':nivel' => $nivel,
					':n0' => $n0,
					':n1' => $n1,
					':n2' => $n2,
					':n3' => $n3,
					':n4' => $n4,
					':n5' => $n5,
					':n6' => $n6,
					':n7' => $n7,
					':n8' => $n8,
					':n9' => $n9,
					':user_alta' => $_SESSION['id_usuario'],
					':fecha_alta' => date("Y-m-d H:i:s")
				)
			);
	}
	function updateNivelClient($dataPadre, $id_cliente){
			switch ($dataPadre['nivel']) {
				case 0:
					$nivel = 1;
					$n0 = $dataPadre['n0'];
					$n1 = $id_cliente; $n2 = 0; $n3 = 0; $n4 = 0; $n5 = 0; $n6 = 0; $n7 = 0; $n8 = 0; $n9 = 0;
					break;
				case 1:
					$nivel = 2;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $id_cliente; $n3 = 0; $n4 = 0; $n5 = 0; $n6 = 0; $n7 = 0; $n8 = 0; $n9 = 0;
					break;
				case 2:
					$nivel = 3;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $id_cliente; $n4 = 0; $n5 = 0; $n6 = 0; $n7 = 0; $n8 = 0; $n9 = 0;
					break;
				case 3:
					$nivel = 4;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $dataPadre['n3'];
					$n4 = $id_cliente; $n5 = 0; $n6 = 0; $n7 = 0; $n8 = 0; $n9 = 0;
					break;
				case 4:
					$nivel = 5;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $dataPadre['n3'];
					$n4 = $dataPadre['n4'];
					$n5 = $id_cliente; $n6 = 0; $n7 = 0; $n8 = 0; $n9 = 0;
					break;
				case 5:
					$nivel = 6;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $dataPadre['n3'];
					$n4 = $dataPadre['n4'];
					$n5 = $dataPadre['n5'];
					$n6 = $id_cliente; $n7 = 0; $n8 = 0; $n9 = 0;
					break;
				case 6:
					$nivel = 7;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $dataPadre['n3'];
					$n4 = $dataPadre['n4'];
					$n5 = $dataPadre['n5'];
					$n6 = $dataPadre['n6'];
					$n7 = $id_cliente; $n8 = 0; $n9 = 0;
					break;
				case 7:
					$nivel = 8;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $dataPadre['n3'];
					$n4 = $dataPadre['n4'];
					$n5 = $dataPadre['n5'];
					$n6 = $dataPadre['n6'];
					$n7 = $dataPadre['n7'];
					$n8 = $id_cliente; $n9 = 0;
					break;
				case 8:
					$nivel = 9;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $dataPadre['n3'];
					$n4 = $dataPadre['n4'];
					$n5 = $dataPadre['n5'];
					$n6 = $dataPadre['n6'];
					$n7 = $dataPadre['n7'];
					$n8 = $dataPadre['n8'];
					$n9 = $id_cliente;
					break;
				case 9:
					$nivel = 10;
					$n0 = $dataPadre['n0'];
					$n1 = $dataPadre['n1'];
					$n2 = $dataPadre['n2'];
					$n3 = $dataPadre['n3'];
					$n4 = $dataPadre['n4'];
					$n5 = $dataPadre['n5'];
					$n6 = $dataPadre['n6'];
					$n7 = $dataPadre['n7'];
					$n8 = $dataPadre['n8'];
					$n9 = $dataPadre['n9'];
					break;
			}
		$sql = "
			UPDATE fw_nivel
			SET
			 nivel = :nivel,
			 n0 = :n0,
			 n1 = :n1,
			 n2 = :n2,
			 n3 = :n3,
			 n4 = :n4,
			 n5 = :n5,
			 n6 = :n6,
			 n7 = :n7,
			 n8 = :n8,
			 n9 = :n9,
			 user_mod = :user_mod
			WHERE
				origen = :origen
			AND id_origen = :id_origen
		";
		$query = $this->db->prepare($sql);
		$data = array(
			':nivel' => $nivel,
			':n0' => $n0,
			':n1' => $n1,
			':n2' => $n2,
			':n3' => $n3,
			':n4' => $n4,
			':n5' => $n5,
			':n6' => $n6,
			':n7' => $n7,
			':n8' => $n8,
			':n9' => $n9,
			':origen' => 'cl_clientes',
			':id_origen' => $id_cliente,
			':user_mod' => $_SESSION['id_usuario']
		);
		$query->execute($data);
	}
	function listado($array,$id_cliente){
		ini_set('memory_limit', '256M');
		$table = 'cl_clientes AS cli';
		$primaryKey = 'id_cliente';
		$columns = array(
			array(
				'db' => 'id_cliente',
				'dbj' => 'id_cliente',
				'real' => 'id_cliente',
				'alias' => 'id_cliente',
				'typ' => 'int',
				'dt' => 0
			),
			array(
				'db' => 'nombre',
				'dbj' => 'nombre',
				'real' => 'nombre',
				'alias' => 'nombre',
				'typ' => 'txt',
				'dt' => 1
			),
			array(
				'db' => 'cat.etiqueta AS etiqueta',
				'dbj' => 'cat.etiqueta',
				'real' => 'cat.etiqueta',
				'alias' => 'etiqueta',
				'typ' => 'txt',
				'dt' => 2
			),
			array(
				'db' => 'cat2.etiqueta AS etiqueta2',
				'dbj' => 'cat2.etiqueta',
				'real' => 'cat2.etiqueta',
				'alias' => 'etiqueta2',
				'typ' => 'txt',
				'dt' => 3
			),
			array(
				'db' => 'rol.descripcion AS rol',
				'dbj' => 'rol.descripcion',
				'real' => 'rol.descripcion',
				'alias' => 'rol',
				'typ' => 'txt',
				'dt' => 4
			),
			array(
				'db' => 'id_cliente',
				'dbj' => 'id_cliente',
				'alias' => 'id_cliente',
				'real' => 'id_cliente',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 5
			)
		);
		$render_table = new acciones_usuario;
		$inner = '
			INNER JOIN cm_catalogo AS cat ON cli.cat_tipocliente = cat.id_cat
			INNER JOIN cm_catalogo AS cat2 ON cli.cat_statuscliente = cat2.id_cat
			INNER JOIN fw_roles AS rol ON cli.id_rol = rol.id_rol
		';
		$where = '
			parent = '.$id_cliente.'
		';
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
	function allthem($array){
		ini_set('memory_limit', '256M');
		$table = 'cl_clientes AS cli';
		$primaryKey = 'id_cliente';
		$columns = array(
			array(
				'db' => 'cli.id_cliente',
				'dbj' => 'cli.id_cliente',
				'real' => 'cli.id_cliente',
				'alias' => 'id_cliente',
				'typ' => 'int',
				'dt' => 0
			),
			array(
				'db' => 'emp.nombre as empresa',
				'dbj' => 'emp.nombre',
				'real' => 'emp.nombre',
				'alias' => 'empresa',
				'typ' => 'txt',
				'dt' => 1
			),
			array(
				'db' => 'cli.nombre',
				'dbj' => 'cli.nombre',
				'real' => 'cli.nombre',
				'alias' => 'nombre',
				'typ' => 'txt',
				'dt' => 2
			),
			array(
				'db' => 'cat.etiqueta AS etiqueta',
				'dbj' => 'cat.etiqueta',
				'real' => 'cat.etiqueta',
				'alias' => 'etiqueta',
				'typ' => 'txt',
				'dt' => 3
			),
			array(
				'db' => 'cat2.etiqueta AS etiqueta2',
				'dbj' => 'cat2.etiqueta',
				'real' => 'cat2.etiqueta',
				'alias' => 'etiqueta2',
				'typ' => 'txt',
				'dt' => 4
			),
			array(
				'db' => 'rol.descripcion AS rol',
				'dbj' => 'rol.descripcion',
				'real' => 'rol.descripcion',
				'alias' => 'rol',
				'typ' => 'txt',
				'dt' => 5
			),
			array(
				'db' => 'cli.id_cliente',
				'dbj' => 'cli.id_cliente',
				'alias' => 'cli.id_cliente',
				'real' => 'id_cliente',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 6
			)
		);
		$render_table = new acciones_usuario;
		$inner = '
			INNER JOIN cm_catalogo AS cat ON cli.cat_tipocliente = cat.id_cat
			INNER JOIN cm_catalogo AS cat2 ON cli.cat_statuscliente = cat2.id_cat
			INNER JOIN fw_roles AS rol ON cli.id_rol = rol.id_rol
			INNER JOIN cl_clientes AS emp ON cli.parent = emp.id_cliente
		';
		$where = '
			cli.parent <> 0
		';
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
	function getTarifas($array){
		ini_set('memory_limit', '256M');
		$table = 'cl_tarifas_clientes AS cltc';
		$primaryKey = 'id_tarifa_cliente';
		$columns = array(
			array(
				'db' => 'cltc.id_tarifa_cliente AS id_tarifa_cliente',
				'dbj' => 'cltc.id_tarifa_cliente',
				'real' => 'cltc.id_tarifa_cliente',
				'alias' => 'id_tarifa_cliente',
				'typ' => 'int',
				'dt' => 0
			),
			array(
				'db' => 'cln.nombre AS cliente',
				'dbj' => 'cln.nombre',
				'real' => 'cln.nombre',
				'alias' => 'cliente',
				'typ' => 'txt',
				'dt' => 1
			),
			array(
				'db' => 'cltc.costo_base AS costo_base',
				'dbj' => 'cltc.costo_base',
				'real' => 'cltc.costo_base',
				'alias' => 'costo_base',
				'typ' => 'int',
				'dt' => 2
			),
			array(
				'db' => 'cltc.km_adicional AS km_adicional',
				'dbj' => 'cltc.km_adicional',
				'real' => 'cltc.km_adicional',
				'alias' => 'km_adicional',
				'typ' => 'int',
				'dt' => 3
			),
			array(
				'db' => 'cltc.descripcion AS descripcion',
				'dbj' => 'cltc.descripcion',
				'real' => 'cltc.descripcion',
				'alias' => 'descripcion',
				'typ' => 'txt',
				'dt' => 4
			),
			array(
				'db' => 'cltc.nombre AS nombre',
				'dbj' => 'cltc.nombre',
				'real' => 'cltc.nombre',
				'alias' => 'nombre',
				'typ' => 'txt',
				'dt' => 5
			),
			array(
				'db' => 'cltc.inicio_vigencia AS inicio_vigencia',
				'dbj' => 'cltc.inicio_vigencia',
				'real' => 'cltc.inicio_vigencia',
				'alias' => 'inicio_vigencia',
				'typ' => 'int',
				'dt' => 6
			),
			array(
				'db' => 'cltc.fin_vigencia AS fin_vigencia',
				'dbj' => 'cltc.fin_vigencia',
				'real' => 'cltc.fin_vigencia',
				'alias' => 'fin_vigencia',
				'typ' => 'txt',
				'dt' => 7
			),
			array(
				'db' => 'cat1.etiqueta AS estado',
				'dbj' => 'cat1.etiqueta',
				'real' => 'cat1.etiqueta',
				'alias' => 'estado',
				'typ' => 'txt',
				'dt' => 8
			),
			array(
				'db' => 'cat2.etiqueta AS tipo',
				'dbj' => 'cat2.etiqueta',
				'real' => 'cat2.etiqueta',
				'alias' => 'tipo',
				'typ' => 'txt',
				'dt' => 9
			),
			array(
				'db' => 'cltc.tabulado AS tabulado',
				'dbj' => 'cltc.tabulado',
				'real' => 'cltc.tabulado',
				'alias' => 'tabulado',
				'typ' => 'int',
				'dt' => 10
			)
		);
		$render_table = new SSP;
		$inner = '
			INNER JOIN cl_clientes AS cln ON cltc.id_cliente = cln.id_cliente
			INNER JOIN cm_catalogo AS cat1 ON cltc.cat_statustarifa = cat1.id_cat
			INNER JOIN cm_catalogo AS cat2 ON cltc.cat_tipo_tarifa = cat2.id_cat
		';
		$where = '';
		$orden = '';
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
	}
	function queryTarifas($array,$id_cliente){
		ini_set('memory_limit', '256M');
		$table = 'cl_tarifas_clientes AS tc';
		$primaryKey = 'id_tarifa_cliente';
		$columns = array(
			array(
				'db' => 'tc.nombre as nombre',
				'dbj' => 'tc.nombre',
				'real' => 'tc.nombre',
				'alias' => 'nombre',
				'typ' => 'txt',
				'dt' => 0
			),
			array(
				'db' => 'tc.descripcion as descripcion',
				'dbj' => 'tc.descripcion',
				'real' => 'tc.descripcion',
				'alias' => 'descripcion',
				'typ' => 'txt',
				'dt' => 1
			),
			array(
				'db' => 'tc.costo_base AS costo_base',
				'dbj' => 'tc.costo_base',
				'real' => 'tc.costo_base',
				'alias' => 'costo_base',
				'typ' => 'int',
				'moneda' => true,
				'dt' => 2
			),
			array(
				'db' => 'tc.km_adicional AS km_adicional',
				'dbj' => 'tc.km_adicional',
				'real' => 'tc.km_adicional',
				'alias' => 'km_adicional',
				'typ' => 'int',
				'moneda' => true,
				'dt' => 3
			),
			array(
				'db' => 'tc.costo_base_venta AS costo_base_venta',
				'dbj' => 'tc.costo_base_venta',
				'real' => 'tc.costo_base_venta',
				'alias' => 'costo_base_venta',
				'typ' => 'int',
				'moneda' => true,
				'dt' => 4
			),
			array(
				'db' => 'tc.km_adicional_venta AS km_adicional_venta',
				'dbj' => 'tc.km_adicional_venta',
				'real' => 'tc.km_adicional_venta',
				'alias' => 'km_adicional_venta',
				'typ' => 'int',
				'moneda' => true,
				'dt' => 5
			),
			array(
				'db' => 'tc.inicio_vigencia AS inicio_vigencia',
				'dbj' => 'tc.inicio_vigencia',
				'real' => 'tc.inicio_vigencia',
				'alias' => 'inicio_vigencia',
				'typ' => 'int',
				'dt' => 6
			),
			array(
				'db' => 'tc.fin_vigencia fin_vigencia',
				'dbj' => 'tc.fin_vigencia',
				'real' => 'tc.fin_vigencia',
				'alias' => 'fin_vigencia',
				'typ' => 'int',
				'dt' => 7
			),
			array(
				'db' => 'cat.etiqueta AS etiqueta1',
				'dbj' => 'cat.etiqueta',
				'real' => 'cat.etiqueta',
				'alias' => 'etiqueta1',
				'typ' => 'txt',
				'dt' => 8
			),
			array(
				'db' => 'cat2.etiqueta AS etiqueta2',
				'dbj' => 'cat2.etiqueta',
				'real' => 'cat2.etiqueta',
				'alias' => 'etiqueta2',
				'typ' => 'txt',
				'dt' => 9
			),
			array(
				'db' => 'tc.tabulado as tabulado',
				'dbj' => 'tc.tabulado',
				'real' => 'tc.tabulado',
				'alias' => 'tabulado',
				'typ' => 'int',
				'bin' => true,
				'dt' => 10
			),
			array(
				'db' => 'tc.id_tarifa_cliente as id_tarifa_cliente',
				'dbj' => 'tc.id_tarifa_cliente',
				'real' => 'tc.id_tarifa_cliente',
				'alias' => 'id_tarifa_cliente',
				'typ' => 'int',
				'dt' => 11
			)
		);
		$render_table = new acciones_tarifas;
		$inner = '
			INNER JOIN cm_catalogo AS cat ON tc.cat_statustarifa = cat.id_cat
			INNER JOIN cm_catalogo AS cat2 ON tc.cat_tipo_tarifa = cat2.id_cat
		';
		$where = '
			tc.id_cliente = '.$id_cliente.'
			AND
			tc.cat_statustarifa = 168
		';
		$orden = '
			order by tc.id_tarifa_cliente desc
		';
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
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
				$salida = "";
				if ( isset( $column['moneda'] ) ) {

					$cantidad = ($data[$i][ $column['alias'] ]);
					$cantidad = money_format('%i',$cantidad);
					$salida = $cantidad;

					$row[ $column['dt'] ] = $salida;
				}else if(isset( $column['bin'])){

					$id_tarifa_cliente = $data[$i][ 'id_tarifa_cliente' ];

					$bin = ($data[$i][ $column['alias'] ]);

					$delete='<a href="javascript:;" data-rel="tooltip" data-original-title="Caducar tarifa" class="red tooltip-error" onclick="caducar_tarifa('.$id_tarifa_cliente.');"><i class="ace-icon fa fa-trash bigger-130"></i></a>';

					$vigente='<a href="javascript:;" data-rel="tooltip" data-original-title="Tarifa vigente" class="green tooltip-success"><i class="ace-icon fa fa-check bigger-130"></i></a>';

					$salida = ($bin == 1)?$delete:$vigente;
					$row[ $column['dt'] ] = $salida;

				}else{
					$row[ $column['dt'] ] = $data[$i][$name_column];
				}
			}
			$out[] = $row;
		}
		return $out;
	}
}
class acciones_usuario extends SSP{ /*Individual*/
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				$salida = "";
				if ( isset( $column['acciones'] ) ) {
					$id_cliente = $data[$i][ 'id_cliente' ];

					if(Controlador::tiene_permiso('Clientes|operador_favorito')){
						$salida .= '<a data-rel="tooltip" data-original-title="Operador preferido" class="green tooltip-success" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'clientes/operador_favorito/'.$id_cliente.'/\');"><i class="ace-icon fa fa-heart bigger-130"></i></a>&nbsp;&nbsp;';
					}
					if(Controlador::tiene_permiso('Clientes|destinos')){
						$salida .= '<a data-rel="tooltip" data-original-title="Destinos del cliente" class="green tooltip-success" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'clientes/destinos/'.$id_cliente.'/\');"><i class="ace-icon fa fa-map-marker bigger-130"></i></a>&nbsp;&nbsp;';
					}
					if(Controlador::tiene_permiso('Clientes|origenes')){
						$salida .= '<a data-rel="tooltip" data-original-title="Origenes del cliente" style="color:#EDA807" class="tooltip-warning" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'clientes/origenes/'.$id_cliente.'/\');"><i class="ace-icon fa fa-map-marker bigger-130"></i></a>&nbsp;&nbsp;';
					}
					if(Controlador::tiene_permiso('Clientes|viajes')){
						$salida .= '<a data-rel="tooltip" data-original-title="Viajes del cliente" style="color:green" class="tooltip-success" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'clientes/viajes/'.$id_cliente.'/\');"><i class="ace-icon fa fa-road bigger-130"></i></a>&nbsp;&nbsp;';
					}
					if(Controlador::tiene_permiso('Clientes|calendario_historico')){
						$salida .= '<a data-rel="tooltip" data-original-title="Calendario Histórico" style="color:green" class="tooltip-success" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'clientes/calendario_historico/'.$id_cliente.'/\');"><i class="ace-icon fa fa-calendar bigger-130"></i></a>&nbsp;&nbsp;';
					}

					$row[ $column['dt'] ] = $salida;
				}else{
					$row[ $column['dt'] ] = $data[$i][$name_column];
				}
			}
			$out[] = $row;
		}
		return $out;
	}
}
class acciones_cliente extends SSP{ /*Corporativo*/
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				$salida = "";
				if ( isset( $column['acciones'] ) ) {
					$id_cliente = $data[$i][ 'id_cliente' ];

					if(Controlador::tiene_permiso('Clientes|editar')){
						$salida .= '<a data-rel="tooltip" data-original-title="Editar Cliente" class="green tooltip-success" onclick="modal_editar_cliente('.$id_cliente.');"><i class="ace-icon fa fa-pencil bigger-130"></i></a>&nbsp;&nbsp;';
					}
					if(Controlador::tiene_permiso('Clientes|nestable_client')){
						$salida .= '<a data-rel="tooltip" data-original-title="Jerarquías de usuarios" class="green tooltip-success" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'clientes/nestable_client/'.$id_cliente.'/\');"><i class="ace-icon fa fa-sitemap bigger-130"></i></a>&nbsp;&nbsp;';
					}
					if(Controlador::tiene_permiso('Clientes|listado')){
						$salida .= '<a data-rel="tooltip" data-original-title="Listado y Acciones" class="green tooltip-success" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'clientes/listado/'.$id_cliente.'/\');"><i class="ace-icon fa fa-list bigger-130"></i></a>&nbsp;&nbsp;';
					}
					if(Controlador::tiene_permiso('Clientes|ubicacion')){
						$salida .= '<a data-rel="tooltip" data-original-title="Direcciones" class="green tooltip-success" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'clientes/ubicacion/'.$id_cliente.'/\');"><i class="ace-icon fa fa-map-marker bigger-130"></i></a>&nbsp;&nbsp;';
					}
					if(Controlador::tiene_permiso('Clientes|tarifas')){
						$salida .= '<a data-rel="tooltip" data-original-title="Tarifas" class="green tooltip-success" onclick="modal_establecer_tarifa('.$id_cliente.')"><i class="ace-icon fa fa-credit-card bigger-130"></i></a>&nbsp;&nbsp;';
					}
					if(Controlador::tiene_permiso('Clientes|finanzas')){
						$salida .= '<a data-rel="tooltip" data-original-title="Movimientos financieros" class="green tooltip-success" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'clientes/finanzas/'.$id_cliente.'/\');"><i class="ace-icon fa fa-usd bigger-130"></i></a>&nbsp;&nbsp;';
					}
					if(Controlador::tiene_permiso('Clientes|anticipos')){
						$salida .= '<a data-rel="tooltip" data-original-title="Anticipos" class="green tooltip-success" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'clientes/anticipos/'.$id_cliente.'/\');"><i class="ace-icon fa fa-money bigger-130"></i></a>&nbsp;&nbsp;';
					}
					if(Controlador::tiene_permiso('Clientes|facturas')){
						$salida .= '<a data-rel="tooltip" data-original-title="Facturas" class="green tooltip-success" onclick="carga_archivo(\'contenedor_principal\',\'' . URL_APP . 'clientes/facturas/'.$id_cliente.'/\');"><i class="ace-icon fa fa-file bigger-130"></i></a>&nbsp;&nbsp;';
					}
					$row[ $column['dt'] ] = $salida;
				}else{
					$row[ $column['dt'] ] = $data[$i][$name_column];
				}
			}
			$out[] = $row;
		}
		return $out;
	}
}
?>
