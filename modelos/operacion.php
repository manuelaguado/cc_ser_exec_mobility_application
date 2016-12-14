<?php
require_once( '../vendor/mysql_datatable.php' );
class OperacionModel{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
        }
    }
	function notificacionesApartados(){
		$qry = "
			SELECT
				viv.id_viaje AS id_viaje,
				vcd.fecha_requerimiento AS fecha_requerimiento,
				clc.nombre AS cliente,
				clp.nombre AS empresa,
				service.etiqueta AS servicio,
				num_eq.num AS numq,
				vcl.id_cliente AS id_cliente
			FROM
				vi_viaje AS viv
			INNER JOIN vi_viaje_detalle AS vcd ON vcd.id_viaje = viv.id_viaje
			INNER JOIN vi_viaje_clientes AS vcl ON vcl.id_viaje = viv.id_viaje
			INNER JOIN cl_clientes AS clc ON vcl.id_cliente = clc.id_cliente
			INNER JOIN cl_clientes AS clp ON clc.parent = clp.id_cliente
			INNER JOIN cm_catalogo AS service ON viv.cat_tiposervicio = service.id_cat
			INNER JOIN cr_operador_unidad ON viv.id_operador_unidad = cr_operador_unidad.id_operador_unidad
			INNER JOIN cr_operador ON cr_operador_unidad.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador.id_operador = cr_operador_numeq.id_operador
			INNER JOIN cr_numeq AS num_eq ON cr_operador_numeq.id_numeq = num_eq.id_numeq
			WHERE
				viv.cat_status_viaje = 195
			AND viv.cat_tipotemporicidad = 162
			AND vcd.fecha_requerimiento > DATE_SUB(NOW(), INTERVAL 30 MINUTE)
			AND vcd.fecha_requerimiento < DATE_ADD(NOW(), INTERVAL 65 MINUTE)
			GROUP BY
				viv.id_viaje
			ORDER BY
				fecha_requerimiento ASC
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$notificacion = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();

			foreach ($data as $row){
				$notificacion[$num]['id_viaje']		= $row->id_viaje;
				$notificacion[$num]['fecha']		= $row->fecha_requerimiento;
				$notificacion[$num]['cliente']		= $row->cliente;
				$notificacion[$num]['empresa']		= $row->empresa;
				$notificacion[$num]['numq']			= $row->numq;
				$notificacion[$num]['total']		= $query->rowCount();
				$num++;
			}
			
		}
		return $notificacion;	
	}
	function addCostoAdicional($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		$sql = "
			INSERT INTO vi_costos_adicionales (
				id_viaje,
				cat_concepto,
				costo,
				user_alta,
				user_mod,
				fecha_alta
			) VALUES (
				:id_viaje,
				:cat_concepto,
				:costo,
				:user_alta,
				:user_mod,
				:fecha_alta
			)";
		$query = $this->db->prepare($sql);
		$query_resp = $query->execute(
			array(
				':id_viaje' =>  $this->id_viaje ,
				':cat_concepto' =>  $this->cat_concepto ,
				':costo' =>  substr($this->costo, 2) ,
				':user_alta' =>  $_SESSION['id_usuario'] ,
				':user_mod' => $_SESSION['id_usuario'] ,
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
	function eliminar_costoAdicional($id_costos_adicionales){
		$qry = "
			DELETE
			FROM
			vi_costos_adicionales
			WHERE
			id_costos_adicionales = '".$id_costos_adicionales."' 
		";
		$query = $this->db->prepare($qry);
		$ok = $query->execute();
		if($ok){
			$array = array('resp' => true , 'mensaje' => 'Registro guardado correctamente.' );
		}else{
			$array = array('false' => true , 'mensaje' => 'Registro guardado correctamente.' );
		}
		return $array;
	}
	function countApart($id_operador,$hit,$id_operador_turno){
		if(!$hit){
			self::setNoHit($id_operador_turno);
		}
		if(self::comprobarApartado($id_operador) == true){
			self::apartUpdate($id_operador,$hit);
		}else{
			self::apartInsert($id_operador,$hit);
		}
	}
	function setNoHit($id_operador){
		if(self::comprobarApartado($id_operador) == true){
			self::turnUpdate($id_operador);
		}else{
			self::turnInsert($id_operador);
		}
	}
	function cambiar_tarifa($id_tarifa_cliente,$id_viaje){
		$qry = "
			UPDATE `vi_viaje`
			SET
			 `id_tarifa_cliente` = ".$id_tarifa_cliente."
			WHERE
				(`id_viaje` = ".$id_viaje.");
		";
		$query = $this->db->prepare($qry);
		$query_resp = $query->execute();
		if($query_resp){
			$respuesta = array('resp' =>  true , 'id_tarifa_cliente' => $id_tarifa_cliente, 'id_viaje' => $id_viaje);
		}else{
			$respuesta = array('resp' => false , 'id_tarifa_cliente' => $id_tarifa_cliente, 'id_viaje' => $id_viaje);
		}
		print json_encode($respuesta);
	}
	function apartUpdate($id_operador,$hit){
		$isHit = ($hit)?1:0;
		$increment = self::resetOrIncrement($id_operador);
		$trna = ($increment['year'])?"`turnos_anuales` + ".$isHit:$isHit;
		$hita = ($increment['year'])?"`hit_anual` + ".$isHit:$isHit;
		$anua = ($increment['year'])?"`anuales` + 1":"1";
		$mens = ($increment['mes'])?"`mensuales` + 1":"1";
		$qry = "
			UPDATE `cr_apartados`
			SET
			 `mensuales` = ".$mens.",
			 `anuales` = ".$anua.",
			 `totales` = `totales` + 1,
			 `hit_anual` = ".$hita.",
			 `hit_total` = `hit_total` + ".$isHit.",
			 `turnos_anuales` = ".$trna.",
			 `turnos_totales` = `turnos_totales` + ".$isHit.",
			 `user_mod` = '".$_SESSION['id_usuario']."'
			WHERE
				(`id_operador` = $id_operador);
		";
		$query = $this->db->prepare($qry);
		$query->execute();
	}	
	function turnUpdate($id_operador){
		$increment = self::resetOrIncrement($id_operador);
		$inc = ($increment['year'])?'`turnos_anuales` + 1':'1';
		$qry = "
			UPDATE `cr_apartados`
			SET
			 `turnos_anuales` = ".$inc.",
			 `turnos_totales` = `turnos_totales` + 1,
			 `user_mod` = '".$_SESSION['id_usuario']."'
			WHERE
				(`id_operador` = $id_operador);
		";
		$query = $this->db->prepare($qry);
		$query->execute();
	}
	function resetOrIncrement($id_operador){
		$qry = "
			SELECT
				cra.fecha_mod
			FROM
				cr_apartados AS cra
			WHERE
				cra.id_operador = $id_operador		
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row){
				$date = $row->fecha_mod;
			}
		}
		$ahora = date("Y-m-d H:i:s");
		$date_mes = substr($date,0,7);
		$ahora_mes = substr($ahora,0,7);
		$date_año = substr($date,0,4);
		$ahora_año = substr($ahora,0,4);
		if($date_año == $ahora_año){$array['year'] = true;}else{$array['year'] = false;}
		if($date_mes == $ahora_mes){$array['mes']  = true;}else{$array['mes'] = false;}
		return $array;
	}
	function turnInsert($id_operador){
		$qry = "
			INSERT INTO `cr_apartados` (
				`id_operador`,
				`mensuales`,
				`anuales`,
				`totales`,
				`hit_anual`,
				`hit_total`,
				`turnos_anuales`,
				`turnos_totales`,
				`user_alta`,
				`fecha_alta`
			)
			VALUES
				(
					'".$id_operador."',
					'0',
					'0',
					'0',
					'0',
					'0',
					'1',
					'1',
					'".$_SESSION['id_usuario']."',
					'".date("Y-m-d H:i:s")."'
				);	
		";
		$query = $this->db->prepare($qry);
		$query->execute();
	}	
	function comprobarApartado($id_operador){
		$qry = "
			SELECT
				cra.id_apartados
			FROM
				cr_apartados AS cra
			WHERE
				cra.id_operador = $id_operador		
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			return true;
		}else{
			return false;
		}
	}
	function apartInsert($id_operador,$hit){
		$isHit = ($hit)?1:0;
		$qry = "
			INSERT INTO `cr_apartados` (
				`id_operador`,
				`mensuales`,
				`anuales`,
				`totales`,
				`hit_anual`,
				`hit_total`,
				`turnos_anuales`,
				`turnos_totales`,
				`user_alta`,
				`fecha_alta`
			)
			VALUES
				(
					'".$id_operador."',
					'1',
					'1',
					'1',
					'".$isHit."',
					'".$isHit."',
					'".$isHit."',
					'".$isHit."',
					'".$_SESSION['id_usuario']."',
					'".date("Y-m-d H:i:s")."'
				);	
		";
		$query = $this->db->prepare($qry);
		$query->execute();
	}
	function turnoApart($anterior){
		$qry = "
			SELECT
				cr_numeq.num
			FROM
				cr_operador
			INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = cr_operador.id_operador
			INNER JOIN cr_numeq ON cr_operador_numeq.id_numeq = cr_numeq.id_numeq
			WHERE
				cr_operador.cat_statusoperador = 8
			AND cr_numeq.num > ".$anterior."
			ORDER BY
				cr_numeq.id_numeq ASC
			LIMIT 0,
			 1
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row){
				$numero = $row->num;
			}
		}else{
			$qry2 = "
				SELECT
					cr_numeq.num
				FROM
					cr_operador
				INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = cr_operador.id_operador
				INNER JOIN cr_numeq ON cr_operador_numeq.id_numeq = cr_numeq.id_numeq
				WHERE
					cr_operador.cat_statusoperador = 8
				ORDER BY
					cr_numeq.id_numeq ASC
				LIMIT 0,
				 1
			";
			$query2 = $this->db->prepare($qry2);
			$query2->execute();
			if($query2->rowCount()>=1){
				$data2 = $query2->fetchAll();
				foreach ($data2 as $row2){
					$numero = $row2->num;
				}
			}
		}
		return $numero;
	}
	function pulledApart(){
		$qry = "
			SELECT
			cr_numeq.num,
			cr_operador.id_operador,
			fw_usuarios.id_usuario,
			cr_operador_unidad.id_operador_unidad,
			CONCAT(
					fw_usuarios.nombres,
					' ',
					fw_usuarios.apellido_paterno,
					' ',
					fw_usuarios.apellido_materno
				) AS nombre
			FROM
			cr_operador
			INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = cr_operador.id_operador
			INNER JOIN cr_numeq ON cr_operador_numeq.id_numeq = cr_numeq.id_numeq
			INNER JOIN fw_usuarios ON cr_operador.id_usuario = fw_usuarios.id_usuario
			INNER JOIN cr_operador_unidad ON cr_operador_unidad.id_operador = cr_operador.id_operador
			WHERE
				cr_operador.cat_statusoperador = 8
			GROUP BY
				cr_numeq.num
			ORDER BY
				cr_numeq.id_numeq ASC
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$operadores = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row){
				$apartados = self::apartData($row->id_operador);
				$operadores[$num]['num'] 				= $row->num;
				$operadores[$num]['id_operador'] 		= $row->id_operador;
				$operadores[$num]['id_usuario'] 		= $row->id_usuario;
				$operadores[$num]['nombre'] 			= $row->nombre;
				$operadores[$num]['id_operador_unidad'] = $row->id_operador_unidad;
				$operadores[$num]['mensual'] 			= $apartados['mensuales'];
				$operadores[$num]['anual'] 				= $apartados['anuales'];
				$operadores[$num]['status'] 			= $apartados['hit_anual'].'/'.$apartados['turnos_anuales'];
				
				$num++;
			}
		}
		return $operadores;			
	}
	function apartData($id_operador){
		$qry = "
			SELECT
				cr_apartados.mensuales,
				cr_apartados.anuales,
				cr_apartados.hit_anual,
				cr_apartados.turnos_anuales
			FROM
				cr_apartados
			WHERE
				cr_apartados.id_operador = $id_operador
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$apartados = array();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row){
				$apartados['mensuales'] 	= $row->mensuales;
				$apartados['anuales'] 		= $row->anuales;
				$apartados['hit_anual'] 	= $row->hit_anual;
				$apartados['turnos_anuales']= $row->turnos_anuales;
			}
		}else{
				$apartados['mensuales'] 	= 0;
				$apartados['anuales'] 		= 0;
				$apartados['hit_anual'] 	= 0;
				$apartados['turnos_anuales']= 0;
		}
		return $apartados;	
	}	
	function adquirirTiemposBase(){
		$units = self::getTBUnitsRed();
		self::vaciarTiempoBase();
		$oper = array();
		$coordsUnits = '';
		foreach($units as $unit){
			$qry = "
				SELECT
					gps.id_gps,
					gps.latitud,
					gps.longitud,
					gps.tiempo,
					gps.`timestamp`
				FROM
					gps
				WHERE
					gps.id_operador = ".$unit['id_operador']."
				ORDER BY
					gps.id_gps DESC
				LIMIT 0,
				 1			
			";
			$query = $this->db->prepare($qry);
			$query->execute();
			if($query->rowCount()>=1){
				$data = $query->fetchAll();
				foreach ($data as $row){
					$coordsUnits .= $row->latitud.','.$row->longitud.'|';
					$oper[] = $unit['id_operador'];
					$latLng[] = $row->latitud.','.$row->longitud;
				}
			}		
		}
		if($coordsUnits != ''){
			$coordBase = '19.434830,-99.211976';
			$coordsUnits = substr($coordsUnits, 0, -1);
			$resultado = self::distancematrix($coordsUnits,$coordBase);
			$array = array();
			foreach($resultado->rows as $n => $tb_units){
				$array['distancia'] = $tb_units->elements[0]->distance->value;
				$array['min'] = $tb_units->elements[0]->duration->value;
				$array['max'] = $tb_units->elements[0]->duration_in_traffic->value;
				$array['id_operador'] = $oper[$n];
				$array['latLng'] = $latLng[$n];
				self::storeTB($array);
			}
		}
	}
	function distancematrix($coordsUnits,$coordBase){
		$url = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$coordsUnits.'&destinations='.$coordBase.'&mode=driving&traffic_model=pessimistic&departure_time=now&language=es-ES&key='.GOOGLE_MAPS;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$resultado = curl_exec ($ch);
		return json_decode($resultado);
	}
	function vaciarTiempoBase(){
		$sql = "TRUNCATE cr_tiempo_base";
		$query = $this->db->prepare($sql);
		$query->execute();	
	}
	function storeTB($array){
		$sql = "
			INSERT INTO `cr_tiempo_base` (
				`id_operador`,
				`distancia`,
				`min_min`,
				`min_max`,
				`latlng`
			)
			VALUES
				(
					'".$array['id_operador']."',
					'".$array['distancia']."',
					'".$array['min']."',
					'".$array['max']."',
					'".$array['latLng']."'
				);		
		";
		$query = $this->db->prepare($sql);
		$query->execute();	
	}
	function getTBUnitsRed(){
		$qry = '
			SELECT
				oun.id_operador_unidad,
				oun.id_operador AS id_operador
			FROM
				cr_operador_unidad AS oun
			INNER JOIN cr_sync AS syc ON oun.sync_token = syc.token
			WHERE
				(
					(
						syc.estado1 = "C1"
						AND syc.estado2 = "F11"
					)
					OR (
						syc.estado1 = "C1"
						AND syc.estado3 = "F11"
					)
				)		
		';
		$query = $this->db->prepare($qry);
		$query->execute();
		$operadores = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row){
				$operadores[$num]['id_operador'] 		= $row->id_operador;
				$operadores[$num]['id_operador_unidad'] = $row->id_operador_unidad;
				$num++;
			}
		}
		return $operadores;			
	}
	function getCurrentCveOperador($id_operador_unidad){
		$qry = "
			SELECT
				syc.clave as llave
			FROM
				cr_operador_unidad AS oun
			INNER JOIN cr_sync AS syc ON oun.sync_token = syc.token
			INNER JOIN cm_catalogo ON syc.clave = cm_catalogo.etiqueta
			WHERE
				oun.id_operador_unidad = $id_operador_unidad
			AND cm_catalogo.catalogo = 'clavesitio'	
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row){
				return	$row->llave;
			}
		}		
	}	
	function getTBUnits(){
		self::adquirirTiemposBase();
		$qry = '
			SELECT
				cr_numeq.num AS numeq,
				CONCAT(
					usu.nombres,
					" ",
					usu.apellido_paterno,
					" ",
					usu.apellido_materno
				) AS nombre,
				cr_operador_unidad.id_operador_unidad,
				cr_operador.id_operador,
				cr_marcas.marca,
				cr_modelos.modelo,
				cr_unidades.color,
				cr_tiempo_base.latlng,
				cr_tiempo_base.distancia,
				cr_tiempo_base.min_min,
				cr_tiempo_base.min_max
			FROM
				cr_tiempo_base
			INNER JOIN cr_operador ON cr_tiempo_base.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = cr_operador.id_operador
			INNER JOIN cr_numeq ON cr_operador_numeq.id_numeq = cr_numeq.id_numeq
			INNER JOIN fw_usuarios AS usu ON cr_operador.id_usuario = usu.id_usuario
			INNER JOIN cr_operador_unidad ON cr_operador_unidad.id_operador = cr_operador.id_operador
			INNER JOIN cr_unidades ON cr_operador_unidad.id_unidad = cr_unidades.id_unidad
			INNER JOIN cr_marcas ON cr_unidades.id_marca = cr_marcas.id_marca
			INNER JOIN cr_modelos ON cr_unidades.id_modelo = cr_modelos.id_modelo			
		';
		$query = $this->db->prepare($qry);
		$query->execute();
		$operadores = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row){
				$operadores[$num]['numeq'] 				= $row->numeq;
				$operadores[$num]['nombre'] 			= $row->nombre;
				$operadores[$num]['marca'] 				= $row->marca;
				$operadores[$num]['modelo'] 			= $row->modelo;
				$operadores[$num]['color'] 				= $row->color;
				$operadores[$num]['id_operador_unidad'] = $row->id_operador_unidad;
				$operadores[$num]['id_operador'] 		= $row->id_operador;
				
				$operadores[$num]['distancia'] 			= $row->distancia;
				$operadores[$num]['min_min'] 			= $row->min_min;
				$operadores[$num]['min_max'] 			= $row->min_max;
				
				$coord = explode(',',$row->latlng);
				$operadores[$num]['latitud'] 			= $coord[0];
				$operadores[$num]['longitud'] 			= $coord[1];
				
				$num++;
			}
		}
		return $operadores;			
	}
	function asignar_viajes($base){
		$operador 	= self::unidades_formadas($base);
		$viajes		= self::viajes_pendientes();
		$array = array();
		if(($operador['procesar'])&&($viajes['procesar'])){
			self::asignar_viaje($viajes['id_viaje'],$operador);
			
			$array['id_operador_unidad'] = $operador['id_operador_unidad'];
			$array['id_viaje'] 	= $viajes['id_viaje'];
			$array['salida'] 	= self::getSalidaId($viajes['salida']);
			$array['process'] 	= true;
		
		}else{
			$array['process'] = false;
		}
		return $array;
	}
	function getSalidaId($salida){
		$sql = "
			SELECT
				cm_catalogo.valor
			FROM
				cm_catalogo
			WHERE
				cm_catalogo.id_cat = $salida
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row) {
				return $row->valor;
			}
		}
	}
	
	
	function asignar_apartado($id_viaje,$operador){
		self::relacionar_operador_apartado($id_viaje,$operador);
		self::set_fecha_asignacion($id_viaje);
	}
	function relacionar_operador_apartado($id_viaje,$operador){
		
		$sql = "
			UPDATE vi_viaje
			SET 
			 id_episodio 		= '".$operador['id_episodio']."',
			 id_operador_unidad = '".$operador['id_operador_unidad']."',
			 cat_status_viaje	= '195'
			WHERE
				id_viaje = ".$id_viaje."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		
	}	
	function asignarApartadoAlAire($id_viaje,$operador){
		self::relacionar_apartadoAlAire($id_viaje,$operador);
		self::set_fecha_asignacion($id_viaje);
	}
	function relacionar_apartadoAlAire($id_viaje,$operador){
		
		$sql = "
			UPDATE vi_viaje
			SET 
			 id_episodio 		= '".$operador['id_episodio']."',
			 id_operador_unidad = '".$operador['id_operador_unidad']."',
			 cat_status_viaje	= '171',
			 cat_tipotemporicidad = '184'
			WHERE
				id_viaje = ".$id_viaje."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		
	}	
	function asignar_viaje($id_viaje,$operador){
		self::relacionar_operador_viaje($id_viaje,$operador);
		self::set_fecha_asignacion($id_viaje);
	}
	function relacionar_operador_viaje($id_viaje,$operador){
		
		$cordon = ($operador['id_cordon'] != '')?"id_cordon = '".$operador['id_cordon']."',":'';
		
		$sql = "
			UPDATE vi_viaje
			SET 
			 $cordon
			 id_episodio 		= '".$operador['id_episodio']."',
			 id_operador_unidad = '".$operador['id_operador_unidad']."',
			 cat_status_viaje	= '171'
			WHERE
				id_viaje = ".$id_viaje."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	function activarApartado($id_viaje,$operador){
		$sql = "
			UPDATE vi_viaje
			SET 
			 id_episodio 		= '".$operador['id_episodio']."',
			 id_operador_unidad = '".$operador['id_operador_unidad']."',
			 cat_status_viaje	= '171',
			 cat_tipotemporicidad = '184'
			WHERE
				id_viaje = ".$id_viaje."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	function setear_status_viaje($post, MobileModel $mobile=NULL, OperadoresModel $operadores = NULL, LoginModel $login = NULL){
		
		$stat_process = true;
		$qrymissing = array();
		$id_operador_unidad = self::getIdOperadorUnidad($post['id_viaje']);
		switch($post['stat']){
			case '170':
				if(!isset($post['status_operador'])){
					$qrymissing = array('qrymissing' => 'status_operador' );
					$stat_process = false;
				}
				$sql = "UPDATE vi_viaje SET id_operador_unidad = NULL, id_episodio = NULL, id_cordon = NULL WHERE id_viaje = ".$post['id_viaje'];
			break;
			case '173':
				if(!$post['cat_cancelaciones']){
					$qrymissing = array('qrymissing' => 'cat_cancelaciones' );
					$stat_process = false;
				}
				if(($post['origen'] == 'asignados')&&(!isset($post['status_operador']))){
					$qrymissing = array('qrymissing' => 'status_operador' );
					$stat_process = false;
				}
				$sql = "UPDATE vi_viaje SET cat_cancelaciones =  ".$post['cat_cancelaciones']." WHERE id_viaje = ".$post['id_viaje'];
			break;
		}
		
		if($stat_process){
			$success = true;
			try {  
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$this->db->beginTransaction();
				$this->db->exec("UPDATE vi_viaje SET cat_status_viaje = '".$post['stat']."' WHERE id_viaje = ".$post['id_viaje']);
				if(isset($sql)){$this->db->exec($sql);}
				$this->db->commit();

			} catch (Exception $e) {
				$this->db->rollBack();
				$success = false;			
			}
			
		}else{
			$success = false;
		}
		
		if($success){
			switch($post['stat']){
				case '170':
					$token = 'SOL:'.Controller::token(62);
					switch($post['status_operador']){
						case 'segundo':
							$mobile->setCveStore($_SESSION['id_usuario'],$token,117,$id_operador_unidad);
							$mobile->setCveStore($_SESSION['id_usuario'],$token,153,$id_operador_unidad,true,'regreso');
						break;
						case 'cola':
							$mobile->cordonCompletado($_SESSION['id_usuario'],$id_operador_unidad,1);
							$mobile->setCveStore($_SESSION['id_usuario'],$token,153,$id_operador_unidad,true,'regreso');
						break;
						case 'suspender':
							/*Setear en suspendido*/
							$operador['cat_statusoperador'] = 10;
							$operador['id_operador'] = $mobile->getIdOperador($id_operador_unidad);
							$operadores->setearstatusoperador($operador);
							
							/*desloguear*/
							$mobile->setCveStore($_SESSION['id_usuario'],$token,154,$id_operador_unidad,false);
							$id_usuario = $mobile->getIdUsuario($id_operador_unidad);
							$login->signout($id_usuario);
						break;
						case 'omitir':
							$mobile->setCveStore($_SESSION['id_usuario'],$token,153,$id_operador_unidad,true,'regreso');
						break;
					}
				break;
				case '173':
					if($post['origen'] == 'asignados'){
						$token = 'SOL:'.Controller::token(62);
						switch($post['status_operador']){
							case 'segundo':
								$mobile->setCveStore($_SESSION['id_usuario'],$token,117,$id_operador_unidad);
								$mobile->setCveStore($_SESSION['id_usuario'],$token,153,$id_operador_unidad,true,'regreso');
							break;
							case 'cola':
								$mobile->cordonCompletado($_SESSION['id_usuario'],$id_operador_unidad,1);
								$mobile->setCveStore($_SESSION['id_usuario'],$token,153,$id_operador_unidad,true,'regreso');
							break;
							case 'omitir':
								$mobile->setCveStore($_SESSION['id_usuario'],$token,153,$id_operador_unidad,true,'regreso');
							break;
						}

					}
				break;
			}
			
			$output = array('resp' => true , 'mensaje' => 'se seteo a '.$post['stat'].' satisfactoriamente' );
			$print = $output + $qrymissing;
			return json_encode($print);
		}else{
			$output = array('resp' => false , 'mensaje' => 'No se seteo a '.$post['stat'] );
			$print = $output + $qrymissing;
			return json_encode($print);
		}
	}
	function cancel_apartado_set($post, MobileModel $mobile=NULL){
		
		$stat_process = true;
		$qrymissing = array();
		$id_operador_unidad = self::getIdOperadorUnidad($post['id_viaje']);

		if(!$post['cat_cancelaciones']){
			$qrymissing = array('qrymissing' => 'cat_cancelaciones' );
			$stat_process = false;
		}		
		if(($post['origen'] == 'rojo')&&(!isset($post['status_operador']))){
			$qrymissing = array('qrymissing' => 'status_operador' );
			$stat_process = false;
		}
		$sql = "UPDATE vi_viaje SET cat_cancelaciones =  ".$post['cat_cancelaciones']." WHERE id_viaje = ".$post['id_viaje'];
		
		if($stat_process){
			$success = true;
			try {  
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$this->db->beginTransaction();
				$this->db->exec("UPDATE vi_viaje SET cat_status_viaje = '173' WHERE id_viaje = ".$post['id_viaje']);
				if(isset($sql)){$this->db->exec($sql);}
				$this->db->commit();

			} catch (Exception $e) {
				$this->db->rollBack();
				$success = false;			
			}
			
		}else{
			$success = false;
		}
		
		if($success){
			
			if($post['origen'] == 'rojo'){
				$token = 'APA:'.Controller::token(62);
				switch($post['status_operador']){
					case 'segundo':
						$mobile->setCveStore($_SESSION['id_usuario'],$token,117,$id_operador_unidad);
						$mobile->setCveStore($_SESSION['id_usuario'],$token,153,$id_operador_unidad,true,'regreso');
					break;
					case 'cola':
						$mobile->cordonCompletado($_SESSION['id_usuario'],$id_operador_unidad,1);
						$mobile->setCveStore($_SESSION['id_usuario'],$token,153,$id_operador_unidad,true,'regreso');
					break;
					case 'omitir':
						$mobile->setCveStore($_SESSION['id_usuario'],$token,153,$id_operador_unidad,true,'regreso');
					break;
				}

			}

			$output = array('resp' => true , 'mensaje' => 'se seteo a 173 satisfactoriamente' );
			$print = $output + $qrymissing;
			return json_encode($print);
		}else{
			$output = array('resp' => false , 'mensaje' => 'No se seteo a 173');
			$print = $output + $qrymissing;
			return json_encode($print);
		}
	}
	function apartado2pendientesDo($post){
	
		$qry = "UPDATE vi_viaje SET cat_status_viaje = '170', cat_tipotemporicidad = '184' WHERE id_viaje = ".$post['id_viaje'];
		$query = $this->db->prepare($qry);
		$success = $query->execute();			
		
		if($success){
			return json_encode(array('resp' => true , 'mensaje' => 'se seteo a 170 satisfactoriamente' ));
		}else{
			return json_encode(array('resp' => false , 'mensaje' => 'No se seteo a 170'));
		}
	}
	function getIdOperadorUnidad($id_viaje){
		$sql ="SELECT id_operador_unidad FROM vi_viaje WHERE id_viaje = ".$id_viaje;
		$query = $this->db->prepare($sql);
		$query->execute();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row) {
				return $row->id_operador_unidad;
			}
		}else{
			return false;
		}
	}
	function queryTarifas($id_company){
		$queryTarifa="
			SELECT
				tc.nombre,
				tc.descripcion,
				tc.costo_base,
				tc.km_adicional,
				tc.inicio_vigencia,
				tc.fin_vigencia,
				cat.etiqueta AS `status`,
				cat2.etiqueta AS `tipo`,
				tc.tabulado,
				tc.id_tarifa_cliente
			FROM
				cl_tarifas_clientes AS tc
			INNER JOIN cm_catalogo AS cat ON tc.cat_statustarifa = cat.id_cat
			INNER JOIN cm_catalogo AS cat2 ON tc.cat_tipo_tarifa = cat2.id_cat
			WHERE
				tc.id_cliente = $id_company
				AND
				tc.cat_statustarifa = 168
			order by tc.id_tarifa_cliente desc
		";
		$query = $this->db->prepare($queryTarifa);
		$query->execute();
		$tarifas =  $query->fetchAll();
		if($query->rowCount()>=1){
			return $tarifas;
		}
	}
	function currentTarifa($id_viaje){
		$sql ="
			SELECT
				viv.id_tarifa_cliente
			FROM
				vi_viaje AS viv
			WHERE
				viv.id_viaje = $id_viaje	
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row) {
				return $row->id_tarifa_cliente;
			}
		}else{
			return false;
		}
	}
	function getIdCliente($id_viaje){
		$sql ="
			SELECT
				vivc.id_cliente
			FROM
				vi_viaje AS viv
			INNER JOIN vi_viaje_clientes AS vivc ON vivc.id_viaje = viv.id_viaje
			WHERE
				viv.id_viaje = $id_viaje
			LIMIT 0,
			 1		
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row) {
				return $row->id_cliente;
			}
		}else{
			return false;
		}
	}
	function id_company($id_cliente){
		$query = "
			SELECT
				clc.parent AS id_company
			FROM
				cl_tarifas_clientes AS tfcl
			INNER JOIN cl_clientes AS clc ON tfcl.id_cliente = clc.parent
			WHERE
				clc.id_cliente = 2
			AND tfcl.cat_statustarifa = 168
			AND tfcl.cat_tipo_tarifa = 189
		";
		$query = $this->db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		$output = '';
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				$output =  $row->id_company;
			}			
		}
		return $output;
	}	
	function set_fecha_asignacion($id_viaje){
		$sql = "
			UPDATE vi_viaje_detalle
			SET 
			 fecha_asignacion = '".date("Y-m-d H:i:s")."'
			WHERE
				id_viaje = ".$id_viaje."
		";
		$query = $this->db->prepare($sql);
		$query->execute();	
	}
	function viajes_pendientes(){
		$sql ="
			SELECT
				viv.id_viaje AS id_viaje,
				viv.cat_tipo_salida AS salida
			FROM
				vi_viaje AS viv
			WHERE
				(viv.cat_status_viaje = 170 OR viv.cat_status_viaje = 188)
				AND
				viv.cat_tipotemporicidad = 184
				AND
				viv.cat_tipo_salida = 180
			ORDER BY
				viv.id_viaje ASC
			Limit 0,1
		";
		
		$query = $this->db->prepare($sql);
		$query->execute();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row) {
				$array['id_viaje'] = $row->id_viaje;
				$array['salida'] = $row->salida;
				$array['procesar'] = true;
			}
		}else{
			$array['procesar'] = false;
		}
		return $array;
	}
	function unidadenSitio($numCordon,$base){
		$sql ="
			SELECT
				cr_cordon.id_cordon,
				cr_cordon.id_operador_unidad,
				cr_cordon.id_episodio
			FROM
				cr_cordon
			WHERE
				cr_cordon.id_base = $base
			AND (
				cr_cordon.cat_statuscordon = 113
				OR cr_cordon.cat_statuscordon = 115
			)
			ORDER BY
				cr_cordon.cat_statuscordon DESC,
				cr_cordon.id_cordon ASC
			LIMIT 0,
			 2
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$array = array();
		;
		if($query->rowCount()>=1){
			$num = 1;
			foreach ($query->fetchAll() as $row) {
				if($num == $numCordon){
					$array['id_operador_unidad'] = $row->id_operador_unidad;
					$array['id_episodio'] = $row->id_episodio;
					$array['id_cordon'] = $row->id_cordon;
					$array['procesar'] = true;
				}
				$num++;
			}
		}else{
			$array['procesar'] = false;
		}
		return $array;	
	}
	function unidadalAire($id_operador_unidad){
		$sql ="
			SELECT
				cre.id_episodio
			FROM
				cr_operador_unidad AS crou
			INNER JOIN cr_operador AS cro ON crou.id_operador = cro.id_operador
			INNER JOIN cr_episodios AS cre ON cre.id_operador = cro.id_operador
			WHERE
				crou.id_operador_unidad = $id_operador_unidad
			AND cre.fin IS NULL
			AND cre.tiempo IS NULL
			ORDER BY
				cre.id_episodio DESC
			LIMIT 0,
			 1
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row) {
				$array['id_operador_unidad'] = $id_operador_unidad;
				$array['id_episodio'] = $row->id_episodio;
				$array['id_cordon'] = '';
				$array['procesar'] = true;
			}
		}else{
			$array['procesar'] = false;
		}
		return $array;
	}
	function unidades_formadas($base){
		$sql ="
			SELECT
				crc.id_operador_unidad,
				crc.id_episodio,
				crc.id_cordon
			FROM
				cr_cordon AS crc
			WHERE
				crc.id_base = $base
			AND (
				crc.cat_statuscordon = 113
				OR crc.cat_statuscordon = 115
			)
			ORDER BY
				crc.cat_statuscordon DESC,
				crc.id_cordon ASC
			LIMIT 0,
			 1
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row) {
				$array['id_operador_unidad'] = $row->id_operador_unidad;
				$array['id_episodio'] = $row->id_episodio;
				$array['id_cordon'] = $row->id_cordon;
				$array['procesar'] = true;
			}
		}else{
			$array['procesar'] = false;
		}
		return $array;
	}
	function id_tarifa_cliente($id_cliente){
		$query = "
			SELECT
				tfcl.id_tarifa_cliente
			FROM
				cl_tarifas_clientes AS tfcl
			INNER JOIN cl_clientes AS clc ON tfcl.id_cliente = clc.parent
			WHERE
				clc.id_cliente = $id_cliente
			AND tfcl.cat_statustarifa = 168
			AND tfcl.cat_tipo_tarifa = 189
		";
		$query = $this->db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		$output = '';
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				$output =  $row->id_tarifa_cliente;
			}			
		}
		return $output;
	}
	function insert_viaje($service){
		$id_tarifa_cliente = self::id_tarifa_cliente($service->id_cliente);
		$sql = "
			INSERT INTO `vi_viaje` (
				`id_cliente_origen`,
				`id_tarifa_cliente`,
				`cat_status_viaje`,
				`cat_tiposervicio`,
				`cat_tipo_salida`,
				`cat_tipotemporicidad`,
				`user_alta`,
				`fecha_alta`
			)
			VALUES
				(
					'".$service->id_cliente_origen."',
					'".$id_tarifa_cliente."',
					'170',
					'".$service->cat_tiposervicio."',
					'".$service->cat_tipo_salida."',
					'".$service->temporicidad."',
					'".$_SESSION['id_usuario']."',
					'".date("Y-m-d H:i:s")."'
				);		
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		return $this->db->lastInsertId();		
	}
	function insert_detallesViaje($service){
		$redondo = (isset($service->viaje_redondo))?'1':'0';
		$apartado = (($service->temporicidad)==162)?'1':'0';
		$sql = "
			INSERT INTO `vi_viaje_detalle` (
				`id_viaje`,
				`fecha_solicitud`,
				`fecha_requerimiento`,
				`redondo`,
				`apartado`,
				`observaciones`,
				`msgPaqArray`,
				`user_alta`,
				`fecha_alta`
			)
			VALUES
				(
					'".$service->id_viaje."',
					'".date("Y-m-d H:i:s")."',
					'".$service->fecha_hora."',
					'".$redondo."',
					'".$apartado."',
					'".$service->observaciones."',
					'".$service->msgPaqArray."',
					'".$_SESSION['id_usuario']."',
					'".date("Y-m-d H:i:s")."'
				);		
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	function insert_formaPago($service){
		$sql = "
			INSERT INTO `vi_viaje_formapago` (
				`id_viaje`,
				`cat_formapago`,
				`user_alta`,
				`fecha_alta`
			)
			VALUES
				(
					'".$service->id_viaje."',
					'".$service->forma_pago."',
					'".$_SESSION['id_usuario']."',
					'".date("Y-m-d H:i:s")."'
				);		
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	function insert_viajeDestino($service){
		$sql = "
			INSERT INTO `it_viaje_destino` (
				`id_viaje`,
				`id_cliente_destino`,
				`user_alta`,
				`fecha_alta`
			)
			VALUES
				(
					'".$service->id_viaje."',
					'".$service->id_cliente_destino."',
					'".$_SESSION['id_usuario']."',
					'".date("Y-m-d H:i:s")."'
				);		
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	function insert_viajeClientes($id_viaje,$id_cliente){
		$sql = "
			INSERT INTO `vi_viaje_clientes` (
				`id_viaje`,
				`id_cliente`,
				`user_alta`,
				`fecha_alta`
			)
			VALUES
				(
					'".$id_viaje."',
					'".$id_cliente."',
					'".$_SESSION['id_usuario']."',
					'".date("Y-m-d H:i:s")."'
				);		
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	function busqueda_usuario($search){
		$query = "
			SELECT
				client.id_cliente,
				cat1.etiqueta,
				(
					SELECT
						nombre
					FROM
						cl_clientes AS client_prent
					WHERE
						id_cliente = client.parent
				) AS parent,
				client.nombre
			FROM
				cl_clientes AS client
			INNER JOIN cm_catalogo AS cat1 ON client.cat_tipocliente = cat1.id_cat		
			WHERE
				client.nombre LIKE lower('%".$search."%')
			ORDER BY
				client.nombre ASC
		";
		$query = $this->db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		$output = array('suggestions'=>array());
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				$output['suggestions'][] = array(
					'value'=> $row->etiqueta . ' > ' . $row->parent . ' > ' . $row->nombre,
					'etiqueta'=>$row->etiqueta,
					'parent'=>$row->parent,
					'nombre'=>$row->nombre,
					'id'=>$row->id_cliente
				);
			}			
		}
		return json_encode($output);
	}	
	function caducarGps(){
		$desde = mktime(date("H")-12,  date("i"), 0,  date("m")  , date("d"), date("Y"));
		$desde = date("Y-m-d H:i:s", $desde );
		$qry = "
			DELETE
			FROM
			gps
			WHERE
			`timestamp` <= '".$desde."' 
		";
		$query = $this->db->prepare($qry);
		$query->execute();
	}
	function c1orc2($id_operador){
		$qry = "
			SELECT
				syc.estado1
			FROM
				cr_operador_unidad AS oun
			INNER JOIN cr_sync AS syc ON oun.sync_token = syc.token
			WHERE
				oun.id_operador = $id_operador
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$data = $query->fetchAll();
		$return =  false;
		if($query->rowCount()>=1){
			foreach ($data as $row) {
				$return = $row->estado1;
			}
		}
		return $return;
	}
	function getSolicitudF14Activa($id_operador){
		$qry = "
			SELECT
				csr.id_sync_ride
			FROM
				cr_sync_ride AS csr
			INNER JOIN cr_operador_unidad AS cou ON csr.id_operador_unidad = cou.id_operador_unidad
			WHERE
				csr.cat_cve_store = 132
			AND csr.procesado = 0
			AND cou.id_operador = $id_operador
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$return = true;
		}else{
			$return = false;
		}
		return $return;
	}
	function formadoenBase($id_operador,$base){
		$qry = "
			SELECT
				crc.id_cordon
			FROM
				cr_cordon AS crc
			INNER JOIN cr_operador_unidad AS cro ON crc.id_operador_unidad = cro.id_operador_unidad
			WHERE
				cro.id_operador = $id_operador
			AND (
				crc.cat_statuscordon = 113
				OR crc.cat_statuscordon = 115
			)
			AND crc.id_base = $base
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$return = true;
		}else{
			$return = false;
		}
		return $return;
	}
	function getActiveSession($id_operador){
		$qry = "
			SELECT
				fwu.usuario AS usuario
			FROM
				fw_login AS fwl
			INNER JOIN fw_usuarios AS fwu ON fwl.id_usuario = fwu.id_usuario
			INNER JOIN cr_operador AS crop ON crop.id_usuario = fwu.id_usuario
			WHERE
				fwl.`open` = 1
			AND crop.id_operador = $id_operador
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$return = true;
		}else{
			$return = false;
		}
		return $return;
	}
	function gatDataOperador($id_operador){
		$qry = "
			SELECT
			cr_numeq.num,
			 concat(
				fw_usuarios.nombres,
				' ',
				fw_usuarios.apellido_paterno,
				' ',
				fw_usuarios.apellido_materno
			) AS nombre
			FROM
			cr_operador
			INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = cr_operador.id_operador
			INNER JOIN cr_numeq ON cr_operador_numeq.id_numeq = cr_numeq.id_numeq
			INNER JOIN fw_usuarios ON cr_operador.id_usuario = fw_usuarios.id_usuario
			WHERE
			cr_operador.id_operador = $id_operador
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$data = $query->fetchAll();
		$output = array();
		if($query->rowCount()>=1){
			foreach ($data as $row) {
				$output['num'] = $row->num; 
				$output['nombre'] = $row->nombre; 
			}
		}
		return $output;
	}
	function delivery_stat($id_mensaje){
		$qry = "
			SELECT
				cr_mensajes.`read`
			FROM
				cr_mensajes
			WHERE
			cr_mensajes.id_mensaje = $id_mensaje
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				echo $row->read;
			}
		}
	}
	function mapearCordon($base){
		$qry = "
			SELECT
				crn.num AS numeq,
				cro.id_operador AS id_operador,
				crc.id_operador_unidad AS id_operador_unidad,
				crc.llegada AS llegada
			FROM
			cr_cordon AS crc
			INNER JOIN cr_operador_unidad AS crou ON crc.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_operador AS cro ON crou.id_operador = cro.id_operador
			INNER JOIN cr_operador_numeq AS cron ON cron.id_operador = cro.id_operador
			INNER JOIN cr_numeq AS crn ON cron.id_numeq = crn.id_numeq
			WHERE
				crc.id_base = $base
			AND (
				crc.cat_statuscordon = 113
				OR crc.cat_statuscordon = 115
			)	
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$unitState = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$unitState[$num]['numeq'] = $row->numeq;
				$unitState[$num]['id_operador'] = $row->id_operador;
				$unitState[$num]['id_operador_unidad'] = $row->id_operador_unidad;
				$unitState[$num]['llegada'] = $row->llegada;
				$num++;
			}
		}
		return $unitState;	
	}
	function enC1(){
		$qry = "
			SELECT
				cr_numeq.num AS numeq,
				oun.id_operador AS id_operador,
				oun.id_operador_unidad,
				syc.serie
			FROM
				cr_operador_unidad AS oun
			INNER JOIN cr_operador_numeq AS crone ON oun.id_operador = crone.id_operador
			INNER JOIN cr_numeq ON crone.id_numeq = cr_numeq.id_numeq
			INNER JOIN cr_sync AS syc ON oun.sync_token = syc.token
			WHERE
				syc.estado1 = 'C1'	
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$unitState = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$unitState[$num]['numeq'] = $row->numeq;
				$unitState[$num]['id_operador'] = $row->id_operador;
				$unitState[$num]['id_operador_unidad'] = $row->id_operador_unidad;
				$unitState[$num]['serie'] = $row->serie;
				$num++;
			}
		}
		return $unitState;		
	}
	function enC8(){
		$qry = "
			SELECT
				cr_numeq.num AS numeq,
				oun.id_operador AS id_operador,
				oun.id_operador_unidad,
				syc.serie
			FROM
				cr_operador_unidad AS oun
			INNER JOIN cr_operador_numeq AS crone ON oun.id_operador = crone.id_operador
			INNER JOIN cr_numeq ON crone.id_numeq = cr_numeq.id_numeq
			INNER JOIN cr_sync AS syc ON oun.sync_token = syc.token
			WHERE
				syc.estado1 = 'C8'	
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$unitState = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$unitState[$num]['numeq'] = $row->numeq;
				$unitState[$num]['id_operador'] = $row->id_operador;
				$unitState[$num]['id_operador_unidad'] = $row->id_operador_unidad;
				$unitState[$num]['serie'] = $row->serie;
				$num++;
			}
		}
		return $unitState;		
	}
	function enA11(){
		$qry = "
			SELECT
				cr_numeq.num AS numeq,
				oun.id_operador AS id_operador,
				oun.id_operador_unidad,
				syc.serie
			FROM
				cr_operador_unidad AS oun
			INNER JOIN cr_operador_numeq AS crone ON oun.id_operador = crone.id_operador
			INNER JOIN cr_numeq ON crone.id_numeq = cr_numeq.id_numeq
			INNER JOIN cr_sync AS syc ON oun.sync_token = syc.token
			WHERE
				syc.estado1 = 'A11'	
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$unitState = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$unitState[$num]['numeq'] = $row->numeq;
				$unitState[$num]['id_operador'] = $row->id_operador;
				$unitState[$num]['id_operador_unidad'] = $row->id_operador_unidad;
				$unitState[$num]['serie'] = $row->serie;
				$num++;
			}
		}
		return $unitState;		
	}
	function getTokenStatusBase($id_base){
		$sql = "
			SELECT
				token_status
			FROM
				cr_bases
			WHERE
				id_base = ".$id_base."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$token = $row->token_status;
			}
		}
		return $token;		
	}
	function tokenStatusBase($id_base,$token){
		$sql = "
			UPDATE cr_bases
			SET 
			 token_status 	= '".$token."'
			WHERE
				id_base = ".$id_base."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}	
	function cordon_hash($base){
		$sql ="
			SELECT
				crc.id_operador_unidad AS id_operador_unidad
			FROM
				cr_cordon AS crc
			WHERE
				crc.id_base = $base
			AND (
				crc.cat_statuscordon = 113
				OR crc.cat_statuscordon = 115
			)
			ORDER BY
				crc.cat_statuscordon DESC,
				crc.id_cordon ASC			
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$output = '';
		$token = self::getTokenStatusBase($base);
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$output .=  $row->id_operador_unidad;
			}
			$output = md5($output);
			$change = ($output != $token)?true:false;
		}else{
			$output = md5(0);
			$change = ($output != $token)?true:false;
		}
		if($change){self::tokenStatusBase($base,$output);}
		return $change;
	}
	function getTokenStatusViaje($status){
		$sql = "
			SELECT
				fw_config.`data` as token
			FROM
				fw_config
			WHERE
				fw_config.valor = '".$status."'
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$token = $row->token;
			}
		}
		return $token;		
	}
	function tokenStatusViaje($status,$output){
		$sql = "
			UPDATE `fw_config`
			SET `data` = '".$output."'
			WHERE
				`valor` = '".$status."'
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	function serv_cve_hash($status){
		$sql ="
			SELECT
				vi_viaje.id_viaje,
				vi_viaje.id_operador_unidad
			FROM
				vi_viaje
			WHERE
				vi_viaje.cat_status_viaje = $status
			ORDER BY
				vi_viaje.id_viaje DESC		
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$output = '';
		$token = self::getTokenStatusViaje($status);
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$output .=  $row->id_viaje.self::getCurrentCveOperador($row->id_operador_unidad);
			}
			$output = md5($output);
			$change = ($output != $token)?true:false;
		}else{
			$output = md5(0);
			$change = ($output != $token)?true:false;
		}
		if($change){self::tokenStatusViaje($status,$output);}
		return $change;
	}	
	function servicio_hash($status){
		$sql ="
			SELECT
				vi_viaje.id_viaje,
				vi_viaje.id_operador_unidad
			FROM
				vi_viaje
			WHERE
				vi_viaje.cat_status_viaje = $status
			ORDER BY
				vi_viaje.id_viaje DESC		
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$output = '';
		$token = self::getTokenStatusViaje($status);
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$output .=  $row->id_viaje;
			}
			$output = md5($output);
			$change = ($output != $token)?true:false;
		}else{
			$output = md5(0);
			$change = ($output != $token)?true:false;
		}
		if($change){self::tokenStatusViaje($status,$output);}
		return $change;
	}
	function guardar_mensaje($array){
		$sql = "
			INSERT INTO cr_mensajes (
				`id_operador`,
				`mensaje`,
				`read`,
				`user_alta`,
				`fecha_alta`
			)
			VALUES
				(
					'".$array['id_operador_msg_mod']."',
					'".$array['mensaje']."',
					'0',
					'".$_SESSION['id_usuario']."',
					'".date("Y-m-d H:i:s")."'
				)
		";
		$query = $this->db->prepare($sql);
		$query_resp = $query->execute();
		if($query_resp){
			$respuesta = array('resp' => true , 'id_mensaje' => $this->db->lastInsertId());
		}else{
			$respuesta = array('resp' => false , 'mensaje' => 'Error en el sistema.' , 'error' => 'Error al insertar registro.' );
		}
		print json_encode($respuesta);
	}
	function control_get($array){
		ini_set('memory_limit', '256M');
		$table = 'cr_operador_unidad AS oun';
		$primaryKey = 'id_operador_unidad';
		$columns = array(
			array(
				'db' => 'cr_numeq.num as numeq',
				'dbj' => 'cr_numeq.num',
				'real' => 'cr_numeq.num',
				'alias' => 'numeq',
				'typ' => 'int',
				'dt' => 0
			),
			array( 
				'db' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno) AS nombre',
				'dbj' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno)',
				'real' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno)',
				'alias' => 'nombre',
				'typ' => 'int',
				'dt' => 1				
			),
			array( 
				'db' => 'mk.marca AS marca',
				'dbj' => 'mk.marca',	
				'alias' => 'marca',
				'real' => 'mk.marca',
				'typ' => 'txt',
				'dt' => 2
			),
			array( 
				'db' => '`mod`.modelo AS modelo',
				'dbj' => '`mod`.modelo',				
				'real' => '`mod`.modelo',
				'alias' => 'modelo',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'uni.color AS color',
				'dbj' => 'uni.color',
				'real' => 'uni.color',
				'alias' => 'color',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'oun.id_operador_unidad',
				'dbj' => 'oun.id_operador_unidad',
				'real' => 'oun.id_operador_unidad',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 5			
			),
			array( 
				'db' => 'oun.id_operador as id_operador',
				'dbj' => 'oun.id_operador',
				'real' => 'oun.id_operador',
				'alias' => 'id_operador',
				'typ' => 'int',
				'dt' => 6			
			)
		);
		$render_table = new acciones_control;
		$inner = '
			INNER JOIN cr_operador AS ope ON oun.id_operador = ope.id_operador
			INNER JOIN fw_usuarios AS usu ON ope.id_usuario = usu.id_usuario
			INNER JOIN cr_unidades AS uni ON oun.id_unidad = uni.id_unidad
			INNER JOIN cr_modelos AS `mod` ON uni.id_modelo = `mod`.id_modelo
			INNER JOIN cr_marcas AS mk ON uni.id_marca = mk.id_marca
			INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = ope.id_operador
			INNER JOIN cr_numeq ON cr_operador_numeq.id_numeq = cr_numeq.id_numeq
			INNER JOIN cr_sync AS syc ON oun.sync_token = syc.token
		';
		$where = "
			syc.estado1 = 'C1' and (syc.estado2 = 'A10' OR syc.estado2 = 'F13' OR syc.estado2 = 'A11' OR syc.estado2 = 'C8')
			AND 
			ope.cat_statusoperador = 8
		";
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
	function inactivos_get($array){
		ini_set('memory_limit', '256M');				
		$table = 'cr_operador_unidad AS oun';
		$primaryKey = 'id_operador_unidad';
		$columns = array(
			array( 
				'db' => 'cr_numeq.num as numeq',
				'dbj' => 'cr_numeq.num',
				'real' => 'cr_numeq.num',
				'alias' => 'numeq',
				'typ' => 'int',
				'dt' => 0
			),
			array( 
				'db' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno) AS nombre',
				'dbj' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno)',
				'real' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno)',
				'alias' => 'nombre',
				'typ' => 'int',
				'dt' => 1				
			),
			array( 
				'db' => 'mk.marca AS marca',
				'dbj' => 'mk.marca',	
				'alias' => 'marca',
				'real' => 'mk.marca',
				'typ' => 'txt',
				'dt' => 2
			),
			array( 
				'db' => '`mod`.modelo AS modelo',
				'dbj' => '`mod`.modelo',				
				'real' => '`mod`.modelo',
				'alias' => 'modelo',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'uni.color AS color',
				'dbj' => 'uni.color',
				'real' => 'uni.color',
				'alias' => 'color',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'oun.id_operador_unidad',
				'dbj' => 'oun.id_operador_unidad',
				'real' => 'oun.id_operador_unidad',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 5			
			)
		);
		$render_table = new acciones_inactivos;
		$inner = '
			INNER JOIN cr_operador AS ope ON oun.id_operador = ope.id_operador
			INNER JOIN fw_usuarios AS usu ON ope.id_usuario = usu.id_usuario
			INNER JOIN cr_unidades AS uni ON oun.id_unidad = uni.id_unidad
			INNER JOIN cr_modelos AS `mod` ON uni.id_modelo = `mod`.id_modelo
			INNER JOIN cr_marcas AS mk ON uni.id_marca = mk.id_marca
			INNER JOIN cr_operador_numeq AS crone ON crone.id_operador = ope.id_operador
			INNER JOIN cr_numeq ON crone.id_numeq = cr_numeq.id_numeq
			INNER JOIN cr_sync AS syc ON oun.sync_token = syc.token
		';
		$where = "
			syc.estado1 = 'C2'
			AND 
			ope.cat_statusoperador = 8
		";
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
	function suspendidas_get($array){
		ini_set('memory_limit', '256M');				
		$table = 'cr_operador_unidad AS oun';
		$primaryKey = 'id_operador_unidad';
		$columns = array(
			array( 
				'db' => 'cr_numeq.num as numeq',
				'dbj' => 'cr_numeq.num',
				'real' => 'cr_numeq.num',
				'alias' => 'numeq',
				'typ' => 'int',
				'dt' => 0
			),
			array( 
				'db' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno) AS nombre',
				'dbj' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno)',
				'real' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno)',
				'alias' => 'nombre',
				'typ' => 'int',
				'dt' => 1				
			),
			array( 
				'db' => 'mk.marca AS marca',
				'dbj' => 'mk.marca',	
				'alias' => 'marca',
				'real' => 'mk.marca',
				'typ' => 'txt',
				'dt' => 2
			),
			array( 
				'db' => '`mod`.modelo AS modelo',
				'dbj' => '`mod`.modelo',				
				'real' => '`mod`.modelo',
				'alias' => 'modelo',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'uni.color AS color',
				'dbj' => 'uni.color',
				'real' => 'uni.color',
				'alias' => 'color',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'oun.id_operador_unidad',
				'dbj' => 'oun.id_operador_unidad',
				'real' => 'oun.id_operador_unidad',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 5			
			)
		);
		$render_table = new acciones_suspendidas;
		$inner = '
			INNER JOIN cr_operador AS ope ON oun.id_operador = ope.id_operador
			INNER JOIN fw_usuarios AS usu ON ope.id_usuario = usu.id_usuario
			INNER JOIN cr_unidades AS uni ON oun.id_unidad = uni.id_unidad
			INNER JOIN cr_modelos AS `mod` ON uni.id_modelo = `mod`.id_modelo
			INNER JOIN cr_marcas AS mk ON uni.id_marca = mk.id_marca
			INNER JOIN cr_operador_numeq AS crone ON crone.id_operador = ope.id_operador
			INNER JOIN cr_numeq ON crone.id_numeq = cr_numeq.id_numeq
			INNER JOIN cr_sync AS syc ON oun.sync_token = syc.token
		';
		$where = "
			ope.cat_statusoperador = 10
		";
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
	function unidades_a11_get($array){
		ini_set('memory_limit', '256M');				
		$table = 'cr_operador_unidad AS oun';
		$primaryKey = 'id_operador_unidad';
		$columns = array(
			array( 
				'db' => 'cr_numeq.num as numeq',
				'dbj' => 'cr_numeq.num',
				'real' => 'cr_numeq.num',
				'alias' => 'numeq',
				'typ' => 'int',
				'dt' => 0
			),
			array( 
				'db' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno) AS nombre',
				'dbj' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno)',
				'real' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno)',
				'alias' => 'nombre',
				'typ' => 'int',
				'dt' => 1				
			),
			array( 
				'db' => 'mk.marca AS marca',
				'dbj' => 'mk.marca',
				'alias' => 'marca',
				'real' => 'mk.marca',
				'typ' => 'txt',
				'dt' => 2
			),
			array( 
				'db' => '`mod`.modelo AS modelo',
				'dbj' => '`mod`.modelo',				
				'real' => '`mod`.modelo',
				'alias' => 'modelo',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'uni.color AS color',
				'dbj' => 'uni.color',
				'real' => 'uni.color',
				'alias' => 'color',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'oun.id_operador_unidad',
				'dbj' => 'oun.id_operador_unidad',
				'real' => 'oun.id_operador_unidad',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 5			
			),
			array( 
				'db' => 'oun.id_operador',
				'dbj' => 'oun.id_operador',
				'real' => 'oun.id_operador',
				'typ' => 'int',
				'dt' => 6			
			)
		);
		$render_table = new acciones_unidades_a11;
		$inner = '
			INNER JOIN cr_operador AS ope ON oun.id_operador = ope.id_operador
			INNER JOIN fw_usuarios AS usu ON ope.id_usuario = usu.id_usuario
			INNER JOIN cr_unidades AS uni ON oun.id_unidad = uni.id_unidad
			INNER JOIN cr_modelos AS `mod` ON uni.id_modelo = `mod`.id_modelo
			INNER JOIN cr_marcas AS mk ON uni.id_marca = mk.id_marca
			INNER JOIN cr_operador_numeq AS crone ON crone.id_operador = ope.id_operador
			INNER JOIN cr_numeq ON crone.id_numeq = cr_numeq.id_numeq
			INNER JOIN cr_sync AS syc ON oun.sync_token = syc.token
		';
		$where = "
			syc.estado1 = 'C1'
			AND 
			syc.estado2 = 'A11'
			AND
			ope.cat_statusoperador = 8
		";
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
	function activos_get($array){
		ini_set('memory_limit', '256M');				
		$table = 'cr_operador_unidad AS oun';
		$primaryKey = 'id_operador_unidad';
		$columns = array(
			array( 
				'db' => 'cr_numeq.num as numeq',
				'dbj' => 'cr_numeq.num',
				'real' => 'cr_numeq.num',
				'alias' => 'numeq',
				'typ' => 'int',
				'dt' => 0
			),
			array( 
				'db' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno) AS nombre',
				'dbj' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno)',
				'real' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno)',
				'alias' => 'nombre',
				'typ' => 'int',
				'dt' => 1				
			),
			array(
				'db' => 'mk.marca AS marca',
				'dbj' => 'mk.marca',	
				'alias' => 'marca',
				'real' => 'mk.marca',
				'typ' => 'txt',
				'dt' => 2
			),
			array( 
				'db' => '`mod`.modelo AS modelo',
				'dbj' => '`mod`.modelo',				
				'real' => '`mod`.modelo',
				'alias' => 'modelo',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'uni.color AS color',
				'dbj' => 'uni.color',
				'real' => 'uni.color',
				'alias' => 'color',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'oun.id_operador AS aid_operador',
				'dbj' => 'oun.id_operador',
				'real' => 'oun.id_operador',
				'alias' => 'aid_operador',
				'typ' => 'int',
				'dt' => 5			
			),
			array( 
				'db' => 'oun.id_operador_unidad',
				'dbj' => 'oun.id_operador_unidad',
				'real' => 'oun.id_operador_unidad',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 6			
			)
		);
		$render_table = new acciones_activos;
		$inner = '
			INNER JOIN cr_operador AS ope ON oun.id_operador = ope.id_operador
			INNER JOIN fw_usuarios AS usu ON ope.id_usuario = usu.id_usuario
			INNER JOIN cr_unidades AS uni ON oun.id_unidad = uni.id_unidad
			INNER JOIN cr_modelos AS `mod` ON uni.id_modelo = `mod`.id_modelo
			INNER JOIN cr_marcas AS mk ON uni.id_marca = mk.id_marca
			INNER JOIN cr_operador_numeq AS crone ON crone.id_operador = ope.id_operador
			INNER JOIN cr_numeq ON crone.id_numeq = cr_numeq.id_numeq
			INNER JOIN cr_sync AS syc ON oun.sync_token = syc.token
		';
		$where = "
			syc.estado1 = 'C1'
			AND 
			ope.cat_statusoperador = 8
		";
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
	function cordon_get($array, $base){
		ini_set('memory_limit', '256M');				
		$table = 'cr_cordon AS crc';
		$primaryKey = 'id_cordon';
		$columns = array(
			array( 
				'db' => 'crc.id_operador_unidad AS id_operador_unidad',
				'dbj' => 'crc.id_operador_unidad',
				'real' => 'crc.id_operador_unidad',
				'alias' => 'id_operador_unidad',
				'typ' => 'int',
				'turno' => true,
				'base' => $base,
				'dt' => 0			
			),
			array( 
				'db' => 'crn.num as num',
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
				'db' => 'crm.marca AS marca',
				'dbj' => 'crm.marca',
				'real' => 'crm.marca',
				'alias' => 'marca',				
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'crmo.modelo AS modelo',
				'dbj' => 'crmo.modelo',				
				'real' => 'crmo.modelo',
				'alias' => 'modelo',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'cru.color AS color',
				'dbj' => 'cru.color',
				'real' => 'cru.color',
				'alias' => 'color',
				'typ' => 'txt',
				'dt' => 5
			),
			array( 
				'db' => 'crc.llegada AS llegada',
				'dbj' => 'crc.llegada',
				'real' => 'crc.llegada',
				'alias' => 'llegada',
				'typ' => 'txt',
				'time_stat' => true,
				'dt' => 6			
			),
			array( 
				'db' => 'crc.id_cordon AS cordon',
				'dbj' => 'crc.id_cordon',
				'real' => 'crc.id_cordon',
				'alias' => 'cordon',
				'typ' => 'int',
				'acciones' => true,
				'base' => $base,
				'dt' => 7			
			),
			array( 
				'db' => 'cro.id_operador AS id_operador',
				'dbj' => 'cro.id_operador',
				'real' => 'cro.id_operador',
				'alias' => 'id_operador',
				'typ' => 'int',
				'dt' => 8			
			)
		);
		$inner = '
			INNER JOIN cr_operador_unidad AS crou ON crc.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_operador AS cro ON crou.id_operador = cro.id_operador
			INNER JOIN fw_usuarios AS fwu ON cro.id_usuario = fwu.id_usuario
			INNER JOIN cr_unidades AS cru ON crou.id_unidad = cru.id_unidad
			INNER JOIN cr_marcas AS crm ON cru.id_marca = crm.id_marca
			INNER JOIN cr_modelos AS crmo ON cru.id_modelo = crmo.id_modelo
			INNER JOIN cr_operador_numeq AS cron ON cron.id_operador = cro.id_operador
			INNER JOIN cr_numeq AS crn ON cron.id_numeq = crn.id_numeq
		';
		$where = "
				crc.id_base = $base
			AND (
				crc.cat_statuscordon = 113
				OR crc.cat_statuscordon = 115
			)

		";
		$orden = "
			ORDER BY
				crc.cat_statuscordon DESC,
				crc.id_cordon ASC
		";
		$render_table = new acciones_cordon;
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
	}
	function servicios_asignados($array){
		ini_set('memory_limit', '256M');				
		$table = 'vi_viaje AS viv';
		$primaryKey = 'id_viaje';
		$columns = array(
			array( 
				'db' => 'viv.id_viaje as id_viaje',
				'dbj' => 'viv.id_viaje',
				'real' => 'viv.id_viaje',
				'alias' => 'id_viaje',
				'typ' => 'int',
				'dt' => 0			
			),
			array( 
				'db' => 'viv.cat_status_viaje as status_viaje',
				'dbj' => 'viv.cat_status_viaje',
				'real' => 'viv.cat_status_viaje',
				'alias' => 'status_viaje',
				'typ' => 'int',
				'dt' => 1
			),
			array( 
				'db' => 'vcd.fecha_solicitud as solicitud',
				'dbj' => 'vcd.fecha_solicitud',
				'real' => 'vcd.fecha_solicitud',
				'alias' => 'solicitud',
				'typ' => 'int',
				'dt' => 2
			),
			array( 
				'db' => 'clc.nombre AS cliente',
				'dbj' => 'clc.nombre',
				'real' => 'clc.nombre',
				'alias' => 'cliente',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'clp.nombre AS empresa',
				'dbj' => 'clp.nombre',
				'real' => 'clp.nombre',
				'alias' => 'empresa',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'service.etiqueta AS servicio',
				'dbj' => 'service.etiqueta',
				'real' => 'service.etiqueta',
				'alias' => 'servicio',
				'typ' => 'txt',
				'dt' => 5
			),
			array( 
				'db' => 'tempo.etiqueta AS temporicidad',
				'dbj' => 'tempo.etiqueta',
				'real' => 'tempo.etiqueta',
				'alias' => 'temporicidad',
				'typ' => 'txt',
				'dt' => 6
			),
			array( 
				'db' => 'num_eq.num AS numq',
				'dbj' => 'num_eq.num',
				'real' => 'num_eq.num',
				'alias' => 'numq',
				'typ' => 'int',
				'dt' => 7
			),
			array( 
				'db' => 'vcl.id_cliente AS id_cliente',
				'dbj' => 'vcl.id_cliente',
				'real' => 'vcl.id_cliente',
				'alias' => 'id_cliente',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 8
			),
			array( 
				'db' => 'cr_operador_unidad.id_operador_unidad as id_operador_unidad',
				'dbj' => 'cr_operador_unidad.id_operador_unidad',
				'real' => 'cr_operador_unidad.id_operador_unidad',
				'alias' => 'id_operador_unidad',
				'typ' => 'int',
				'dt' => 9
			)			
		);
		$inner = '
			INNER JOIN vi_viaje_detalle AS vcd ON vcd.id_viaje = viv.id_viaje
			INNER JOIN vi_viaje_clientes AS vcl ON vcl.id_viaje = viv.id_viaje
			INNER JOIN cl_clientes AS clc ON vcl.id_cliente = clc.id_cliente
			INNER JOIN cl_clientes AS clp ON clc.parent = clp.id_cliente
			INNER JOIN cm_catalogo AS service ON viv.cat_tiposervicio = service.id_cat
			INNER JOIN cm_catalogo AS tempo ON viv.cat_tipotemporicidad = tempo.id_cat
			INNER JOIN cr_operador_unidad ON viv.id_operador_unidad = cr_operador_unidad.id_operador_unidad
			INNER JOIN cr_operador ON cr_operador_unidad.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador.id_operador = cr_operador_numeq.id_operador
			INNER JOIN cr_numeq AS num_eq ON cr_operador_numeq.id_numeq = num_eq.id_numeq	
		';
		$where = '
			viv.cat_status_viaje = 179
			AND
			viv.cat_tipotemporicidad = 184
		';
		$orden = '
			GROUP BY
				viv.id_viaje		
			ORDER BY
				viv.id_viaje DESC		
		';
		$render_table = new acciones_asignados;
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
	}
	function servicios_enProceso($array){
		ini_set('memory_limit', '256M');				
		$table = 'vi_viaje AS viv';
		$primaryKey = 'id_viaje';
		$columns = array(
			array( 
				'db' => 'viv.id_viaje as id_viaje',
				'dbj' => 'viv.id_viaje',
				'real' => 'viv.id_viaje',
				'alias' => 'id_viaje',
				'typ' => 'int',
				'dt' => 0			
			),
			array( 
				'db' => 'viv.cat_status_viaje as status_viaje',
				'dbj' => 'viv.cat_status_viaje',
				'real' => 'viv.cat_status_viaje',
				'alias' => 'status_viaje',
				'typ' => 'int',
				'dt' => 1
			),
			array( 
				'db' => 'vcd.fecha_solicitud as solicitud',
				'dbj' => 'vcd.fecha_solicitud',
				'real' => 'vcd.fecha_solicitud',
				'alias' => 'solicitud',
				'typ' => 'int',
				'dt' => 2
			),
			array( 
				'db' => 'clc.nombre AS cliente',
				'dbj' => 'clc.nombre',
				'real' => 'clc.nombre',
				'alias' => 'cliente',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'clp.nombre AS empresa',
				'dbj' => 'clp.nombre',
				'real' => 'clp.nombre',
				'alias' => 'empresa',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'service.etiqueta AS servicio',
				'dbj' => 'service.etiqueta',
				'real' => 'service.etiqueta',
				'alias' => 'servicio',
				'typ' => 'txt',
				'dt' => 5
			),
			array( 
				'db' => 'tempo.etiqueta AS temporicidad',
				'dbj' => 'tempo.etiqueta',
				'real' => 'tempo.etiqueta',
				'alias' => 'temporicidad',
				'typ' => 'txt',
				'dt' => 6
			),
			array( 
				'db' => 'num_eq.num AS numq',
				'dbj' => 'num_eq.num',
				'real' => 'num_eq.num',
				'alias' => 'numq',
				'typ' => 'int',
				'dt' => 7
			),			
			array( 
				'db' => 'vcl.id_cliente AS id_cliente',
				'dbj' => 'vcl.id_cliente',
				'real' => 'vcl.id_cliente',
				'alias' => 'id_cliente',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 8
			)		
		);
		$inner = '
			INNER JOIN vi_viaje_detalle AS vcd ON vcd.id_viaje = viv.id_viaje
			INNER JOIN vi_viaje_clientes AS vcl ON vcl.id_viaje = viv.id_viaje
			INNER JOIN cl_clientes AS clc ON vcl.id_cliente = clc.id_cliente
			INNER JOIN cl_clientes AS clp ON clc.parent = clp.id_cliente
			INNER JOIN cm_catalogo AS service ON viv.cat_tiposervicio = service.id_cat
			INNER JOIN cm_catalogo AS tempo ON viv.cat_tipotemporicidad = tempo.id_cat
			INNER JOIN cr_operador_unidad ON viv.id_operador_unidad = cr_operador_unidad.id_operador_unidad
			INNER JOIN cr_operador ON cr_operador_unidad.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador.id_operador = cr_operador_numeq.id_operador
			INNER JOIN cr_numeq AS num_eq ON cr_operador_numeq.id_numeq = num_eq.id_numeq		
		';
		$where = '
			viv.cat_status_viaje = 171
			AND
			viv.cat_tipotemporicidad = 184
		';
		$orden = '
			GROUP BY
				viv.id_viaje		
			ORDER BY
				viv.id_viaje DESC		
		';
		$render_table = new acciones_enproceso;
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
	}
	function servicios_pendientes($array){
		ini_set('memory_limit', '256M');				
		$table = 'vi_viaje AS viv';
		$primaryKey = 'id_viaje';
		$columns = array(
			array( 
				'db' => 'viv.id_viaje as id_viaje',
				'dbj' => 'viv.id_viaje',
				'real' => 'viv.id_viaje',
				'alias' => 'id_viaje',
				'typ' => 'int',
				'dt' => 0			
			),
			array( 
				'db' => 'viv.cat_status_viaje as status_viaje',
				'dbj' => 'viv.cat_status_viaje',
				'real' => 'viv.cat_status_viaje',
				'alias' => 'status_viaje',
				'typ' => 'int',
				'dt' => 1
			),
			array( 
				'db' => 'vcd.fecha_solicitud as solicitud',
				'dbj' => 'vcd.fecha_solicitud',
				'real' => 'vcd.fecha_solicitud',
				'alias' => 'solicitud',
				'typ' => 'int',
				'dt' => 2
			),
			array( 
				'db' => 'clc.nombre AS cliente',
				'dbj' => 'clc.nombre',
				'real' => 'clc.nombre',
				'alias' => 'cliente',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'clp.nombre AS empresa',
				'dbj' => 'clp.nombre',
				'real' => 'clp.nombre',
				'alias' => 'empresa',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'service.etiqueta AS servicio',
				'dbj' => 'service.etiqueta',
				'real' => 'service.etiqueta',
				'alias' => 'servicio',
				'typ' => 'txt',
				'dt' => 5
			),
			array( 
				'db' => 'tempo.etiqueta AS temporicidad',
				'dbj' => 'tempo.etiqueta',
				'real' => 'tempo.etiqueta',
				'alias' => 'temporicidad',
				'typ' => 'txt',
				'dt' => 6
			),
			array( 
				'db' => 'vcl.id_cliente AS id_cliente',
				'dbj' => 'vcl.id_cliente',
				'real' => 'vcl.id_cliente',
				'alias' => 'id_cliente',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 7
			)			
		);
		$inner = '
			INNER JOIN vi_viaje_detalle AS vcd ON vcd.id_viaje = viv.id_viaje
			INNER JOIN vi_viaje_clientes AS vcl ON vcl.id_viaje = viv.id_viaje
			INNER JOIN cl_clientes AS clc ON vcl.id_cliente = clc.id_cliente
			INNER JOIN cl_clientes AS clp ON clc.parent = clp.id_cliente
			INNER JOIN cm_catalogo AS service ON viv.cat_tiposervicio = service.id_cat
			INNER JOIN cm_catalogo AS tempo ON viv.cat_tipotemporicidad = tempo.id_cat				
		';
		$where = '
			(viv.cat_status_viaje = 170 OR viv.cat_status_viaje = 188)
			AND
			viv.cat_tipotemporicidad = 184
		';
		$orden = '
			GROUP BY
				viv.id_viaje		
			ORDER BY
				viv.id_viaje DESC
		';
		$render_table = new acciones_pendientes;
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
	}
	function programados_rojo($array){
		ini_set('memory_limit', '256M');				
		$table = 'vi_viaje AS viv';
		$primaryKey = 'id_viaje';
		$columns = array(
			array( 
				'db' => 'viv.id_viaje as id_viaje',
				'dbj' => 'viv.id_viaje',
				'real' => 'viv.id_viaje',
				'alias' => 'id_viaje',
				'typ' => 'int',
				'dt' => 0			
			),
			array( 
				'db' => 'viv.cat_status_viaje as status_viaje',
				'dbj' => 'viv.cat_status_viaje',
				'real' => 'viv.cat_status_viaje',
				'alias' => 'status_viaje',
				'typ' => 'int',
				'dt' => 1
			),
			array( 
				'db' => 'vcd.fecha_requerimiento as fecha_requerimiento',
				'dbj' => 'vcd.fecha_requerimiento',
				'real' => 'vcd.fecha_requerimiento',
				'alias' => 'fecha_requerimiento',
				'typ' => 'int',
				'dt' => 2
			),
			array( 
				'db' => 'clc.nombre AS cliente',
				'dbj' => 'clc.nombre',
				'real' => 'clc.nombre',
				'alias' => 'cliente',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'clp.nombre AS empresa',
				'dbj' => 'clp.nombre',
				'real' => 'clp.nombre',
				'alias' => 'empresa',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'service.etiqueta AS servicio',
				'dbj' => 'service.etiqueta',
				'real' => 'service.etiqueta',
				'alias' => 'servicio',
				'typ' => 'txt',
				'dt' => 5
			),
			array( 
				'db' => 'tempo.etiqueta AS temporicidad',
				'dbj' => 'tempo.etiqueta',
				'real' => 'tempo.etiqueta',
				'alias' => 'temporicidad',
				'typ' => 'txt',
				'dt' => 6
			),
			array( 
				'db' => 'num_eq.num AS numq',
				'dbj' => 'num_eq.num',
				'real' => 'num_eq.num',
				'alias' => 'numq',
				'typ' => 'int',
				'dt' => 7
			),				
			array( 
				'db' => 'vcl.id_cliente AS id_cliente',
				'dbj' => 'vcl.id_cliente',
				'real' => 'vcl.id_cliente',
				'alias' => 'id_cliente',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 8
			)
		);
		$inner = '
			INNER JOIN vi_viaje_detalle AS vcd ON vcd.id_viaje = viv.id_viaje
			INNER JOIN vi_viaje_clientes AS vcl ON vcl.id_viaje = viv.id_viaje
			INNER JOIN cl_clientes AS clc ON vcl.id_cliente = clc.id_cliente
			INNER JOIN cl_clientes AS clp ON clc.parent = clp.id_cliente
			INNER JOIN cm_catalogo AS service ON viv.cat_tiposervicio = service.id_cat
			INNER JOIN cm_catalogo AS tempo ON viv.cat_tipotemporicidad = tempo.id_cat
			INNER JOIN cr_operador_unidad ON viv.id_operador_unidad = cr_operador_unidad.id_operador_unidad
			INNER JOIN cr_operador ON cr_operador_unidad.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador.id_operador = cr_operador_numeq.id_operador
			INNER JOIN cr_numeq AS num_eq ON cr_operador_numeq.id_numeq = num_eq.id_numeq				
		';
		$where = '
			viv.cat_status_viaje = 195
			AND
				viv.cat_tipotemporicidad = 162
			AND 
				NOW() < vcd.fecha_requerimiento
			AND
				vcd.fecha_requerimiento < DATE_ADD(NOW(),	INTERVAL 60 MINUTE)
		';
		$orden = '
			GROUP BY
				viv.id_viaje		
			ORDER BY
				viv.id_viaje DESC
		';
		$render_table = new programados_rojo;
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
	}
	function programados_naranja($array){
		ini_set('memory_limit', '256M');				
		$table = 'vi_viaje AS viv';
		$primaryKey = 'id_viaje';
		$columns = array(
			array( 
				'db' => 'viv.id_viaje as id_viaje',
				'dbj' => 'viv.id_viaje',
				'real' => 'viv.id_viaje',
				'alias' => 'id_viaje',
				'typ' => 'int',
				'dt' => 0			
			),
			array( 
				'db' => 'viv.cat_status_viaje as status_viaje',
				'dbj' => 'viv.cat_status_viaje',
				'real' => 'viv.cat_status_viaje',
				'alias' => 'status_viaje',
				'typ' => 'int',
				'dt' => 1
			),
			array( 
				'db' => 'vcd.fecha_requerimiento as fecha_requerimiento',
				'dbj' => 'vcd.fecha_requerimiento',
				'real' => 'vcd.fecha_requerimiento',
				'alias' => 'fecha_requerimiento',
				'typ' => 'int',
				'dt' => 2
			),
			array( 
				'db' => 'clc.nombre AS cliente',
				'dbj' => 'clc.nombre',
				'real' => 'clc.nombre',
				'alias' => 'cliente',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'clp.nombre AS empresa',
				'dbj' => 'clp.nombre',
				'real' => 'clp.nombre',
				'alias' => 'empresa',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'service.etiqueta AS servicio',
				'dbj' => 'service.etiqueta',
				'real' => 'service.etiqueta',
				'alias' => 'servicio',
				'typ' => 'txt',
				'dt' => 5
			),
			array( 
				'db' => 'tempo.etiqueta AS temporicidad',
				'dbj' => 'tempo.etiqueta',
				'real' => 'tempo.etiqueta',
				'alias' => 'temporicidad',
				'typ' => 'txt',
				'dt' => 6
			),
			array( 
				'db' => 'num_eq.num AS numq',
				'dbj' => 'num_eq.num',
				'real' => 'num_eq.num',
				'alias' => 'numq',
				'typ' => 'int',
				'dt' => 7
			),				
			array( 
				'db' => 'vcl.id_cliente AS id_cliente',
				'dbj' => 'vcl.id_cliente',
				'real' => 'vcl.id_cliente',
				'alias' => 'id_cliente',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 8
			)
		);
		$inner = '
			INNER JOIN vi_viaje_detalle AS vcd ON vcd.id_viaje = viv.id_viaje
			INNER JOIN vi_viaje_clientes AS vcl ON vcl.id_viaje = viv.id_viaje
			INNER JOIN cl_clientes AS clc ON vcl.id_cliente = clc.id_cliente
			INNER JOIN cl_clientes AS clp ON clc.parent = clp.id_cliente
			INNER JOIN cm_catalogo AS service ON viv.cat_tiposervicio = service.id_cat
			INNER JOIN cm_catalogo AS tempo ON viv.cat_tipotemporicidad = tempo.id_cat
			INNER JOIN cr_operador_unidad ON viv.id_operador_unidad = cr_operador_unidad.id_operador_unidad
			INNER JOIN cr_operador ON cr_operador_unidad.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador.id_operador = cr_operador_numeq.id_operador
			INNER JOIN cr_numeq AS num_eq ON cr_operador_numeq.id_numeq = num_eq.id_numeq				
		';
		$where = '
			viv.cat_status_viaje = 195
			AND
				viv.cat_tipotemporicidad = 162
			AND 
				vcd.fecha_requerimiento >= DATE_ADD(NOW(),	INTERVAL 60 MINUTE)
			AND
				vcd.fecha_requerimiento < DATE_ADD(NOW(),	INTERVAL 90 MINUTE)
		';
		$orden = '
			GROUP BY
				viv.id_viaje		
			ORDER BY
				viv.id_viaje DESC
		';
		$render_table = new programados_naranja;
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
	}
	function programados_amarillo($array){
		ini_set('memory_limit', '256M');				
		$table = 'vi_viaje AS viv';
		$primaryKey = 'id_viaje';
		$columns = array(
			array( 
				'db' => 'viv.id_viaje as id_viaje',
				'dbj' => 'viv.id_viaje',
				'real' => 'viv.id_viaje',
				'alias' => 'id_viaje',
				'typ' => 'int',
				'dt' => 0			
			),
			array( 
				'db' => 'viv.cat_status_viaje as status_viaje',
				'dbj' => 'viv.cat_status_viaje',
				'real' => 'viv.cat_status_viaje',
				'alias' => 'status_viaje',
				'typ' => 'int',
				'dt' => 1
			),
			array( 
				'db' => 'vcd.fecha_requerimiento as fecha_requerimiento',
				'dbj' => 'vcd.fecha_requerimiento',
				'real' => 'vcd.fecha_requerimiento',
				'alias' => 'fecha_requerimiento',
				'typ' => 'int',
				'dt' => 2
			),
			array( 
				'db' => 'clc.nombre AS cliente',
				'dbj' => 'clc.nombre',
				'real' => 'clc.nombre',
				'alias' => 'cliente',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'clp.nombre AS empresa',
				'dbj' => 'clp.nombre',
				'real' => 'clp.nombre',
				'alias' => 'empresa',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'service.etiqueta AS servicio',
				'dbj' => 'service.etiqueta',
				'real' => 'service.etiqueta',
				'alias' => 'servicio',
				'typ' => 'txt',
				'dt' => 5
			),
			array( 
				'db' => 'tempo.etiqueta AS temporicidad',
				'dbj' => 'tempo.etiqueta',
				'real' => 'tempo.etiqueta',
				'alias' => 'temporicidad',
				'typ' => 'txt',
				'dt' => 6
			),
			array( 
				'db' => 'num_eq.num AS numq',
				'dbj' => 'num_eq.num',
				'real' => 'num_eq.num',
				'alias' => 'numq',
				'typ' => 'int',
				'dt' => 7
			),				
			array( 
				'db' => 'vcl.id_cliente AS id_cliente',
				'dbj' => 'vcl.id_cliente',
				'real' => 'vcl.id_cliente',
				'alias' => 'id_cliente',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 8
			)
		);
		$inner = '
			INNER JOIN vi_viaje_detalle AS vcd ON vcd.id_viaje = viv.id_viaje
			INNER JOIN vi_viaje_clientes AS vcl ON vcl.id_viaje = viv.id_viaje
			INNER JOIN cl_clientes AS clc ON vcl.id_cliente = clc.id_cliente
			INNER JOIN cl_clientes AS clp ON clc.parent = clp.id_cliente
			INNER JOIN cm_catalogo AS service ON viv.cat_tiposervicio = service.id_cat
			INNER JOIN cm_catalogo AS tempo ON viv.cat_tipotemporicidad = tempo.id_cat
			INNER JOIN cr_operador_unidad ON viv.id_operador_unidad = cr_operador_unidad.id_operador_unidad
			INNER JOIN cr_operador ON cr_operador_unidad.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador.id_operador = cr_operador_numeq.id_operador
			INNER JOIN cr_numeq AS num_eq ON cr_operador_numeq.id_numeq = num_eq.id_numeq				
		';
		$where = '
			viv.cat_status_viaje = 195
			AND
				viv.cat_tipotemporicidad = 162
			AND 
				vcd.fecha_requerimiento >= DATE_ADD(NOW(),	INTERVAL 90 MINUTE)
			AND
				vcd.fecha_requerimiento < DATE_ADD(NOW(),	INTERVAL 1 DAY)			
		';
		$orden = '
			GROUP BY
				viv.id_viaje		
			ORDER BY
				viv.id_viaje DESC
		';
		$render_table = new programados_amarillo;
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
	}
	function programados_verde($array){
		ini_set('memory_limit', '256M');				
		$table = 'vi_viaje AS viv';
		$primaryKey = 'id_viaje';
		$columns = array(
			array( 
				'db' => 'viv.id_viaje as id_viaje',
				'dbj' => 'viv.id_viaje',
				'real' => 'viv.id_viaje',
				'alias' => 'id_viaje',
				'typ' => 'int',
				'dt' => 0			
			),
			array( 
				'db' => 'viv.cat_status_viaje as status_viaje',
				'dbj' => 'viv.cat_status_viaje',
				'real' => 'viv.cat_status_viaje',
				'alias' => 'status_viaje',
				'typ' => 'int',
				'dt' => 1
			),
			array( 
				'db' => 'vcd.fecha_requerimiento as fecha_requerimiento',
				'dbj' => 'vcd.fecha_requerimiento',
				'real' => 'vcd.fecha_requerimiento',
				'alias' => 'fecha_requerimiento',
				'typ' => 'int',
				'dt' => 2
			),
			array( 
				'db' => 'clc.nombre AS cliente',
				'dbj' => 'clc.nombre',
				'real' => 'clc.nombre',
				'alias' => 'cliente',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'clp.nombre AS empresa',
				'dbj' => 'clp.nombre',
				'real' => 'clp.nombre',
				'alias' => 'empresa',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'service.etiqueta AS servicio',
				'dbj' => 'service.etiqueta',
				'real' => 'service.etiqueta',
				'alias' => 'servicio',
				'typ' => 'txt',
				'dt' => 5
			),
			array( 
				'db' => 'tempo.etiqueta AS temporicidad',
				'dbj' => 'tempo.etiqueta',
				'real' => 'tempo.etiqueta',
				'alias' => 'temporicidad',
				'typ' => 'txt',
				'dt' => 6
			),
			array( 
				'db' => 'num_eq.num AS numq',
				'dbj' => 'num_eq.num',
				'real' => 'num_eq.num',
				'alias' => 'numq',
				'typ' => 'int',
				'dt' => 7
			),	
			array( 
				'db' => 'vcl.id_cliente AS id_cliente',
				'dbj' => 'vcl.id_cliente',
				'real' => 'vcl.id_cliente',
				'alias' => 'id_cliente',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 8
			)
		);
		$inner = '
			INNER JOIN vi_viaje_detalle AS vcd ON vcd.id_viaje = viv.id_viaje
			INNER JOIN vi_viaje_clientes AS vcl ON vcl.id_viaje = viv.id_viaje
			INNER JOIN cl_clientes AS clc ON vcl.id_cliente = clc.id_cliente
			INNER JOIN cl_clientes AS clp ON clc.parent = clp.id_cliente
			INNER JOIN cm_catalogo AS service ON viv.cat_tiposervicio = service.id_cat
			INNER JOIN cm_catalogo AS tempo ON viv.cat_tipotemporicidad = tempo.id_cat
			INNER JOIN cr_operador_unidad ON viv.id_operador_unidad = cr_operador_unidad.id_operador_unidad
			INNER JOIN cr_operador ON cr_operador_unidad.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador.id_operador = cr_operador_numeq.id_operador
			INNER JOIN cr_numeq AS num_eq ON cr_operador_numeq.id_numeq = num_eq.id_numeq				
		';
		$where = '
			viv.cat_status_viaje = 171
			AND
				viv.cat_tipotemporicidad = 162
			AND 
				vcd.fecha_requerimiento >= DATE_ADD(NOW(),	INTERVAL 1 DAY)
		';
		$orden = '
			GROUP BY
				viv.id_viaje		
			ORDER BY
				viv.id_viaje DESC
		';
		$render_table = new programados_verde;
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
	}
	function programados_gris($array){
		ini_set('memory_limit', '256M');				
		$table = 'vi_viaje AS viv';
		$primaryKey = 'id_viaje';
		$columns = array(
			array( 
				'db' => 'viv.id_viaje as id_viaje',
				'dbj' => 'viv.id_viaje',
				'real' => 'viv.id_viaje',
				'alias' => 'id_viaje',
				'typ' => 'int',
				'dt' => 0			
			),
			array( 
				'db' => 'viv.cat_status_viaje as status_viaje',
				'dbj' => 'viv.cat_status_viaje',
				'real' => 'viv.cat_status_viaje',
				'alias' => 'status_viaje',
				'typ' => 'int',
				'dt' => 1
			),
			array( 
				'db' => 'vcd.fecha_requerimiento as fecha_requerimiento',
				'dbj' => 'vcd.fecha_requerimiento',
				'real' => 'vcd.fecha_requerimiento',
				'alias' => 'fecha_requerimiento',
				'typ' => 'int',
				'dt' => 2
			),
			array( 
				'db' => 'clc.nombre AS cliente',
				'dbj' => 'clc.nombre',
				'real' => 'clc.nombre',
				'alias' => 'cliente',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'clp.nombre AS empresa',
				'dbj' => 'clp.nombre',
				'real' => 'clp.nombre',
				'alias' => 'empresa',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'service.etiqueta AS servicio',
				'dbj' => 'service.etiqueta',
				'real' => 'service.etiqueta',
				'alias' => 'servicio',
				'typ' => 'txt',
				'dt' => 5
			),
			array( 
				'db' => 'tempo.etiqueta AS temporicidad',
				'dbj' => 'tempo.etiqueta',
				'real' => 'tempo.etiqueta',
				'alias' => 'temporicidad',
				'typ' => 'txt',
				'dt' => 6
			),
			array( 
				'db' => 'num_eq.num AS numq',
				'dbj' => 'num_eq.num',
				'real' => 'num_eq.num',
				'alias' => 'numq',
				'typ' => 'int',
				'dt' => 7
			),				
			array( 
				'db' => 'vcl.id_cliente AS id_cliente',
				'dbj' => 'vcl.id_cliente',
				'real' => 'vcl.id_cliente',
				'alias' => 'id_cliente',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 8
			)
		);
		$inner = '
			INNER JOIN vi_viaje_detalle AS vcd ON vcd.id_viaje = viv.id_viaje
			INNER JOIN vi_viaje_clientes AS vcl ON vcl.id_viaje = viv.id_viaje
			INNER JOIN cl_clientes AS clc ON vcl.id_cliente = clc.id_cliente
			INNER JOIN cl_clientes AS clp ON clc.parent = clp.id_cliente
			INNER JOIN cm_catalogo AS service ON viv.cat_tiposervicio = service.id_cat
			INNER JOIN cm_catalogo AS tempo ON viv.cat_tipotemporicidad = tempo.id_cat
			INNER JOIN cr_operador_unidad ON viv.id_operador_unidad = cr_operador_unidad.id_operador_unidad
			INNER JOIN cr_operador ON cr_operador_unidad.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador.id_operador = cr_operador_numeq.id_operador
			INNER JOIN cr_numeq AS num_eq ON cr_operador_numeq.id_numeq = num_eq.id_numeq				
		';
		$where = '
			viv.cat_status_viaje = 195
			AND
				viv.cat_tipotemporicidad = 162
			AND 
				NOW() > vcd.fecha_requerimiento
		';
		$orden = '
			GROUP BY
				viv.id_viaje		
			ORDER BY
				viv.id_viaje DESC
		';
		$render_table = new programados_gris;
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
	}
	function completados($array){
		ini_set('memory_limit', '256M');				
		$table = 'vi_viaje AS viv';
		$primaryKey = 'id_viaje';
		$columns = array(
			array( 
				'db' => 'viv.id_viaje as id_viaje',
				'dbj' => 'viv.id_viaje',
				'real' => 'viv.id_viaje',
				'alias' => 'id_viaje',
				'typ' => 'int',
				'dt' => 0			
			),
			array( 
				'db' => 'viv.cat_status_viaje as status_viaje',
				'dbj' => 'viv.cat_status_viaje',
				'real' => 'viv.cat_status_viaje',
				'alias' => 'status_viaje',
				'typ' => 'int',
				'dt' => 1
			),
			array( 
				'db' => 'vcd.fecha_solicitud as solicitud',
				'dbj' => 'vcd.fecha_solicitud',
				'real' => 'vcd.fecha_solicitud',
				'alias' => 'solicitud',
				'typ' => 'int',
				'dt' => 2
			),
			array( 
				'db' => 'clc.nombre AS cliente',
				'dbj' => 'clc.nombre',
				'real' => 'clc.nombre',
				'alias' => 'cliente',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'clp.nombre AS empresa',
				'dbj' => 'clp.nombre',
				'real' => 'clp.nombre',
				'alias' => 'empresa',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'service.etiqueta AS servicio',
				'dbj' => 'service.etiqueta',
				'real' => 'service.etiqueta',
				'alias' => 'servicio',
				'typ' => 'txt',
				'dt' => 5
			),
			array( 
				'db' => 'num_eq.num AS numq',
				'dbj' => 'num_eq.num',
				'real' => 'num_eq.num',
				'alias' => 'numq',
				'typ' => 'int',
				'dt' => 6
			),
			array( 
				'db' => 'vcd.apartado AS apartado',
				'dbj' => 'vcd.apartado',
				'real' => 'vcd.apartado',
				'alias' => 'apartado',
				'typ' => 'int',
				'bin' => true,
				'dt' => 7
			),
			array( 
				'db' => 'vcl.id_cliente AS id_cliente',
				'dbj' => 'vcl.id_cliente',
				'real' => 'vcl.id_cliente',
				'alias' => 'id_cliente',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 8
			)
		);
		$inner = '
			INNER JOIN vi_viaje_detalle AS vcd ON vcd.id_viaje = viv.id_viaje
			INNER JOIN vi_viaje_clientes AS vcl ON vcl.id_viaje = viv.id_viaje
			INNER JOIN cl_clientes AS clc ON vcl.id_cliente = clc.id_cliente
			INNER JOIN cl_clientes AS clp ON clc.parent = clp.id_cliente
			INNER JOIN cm_catalogo AS service ON viv.cat_tiposervicio = service.id_cat
			INNER JOIN cm_catalogo AS tempo ON viv.cat_tipotemporicidad = tempo.id_cat
			INNER JOIN cr_operador_unidad ON viv.id_operador_unidad = cr_operador_unidad.id_operador_unidad
			INNER JOIN cr_operador ON cr_operador_unidad.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador.id_operador = cr_operador_numeq.id_operador
			INNER JOIN cr_numeq AS num_eq ON cr_operador_numeq.id_numeq = num_eq.id_numeq				
		';
		$where = '
			viv.cat_status_viaje = 172
		';
		$orden = '
			GROUP BY
				viv.id_viaje
		';
		$render_table = new acciones_completados;
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
	}
	function cancelados($array){
		ini_set('memory_limit', '256M');				
		$table = 'vi_viaje AS viv';
		$primaryKey = 'id_viaje';
		$columns = array(
			array( 
				'db' => 'viv.id_viaje as id_viaje',
				'dbj' => 'viv.id_viaje',
				'real' => 'viv.id_viaje',
				'alias' => 'id_viaje',
				'typ' => 'int',
				'dt' => 0			
			),
			array( 
				'db' => 'viv.cat_status_viaje as status_viaje',
				'dbj' => 'viv.cat_status_viaje',
				'real' => 'viv.cat_status_viaje',
				'alias' => 'status_viaje',
				'typ' => 'int',
				'dt' => 1
			),
			array( 
				'db' => 'vcd.fecha_solicitud as solicitud',
				'dbj' => 'vcd.fecha_solicitud',
				'real' => 'vcd.fecha_solicitud',
				'alias' => 'solicitud',
				'typ' => 'int',
				'dt' => 2
			),
			array( 
				'db' => 'clc.nombre AS cliente',
				'dbj' => 'clc.nombre',
				'real' => 'clc.nombre',
				'alias' => 'cliente',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'clp.nombre AS empresa',
				'dbj' => 'clp.nombre',
				'real' => 'clp.nombre',
				'alias' => 'empresa',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'service.etiqueta AS servicio',
				'dbj' => 'service.etiqueta',
				'real' => 'service.etiqueta',
				'alias' => 'servicio',
				'typ' => 'txt',
				'dt' => 5
			),
			array( 
				'db' => 'vcd.apartado AS apartado',
				'dbj' => 'vcd.apartado',
				'real' => 'vcd.apartado',
				'alias' => 'apartado',
				'typ' => 'int',
				'bin' => true,
				'dt' => 6
			),				
			array( 
				'db' => 'vcl.id_cliente AS id_cliente',
				'dbj' => 'vcl.id_cliente',
				'real' => 'vcl.id_cliente',
				'alias' => 'id_cliente',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 7
			)
		);
		$inner = '
			INNER JOIN vi_viaje_detalle AS vcd ON vcd.id_viaje = viv.id_viaje
			INNER JOIN vi_viaje_clientes AS vcl ON vcl.id_viaje = viv.id_viaje
			INNER JOIN cl_clientes AS clc ON vcl.id_cliente = clc.id_cliente
			INNER JOIN cl_clientes AS clp ON clc.parent = clp.id_cliente
			INNER JOIN cm_catalogo AS service ON viv.cat_tiposervicio = service.id_cat
			INNER JOIN cm_catalogo AS tempo ON viv.cat_tipotemporicidad = tempo.id_cat				
		';
		$where = '
			viv.cat_status_viaje = 173
		';
		$orden = '
			GROUP BY
				viv.id_viaje
		';
		$render_table = new acciones_cancelados;
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
	}	
	function tiempo_base_get($array){
		ini_set('memory_limit', '256M');				
		$table = 'cr_tiempo_base AS crtb';
		$primaryKey = 'id_tiempo_base';
		$columns = array(
			array( 
				'db' => 'crnq.num AS numeq',
				'dbj' => 'crnq.num',
				'real' => 'crnq.num',
				'alias' => 'numeq',
				'typ' => 'int',
				'dt' => 0
			),
			array( 
				'db' => 'crtb.distancia AS metros',
				'dbj' => 'crtb.distancia',
				'real' => 'crtb.distancia',
				'alias' => 'metros',
				'typ' => 'int',
				'distance' => true,
				'dt' => 1				
			),
			array( 
				'db' => 'crtb.min_min AS min_seg',
				'dbj' => 'crtb.min_min',
				'real' => 'crtb.min_min',
				'alias' => 'min_seg',
				'typ' => 'int',
				'time_min' => true,
				'dt' => 2				
			),
			array( 
				'db' => 'crtb.min_max AS max_seg',
				'dbj' => 'crtb.min_max',
				'real' => 'crtb.min_max',
				'alias' => 'max_seg',
				'typ' => 'int',
				'time_max' => true,
				'dt' => 3				
			),
			array( 
				'db' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno) AS nombre',
				'dbj' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno)',
				'real' => 'CONCAT(usu.nombres, " " ,	usu.apellido_paterno, " " ,	usu.apellido_materno)',
				'alias' => 'nombre',
				'typ' => 'int',
				'dt' => 4				
			),
			array( 
				'db' => 'crmk.marca AS marca',
				'dbj' => 'crmk.marca',	
				'real' => 'crmk.marca',
				'alias' => 'marca',
				'typ' => 'txt',
				'dt' => 5
			),
			array( 
				'db' => 'crmd.modelo AS modelo',
				'dbj' => 'crmd.modelo',				
				'real' => 'crmd.modelo',
				'alias' => 'modelo',
				'typ' => 'txt',
				'dt' => 6
			),
			array( 
				'db' => 'cru.color AS color',
				'dbj' => 'cru.color',
				'real' => 'cru.color',
				'alias' => 'color',
				'typ' => 'txt',
				'dt' => 7
			),
			array( 
				'db' => 'crou.id_operador_unidad AS id_operador_unidad',
				'dbj' => 'crou.id_operador_unidad',
				'real' => 'crou.id_operador_unidad',
				'alias' => 'id_operador_unidad',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 8			
			),				
			array( 
				'db' => 'crop.id_operador AS id_operador',
				'dbj' => 'crop.id_operador',
				'real' => 'crop.id_operador',
				'alias' => 'id_operador',
				'typ' => 'int',
				'dt' => 9			
			)		
		);
		$render_table = new acciones_tiempo_base;
		$inner = '
			INNER JOIN cr_operador AS crop ON crtb.id_operador = crop.id_operador
			INNER JOIN cr_operador_numeq AS cron ON cron.id_operador = crop.id_operador
			INNER JOIN cr_numeq AS crnq ON cron.id_numeq = crnq.id_numeq
			INNER JOIN fw_usuarios AS usu ON crop.id_usuario = usu.id_usuario
			INNER JOIN cr_operador_unidad AS crou ON crou.id_operador = crop.id_operador
			INNER JOIN cr_unidades AS cru ON crou.id_unidad = cru.id_unidad
			INNER JOIN cr_modelos AS crmd ON cru.id_modelo = crmd.id_modelo
			INNER JOIN cr_marcas AS crmk ON cru.id_marca = crmk.id_marca
		';
		$where = "";
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
	function queryCostosAdicionales($array,$id_viaje){
		ini_set('memory_limit', '256M');				
		$table = 'vi_costos_adicionales AS vca';
		$primaryKey = 'id_costos_adicionales';
		$columns = array(
			array( 
				'db' => 'cat.etiqueta as etiqueta',
				'dbj' => 'cat.etiqueta',
				'real' => 'cat.etiqueta',
				'alias' => 'etiqueta',
				'typ' => 'txt',
				'dt' => 0			
			),
			array( 
				'db' => 'vca.costo as costo',
				'dbj' => 'vca.costo',
				'real' => 'vca.costo',
				'alias' => 'costo',
				'typ' => 'int',
				'moneda' => true,
				'dt' => 1
			),
			array( 
				'db' => 'usr.usuario as usuario',
				'dbj' => 'usr.usuario',
				'real' => 'usr.usuario',
				'alias' => 'usuario',
				'typ' => 'txt',
				'dt' => 2
			),
			array( 
				'db' => 'vca.fecha as fecha',
				'dbj' => 'vca.fecha',
				'real' => 'vca.fecha',
				'alias' => 'fecha',
				'typ' => 'int',
				'dt' => 3
			),
			array( 
				'db' => 'vca.id_costos_adicionales as id_costos_adicionales',
				'dbj' => 'vca.id_costos_adicionales',
				'real' => 'vca.id_costos_adicionales',
				'alias' => 'id_costos_adicionales',
				'typ' => 'int',
				'acciones' => true,
				'id_viaje' => $id_viaje,
				'dt' => 4
			)
		);
		$inner = '
			INNER JOIN cm_catalogo AS cat ON vca.cat_concepto = cat.id_cat
			INNER JOIN fw_usuarios AS usr ON usr.id_usuario = vca.user_mod			
		';
		$where = '
			vca.id_viaje = '.$id_viaje.'
		';
		$orden = '
			GROUP BY
				vca.id_costos_adicionales ASC
		';
		$render_table = new acciones_costosAdicionales;
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
	}	
}
class acciones_costosAdicionales extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				
				if ( isset( $column['acciones'] ) ) {
					$id_viaje = ($data[$i][ $column['alias'] ]);
					$id_costos_adicionales = ($data[$i][ $column['alias'] ]);
					
					$salida = '';
					$salida .= '<a onclick="eliminar_costoAdicional('.$id_costos_adicionales.')" data-rel="tooltip" data-original-title="Eliminar costo"><i class="fa fa-trash" style="font-size:1.4em; color:#c40b0b;"></i></a>&nbsp;&nbsp;';				
					
					$row[ $column['dt'] ] = $salida;
				}else if ( isset( $column['moneda'] ) ){
					
					$cantidad = ($data[$i][ $column['alias'] ]);
					$cantidad = money_format('%i',$cantidad);
					$salida = $cantidad;		
					
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
class acciones_pendientes extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				
				if ( isset( $column['acciones'] ) ) {
					$id_cliente = $data[$i][ 'id_cliente' ];
					$id_viaje = $data[$i][ 'id_viaje' ];
					
					$salida = '';
					$salida .= '<a onclick="set_status_viaje('.$id_viaje.',173,\'pendientes\')" data-rel="tooltip" data-original-title="Cancelar servicio"><i class="fa fa-trash" style="font-size:1.4em; color:#c40b0b;"></i></a>&nbsp;&nbsp;';
					$salida .= '<a onclick="viajeAlAire('.$id_viaje.')" href="javascript:;" data-rel="tooltip" data-original-title="Ofrecer servicio al aire"><i class="icofont icofont-wind" style="font-size:1.4em; color:#c40b0b;"></i></a>&nbsp;&nbsp;';		
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
class acciones_enproceso extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				
				if ( isset( $column['acciones'] ) ) {
					$id_cliente = $data[$i][ 'id_cliente' ];
					$id_viaje = $data[$i][ 'id_viaje' ];
					
					$salida = '';
					$salida .= '<a onclick="set_status_viaje('.$id_viaje.',173,\'proceso\')" data-rel="tooltip" data-original-title="Cancelar servicio"><i class="fa fa-trash" style="font-size:1.4em; color:#c40b0b;"></i></a>&nbsp;&nbsp;';
					$salida .= '<a onclick="set_status_viaje('.$id_viaje.',170,\'proceso\')" data-rel="tooltip" data-original-title="Enviar a pendientes"><i class="fa fa-chain-broken" style="font-size:1.4em; color:#c40b0b;"></i></a>&nbsp;&nbsp;';
							
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
class acciones_asignados extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				
				if ( isset( $column['acciones'] ) ) {
					
					$id_cliente = $data[$i][ 'id_cliente' ];
					$id_viaje = $data[$i][ 'id_viaje' ];
					$id_operador_unidad = $data[$i][ 'id_operador_unidad' ];
					
					$salida = '<div class="line_force">';
					$salida .= '<a onclick="set_status_viaje('.$id_viaje.',173,\'asignados\')" data-rel="tooltip" data-original-title="Cancelar servicio"><i class="fa fa-trash" style="font-size:1.4em; color:#c40b0b;"></i></a>&nbsp;&nbsp;';
					$salida .= '<a onclick="set_status_viaje('.$id_viaje.',170,\'asignados\')" data-rel="tooltip" data-original-title="Enviar a pendientes"><i class="fa fa-chain-broken" style="font-size:1.4em; color:#c40b0b;"></i></a>&nbsp;&nbsp;';
					
					$cveStat = self::getCurrentCveOperador($id_operador_unidad,$db);
					
					switch ($cveStat['clave']){
						case 'R6':	$color = '#9DBF00';	break;
						case 'F15':	$color = '#697F00';	break;
						case 'A15':	$color = '#001A40';	break;
						case 'C10':	$color = '#344000';	break;
						case 'C11':	$color = '#1a6600';	break;
						case 'C14':	$color = '#BF9A16';	break;
						case 'C9':	$color = '#403307';	break;
						case 'C8':	$color = '#E5B81A';	break;
						case 'A14':	$color = '#BF3000';	break;
						case 'R2':	$color = '#7F2000';	break;
						case 'X3':	$color = '#401000';	break;
						case 'X4':	$color = '#E53A00';	break;
						case 'X5':	$color = '#004EBF';	break;
						case 'X6':	$color = '#00347F';	break;
						case 'X7':	$color = '#001A40';	break;
						case 'X8':	$color = '#005EE5';	break;
						default:	$color = '#000000';	break;
					}
					
					$salida .= '<a href="javascript:;" class="circle_num" data-rel="tooltip"  style="background:'.$color.';" data-original-title="'.$cveStat['clave'].' - '.$cveStat['valor'].'">'.$cveStat['clave'].'</a>&nbsp;&nbsp;';
						
					$salida .= '<a href="javascript:;" onclick="activar_cancelacion('.$id_viaje.')" data-rel="tooltip" data-original-title="Activar cancelación en operador"><i class="fa fa-ban" style="font-size:1.4em; color:#c40b0b;"></i></a>&nbsp;&nbsp;';
					
					$salida .= '<a href="javascript:;" onclick="activar_abandono('.$id_viaje.')" data-rel="tooltip" data-original-title="Activar abandono en operador"><i class="icofont icofont-offside" style="font-size:1.4em; color:#aa2424;"></i></a>&nbsp;&nbsp;';
					
					$salida .= '<a href="javascript:;" onclick="costos_adicionales('.$id_viaje.')" data-rel="tooltip" data-original-title="Costos adicionales"><i class="icofont icofont-money-bag" style="font-size:1.4em; color:#008c23;"></i></a>&nbsp;&nbsp;';
					
					$salida .= '<a href="javascript:;" onclick="cambiar_tarifa('.$id_viaje.')" data-rel="tooltip" data-original-title="Cambiar tarifa"><i class="icofont icofont-exchange" style="font-size:1.4em; color:#008c23;"></i></a>&nbsp;&nbsp;';
					
					$salida .= '</div>';
					
					$row[ $column['dt'] ] = $salida;
				}else{
					$row[ $column['dt'] ] = ( self::detectUTF8($data[$i][$name_column]) )? $data[$i][$name_column] : utf8_encode($data[$i][$name_column]);	
				}
				
			}
			$out[] = $row;
		}
		return $out;
	}
	static function getCurrentCveOperador($id_operador_unidad,$db){
		$qry = "
			SELECT
				syc.clave as llave,
				cm_catalogo.valor as valor
			FROM
				cr_operador_unidad AS oun
			INNER JOIN cr_sync AS syc ON oun.sync_token = syc.token
			INNER JOIN cm_catalogo ON syc.clave = cm_catalogo.etiqueta
			WHERE
				oun.id_operador_unidad = $id_operador_unidad
			AND cm_catalogo.catalogo = 'clavesitio'	
		";
		$query = $db->prepare($qry);
		$query->execute();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row){
				$array['clave']	=	$row['llave'];
				$array['valor']	=	utf8_encode($row['valor']);
			}
		}
		return $array;
	}	
}
class acciones_tiempo_base extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				if ( isset( $column['acciones'] ) ) {
					
