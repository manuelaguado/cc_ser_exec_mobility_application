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
	function dataViaje($id_viaje){
		$qry = "
			SELECT
				viv.id_viaje,
				vid.fecha_solicitud,
				vid.fecha_asignacion,
				vid.observaciones,
				clc.nombre,
				dir1.calle AS calleo,
				dir1.num_ext AS exto,
				dir1.num_int AS int_o,
				dir1.telefono AS telo,
				dir1.celular AS celo,
				dir1.referencia AS refo,
				dir1.geocodificacion_inversa AS invo,
				dir1.geocoordenadas AS coodo,
				dir2.calle AS called,
				dir2.num_ext AS extd,
				dir2.num_int AS int_d,
				dir2.telefono AS teld,
				dir2.celular AS celd,
				dir2.referencia AS refd,
				dir2.geocodificacion_inversa AS invd,
				dir2.geocoordenadas AS coodd,
				cm1.etiqueta AS status_viaje,
				cm2.etiqueta AS tipo_servicio,
				cm3.etiqueta AS forma_pago,
				emp.nombre AS empresa,
				vid.redondo AS redondo,
				vid.apartado AS apartado
			FROM
				vi_viaje AS viv
			INNER JOIN vi_viaje_detalle AS vid ON vid.id_viaje = viv.id_viaje
			INNER JOIN vi_viaje_clientes AS vic ON vic.id_viaje = viv.id_viaje
			INNER JOIN cl_clientes AS clc ON vic.id_cliente = clc.id_cliente
			INNER JOIN it_cliente_origen AS clo ON viv.id_cliente_origen = clo.id_cliente_origen
			INNER JOIN it_origenes AS ito ON clo.id_origen = ito.id_origen
			INNER JOIN it_direcciones AS dir1 ON ito.id_direccion = dir1.id_direccion
			INNER JOIN it_viaje_destino AS itvd ON itvd.id_viaje = viv.id_viaje
			INNER JOIN it_cliente_destino AS itcd ON itvd.id_cliente_destino = itcd.id_cliente_destino
			INNER JOIN it_destinos AS itd ON itcd.id_destino = itd.id_destino
			INNER JOIN it_direcciones AS dir2 ON itd.id_direccion = dir2.id_direccion
			INNER JOIN vi_viaje_formapago AS vfp ON vfp.id_viaje = viv.id_viaje
			INNER JOIN cm_catalogo AS cm1 ON viv.cat_status_viaje = cm1.id_cat
			INNER JOIN cm_catalogo AS cm2 ON viv.cat_tiposervicio = cm2.id_cat
			INNER JOIN cm_catalogo AS cm3 ON vfp.cat_formapago = cm3.id_cat

			INNER JOIN cl_clientes AS emp ON clc.parent = emp.id_cliente
			WHERE
				viv.id_viaje = $id_viaje
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$array = array();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$array['ID'] 					= $row->id_viaje;
				$array['Status'] 				= $row->status_viaje;
				$array['Tipo'] 			= $row->tipo_servicio;
				$array['Solicitado el'] 			= $row->fecha_solicitud;
				$array['Asignado el'] 			= $row->fecha_asignacion;
				$array['Forma de pago'] 			= $row->forma_pago;
				$array['Redondo'] 			= $row->redondo;
				$array['Apartado'] 			= $row->apartado;
				$array['Empresa'] 			= $row->empresa;
				$array['Cliente'] =$row->nombre;

				$o5 = ($row->celo != '')?'<br><strong>Cel:</strong> '.$row->celo:'';
				$o4 = ($row->telo != '')?'<br><strong>Tel:</strong> '.$row->telo:'';
				$o2 = ($row->int_o != '')?'<br><strong>Int:</strong> '.$row->int_o:'';
				$o3 = ($row->exto != '')?'<br><strong>Ext:</strong> '.$row->exto:'';
				$o1 = ($row->calleo != '')?'<br><br><strong>Calle:</strong> '.$row->calleo:'';

				$d5 = ($row->celd != '')?'<br><strong>Cel:</strong> '.$row->celd:'';
				$d4 = ($row->teld != '')?'<br><strong>Tel:</strong> '.$row->teld:'';
				$d2 = ($row->int_d != '')?'<br><strong>Int:</strong> '.$row->int_d:'';
				$d3 = ($row->extd != '')?'<br><strong>Ext:</strong> '.$row->extd:'';
				$d1 = ($row->called != '')?'<br><br><strong>Calle:</strong> '.$row->called:'';

				$dato =  $o1.$o2.$o3.$o4.$o5;
				$datd =  $d1.$d2.$d3.$d4.$d5;


				$ro = ($row->refo != '')?'<br><br><strong>Ref:</strong> '.$row->refo.'<br>':'';
				$rd = ($row->refd != '')?'<br><br><strong>Ref:</strong> '.$row->refd.'<br>':'';

				$array['Origen'] 	= $row->invo.$dato.$ro;
				$array['Destino'] 	= $row->invd.$datd.$rd;

				$array['Observaciones'] 			= $row->observaciones;
			}
		}
		return $array;
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
			INNER JOIN cr_operador_unidad as crou ON viv.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_operador ON crou.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador.id_operador = cr_operador_numeq.id_operador
			INNER JOIN cr_numeq AS num_eq ON cr_operador_numeq.id_numeq = num_eq.id_numeq
			WHERE
				viv.cat_status_viaje = 195
			AND viv.cat_tipotemporicidad = 162
			AND vcd.fecha_requerimiento > DATE_SUB(NOW(), INTERVAL 30 MINUTE)
			AND vcd.fecha_requerimiento < DATE_ADD(NOW(), INTERVAL 65 MINUTE)
			AND crou.status_operador_unidad = 198
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
				fecha,
				user_alta,
				user_mod,
				fecha_alta
			) VALUES (
				:id_viaje,
				:cat_concepto,
				:costo,
				:fecha,
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
				':fecha' =>  date("Y-m-d H:i:s") ,
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
       // TODO: Aqui aparecio un error lineas 346 y 358 al crear un apartado
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
			crou.id_operador_unidad,
			CONCAT(
					fw_usuarios.nombres,
					' ',
					fw_usuarios.apellido_paterno,
					' ',
					fw_usuarios.apellido_materno
				) AS nombre,
				count(DISTINCT id_operador_unidad) as multi
			FROM
			cr_operador
			INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = cr_operador.id_operador
			INNER JOIN cr_numeq ON cr_operador_numeq.id_numeq = cr_numeq.id_numeq
			INNER JOIN fw_usuarios ON cr_operador.id_usuario = fw_usuarios.id_usuario
			INNER JOIN cr_operador_unidad as crou ON crou.id_operador = cr_operador.id_operador
			WHERE
				cr_operador.cat_statusoperador = 8
				AND crou.status_operador_unidad = 198
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
				$operadores[$num]['multi'] 				= $row->multi;
				$operadores[$num]['mensual'] 			= $apartados['mensuales'];
				$operadores[$num]['anual'] 				= $apartados['anuales'];
				$operadores[$num]['status'] 			= $apartados['hit_anual'].'/'.$apartados['turnos_anuales'];

				$num++;
			}
		}
		return $operadores;
	}
	function elegirVehiculo($id_operador){
		$qry = "
			SELECT
				cr_marcas.marca,
				cr_modelos.modelo,
				cr_unidades.`year`,
				cr_unidades.placas,
				cr_unidades.color,
				crou.id_operador_unidad,
				CONCAT(
					fwu.nombres,
					' ',
					fwu.apellido_paterno,
					' ',
					fwu.apellido_materno
				) AS nombre,
				cr_numeq.num,
				cr_operador.id_usuario
			FROM
				cr_operador_unidad as crou
			INNER JOIN cr_unidades ON crou.id_unidad = cr_unidades.id_unidad
			INNER JOIN cr_marcas ON cr_unidades.id_marca = cr_marcas.id_marca
			INNER JOIN cr_modelos ON cr_unidades.id_modelo = cr_modelos.id_modelo
			INNER JOIN cr_operador ON crou.id_operador = cr_operador.id_operador
			INNER JOIN fw_usuarios AS fwu ON cr_operador.id_usuario = fwu.id_usuario
			INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = cr_operador.id_operador
			INNER JOIN cr_numeq ON cr_operador_numeq.id_numeq = cr_numeq.id_numeq
			WHERE
				crou.id_operador = $id_operador
				AND crou.status_operador_unidad = 198
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$vehiculos = array();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			$n = 0;
			foreach ($data as $row){
				$vehiculos[$n]['marca'] = $row->marca;
				$vehiculos[$n]['modelo']= $row->modelo;
				$vehiculos[$n]['year'] 	= $row->year;
				$vehiculos[$n]['placas']= $row->placas;
				$vehiculos[$n]['color']	= $row->color;
				$vehiculos[$n]['id_operador_unidad']= $row->id_operador_unidad;
				$vehiculos[$n]['nombre']= $row->nombre;
				$vehiculos[$n]['num']= $row->num;
				$vehiculos[$n]['id_usuario']= $row->id_usuario;
				$n++;
			}
		}
		return $vehiculos;
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
	function getTBUnits(){
		$qry = "
                     SELECT
                     	CONCAT(
                     		usu.nombres,
                     		' ',
                     		usu.apellido_paterno,
                     		' ',
                     		usu.apellido_materno
                     	) AS nombre,
                     	cr_numeq.num,
                     	cr_marcas.marca,
                     	cr_modelos.modelo,
                     	cr_unidades.color,
                     	cr_unidades.placas,
                     	stt.id_operador,
                     	stt.id_operador_unidad,
                     	stt.id_episodio,
                     	stt.id_viaje
                     FROM
                     	cr_state AS stt
                     INNER JOIN cr_operador AS cro ON stt.id_operador = cro.id_operador
                     INNER JOIN fw_usuarios AS usu ON cro.id_usuario = usu.id_usuario
                     INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = cro.id_operador
                     INNER JOIN cr_numeq ON cr_operador_numeq.id_numeq = cr_numeq.id_numeq
                     INNER JOIN cr_operador_unidad ON cr_operador_unidad.id_operador_unidad = stt.id_operador_unidad
                     INNER JOIN cr_unidades ON cr_operador_unidad.id_unidad = cr_unidades.id_unidad
                     INNER JOIN cr_marcas ON cr_unidades.id_marca = cr_marcas.id_marca
                     INNER JOIN cr_modelos ON cr_unidades.id_modelo = cr_modelos.id_modelo
                     WHERE
                     	(
                     (
              		stt.state = 'C1'
              		AND stt.flag1 = 'C1'
              		AND stt.flag2 = 'F11'
              	)
                     OR (
                     	stt.state = 'C6'
                     	AND stt.flag1 = 'C1'
                     	AND stt.flag2 = 'C6'
                     	AND stt.flag3 = 'F11'
                     )
                     OR (
                     	stt.state = 'C9'
                     	AND stt.flag1 = 'C1'
                     	AND stt.flag2 = 'C9'
                     	AND stt.flag3 = 'F11'
                     )
                     OR (
                     	stt.state = 'C18'
                     	AND stt.flag1 = 'C1'
                     	AND stt.flag2 = 'C18'
                            AND stt.flag3 = 'F11'
                     )
                     OR (
                     	stt.state = 'C19'
                     	AND stt.flag1 = 'C1'
                     	AND stt.flag2 = 'C19'
                            AND stt.flag3 = 'F11'
                     )
                     )
                     AND stt.activo = 1
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$operadores = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row){
				$operadores[$num]['numeq'] 				= $row->num;
				$operadores[$num]['nombre'] 			= $row->nombre;
				$operadores[$num]['marca'] 				= $row->marca;
				$operadores[$num]['modelo'] 			= $row->modelo;
				$operadores[$num]['color'] 				= $row->color;
				$operadores[$num]['id_operador_unidad']          = $row->id_operador_unidad;
				$operadores[$num]['id_operador'] 		       = $row->id_operador;
                            $operadores[$num]['id_episodio'] 		       = $row->id_episodio;

				$num++;
			}
		}
		return $operadores;
	}
	function asignar_viajes($base,ShareModel $share){
		$operador            = self::unidades_formadas($base);
		$viajes		= self::viajes_pendientes();
		$array = array();
		if(($operador['procesar'])&&($viajes['procesar'])){

			self::asignar_viaje($viajes['id_viaje'],$operador);

                     $share->cordonFinishSuccess($_SESSION['id_usuario'],$operador['id_operador_unidad'],$viajes['id_viaje']);
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
			 cat_status_viaje	= '179',
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
			 cat_status_viaje	= '179'
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
			 cat_status_viaje	= '179',
			 cat_tipotemporicidad = '184'
			WHERE
				id_viaje = ".$id_viaje."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	function alAire($id_operador_unidad){
		$operadores = self::getTBUnits();
		$alAire = 0;
		if(count($operadores)>0){
			foreach ($operadores as $operador) {
				if($id_operador_unidad == $operador['id_operador_unidad']){$alAire++;}
			}
		}
		if($alAire == 0){return false;}else{return true;}
	}
	function setear_status_viaje($post, ShareModel $share=NULL, OperadoresModel $operadores = NULL){

		$stat_process = true;
		$qrymissing = array();
		$id_operador_unidad = self::getIdOperadorUnidadViaje($post['id_viaje']);
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
                     $id_operador = $share->getIdOperador($id_operador_unidad);
                     $id_episodio = $share->getIdEpisodio($id_operador_unidad);
			switch($post['stat']){
				case '170':
					$token = 'SOL:'.Controller::token(60);
					switch($post['status_operador']){
						case 'suspender':
							/*Setear en suspendido*/
							$operador['cat_statusoperador'] = 10;
                                                 $operador['id_operador']=$id_operador;
							$operadores->setearstatusoperador($operador);
							/*desloguear si existiera la version mobil*/
                                                 $share->setstatlocal($id_operador,$id_operador_unidad,$id_episodio,'F6','F6','NULL','NULL','NULL','VIAJE EN PROCESO',$post['id_viaje']);
						break;
						case 'omitir':
                                                 $share->setstatlocal($id_operador,$id_operador_unidad,$id_episodio,'C19','C1','C19','F11','NULL','CLIENTE REASIGNADO',$post['id_viaje']);
						break;
					}
				break;
				case '173':
					if($post['origen'] == 'asignados'){
						$token = 'SOL:'.Controller::token(60);
						switch($post['status_operador']){
							case 'segundo':
                                                        $share->setstatlocal($id_operador,$id_operador_unidad,$id_episodio,'C6','C1','C6','F11','NULL','Se canceló el servicio',$post['id_viaje']);
                                                        /*se ingresa al cordon de segundo*/
                                                        $share->formarse_directo($id_episodio,$id_operador_unidad,'1','115');
							break;
							case 'cola':
                                                        $share->setstatlocal($id_operador,$id_operador_unidad,$id_episodio,'C6','C1','C6','F11','NULL','Se canceló el servicio',$post['id_viaje']);
                                                        /*Se ingresa al cordon*/
                                                        $share->formarse_directo($id_episodio,$id_operador_unidad,'1','113');
							break;
							case 'omitir':
                                                        $share->setstatlocal($id_operador,$id_operador_unidad,$id_episodio,'C6','C1','C6','F11','NULL','Se canceló el servicio',$post['id_viaje']);
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
	function cancel_apartado_set($post, ShareModel $share=NULL){

		$stat_process = true;
		$qrymissing = array();
		$id_operador_unidad = self::getIdOperadorUnidadViaje($post['id_viaje']);

		if(!$post['cat_cancelaciones']){
			$qrymissing = array('qrymissing' => 'cat_cancelaciones' );
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
	function getIdOperadorUnidadViaje($id_viaje){
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
                     cr_cordon.id_episodio,
                     cr_operador_unidad.id_operador
                     FROM
                     cr_cordon
                     INNER JOIN cr_operador_unidad ON cr_operador_unidad.id_operador_unidad = cr_cordon.id_operador_unidad
                     WHERE
                     				cr_cordon.id_base = 1
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
                                   $array['id_operador'] = $row->id_operador;
					$array['procesar'] = true;
				}
				$num++;
			}
		}else{
			$array['procesar'] = false;
		}
		return $array;
	}
       function unidadaGlobal($id_operador_unidad){
		$sql ="
                     SELECT
                     	stt.id_operador,
                     	stt.id_operador_unidad,
                     	stt.id_episodio,
                     	cr_numeq.num
                     FROM
                     	cr_state AS stt
                     INNER JOIN cr_operador AS cro ON stt.id_operador = cro.id_operador
                     INNER JOIN cr_operador_unidad ON cr_operador_unidad.id_operador_unidad = stt.id_operador_unidad
                     INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = cro.id_operador
                     INNER JOIN cr_numeq ON cr_operador_numeq.id_numeq = cr_numeq.id_numeq
                     WHERE
                     	stt.id_operador_unidad = $id_operador_unidad
                     AND stt.activo = 1
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row) {
				$array['id_operador_unidad'] = $id_operador_unidad;
                            $array['id_operador'] = $row->id_operador;
                            $array['num'] = $row->num;
				$array['id_episodio'] = $row->id_episodio;
				$array['id_cordon'] = '';
				$array['procesar'] = true;
			}
		}else{
			$array['procesar'] = false;
		}
		return $array;
	}
	function unidadalAire($id_operador_unidad){
		$sql ="
                     SELECT
                     	stt.id_operador,
                     	stt.id_operador_unidad,
                     	stt.id_episodio,
                     	cr_numeq.num
                     FROM
                     	cr_state AS stt
                     INNER JOIN cr_operador AS cro ON stt.id_operador = cro.id_operador
                     INNER JOIN cr_operador_unidad ON cr_operador_unidad.id_operador_unidad = stt.id_operador_unidad
                     INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = cro.id_operador
                     INNER JOIN cr_numeq ON cr_operador_numeq.id_numeq = cr_numeq.id_numeq
                     WHERE
                     	(
                     		(
                     			stt.state = 'C1'
                     			AND stt.flag1 = 'C1'
                     			AND stt.flag2 = 'F11'
                     		)
                     		OR (
                     			stt.state = 'C6'
                     			AND stt.flag1 = 'C1'
                     			AND stt.flag2 = 'C6'
                     			AND stt.flag3 = 'F11'
                     		)
                     		OR (
                     			stt.state = 'C9'
                     			AND stt.flag1 = 'C1'
                     			AND stt.flag2 = 'C9'
                     			AND stt.flag3 = 'F11'
                     		)
                     		OR (
                     			stt.state = 'C18'
                     			AND stt.flag1 = 'C1'
                     			AND stt.flag2 = 'C18'
                     			AND stt.flag3 = 'F11'
                     		)
                     		OR (
                     			stt.state = 'C19'
                     			AND stt.flag1 = 'C1'
                     			AND stt.flag2 = 'C19'
                     			AND stt.flag3 = 'F11'
                     		)
                     	)
                     AND stt.activo = 1
                     AND stt.id_operador_unidad = $id_operador_unidad
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row) {
				$array['id_operador'] = $row->id_operador;
                            $array['id_operador_unidad'] = $id_operador_unidad;
                            $array['num'] = $row->num;
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
			AND tfcl.tabulado = 0
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
				client.nombre,
				client.cat_tipocliente as tipocliente
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
					'id'=>$row->id_cliente,
					'tipocliente'=>$row->tipocliente
				);
			}
		}
		return json_encode($output);
	}
	function formadoAnyBase(BasesModel $bases, $id_operador_unidad){
		$return = false;
		foreach($bases->listarBases() as $base){
			$return .= self::formadoenBase2($id_operador_unidad,$base->id_base);
		}
		return $return;
	}
	function formadoenBase2($id_operador_unidad,$base){
		$qry = "
			SELECT
				cr_bases.descripcion
			FROM
				cr_cordon AS crc
			INNER JOIN cr_operador_unidad AS crou ON crc.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_bases ON crc.id_base = cr_bases.id_base
			WHERE
				crou.id_operador_unidad = $id_operador_unidad
			AND (
				crc.cat_statuscordon = 113
				OR crc.cat_statuscordon = 115
			)
			AND crc.id_base = $base
			AND crou.status_operador_unidad = 198
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$data = $query->fetchAll();
		$return = "";
		if($query->rowCount()>=1){
			foreach ($data as $row) {
				$return .= $row->descripcion;
			}
		}
		return $return;
	}
	function formadoenBase($id_operador,$base){
		$qry = "
			SELECT
				cr_bases.descripcion
			FROM
				cr_cordon AS crc
			INNER JOIN cr_operador_unidad AS crou ON crc.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_bases ON crc.id_base = cr_bases.id_base
			WHERE
				crou.id_operador = $id_operador
			AND (
				crc.cat_statuscordon = 113
				OR crc.cat_statuscordon = 115
			)
			AND crc.id_base = $base
			AND crou.status_operador_unidad = 198
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$data = $query->fetchAll();
		$return = "";
		if($query->rowCount()>=1){
			foreach ($data as $row) {
				$return .= $row->descripcion;
			}
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
			AND crou.status_operador_unidad = 198
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
       function getCurrentCveOperador($id_operador_unidad){
		$qry = "
			SELECT
				syc.clave as llave
			FROM
				cr_operador_unidad AS crou
			INNER JOIN cr_sync AS syc ON crou.sync_token = syc.token
			INNER JOIN cm_catalogo ON syc.clave = cm_catalogo.etiqueta
			WHERE
				crou.id_operador_unidad = $id_operador_unidad
			AND cm_catalogo.catalogo = 'clavesitio'
			AND crou.status_operador_unidad = 198
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
	function inactivos_get($array){
		ini_set('memory_limit', '256M');
		$table = 'cr_state AS stt';
		$primaryKey = 'id_state';
		$columns = array(
			array(
				'db' => 'stt.id_state as id',
				'dbj' => 'stt.id_state',
				'real' => 'stt.id_state',
				'alias' => 'id',
				'typ' => 'int',
				'dt' => 0
			),
                     array(
				'db' => 'stt.numeq as num',
				'dbj' => 'stt.numeq',
				'real' => 'stt.numeq',
				'alias' => 'num',
				'typ' => 'int',
				'dt' => 1
			),
			array(
				'db' => 'CONCAT(fwu.nombres, " " ,fwu.apellido_paterno, " " ,fwu.apellido_materno) AS nombre',
				'dbj' => 'CONCAT(fwu.nombres, " " ,fwu.apellido_paterno, " " ,fwu.apellido_materno)',
				'real' => 'CONCAT(fwu.nombres, " " ,fwu.apellido_paterno, " " ,fwu.apellido_materno)',
				'alias' => 'nombre',
				'typ' => 'int',
				'dt' => 2
			),
                     array(
				'db' => 'stt.id_operador as id_operador',
				'dbj' => 'stt.id_operador',
				'real' => 'stt.id_operador',
				'alias' => 'id_operador',
                            'acciones' => true,
				'typ' => 'int',
				'dt' => 3
			),
                     array(
				'db' => 'stt.id_operador_unidad as id_operador_unidad',
				'dbj' => 'stt.id_operador_unidad',
				'real' => 'stt.id_operador_unidad',
				'alias' => 'id_operador_unidad',
				'typ' => 'int',
				'dt' => 4
			),
                     array(
				'db' => 'stt.id_episodio as id_episodio',
				'dbj' => 'stt.id_episodio',
				'real' => 'stt.id_episodio',
				'alias' => 'id_episodio',
				'typ' => 'int',
				'dt' => 5
			),
                     array(
				'db' => 'fwu.id_usuario as id_usuario',
				'dbj' => 'fwu.id_usuario',
				'real' => 'fwu.id_usuario',
				'alias' => 'id_usuario',
				'typ' => 'int',
				'dt' => 6
			)
		);
		$render_table = new acciones_inactivos;
		$inner = '
              INNER JOIN cr_operador AS cro ON stt.id_operador = cro.id_operador
              INNER JOIN fw_usuarios AS fwu ON cro.id_usuario = fwu.id_usuario
		';
		$where = "
                     stt.activo = 1
                     AND stt.state = 'C2'
                     AND stt.flag1 = 'C2'
		";
		$orden = "";
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden  )
		);
	}
	function suspendidas_get($array){
              ini_set('memory_limit', '256M');
		$table = 'cr_state AS stt';
		$primaryKey = 'id_state';
		$columns = array(
			array(
				'db' => 'stt.id_state as id',
				'dbj' => 'stt.id_state',
				'real' => 'stt.id_state',
				'alias' => 'id',
				'typ' => 'int',
				'dt' => 0
			),
                     array(
				'db' => 'stt.numeq as num',
				'dbj' => 'stt.numeq',
				'real' => 'stt.numeq',
				'alias' => 'num',
				'typ' => 'int',
				'dt' => 1
			),
			array(
				'db' => 'CONCAT(fwu.nombres, " " ,fwu.apellido_paterno, " " ,fwu.apellido_materno) AS nombre',
				'dbj' => 'CONCAT(fwu.nombres, " " ,fwu.apellido_paterno, " " ,fwu.apellido_materno)',
				'real' => 'CONCAT(fwu.nombres, " " ,fwu.apellido_paterno, " " ,fwu.apellido_materno)',
				'alias' => 'nombre',
				'typ' => 'int',
				'dt' => 2
			),
                     array(
				'db' => 'stt.id_operador as id_operador',
				'dbj' => 'stt.id_operador',
				'real' => 'stt.id_operador',
				'alias' => 'id_operador',
                            'acciones' => true,
				'typ' => 'int',
				'dt' => 3
			),
                     array(
				'db' => 'stt.id_operador_unidad as id_operador_unidad',
				'dbj' => 'stt.id_operador_unidad',
				'real' => 'stt.id_operador_unidad',
				'alias' => 'id_operador_unidad',
				'typ' => 'int',
				'dt' => 4
			),
                     array(
				'db' => 'stt.id_episodio as id_episodio',
				'dbj' => 'stt.id_episodio',
				'real' => 'stt.id_episodio',
				'alias' => 'id_episodio',
				'typ' => 'int',
				'dt' => 5
			),
                     array(
				'db' => 'fwu.id_usuario as id_usuario',
				'dbj' => 'fwu.id_usuario',
				'real' => 'fwu.id_usuario',
				'alias' => 'id_usuario',
				'typ' => 'int',
				'dt' => 6
			)
		);
		$render_table = new acciones_suspendidas;
		$inner = '
              INNER JOIN cr_operador AS cro ON stt.id_operador = cro.id_operador
              INNER JOIN fw_usuarios AS fwu ON cro.id_usuario = fwu.id_usuario
		';
		$where = "
                     stt.activo = 1
                     AND stt.state = 'F6'
                     AND stt.flag1 = 'F6'
		";
		$orden = "";
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden  )
		);
	}

	function activos_get($array){
              ini_set('memory_limit', '256M');
		$table = 'cr_state AS stt';
		$primaryKey = 'id_state';
		$columns = array(
			array(
				'db' => 'stt.id_state as id',
				'dbj' => 'stt.id_state',
				'real' => 'stt.id_state',
				'alias' => 'id',
				'typ' => 'int',
				'dt' => 0
			),
                     array(
				'db' => 'stt.numeq as num',
				'dbj' => 'stt.numeq',
				'real' => 'stt.numeq',
				'alias' => 'num',
				'typ' => 'int',
				'dt' => 1
			),
			array(
				'db' => 'CONCAT(fwu.nombres, " " ,fwu.apellido_paterno, " " ,fwu.apellido_materno) AS nombre',
				'dbj' => 'CONCAT(fwu.nombres, " " ,fwu.apellido_paterno, " " ,fwu.apellido_materno)',
				'real' => 'CONCAT(fwu.nombres, " " ,fwu.apellido_paterno, " " ,fwu.apellido_materno)',
				'alias' => 'nombre',
				'typ' => 'int',
				'dt' => 2
			),
                     array(
				'db' => 'stt.id_operador_unidad as id_operador_unidad',
				'dbj' => 'stt.id_operador_unidad',
				'real' => 'stt.id_operador_unidad',
				'alias' => 'id_operador_unidad',
				'typ' => 'int',
				'dt' => 3
			),
                     array(
				'db' => 'stt.id_episodio as id_episodio',
				'dbj' => 'stt.id_episodio',
				'real' => 'stt.id_episodio',
				'alias' => 'id_episodio',
				'typ' => 'int',
				'dt' => 4
			),
                     array(
				'db' => 'fwu.id_usuario as id_usuario',
				'dbj' => 'fwu.id_usuario',
				'real' => 'fwu.id_usuario',
				'alias' => 'id_usuario',
				'typ' => 'int',
				'dt' => 5
			),
                     array(
				'db' => 'mrc.marca as marca',
				'dbj' => 'mrc.marca',
				'real' => 'mrc.marca',
				'alias' => 'marca',
				'typ' => 'txt',
				'dt' => 6
			),
                     array(
				'db' => '`mod`.modelo as modelo',
				'dbj' => '`mod`.modelo',
				'real' => '`mod`.modelo',
				'alias' => 'modelo',
				'typ' => 'txt',
				'dt' => 7
			),
                     array(
				'db' => 'uni.color as color',
				'dbj' => 'uni.color',
				'real' => 'uni.color',
				'alias' => 'color',
				'typ' => 'txt',
				'dt' => 8
			),
                     array(
				'db' => 'uni.placas as placas',
				'dbj' => 'uni.placas',
				'real' => 'uni.placas',
				'alias' => 'placas',
				'typ' => 'txt',
				'dt' => 9
			),
                     array(
				'db' => 'stt.id_operador as id_operador',
				'dbj' => 'stt.id_operador',
				'real' => 'stt.id_operador',
				'alias' => 'id_operador',
                            'acciones' => true,
				'typ' => 'int',
				'dt' => 10
			),
		);
		$render_table = new acciones_activos;
		$inner = '
              INNER JOIN cr_operador AS cro ON stt.id_operador = cro.id_operador
              INNER JOIN fw_usuarios AS fwu ON cro.id_usuario = fwu.id_usuario
              INNER JOIN cr_operador_unidad AS opu ON stt.id_operador_unidad = opu.id_operador_unidad
              INNER JOIN cr_unidades AS uni ON opu.id_unidad = uni.id_unidad
              INNER JOIN cr_marcas AS mrc ON uni.id_marca = mrc.id_marca
              INNER JOIN cr_modelos AS `mod` ON uni.id_modelo = `mod`.id_modelo
		';
		$where = "
                     stt.activo = 1
                     AND stt.flag1 = 'C1'
		";
		$orden = "";
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden  )
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
			AND crou.status_operador_unidad = 198

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
				'db' => 'crou.id_operador_unidad as id_operador_unidad',
				'dbj' => 'crou.id_operador_unidad',
				'real' => 'crou.id_operador_unidad',
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
			INNER JOIN cr_operador_unidad as crou ON viv.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_operador ON crou.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador.id_operador = cr_operador_numeq.id_operador
			INNER JOIN cr_numeq AS num_eq ON cr_operador_numeq.id_numeq = num_eq.id_numeq
		';
		$where = '
			viv.cat_status_viaje = 179
			AND
			viv.cat_tipotemporicidad = 184
			AND crou.status_operador_unidad = 198
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
			INNER JOIN cr_operador_unidad as crou ON viv.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_operador ON crou.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador.id_operador = cr_operador_numeq.id_operador
			INNER JOIN cr_numeq AS num_eq ON cr_operador_numeq.id_numeq = num_eq.id_numeq
		';
		$where = '
			viv.cat_status_viaje = 171
			AND
			viv.cat_tipotemporicidad = 184
			AND crou.status_operador_unidad = 198
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
			INNER JOIN cr_operador_unidad as crou ON viv.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_operador ON crou.id_operador = cr_operador.id_operador
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
			AND
				crou.status_operador_unidad = 198
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
			INNER JOIN cr_operador_unidad as crou ON viv.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_operador ON crou.id_operador = cr_operador.id_operador
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
			AND
				crou.status_operador_unidad = 198
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
			INNER JOIN cr_operador_unidad as crou ON viv.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_operador ON crou.id_operador = cr_operador.id_operador
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
			AND
				crou.status_operador_unidad = 198
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
			INNER JOIN cr_operador_unidad as crou ON viv.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_operador ON crou.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador.id_operador = cr_operador_numeq.id_operador
			INNER JOIN cr_numeq AS num_eq ON cr_operador_numeq.id_numeq = num_eq.id_numeq
		';
		$where = '
			viv.cat_status_viaje = 195
			AND
				viv.cat_tipotemporicidad = 162
			AND
				vcd.fecha_requerimiento >= DATE_ADD(NOW(),	INTERVAL 1 DAY)
			AND
				crou.status_operador_unidad = 198
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
	function viajeVigente($id_viaje){
		$sql = "
			SELECT
				vcd.fecha_requerimiento AS time
			FROM
				vi_viaje AS viv
			INNER JOIN vi_viaje_detalle AS vcd ON vcd.id_viaje = viv.id_viaje
			WHERE
				viv.id_viaje = $id_viaje
			AND viv.cat_status_viaje = 195
			AND viv.cat_tipotemporicidad = 162
			AND NOW() < vcd.fecha_requerimiento
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		if($query->rowCount()>=1){
			return true;
		}else{
			return false;
		}
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
			INNER JOIN cr_operador_unidad as crou ON viv.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_operador ON crou.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador.id_operador = cr_operador_numeq.id_operador
			INNER JOIN cr_numeq AS num_eq ON cr_operador_numeq.id_numeq = num_eq.id_numeq
		';
		$where = '
			viv.cat_status_viaje = 195
			AND
				viv.cat_tipotemporicidad = 162
			AND
				NOW() > vcd.fecha_requerimiento
			AND
				crou.status_operador_unidad = 198
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
			INNER JOIN cr_operador_unidad as crou ON viv.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_operador ON crou.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_numeq ON cr_operador.id_operador = cr_operador_numeq.id_operador
			INNER JOIN cr_numeq AS num_eq ON cr_operador_numeq.id_numeq = num_eq.id_numeq
		';
		$where = '
			viv.cat_status_viaje = 172
			AND
				crou.status_operador_unidad = 198
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
					$row[ $column['dt'] ] = $data[$i][$name_column];
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
					$salida .= '<a onclick="dataViaje('.$id_viaje.')" href="javascript:;" data-rel="tooltip" data-original-title="Datos del viaje"><i class="fa fa-question-circle" style="font-size:1.4em; color:#0080ff;"></i></a>&nbsp;&nbsp;';
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
					$salida .= '<a onclick="dataViaje('.$id_viaje.')" href="javascript:;" data-rel="tooltip" data-original-title="Datos del viaje"><i class="fa fa-question-circle" style="font-size:1.4em; color:#0080ff;"></i></a>&nbsp;&nbsp;';

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

					$salida .= '<a href="javascript:;" onclick="costos_adicionales('.$id_viaje.')" data-rel="tooltip" data-original-title="Costos adicionales"><i class="icofont icofont-money-bag" style="font-size:1.4em; color:#008c23;"></i></a>&nbsp;&nbsp;';

					$salida .= '<a href="javascript:;" onclick="cambiar_tarifa('.$id_viaje.')" data-rel="tooltip" data-original-title="Cambiar tarifa"><i class="icofont icofont-exchange" style="font-size:1.4em; color:#008c23;"></i></a>&nbsp;&nbsp;';

					$salida .= '<a onclick="dataViaje('.$id_viaje.')" href="javascript:;" data-rel="tooltip" data-original-title="Datos del viaje"><i class="fa fa-question-circle" style="font-size:1.4em; color:#0080ff;"></i></a>&nbsp;&nbsp;';

					$salida .= '</div>';

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

					$salida .= '<a onclick="dataViaje('.$id_viaje.')" href="javascript:;" data-rel="tooltip" data-original-title="Datos del viaje"><i class="fa fa-question-circle" style="font-size:1.4em; color:#0080ff;"></i></a>&nbsp;&nbsp;';

					$row[ $column['dt'] ] = $salida;
				}else if(isset( $column['bin'])){

					$a = ($data[$i][ 'apartado' ] == 1)? '<a data-rel="tooltip" data-original-title="Salida programada" href="javascript:;"><i class="icofont icofont-delivery-time bigger-200 brown darken-1"></i></a>':'<a data-rel="tooltip" data-original-title="Salida inmediata" href="javascript:;"><i class="icofont icofont-fast-delivery bigger-200 blue"></i></a>';

					$salida = $a;
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
					$salida .= '<a onclick="dataViaje('.$id_viaje.')" href="javascript:;" data-rel="tooltip" data-original-title="Datos del viaje"><i class="fa fa-question-circle" style="font-size:1.4em; color:#0080ff;"></i></a>&nbsp;&nbsp;';

					$row[ $column['dt'] ] = $salida;
				}else if(isset( $column['bin'])){

					$a = ($data[$i][ 'apartado' ] == 1)? '<a data-rel="tooltip" data-original-title="Salida programada" href="javascript:;"><i class="icofont icofont-delivery-time bigger-200 brown darken-1"></i></a>':'<a data-rel="tooltip" data-original-title="Salida inmediata" href="javascript:;"><i class="icofont icofont-fast-delivery bigger-200 blue"></i></a>';

					$salida = $a;
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

					$salida .= '<a onclick="dataViaje('.$id_viaje.')" href="javascript:;" data-rel="tooltip" data-original-title="Datos del viaje"><i class="fa fa-question-circle" style="font-size:1.4em; color:#0080ff;"></i></a>&nbsp;&nbsp;';

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
					$salida .= '<a onclick="dataViaje('.$id_viaje.')" href="javascript:;" data-rel="tooltip" data-original-title="Datos del viaje"><i class="fa fa-question-circle" style="font-size:1.4em; color:#0080ff;"></i></a>&nbsp;&nbsp;';
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
					$salida .= '<a onclick="dataViaje('.$id_viaje.')" href="javascript:;" data-rel="tooltip" data-original-title="Datos del viaje"><i class="fa fa-question-circle" style="font-size:1.4em; color:#0080ff;"></i></a>&nbsp;&nbsp;';

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
					$salida .= '<a onclick="dataViaje('.$id_viaje.')" href="javascript:;" data-rel="tooltip" data-original-title="Datos del viaje"><i class="fa fa-question-circle" style="font-size:1.4em; color:#0080ff;"></i></a>&nbsp;&nbsp;';

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
					$salida .= '<a onclick="dataViaje('.$id_viaje.')" href="javascript:;" data-rel="tooltip" data-original-title="Datos del viaje"><i class="fa fa-question-circle" style="font-size:1.4em; color:#0080ff;"></i></a>&nbsp;&nbsp;';

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
					$row[ $column['dt'] ] = $data[$i][$name_column];
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
              $numero = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			$count = 1;
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

					$id_operador = $data[$i][ 'id_operador' ];
                                   $num = $data[$i][ 'num' ];
                                   $id_operador_unidad = $data[$i][ 'id_operador_unidad' ];

                                   $salida = '';
						if(Controlador::tiene_permiso('Operacion|activar_c2')){
                                                 $salida .= '<a onclick="modal_activar_c02('.$id_operador.','.$num.','.$id_operador_unidad.')" data-rel="tooltip" data-original-title="Fin de operaciones">
							<i class="fa fa-sign-out" style="font-size:1.8em; color:green;"></i>
							</a>&nbsp;&nbsp;';
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

                                   $id_operador = $data[$i][ 'id_operador' ];
                                   $num = $data[$i][ 'num' ];

                                   $salida = '';

						if(Controlador::tiene_permiso('Operacion|desactivar_f06')){
                                                 $salida .= '<a onclick="modal_desactivar_f06('.$id_operador.','.$num.')" data-rel="tooltip" data-original-title="Activar Operador">
							<i class="fa fa-check-circle-o" style="font-size:1.8em; color:green;"></i>
							</a>&nbsp;&nbsp;';
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

					$id_operador = $data[$i][ 'id_operador' ];
                                   $num = $data[$i][ 'num' ];

                                   $salida = '';
						if(Controlador::tiene_permiso('Operacion|activar_c1')){
                                                 $salida .= '<a onclick="modal_activar_c1('.$id_operador.','.$num.')" data-rel="tooltip" data-original-title="Inicio de operaciones">
							<i class="fa fa-sign-in" style="font-size:1.8em; color:green;"></i>
							</a>&nbsp;&nbsp;';
						}
                                          if(Controlador::tiene_permiso('Operacion|activar_f6')){
                                                 $salida .= '<a onclick="modal_activar_f6('.$id_operador.','.$num.')" data-rel="tooltip" data-original-title="Suspender Operador">
							<i class="fa fa-ban" style="font-size:1.8em; color:red;"></i>
							</a>&nbsp;&nbsp;';
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