					$id_operador_unidad = $data[$i][ 'id_operador_unidad' ];
					$id_operador = $data[$i][ 'id_operador' ];
					
					$salida = '';
						if(Controlador::tiene_permiso('Operacion|activar_f15')){
							$salida .= '<a onclick="activar_f15('.$id_operador_unidad.')" data-rel="tooltip" class="btn btn-app btn-yellow btn-xs" data-original-title="Activar servicio al aire">F15</a>&nbsp;&nbsp;';
						}
						if(Controlador::tiene_permiso('Operacion|activar_f16')){
							$salida .= '<a onclick="activar_f16('.$id_operador_unidad.')" data-rel="tooltip" class="btn btn-app btn-yellow btn-xs" data-original-title="Activar modificar modo de viaje">F16</a>&nbsp;&nbsp;';
						}
						if(Controlador::tiene_permiso('Gps|geolocalizacion')){
							$salida .= '<a onclick="modal_geolocalizacion('.$id_operador.');" data-rel="tooltip" data-original-title="Geolocalizar Unidad">
							<i class="icon-centralcar_geolocalizacion" style="font-size:2em; color:green;"></i>
							</a>&nbsp;&nbsp;';
						}
						
					$row[ $column['dt'] ] = $salida;
					
				}else if ( isset( $column['time_min'] ) ) {
					
					$min_seg = $data[$i][ 'min_seg' ];
					$salida = '';
					$salida .= round(($min_seg/60),0).' min';
					$row[ $column['dt'] ] = $salida;
					
				}else if ( isset( $column['time_max'] ) ) {
					
					$max_seg = $data[$i][ 'max_seg' ];
					$salida = '';
					$salida .= round(($max_seg/60),0).' min';
					$row[ $column['dt'] ] = $salida;
					
				}else if ( isset( $column['distance'] ) ) {
					
					$metros = $data[$i][ 'metros' ];
					$salida = '';
					$salida .= round(($metros/1000),2).' km';
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
class acciones_asiggn extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				
				if ( isset( $column['acciones'] ) ) {
					$id_cliente = $data[$i][ 'id_cliente' ];
					$id_viaje = $data[$i][ 'id_viaje' ];
					
					$salida = '';
					$salida .= '<a href="#">'.$id_cliente.' - '.$id_viaje.'</a>&nbsp;&nbsp;';
							
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
class acciones_proceso extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				
				if ( isset( $column['acciones'] ) ) {
					$id_cliente = $data[$i][ 'id_cliente' ];
					$id_viaje = $data[$i][ 'id_viaje' ];
					
					$salida = '';
					$salida .= '<a href="#">'.$id_cliente.' - '.$id_viaje.'</a>&nbsp;&nbsp;';
							
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
class acciones_completados extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				
				if ( isset( $column['acciones'] ) ) {
					$id_viaje = $data[$i][ 'id_viaje' ];
					
					$salida = '';
					
					$salida .= '<a href="javascript:;" onclick="costos_adicionales('.$id_viaje.')" data-rel="tooltip" data-original-title="Costos adicionales"><i class="icofont icofont-money-bag" style="font-size:1.4em; color:#008c23;"></i></a>&nbsp;&nbsp;';
					
					$salida .= '<a href="javascript:;" onclick="cambiar_tarifa('.$id_viaje.')" data-rel="tooltip" data-original-title="Cambiar tarifa"><i class="icofont icofont-exchange" style="font-size:1.4em; color:#008c23;"></i></a>&nbsp;&nbsp;';
					
					$row[ $column['dt'] ] = $salida;
				}else if(isset( $column['bin'])){
					
					$a = ($data[$i][ 'apartado' ] == 1)? '<a data-rel="tooltip" data-original-title="Salida programada" href="javascript:;"><i class="icofont icofont-delivery-time bigger-200 brown darken-1"></i></a>':'<a data-rel="tooltip" data-original-title="Salida inmediata" href="javascript:;"><i class="icofont icofont-fast-delivery bigger-200 blue"></i></a>';

					$salida = $a;
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
class acciones_cancelados extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				
				if ( isset( $column['acciones'] ) ) {
					$id_viaje = $data[$i][ 'id_viaje' ];
					
					$salida = '';
					$salida .= '<a href="javascript:;" onclick="costos_adicionales('.$id_viaje.')" data-rel="tooltip" data-original-title="Costos adicionales"><i class="icofont icofont-money-bag" style="font-size:1.4em; color:#008c23;"></i></a>&nbsp;&nbsp;';
							
					$row[ $column['dt'] ] = $salida;
				}else if(isset( $column['bin'])){
					
					$a = ($data[$i][ 'apartado' ] == 1)? '<a data-rel="tooltip" data-original-title="Salida programada" href="javascript:;"><i class="icofont icofont-delivery-time bigger-200 brown darken-1"></i></a>':'<a data-rel="tooltip" data-original-title="Salida inmediata" href="javascript:;"><i class="icofont icofont-fast-delivery bigger-200 blue"></i></a>';

					$salida = $a;
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
class programados_rojo extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				
				if ( isset( $column['acciones'] ) ) {
					$id_cliente = $data[$i][ 'id_cliente' ];
					$id_viaje = $data[$i][ 'id_viaje' ];
					
					$salida = '';
					$salida .= '<a onclick="cancel_apartado('.$id_viaje.',\'rojo\')" data-rel="tooltip" data-original-title="Cancelar servicio"><i class="fa fa-trash" style="font-size:1.4em; color:#c40b0b;"></i></a>&nbsp;&nbsp;';
					
					$salida .= '<a onclick="apartado2pendientes('.$id_viaje.',\'rojo\')" data-rel="tooltip" data-original-title="Enviar a pendientes"><i class="fa fa-chain-broken" style="font-size:1.4em; color:#d96c00;"></i></a>&nbsp;&nbsp;';
					
					$salida .= '<a onclick="apartadoAlAire('.$id_viaje.',\'rojo\')" data-rel="tooltip" data-original-title="Enviar al aire"><i class="icofont icofont-wind" style="font-size:1.4em; color:#4d4cff;"></i></a>&nbsp;&nbsp;';
					
					$salida .= '<a onclick="procesarNormal('.$id_viaje.',\'rojo\')" data-rel="tooltip" data-original-title="Procesar normalmente"><i class="fa fa-play-circle-o" style="font-size:1.4em; color:#00b32d;"></i></a>&nbsp;&nbsp;';
							
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
class programados_naranja extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				
				if ( isset( $column['acciones'] ) ) {
					$id_cliente = $data[$i][ 'id_cliente' ];
					$id_viaje = $data[$i][ 'id_viaje' ];
					
					$salida = '';
					$salida .= '<a onclick="cancel_apartado('.$id_viaje.',\'naranja\')" data-rel="tooltip" data-original-title="Cancelar servicio"><i class="fa fa-trash" style="font-size:1.4em; color:#c40b0b;"></i></a>&nbsp;&nbsp;';
							
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
class programados_amarillo extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				
				if ( isset( $column['acciones'] ) ) {
					$id_cliente = $data[$i][ 'id_cliente' ];
					$id_viaje = $data[$i][ 'id_viaje' ];
					
					$salida = '';
					$salida .= '<a onclick="cancel_apartado('.$id_viaje.',\'amarillo\')" data-rel="tooltip" data-original-title="Cancelar servicio"><i class="fa fa-trash" style="font-size:1.4em; color:#c40b0b;"></i></a>&nbsp;&nbsp;';
							
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
class programados_verde extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				
				if ( isset( $column['acciones'] ) ) {
					$id_cliente = $data[$i][ 'id_cliente' ];
					$id_viaje = $data[$i][ 'id_viaje' ];
					
					$salida = '';
					$salida .= '<a onclick="cancel_apartado('.$id_viaje.',\'verde\')" data-rel="tooltip" data-original-title="Cancelar servicio"><i class="fa fa-trash" style="font-size:1.4em; color:#c40b0b;"></i></a>&nbsp;&nbsp;';
							
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
class programados_gris extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				
				if ( isset( $column['acciones'] ) ) {
					$id_cliente = $data[$i][ 'id_cliente' ];
					$id_viaje = $data[$i][ 'id_viaje' ];
					
					$salida = '';
							
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
class acciones_cordon extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				if ( isset( $column['acciones'] ) ) {
					$id_operador_unidad = $data[$i][ 'id_operador_unidad' ];
					$id_operador = $data[$i][ 'id_operador' ];
					
					$salida = '';
						if(Controlador::tiene_permiso('Gps|geolocalizacion')){
							$salida .= '<a onclick="modal_geolocalizacion('.$id_operador.');" data-rel="tooltip" data-original-title="Geolocalizar Unidad">
							<i class="icon-centralcar_geolocalizacion" style="font-size:2em; color:green;"></i>
							</a>&nbsp;&nbsp;';
						}
						if(Controlador::tiene_permiso('Operacion|mensajeria')){
							$salida .= '<a onclick="modal_mensajeria('.$id_operador.')" data-rel="tooltip" data-original-title="Enviar mensaje">
							<i class="fa fa-comment-o" style="font-size:1.8em; color:green;"></i>
							</a>&nbsp;&nbsp;';
						}
						if(Controlador::tiene_permiso('Operacion|activar_a10')){
							//$turno = self::turno($id_operador_unidad,$column['base'],$db);
							$salida .= '<a onclick="modal_activar_out('.$id_operador_unidad.','.$column['base'].')" data-rel="tooltip" data-original-title="Sacar del cordón">
							<i class="fa fa-sign-out" style="font-size:1.8em; color:green;"></i>
							</a>&nbsp;&nbsp;';
						}
					$row[ $column['dt'] ] = $salida;
				}else if ( isset( $column['turno'] ) ){
					$row[ $column['dt'] ] = self::turno($data[$i][ 'id_operador_unidad' ],$column['base'],$db);
				}else if ( isset( $column['time_stat'] ) ){
					$espera = Controller::diferenciaFechasD($data[$i]['llegada'],date("Y-m-d H:i:s"));
					
					$row[ $column['dt'] ] = substr($data[$i]['llegada'], 11, -3).'&nbsp;/&nbsp;'.substr($espera, 11, -3);
				}else{
					$row[ $column['dt'] ] = ( self::detectUTF8($data[$i][$name_column]) )? $data[$i][$name_column] : utf8_encode($data[$i][$name_column]);	
				}
			}
			$out[] = $row;
		}
		return $out;
	}
	static function turno($id_operador_unidad,$base,$db){
		$qry = "
			SELECT
				id_cordon,
				id_operador_unidad,
				cat_statuscordon
			FROM
				cr_cordon
			WHERE
				cr_cordon.id_base = $base
			AND (
				cr_cordon.cat_statuscordon = 113
				OR cr_cordon.cat_statuscordon = 115
			)
			ORDER BY
				cr_cordon.cat_statuscordon DESC,
				cr_cordon.id_cordon ASC
		";
		$query = $db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			$count = 1;
			$numero = 0;
			foreach ($data as $row) {
				if($row['id_operador_unidad'] == $id_operador_unidad){
					$numero = $count;
				}
				$count++;
			}
		}
		return $numero;
	}	
}
class acciones_activos extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				if ( isset( $column['acciones'] ) ) {
					$id_operador = $data[$i][ 'aid_operador' ];
					
					$salida = '';
						if(Controlador::tiene_permiso('Gps|geolocalizacion')){
							$salida .= '<a onclick="modal_geolocalizacion('.$id_operador.');" data-rel="tooltip" data-original-title="Geolocalizar Unidad">
							<i class="icon-centralcar_geolocalizacion" style="font-size:2em; color:green;"></i>
							</a>&nbsp;&nbsp;';
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
class acciones_unidades_a11 extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				if ( isset( $column['acciones'] ) ) {
					$id_tarifa_operador = '';//$data[$i][ 'id_tarifa_operador' ];
					$id_operador = $data[$i][ 'id_operador' ];
					
					$salida = '';
						if(Controlador::tiene_permiso('Gps|geolocalizacion')){
							$salida .= '<a onclick="modal_geolocalizacion('.$id_operador.');" data-rel="tooltip" data-original-title="Geolocalizar Unidad">
							<i class="icon-centralcar_geolocalizacion" style="font-size:2em; color:green;"></i>
							</a>&nbsp;&nbsp;';
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
class acciones_control extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				if ( isset( $column['acciones'] ) ) {
					$id_tarifa_operador = '';//$data[$i][ 'id_tarifa_operador' ];
					$id_operador = $data[$i][ 'id_operador' ];
					
					$salida = '';
						if(Controlador::tiene_permiso('Gps|geolocalizacion')){
							$salida .= '<a onclick="modal_geolocalizacion('.$id_operador.');" data-rel="tooltip" data-original-title="Geolocalizar Unidad">
							<i class="icon-centralcar_geolocalizacion" style="font-size:2em; color:green;"></i>
							</a>&nbsp;&nbsp;';
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
class acciones_suspendidas extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				if ( isset( $column['acciones'] ) ) {
					$id_tarifa_operador = '';//$data[$i][ 'id_tarifa_operador' ];
					$id_operador = '';//$column['id_operador'];
					
					$salida = '';
						
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
class acciones_inactivos extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				if ( isset( $column['acciones'] ) ) {
					$id_tarifa_operador = '';//$data[$i][ 'id_tarifa_operador' ];
					$id_operador = '';//$column['id_operador'];
					
					$salida = '';

						
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
