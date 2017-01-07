<?php
use Pubnub\Pubnub;
class MobileModel
{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
        }
    }
	function store($claves, OperacionModel $operacion, $tknses){
		$output[0] = array('resp' => false);
		foreach($claves as $num => $clave){
			$tokenStore = self::tokenStore($clave['token']);
			if($tokenStore == 0){
				switch ($clave['clave']) {
					case 'A2':/*Servicio por tiempo*/
						$output[$num] = self::storeToSync($clave);
						break;
					case 'A10':/*Me dirijo al punto*/
						$output[$num] = self::storeToSync($clave);
						break;
					case 'A11':/*En el punto*/
						self::updateArribo($clave);
						$output[$num] = self::storeToSync($clave);
						break;
					case 'A14':/*Abandono de servicio*/
						self::storeToSyncRide($clave['id_usuario'],$clave['token'],116,$clave['id_operador_unidad']);
						
						$setear_status_viaje['id_viaje'] = $clave['id_viaje'];
						$setear_status_viaje['stat'] = 188;
						$setear_status_viaje['origen'] = 'ModelMobile';
						$operacion->setear_status_viaje($setear_status_viaje);
						
						$output[$num] = self::storeToSync($clave);
						break;
					case 'C1':/*Inicio de labores*/
						
						$clave['id_episodio'] = self::getEpisodio($clave['id_operador'],$clave['id_usuario'],$clave['id_operador_unidad'],$tknses);
						
						$array = array(
							'clave'					=> $clave['clave'],
							'id_episodio' 			=> $clave['id_episodio'],
							'id'					=> $clave['id'],
							'viaje' 				=> array('id_viaje' => 'IR002'),
							'resp' 					=> true,
							'token'					=> $clave['token']
						);
						
						self::storeToSyncRide($clave['id_usuario'],$clave['token'],124,$clave['id_operador_unidad']);
						$storeToSync = $clave + $array;
						self::storeToSync($storeToSync);
						$output[$num] = $array;
						break;
					case 'C2':/*Fin de labores*/
						$id_base = self::getIdBase($clave['estado2']);
						self::cerrarEpisodio($clave['id_episodio'],$clave['id_usuario']);
						if($id_base != $clave['estado2']){
							self::cordonCompletado($clave['id_usuario'],$clave['id_operador_unidad'],$id_base);
						}
						self::storeToSyncRide($clave['id_usuario'],$clave['token'],123,$clave['id_operador_unidad']);
						$output[$num] = self::storeToSync($clave);
						break;
					case 'C3':/*Inicio alimentos*/
						$output[$num] = self::storeToSync($clave);
						break;
					case 'C6':/*Servicio cancelado*/
						self::storeToSyncRide($clave['id_usuario'],$clave['token'],116,$clave['id_operador_unidad']);
						
						$setear_status_viaje['id_viaje'] = $clave['id_viaje'];
						$setear_status_viaje['cat_cancelaciones'] = 175;
						$setear_status_viaje['stat'] = 173;
						$setear_status_viaje['origen'] = 'ModelMobile';
						$operacion->setear_status_viaje($setear_status_viaje);
						
						$output[$num] = self::storeToSync($clave);
						break;						
					case 'C8':/*Servicio abordo*/
						$output[$num] = self::storeToSync($clave);
						break;
					case 'C9':/*Servicio concluido*/
						self::updateFinalizacion($clave);
						self::storeToSyncRide($clave['id_usuario'],$clave['token'],116,$clave['id_operador_unidad']);
						
						$setear_status_viaje['id_viaje'] = $clave['id_viaje'];
						$setear_status_viaje['stat'] = 172;
						$setear_status_viaje['origen'] = 'ModelMobile';
						$operacion->setear_status_viaje($setear_status_viaje);
						
						$output[$num] = self::storeToSync($clave);
						break;
					case 'C10':/*inicio de escala*/
						$output[$num] = self::storeToSync($clave);
						break;
					case 'C11':/*Fin de escala*/
						$output[$num] = self::storeToSync($clave);
						break;
					case 'C12':/*Cambio de ruta*/
						$output[$num] = self::storeToSync($clave);
						break;
					case 'C13':/*Fin de alimentos*/
						$output[$num] = self::storeToSync($clave);
						break;
					case 'C14':/*Destino parcial*/
						$output[$num] = self::storeToSync($clave);
						break;
					case 'F12':/*En cordon*/
						$output[$num] = self::storeToSync($clave);
						break;
					case 'F13':/*Salida por sitio*/
						$output[$num]['clave'] = 'F13';
						$output[$num] = self::storeToSync($clave);					
						break;
					case 'F14':/*Solicitar cordon*/
						$output[$num] = self::storeToSync($clave);
						$output[$num]['clave'] = 'F14';
						$output[$num]['base'] =  $clave['estado2'];
						self::storeToSyncRide($clave['id_usuario'],$clave['token'],132,$clave['id_operador_unidad']);
						break;
					case 'F15':/*Servicio al aire*/
						$output[$num] = self::storeToSync($clave);
						
						$output[$num]['total'] = 14;
						$output[$num]['turno'] = 14;
						$output[$num]['estim'] = '90';
						
						break;
					case 'F16':/*Modificar modo viaje*/
						$output[$num] = self::storeToSync($clave);
						
						$output[$num]['total'] = 14;
						$output[$num]['turno'] = 14;
						$output[$num]['estim'] = '90';
						
						break;
					case 'F17':/*Marcar mensaje como leido*/
						self::setMsgRead($clave['estado2'],$clave['id_usuario']);
						$output[$num] = self::storeToSync($clave);
						break;
					case 'R1':/*Establecer estado de la pantalla del movil*/
						$output[$num] = self::storeToSync($clave);
						break;
					case 'R2':/*Acuse de recibo de cordon*/
						if($clave['id_operador_unidad'] != 'select'){
							self::firmarAcuseCordon($clave['id_operador_unidad']);
						}
						$output[$num] = self::storeToSync($clave);
						break;
					case 'R5':/*Acuse de A10*/
						$id_base = ($clave['estado2'] == 'B1')?1:2;
						$id_viaje = $clave['id_viaje'];
						self::cordonCompletado($clave['id_usuario'],$clave['id_operador_unidad'],$id_base);
						self::servicioAsignado($id_viaje);
						self::storeToSyncRide($clave['id_usuario'],$clave['token'],156,$clave['id_operador_unidad'],false,false,true);
						$output[$num] = self::storeToSync($clave);
						break;
					case 'R6':/*Acuse de F15*/
						$id_viaje = $clave['id_viaje'];
						
						self::servicioAsignado($id_viaje);
						self::storeToSyncRide($clave['id_usuario'],$clave['token'],157,$clave['id_operador_unidad'],false,false,true);
						$output[$num] = self::storeToSync($clave);
						break;
					case 'R7':/*Acuse de F13*/
						$id_base = ($clave['estado2'] == 'B1')?1:2;
						self::cordonCompletado($clave['id_usuario'],$clave['id_operador_unidad'],$id_base);
						$id_viaje = $clave['id_viaje'];
						self::servicioAsignado($id_viaje);
						self::storeToSyncRide($clave['id_usuario'],$clave['token'],158,$clave['id_operador_unidad'],false,false,true);
						$output[$num] = self::storeToSync($clave);
						break;
					case 'R8':/*Acuse de F14*/
						self::storeToSyncRide($clave['id_usuario'],$clave['token'],159,$clave['id_operador_unidad'],false,false,true);
						$output[$num] = self::storeToSync($clave);
						break;
					case 'R10':/*Acuse de C1*/
						self::storeToSyncRide($clave['id_usuario'],$clave['token'],161,$clave['id_operador_unidad'],false,false,true);
						$output[$num] = self::storeToSync($clave);
						break;
					case 'R12':/*Acuse de C6*/
						self::storeToSyncRide($clave['id_usuario'],$clave['token'],186,$clave['id_operador_unidad'],false,false,true);
						$output[$num] = self::storeToSync($clave);
						break;
					case 'R13':/*Acuse de A14*/
						self::storeToSyncRide($clave['id_usuario'],$clave['token'],187,$clave['id_operador_unidad'],false,false,true);
						$output[$num] = self::storeToSync($clave);
						break;
					case 'R14':/*Acuse de A19*/
						$id_viaje = $clave['id_viaje'];
						
						self::servicioAsignado($id_viaje);
						self::storeToSyncRide($clave['id_usuario'],$clave['token'],196,$clave['id_operador_unidad'],false,false,true);
						$output[$num] = self::storeToSync($clave);
						break;
					default:
						$output[$num] = self::storeToSync($clave);
						break;
				}
			}else{
				$output[$num] = array('resp' => true, 'id' => $clave['id'], 'token' => $clave['token'],'clave' => $clave['clave'], 'insert' => false);
			}
		}
		
		$emitir =  json_encode($output);
		self::transmitir($emitir,'sync'.$clave['id_operador']);
	}
	function getIdBase($clave){
		$sql = "
			SELECT
				crb.id_base
			FROM
				cr_bases AS crb
			WHERE
				crb.clave = '".$clave."'
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$return = '';
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$return = $row->id_base;
			}
		}else{
			$return = $clave;
		}
		return $return;	
	}	
	function verify_token($tknses){
		$qry = "
			SELECT
				cre.id_episodio
			FROM
				cr_episodios AS cre
			WHERE
				cre.token_session = '$tknses'
				AND
				cre.fin IS NULL
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount() != 1){
			exit();
		}
	}
	function updateFinalizacion($clave){
		$sql = "
			UPDATE vi_viaje_detalle
			SET
			 fecha_finalizacion	= '".$clave['timestamp']."'
			WHERE
				id_viaje = ".$clave['id_viaje']."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	function updateArribo($clave){
		$sql = "
			UPDATE vi_viaje_detalle
			SET
			 fecha_arribo	= '".$clave['timestamp']."'
			WHERE
				id_viaje = ".$clave['id_viaje']."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	function servicioAsignado($id_viaje){
		$sql = "
			UPDATE vi_viaje
			SET
			 cat_status_viaje	= '179'
			WHERE
				id_viaje = ".$id_viaje."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	function getIdOperadorUnidadEpisode($id,$type){
		$sql = "
			SELECT
				cre.id_operador_unidad
			FROM
				cr_episodios AS cre
			WHERE
				cre.$type = $id
			AND cre.fin IS NULL
			ORDER BY
				cre.id_episodio DESC
			LIMIT 0,
			 1
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$return = '';
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$return = $row->id_operador_unidad;
			}
		}
		return $return;	
	}
	function getDataActual($id_operador_unidad){
		$qry = "
			SELECT
				*
			FROM
				cr_sync as syc
			WHERE
				(syc.clave = 'F14'
			OR syc.clave = 'C2'
			OR syc.clave = 'C1')
			AND syc.id_operador_unidad = $id_operador_unidad
			ORDER BY
				syc.id_sync DESC
			LIMIT 0,
			 1
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$array = array('clave' => 'XX');
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$array['clave'] 		     = $row->clave;
				$array['base'] 		         = $row->estado2;
				$array['id_base'] 	         = ($row->estado2 == 'B1')?1:2;
				$array['id_operador'] 		 = $row->id_operador;	
				$array['id_episodio'] 	     = $row->id_episodio;
				$array['serie'] 	         = $row->serie;
				$array['id_usuario'] 	     = self::getIdUsuario($row->id_operador_unidad);
				$array['token'] 	     	 = $row->token;
				$array['id_operador_unidad'] = $row->id_operador_unidad;
			}
		}
		return $array;
	}
	function getIdUsuario($id_operador_unidad){
		$qry = "
			SELECT
				crop.id_usuario
			FROM
				cr_operador_unidad AS crou
			INNER JOIN cr_operador AS crop ON crou.id_operador = crop.id_operador
			WHERE
				crou.id_operador_unidad = $id_operador_unidad
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$data = $query->fetchAll();
		foreach ($data as $row) {
			return $row->id_usuario;
		}
	}	
	function getIdEpisodio($id_operador_unidad){
		$qry = "
			SELECT
				cre.id_episodio
			FROM
				cr_operador_unidad as crou
			INNER JOIN cr_episodios as cre ON crou.id_operador = cre.id_operador
			WHERE
				crou.id_operador_unidad = ".$id_operador_unidad."
				AND crou.status_operador_unidad = 198
			ORDER BY
				cre.id_episodio DESC
			LIMIT 0,
			 1
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$data = $query->fetchAll();
		foreach ($data as $row) {
			return $row->id_episodio;
		}
	}	
	function initEpisodio($id_episodio){
		$qry = "
			SELECT
				inicio
			FROM
				cr_episodios
			WHERE
				id_episodio = $id_episodio
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$fila = array();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				return $row->inicio;
			}
		}
	}
	static function diferenciaFechas($init,$end){
		$datetime1 = new DateTime($init);
		$datetime2 = new DateTime($end);
		$dteDiff = $datetime1->diff($datetime2);
		return $dteDiff->format("%H:%I:%S");
	}
	function cerrarEpisodio($id_episodio,$id_usuario){
		$init = self::initEpisodio($id_episodio);
		$fin = date("Y-m-d H:i:s");
		$tiempo = self::diferenciaFechas($init , $fin);
		$sql = "
			UPDATE cr_episodios
			SET 
			 fin 			= '".$fin."',
			 tiempo			= '".$tiempo."',
			 user_mod 		= ".$id_usuario."
			WHERE
				id_episodio = ".$id_episodio."
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	function closeEpisodeOpen($id_operador,$id_usuario){
		$qry = "
			SELECT
				ep.id_episodio
			FROM
				cr_episodios AS ep
			WHERE
				ep.id_operador = $id_operador
			AND ep.fin IS NULL
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				self::cerrarEpisodio($row->id_episodio,$id_usuario);
			}
		}
	}
	function getEpisodio($id_operador,$id_usuario,$id_operador_unidad,$tknses){
		self::closeEpisodeOpen($id_operador,$id_usuario);
		$sql = "
			INSERT INTO cr_episodios (
				id_operador,
				inicio,
				token_session,
				id_operador_unidad,
				user_alta,
				fecha_alta
			) VALUES (
				:id_operador,
				:inicio,
				:token_session,
				:id_operador_unidad,
				:user_alta,
				:fecha_alta
			)";
		$query = $this->db->prepare($sql);
		$query->execute(
			array(
				':id_operador' 			=> 	$id_operador,
				':inicio' 				=> 	date("Y-m-d H:i:s"),
				':token_session'		=> 	$tknses,
				':id_operador_unidad'	=> 	$id_operador_unidad,
				':user_alta' 			=> 	$id_usuario,
				':fecha_alta' 			=> 	date("Y-m-d H:i:s")
			)
		);
		$lastInsertId = $this->db->lastInsertId();
		return $lastInsertId;
	}
	function cve_store($id_operador_unidad){
		$qry = "
			SELECT
				cm_catalogo.etiqueta,
				cr_sync_ride.valor,
				cr_sync_ride.token
			FROM
				cm_catalogo
			INNER JOIN cr_sync_ride ON cr_sync_ride.cat_cve_store = cm_catalogo.id_cat
			WHERE
				cr_sync_ride.id_operador_unidad = $id_operador_unidad
			AND cr_sync_ride.procesado = 0
			ORDER BY
				cr_sync_ride.id_sync_ride DESC
			LIMIT 0,
			 1
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$array = array();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$array['etiqueta'] =  $row->etiqueta;
				$array['valor'] =  $row->valor;
				$array['token'] =  $row->token;
			}
		}
		return $array;
	}
	function storeToSyncRide($id_usuario,$token,$clave,$id_operador_unidad,$procesar_precedentes = true, $valor = false, $procesar = false){
		try {
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->db->setAttribute(PDO::ATTR_PERSISTENT,true);
			$this->db->beginTransaction();
			if($procesar_precedentes){
				date_default_timezone_set('America/Mexico_City');
				$sqlupd = "
					UPDATE `cr_sync_ride`
					SET
					 `procesado` = 1,
					 `user_mod` = NULL
					WHERE
						`id_operador_unidad` = ".$id_operador_unidad." AND
						`procesado` = 0
				";
				$queryupd = $this->db->prepare($sqlupd);
				$ok = $queryupd->execute();
			}
			
			$set = ($valor)?$valor:'';
			$prc = ($procesar)?1:0;
			date_default_timezone_set('America/Mexico_City');
			$sql = "
				INSERT INTO `cr_sync_ride` (
					`token`,
					`id_operador_unidad`,
					`cat_cve_store`,
					`valor`,
					`procesado`,
					`user_alta`,
					`fecha_alta`
				)
				VALUES
					(
						'".$token."',
						".$id_operador_unidad.",
						".$clave.",
						'".$set."',
						'".$prc."',
						".$id_usuario.",
						'".date("Y-m-d H:i:s")."'
					);
			";
			$query = $this->db->prepare($sql);
			$ok = $query->execute();
			$this->db->commit();
		} catch (Exception $e) {
			$this->db->rollBack();
		}
	}	
	function cordon_operadores($base){
		$qry = "
			SELECT
				cr_numeq.num,
				concat(
					fw_usuarios.nombres,
					' ',
					fw_usuarios.apellido_paterno,
					' ',
					fw_usuarios.apellido_materno
				) AS nombre,
				cr_modelos.modelo,
				cr_unidades.color
			FROM
				cr_cordon
			INNER JOIN cr_operador_unidad as crou ON cr_cordon.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_operador ON crou.id_operador = cr_operador.id_operador
			INNER JOIN fw_usuarios ON cr_operador.id_usuario = fw_usuarios.id_usuario
			INNER JOIN cr_unidades ON crou.id_unidad = cr_unidades.id_unidad
			INNER JOIN cr_modelos ON cr_unidades.id_modelo = cr_modelos.id_modelo
			INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = cr_operador.id_operador
			INNER JOIN cr_numeq ON cr_operador_numeq.id_numeq = cr_numeq.id_numeq
			WHERE
				cr_cordon.id_base = $base
				AND crou.status_operador_unidad = 198
			AND (
				cr_cordon.cat_statuscordon = 113
				OR cr_cordon.cat_statuscordon = 115
			)
			ORDER BY
				cr_cordon.cat_statuscordon DESC,
				cr_cordon.id_cordon ASC
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$fila = array();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();

			foreach ($data as $row) {
				array_push($fila, '  [  '. $row->num .'  ] '. $row->nombre.', '. $row->modelo . ' ' .  $row->color.'');
			}
		}
		return $fila;
	}
	function signCordon($id_operador_unidad){
		$qry = "
			SELECT
				valor
			FROM
				cr_sync_ride
			WHERE
				id_operador_unidad = $id_operador_unidad
			AND procesado = 0
			AND cat_cve_store = 122
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				return $row->valor;
			}
		}else{
			return false;
		}
	}
	function firmarAcuseCordon($id_operador_unidad){
			date_default_timezone_set('America/Mexico_City');
			$sql = "
			UPDATE cr_sync_ride
			SET 
					valor	=	'SIGNED'
			WHERE
				cat_cve_store = 122
				AND procesado = 0
				AND id_operador_unidad = $id_operador_unidad
			";
			$query = $this->db->prepare($sql);
			$query->execute();
	}	
	function solicitarAcuseCordon(){
			/*No lleva identificador por que se usa para actualizar el cordon en los celulares*/
			date_default_timezone_set('America/Mexico_City');
			$sql = "
			UPDATE cr_sync_ride
			SET 
					valor	=	'UNSIGNED'
			WHERE
				cat_cve_store = 122
				AND procesado = 0
			";
			$query = $this->db->prepare($sql);
			$query->execute();
	}
	function cordonCompletado($id_usuario,$id_operador_unidad,$id_base){
		if(self::turno($id_operador_unidad,$id_base) != 'No formado'){
			$sql = "
			UPDATE cr_cordon
			SET 
					cat_statuscordon	=	'". 114 ."',
					salida				=	'".date("Y-m-d H:i:s")."',
					user_mod 			= 	'".$id_usuario."'
			WHERE
				id_operador_unidad = ".$id_operador_unidad."
				AND cat_statuscordon <> 114
			";
			$query = $this->db->prepare($sql);
			$query_resp = $query->execute();
			self::solicitarAcuseCordon();
			self::firmarAcuseCordon($id_operador_unidad);
		}
	}
	function exitCordonFromLogin($id_usuario,$id_operador_unidad){
		$dataFull = "
			SELECT
				cro.id_operador,
				base.id_operador_unidad
			FROM
				cr_operador as cro
			INNER JOIN cr_operador_unidad AS indice ON indice.id_operador = cro.id_operador
			INNER JOIN cr_operador_unidad AS base ON base.id_operador = cro.id_operador
			WHERE
				indice.id_operador_unidad = $id_operador_unidad
				AND base.status_operador_unidad = 198
		";
		$datos = $this->db->prepare($dataFull);
		$datos->execute();
		$fila = array();
		if($datos->rowCount()>=1){
			$data = $datos->fetchAll();
			foreach ($data as $row) {
				$sql = "
				UPDATE cr_cordon
				SET 
						cat_statuscordon	=	'". 114 ."',
						salida				=	'".date("Y-m-d H:i:s")."',
						user_mod 			= 	'".$id_usuario."'
				WHERE
					id_operador_unidad = ".$row->id_operador_unidad."
					AND cat_statuscordon <> 114
				";
				$query = $this->db->prepare($sql);
				$query_resp = $query->execute();
				self::solicitarAcuseCordon();
				self::firmarAcuseCordon($row->id_operador_unidad);
			}
		}
	}
	function verificaCveStore($id_operador_unidad){
		$qry = "
			SELECT
				cmc.etiqueta
			FROM
				cm_catalogo AS cmc
			INNER JOIN cr_sync_ride ON cr_sync_ride.cat_cve_store = cmc.id_cat
			WHERE
				cr_sync_ride.id_operador_unidad = ".$id_operador_unidad."
			AND cr_sync_ride.procesado = 0
			ORDER BY
				cr_sync_ride.id_sync_ride DESC
			LIMIT 0,
			 1
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$fila = array();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				return $row->etiqueta;
			}
		}
	}
	private function tokenStore($token){
		$qry = "
			SELECT
				count(cr_sync.token) AS tok_en
			FROM
				cr_sync
			WHERE
				cr_sync.token = '".$token."'
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				return $row->tok_en;
			}
		}
	}
	function formarse_directo($token,$id_operador_unidad,$id_base,$statuscordon){
		$sql = "
			INSERT INTO cr_cordon (
				id_operador_unidad,
				id_episodio,
				id_base,
				cat_statuscordon,
				llegada,
				token,
				user_alta,
				fecha_alta
			) VALUES (
				:id_operador_unidad,
				:id_episodio,
				:id_base,
				:cat_statuscordon,
				:llegada,
				:token,
				:user_alta,
				:fecha_alta
			)";
		$query = $this->db->prepare($sql);
		$query->execute(
			array(
				':id_operador_unidad' 	=> 	$id_operador_unidad,
				':id_episodio' 			=> 	self::getIdEpisodio($id_operador_unidad),
				':id_base' 				=> 	$id_base,
				':cat_statuscordon' 	=> 	$statuscordon,
				':llegada' 				=> 	date("Y-m-d H:i:s"),
				':token' 				=> 	$token,
				':user_alta' 			=> 	$_SESSION['id_usuario'],
				':fecha_alta' 			=> 	date("Y-m-d H:i:s")
			)
		);
		self::solicitarAcuseCordon();
	}
	function formarse($clave){
		$id_base = ($clave['estado2'] == 'B1')?1:2;
		
		if(self::turno($clave['id_operador_unidad'],$id_base) == 'No formado'){
			$cat_statuscordon = (self::verificaCveStore($clave['id_operador_unidad']) == 'C6')?115:113;

			$id_operador_unidad = $clave['id_operador_unidad'];

			$sql = "
				INSERT INTO cr_cordon (
					id_operador_unidad,
					id_episodio,
					id_base,
					cat_statuscordon,
					llegada,
					token,
					user_alta,
					fecha_alta
				) VALUES (
					:id_operador_unidad,
					:id_episodio,
					:id_base,
					:cat_statuscordon,
					:llegada,
					:token,
					:user_alta,
					:fecha_alta
				)";
			$query = $this->db->prepare($sql);
			$query->execute(
				array(
					':id_operador_unidad' 	=> 	$id_operador_unidad,
					':id_episodio' 			=> 	$clave['id_episodio'],
					':id_base' 				=> 	$id_base,
					':cat_statuscordon' 	=> 	$cat_statuscordon,
					':llegada' 				=> 	date("Y-m-d H:i:s"),
					':token' 				=> 	$clave['token'],
					':user_alta' 			=> 	$clave['id_usuario'],
					':fecha_alta' 			=> 	date("Y-m-d H:i:s")
				)
			);
			$turno = self::turno($id_operador_unidad,$id_base);

			self::storeToSyncRide($clave['id_usuario'],$clave['token'],122,$id_operador_unidad);
			self::solicitarAcuseCordon();				
			return $turno;
			
		}else{
			return 'Ya estaba formado';
		}
	}
	function turno($id_operador_unidad,$id_base){
		$qry = "
			SELECT
				id_cordon,
				id_operador_unidad,
				cat_statuscordon
			FROM
				cr_cordon
			WHERE
				cr_cordon.id_base = $id_base
			AND (
				cr_cordon.cat_statuscordon = 113
				OR cr_cordon.cat_statuscordon = 115
			)
			ORDER BY
				cr_cordon.cat_statuscordon DESC,
				cr_cordon.id_cordon ASC
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$numero = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			$count = 1;
			foreach ($data as $row) {
				if($row->id_operador_unidad == $id_operador_unidad){
					$numero = $count;
				}
				$count++;
			}
		}
		if($numero != 0){
			return $numero;
		}else{
			return 'No formado';
		}
	}
	function set2enc6($id_base){
		$qry = "
			SELECT
				id_cordon,
				id_operador_unidad,
				cat_statuscordon
			FROM
				cr_cordon
			WHERE
				cr_cordon.id_base = $id_base
			AND (
				cr_cordon.cat_statuscordon = 113
				OR cr_cordon.cat_statuscordon = 115
			)
			ORDER BY
				cr_cordon.cat_statuscordon DESC,
				cr_cordon.id_cordon ASC
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			$count = 1;
			foreach ($data as $row) {
				if($count == 2){
					self::setCordonStatus($row->id_operador_unidad,115);
				}
				$count++;
			}
		}
	}
	function setCordonStatus($id_operador_unidad,$stat){
		$sql = "
		UPDATE cr_cordon
		SET 
				cat_statuscordon	=	'".$stat."',
				user_mod 			= 	'".$_SESSION['id_usuario']."'
		WHERE
			id_operador_unidad = ".$id_operador_unidad."
			AND
			cat_statuscordon = 113
		";
		$query = $this->db->prepare($sql);
		$query_resp = $query->execute();
	}
	function ultimaPositionByIdOperador($id_operador){
		$sql="
			SELECT
				gps.latitud,
				gps.longitud,
				gps.tiempo,
				gps.`timestamp`,
				gps.bateria
			FROM
				gps
			WHERE
				gps.id_operador = $id_operador
			ORDER BY
				gps.id_gps DESC
			LIMIT 0,
			 1
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$point = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($point as $row) {
				return '{"lat":'.$row->latitud.',"lng":'.$row->longitud.',"time":"'.$row->timestamp.'","bateria":"'.$row->bateria.'","times":"'.$row->timestamp.'"}';
			}
		}
	}
	function enGeocercas($id_operador){
			$position = self::ultimaPositionByIdOperador($id_operador);
			$position = json_decode($position);
			
			$geoVars['latitud_act'] 	= $position->lat;
			$geoVars['longitud_act'] 	= $position->lng;
			
			$ahora = date("Y-m-d H:i:s");
			$timeFresh = self::minutosDiferencia($position->times,$ahora);
				$time_return = ($timeFresh < 3)?true:false;
				
			$enGeocerca1 = self::enGeocercaNum($geoVars,'B1');
				$ret_geo1 = ($enGeocerca1 == 'in')?true:false;
				
			$enGeocerca2 = self::enGeocercaNum($geoVars,'B2');
				$ret_geo2 = ($enGeocerca2 == 'in')?true:false;
				
			return array('time' => $time_return, 'geo1' => $ret_geo1, 'geo2' => $ret_geo2);
	}
	function enGeocercaNum($geoVars,$base){
		require_once( '../vendor/geocerca.php' );
		$geoCerca = new geoCerca();
		$gt_goc = self::getGeocerca($base);
		$poligono = $gt_goc['geocerca'];
		return $geoCerca->puntoEnPoligono(''.$geoVars['latitud_act'].', '.$geoVars['longitud_act'].'', $poligono);
	}	
	function enGeocerca($clave){
		require_once( '../vendor/geocerca.php' );
		$geoCerca = new geoCerca();
		$gt_goc = self::getGeocerca($clave['estado2']);
		$poligono = $gt_goc['geocerca'];
		return $geoCerca->puntoEnPoligono(''.$clave['latitud_act'].', '.$clave['longitud_act'].'', $poligono);
	}
	function getGeocerca($clave){
		$qry = "
			SELECT
				latitud,
				longitud,
				geocerca
			FROM
				cr_bases
			WHERE
				clave = '".$clave."'
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$array = array();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$array['latitud'] = $row->latitud;
				$array['longitud'] = $row->longitud;
				$array['geocerca'] = $row->geocerca;
			}
		}
		return $array;
	}
	function storeToSync($clave){
		$sql = "
			INSERT INTO cr_sync (
				accurate,
				clave,
				estado1,
				estado2,
				estado3,
				estado4,
				id_indexeddb,
				id_episodio,
				id_operador,
				id_operador_unidad,
				id_viaje,
				latitud,
				longitud,
				motivo,
				serie,
				tiempo,
				timestamp,
				token,
				origen
			) VALUES (
				:accurate,
				:clave,
				:estado1,
				:estado2,
				:estado3,
				:estado4,
				:id_indexeddb,
				:id_episodio,
				:id_operador,
				:id_operador_unidad,
				:id_viaje,
				:latitud,
				:longitud,
				:motivo,
				:serie,
				:tiempo,
				:timestamp,
				:token,
				:origen
			)";
		$query = $this->db->prepare($sql);
		$ok = $query->execute(
			array(
				':accurate' => 			$clave['accurate'],
				':clave' => 			$clave['clave'],
				':estado1' => 			$clave['estado1'],
				':estado2' => 			$clave['estado2'],
				':estado3' => 			$clave['estado3'],
				':estado4' => 			$clave['estado4'],
				':id_indexeddb' => 		$clave['id'],
				':id_episodio' => 		$clave['id_episodio'],
				':id_operador' => 		$clave['id_operador'],
				':id_operador_unidad' =>$clave['id_operador_unidad'],
				':id_viaje' => 			$clave['id_viaje'],
				':latitud' => 			$clave['latitud'],
				':longitud' => 			$clave['longitud'],
				':motivo' => 			$clave['motivo'],
				':serie' => 			$clave['serie'],
				':tiempo' => 			$clave['tiempo'],
				':timestamp' => 		$clave['timestamp'],
				':token' => 			$clave['token'],
				':origen' => 			$clave['origen']
			)
		);
		if($ok){
			$id_sync = $this->db->lastInsertId();
			$noStore = array('R1','F17');
			if(!in_array($clave['clave'], $noStore)){
				self::updateEstadoOperador($id_sync,$clave['token'],$clave['id_operador_unidad'],$clave['id_usuario']);
			}
			$output = array('resp' => true, 'id' => $clave['id'], 'token' => $clave['token'],'clave' => $clave['clave'],'insert' => true);
		}else{
			$output = array('resp' => false, 'id' => $clave['id'], 'token' => $clave['token'],'clave' => $clave['clave'],'insert' => false);
		}
		return $output;
	}
	function updateEstadoOperador($id_sync,$token,$id_operador_unidad,$id_usuario){
		$sql = "
			UPDATE cr_operador_unidad
			SET 
			 id_sync = :id_sync,
			 sync_token = :sync_token,
			 user_mod = :user_mod
			WHERE
				id_operador_unidad = :id_operador_unidad
		";
		$query = $this->db->prepare($sql);
		$data = array(
			':id_sync' => $id_sync, 
			':sync_token' => $token, 
			':user_mod' => $id_usuario,
			':id_operador_unidad' => $id_operador_unidad
		);
		$query->execute($data);
	}
	function ponerEnC2($id_operador_unidad,$id_base,$id_operador){
		$sql = "
			INSERT INTO cr_sync (
				accurate,
				clave,
				estado1,
				estado2,
				estado3,
				estado4,
				id_indexeddb,
				id_episodio,
				id_operador,
				id_operador_unidad,
				id_viaje,
				latitud,
				longitud,
				motivo,
				serie,
				tiempo,
				timestamp,
				token,
				origen
			) VALUES (
				:accurate,
				:clave,
				:estado1,
				:estado2,
				:estado3,
				:estado4,
				:id_indexeddb,
				:id_episodio,
				:id_operador,
				:id_operador_unidad,
				:id_viaje,
				:latitud,
				:longitud,
				:motivo,
				:serie,
				:tiempo,
				:timestamp,
				:token,
				:origen
			)";
		$query = $this->db->prepare($sql);
		$base = self::getClaveBase($id_base);
		$token = 'CN:'.self::token(60);
		$query->execute(
			array(
				':accurate' => 			'1',
				':clave' => 			'C2',
				':estado1' => 			'C2',
				':estado2' => 			$base['clave'],
				':estado3' => 			'NULL',
				':estado4' => 			'NULL',
				':id_indexeddb' => 		1,
				':id_episodio' => 		self::getIdEpisodio($id_operador_unidad),
				':id_operador' => 		$id_operador,
				':id_operador_unidad' =>$id_operador_unidad,
				':id_viaje' => 			0,
				':latitud' => 			$base['latitud'],
				':longitud' => 			$base['longitud'],
				':motivo' => 			'NO ACEPTAR LA CLAVE A10',
				':serie' => 			self::getSerie($id_operador),
				':tiempo' => 			date("Y-m-d H:i:s"),
				':timestamp' => 		date("Y-m-d H:i:s"),
				':token' => 			$token,
				':origen' => 			'base'
			)
		);
		$id_sync = $this->db->lastInsertId();
		self::updateEstadoOperador($id_sync,$token,$id_operador_unidad,$_SESSION['id_usuario']);
	}	
	static function token($long=25){
		$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		mt_srand((double)microtime()*1000000); 
		$i=0;
		$pass = '';
		while ($i != $long) {
			$rand=mt_rand() % strlen($chars);
			$tmp=$chars[$rand];
			$pass=$pass . $tmp;
			$chars=str_replace($tmp, "", $chars);
			$i++;
		}
		return strrev($pass);
	}
	function getIdensOperadorEnC2($id_operador){
		$qry = "
			SELECT
				crou.id_operador_unidad
			FROM
				cr_operador_unidad AS crou
			INNER JOIN cr_sync AS syc ON crou.sync_token = syc.token
			WHERE
				(
					(
						syc.estado1 = 'C1'
						AND syc.estado2 = 'F11'
					)
					OR (
						syc.estado1 = 'C1'
						AND syc.estado3 = 'F11'
					)
				)
			AND crou.id_operador = $id_operador	
			AND crou.status_operador_unidad = 198
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$par = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$par[$num]['id_operador_unidad'] = $row->id_operador_unidad;
				$num++;
			}
		}
		return $par;
	}
	function getSerie($id_operador){
		$qry = "
			SELECT
				cr_celulares.serie
			FROM
				cr_celulares
			INNER JOIN cr_operador_celular ON cr_operador_celular.id_celular = cr_celulares.id_celular
			WHERE
				cr_operador_celular.id_operador = $id_operador
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				return $row->serie;
			}
		}
	}	
	function setMsgRead($id_mensaje,$id_usuario){
		$sql = "
			UPDATE `cr_mensajes`
			SET 
			 `read` = '1',
			 `user_mod` = '".$id_usuario."',
			 `fecha_mod` = '".date("Y-m-d H:i:s")."'
			WHERE
				(`id_mensaje` = '".$id_mensaje."');
		";
		$query = $this->db->prepare($sql);
		$query->execute();
	}		
	function mensajeria($id_operador){
		$qry = "
			SELECT
				cr_mensajes.id_mensaje,
				cr_mensajes.mensaje
			FROM
				cr_mensajes
			WHERE
				cr_mensajes.id_operador = ".$id_operador."
			AND cr_mensajes.`read` = 0
			ORDER BY
				cr_mensajes.id_mensaje ASC
			LIMIT 0,
			 1
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$data = array();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$data['mensaje'] = $row->mensaje;
				$data['id_mensaje'] = $row->id_mensaje;
			}
			return $data;
		}else{
			return false;
		}
	}
	function getIdOperador($id_operador_unidad){
		$qry = "
			SELECT
				id_operador
			FROM
				cr_operador_unidad
			WHERE
				id_operador_unidad = $id_operador_unidad
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				return $row->id_operador;
			}
		}
	}	
	function getClaveBase($id_base){
		$qry = "
			SELECT
				clave,
				latitud,
				longitud
			FROM
				cr_bases
			WHERE
				id_base = $id_base
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$array = array();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$array['clave'] = $row->clave;
				$array['latitud'] = $row->latitud;
				$array['longitud'] = $row->longitud;
			}
		}
		return $array;
	}
	function storeGps($claves){
		$output[0] = array('resp' => false);
		foreach($claves as $num => $clave){
			self::storeToGps($clave, $num);
		}	
	}	
	function storeToGps($clave, $num){
		$sql = "
			INSERT INTO gps (
				latitud,
				longitud,
				tiempo,
				bateria,
				id_android,
				serie,
				acurate,
				version,
				cc,
				id_operador
			)
			VALUES
			(
				:latitud,
				:longitud,
				:tiempo,
				:bateria,
				:id_android,
				:serie,
				:acurate,
				:version,
				:cc,
				:id_operador
			)
		";
		$stmt = $this->db->prepare($sql);
		$insert = $stmt->execute(
			array(
				':latitud' => 	$clave['latitud'],
				':longitud' => 	$clave['longitud'],
				':tiempo' => 	$clave['tiempo'],
				':bateria' => 	'CCD',
				':id_android' =>'CCD',
				':serie' => 	$clave['serie'],
				':acurate' => 	$clave['acurate'],
				':version' => 	'CCD',
				':cc' => 		$clave['cc'],
				':id_operador' =>$clave['id_operador']
			)
		);
	}
	function ultimaPosition($serie){
		$sql="
			SELECT
				gps.latitud,
				gps.longitud,
				gps.tiempo,
				gps.`timestamp`,
				gps.bateria
			FROM
				gps
			WHERE
				gps.serie = '".$serie."'
			ORDER BY
				gps.id_gps DESC
			LIMIT 0,
			 1	
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$point = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($point as $row) {
				return '{"lat":'.$row->latitud.',"lng":'.$row->longitud.',"time":"'.$row->timestamp.'","bateria":"'.$row->bateria.'","times":"'.$row->timestamp.'"}';
			}
		}
	}
	public function minutosDiferencia($init,$ahora){
		$res = strtotime($ahora) - strtotime($init);
		return round($res/60);
	}
	public function getDataViaje($id_operador_unidad, $tipo){
		if($tipo == 'base'){
			$inner = 'INNER JOIN cr_cordon AS crc ON viv.id_cordon = crc.id_cordon';
			$where = "crc.id_operador_unidad = $id_operador_unidad";
			$order = 'crc.id_cordon DESC';
		}elseif($tipo == 'air'){
			$inner = '';
			$where = "viv.id_operador_unidad = $id_operador_unidad";
			$order = 'viv.id_viaje DESC';
		}
		$sql = "
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

				concat(
					cp1.codigo_postal,
					' ',
					ta1.d_tipo_asenta,
					' ',
					as1.asentamiento,
					' ',
					mun1.municipio,
					' ',
					edo1.estado,
					' ',
					cid1.ciudad
				) AS origen,
				concat(
					cp2.codigo_postal,
					' ',
					ta2.d_tipo_asenta,
					' ',
					as2.asentamiento,
					' ',
					mun2.municipio,
					' ',
					edo2.estado,
					' ',
					cid2.ciudad
				) AS destino,
				emp.nombre as empresa,
				vid.redondo AS redondo,
				vid.apartado AS apartado
				
			FROM
				vi_viaje AS viv
			$inner
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
			
			INNER JOIN it_asentamientos AS as1 ON dir1.id_asentamiento = as1.id_asentamiento
			INNER JOIN it_asentamientos AS as2 ON dir2.id_asentamiento = as2.id_asentamiento
			INNER JOIN it_codigos_postales AS cp1 ON as1.id_codigo_postal = cp1.id_codigo_postal
			INNER JOIN it_codigos_postales AS cp2 ON as2.id_codigo_postal = cp2.id_codigo_postal
			INNER JOIN it_tipo_asentamientos AS ta1 ON as1.id_tipo_asenta = ta1.id_tipo_asenta
			INNER JOIN it_tipo_asentamientos AS ta2 ON as2.id_tipo_asenta = ta2.id_tipo_asenta
			INNER JOIN it_municipios AS mun2 ON as2.id_municipio = mun2.id_municipio
			INNER JOIN it_municipios AS mun1 ON as1.id_municipio = mun1.id_municipio
			INNER JOIN it_estados AS edo1 ON as1.id_estado = edo1.id_estado
			INNER JOIN it_estados AS edo2 ON as2.id_estado = edo2.id_estado
			INNER JOIN it_ciudades as cid1 ON as1.id_ciudad = cid1.id_ciudad
			INNER JOIN it_ciudades as cid2 ON as2.id_ciudad = cid2.id_ciudad
			INNER JOIN cl_clientes AS emp ON clc.parent = emp.id_cliente
			
			WHERE
				$where
				AND
				viv.cat_status_viaje = 171
			ORDER BY
				$order
			LIMIT 0,
			 1
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$data = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($data as $row) {
				
				$array['status_viaje'] 				= $row->status_viaje;
				$array['tipo_servicio'] 			= $row->tipo_servicio;
				$array['fecha_solicitud'] 			= $row->fecha_solicitud;
				$array['fecha_asignacion'] 			= $row->fecha_asignacion;
				

				$array['id_viaje'] 					= $row->id_viaje;
				
				if($row->forma_pago == 'Vale'){$fp = '<i class="fa fa-file-text-o iconfloat"></i>';}
				else if($row->forma_pago == 'Efectivo'){$fp = '<i class="fa fa-money iconfloat"></i>';}
				else if($row->forma_pago == 'Tarjeta'){$fp = '<i class="fa fa-credit-card iconfloat"></i>';}
				
				if($row->redondo == '1'){$vr = '<i class="fa fa-exchange iconfloat"></i>';}
				else{$vr = '';}
				
				if($row->apartado == '1'){$ap = '<i class="fa fa-clock-o iconfloat"></i>';}
				else{$ap = '';}
				
				$array['Cliente'] = $ap.$vr.$fp.$row->nombre.'<span class="pull-right">'.$row->empresa.'</span>';

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
				
				
				$mapo = '<a class="iconfloat external" target="_blank" href="http://maps.google.com/maps?q=loc:'.$row->coodo.'"><i class="fa fa-map"></i></a>';
				$mapd = '<a class="iconfloat external" target="_blank" href="http://maps.google.com/maps?q=loc:'.$row->coodd.'"><i class="fa fa-map"></i></a>';			
				
				$ro = ($row->refo != '')?'<br><br><strong>Ref:</strong> '.$row->refo.'<br>':'';
				$rd = ($row->refd != '')?'<br><br><strong>Ref:</strong> '.$row->refd.'<br>':'';
				
				$array['Origen'] 	= ($row->invo != '')?$mapo.$row->invo.$dato.$ro:$row->origen.$dato.$ro;
				$array['Destino'] 	= ($row->invd != '')?$mapd.$row->invd.$datd.$rd:$row->destino.$datd.$rd;
				
				$array['Observaciones'] 			= $row->observaciones;		
			}
		}
		return $array;
	}
	public function jumGeoposition(){
		$qry = "
			SELECT
				fwu.id_usuario
			FROM
				fw_usuarios AS fwu
			INNER JOIN fw_usuarios_config AS fwuc ON fwuc.id_usuario = fwu.id_usuario
			INNER JOIN cr_operador AS cro ON cro.id_usuario = fwu.id_usuario
			WHERE
				fwuc.poseido = 1
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$poseidos = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$poseidos[] = $row->id_usuario;
			}
		}
		return $poseidos;	
	}
    public function broadcast($id_operador_unidad)
    {
		$current = self::getDataActual($id_operador_unidad);
		if($current['clave'] != 'XX'){
			$base 				= $current['base'];
			$id_base 			= $current['id_base'];
			$id_operador 		= $current['id_operador'];
			$id_episodio 		= $current['id_episodio'];
			$serie 				= $current['serie'];
			$id_usuario 		= $current['id_usuario'];
			$state 				= $current['clave'];
			if($id_operador_unidad != $current['id_operador_unidad']){
				if(DEVELOPER){D::bug('Verificar estados de identificadores mobil 1534');}
			}
			$cordon_sign		= '';
					
					$clave = self::cve_store($id_operador_unidad);
					$new = false;
					$ride_0 = array(
						'clave'			=> $clave['etiqueta'],
						'id_operador'	=> $id_operador,
						'proceso'		=> 'ride'.$id_operador
					);
					self::send_msg($id_operador,$clave['etiqueta']);
					$token = 'RD:'.$this->token(60);
					switch ($clave['etiqueta']) {
						
						case 'R11':
						case 'A10':
						case 'F13':
					
							self::storeToSyncRide($id_usuario,$token,116,$id_operador_unidad);
							$ride_1 = array(
								'new' 					=> true,
								'viaje' 				=> self::getDataViaje($id_operador_unidad, 'base'),
								'id_operador_unidad' 	=> $id_operador_unidad,
								'id_episodio' 			=> $id_episodio,
								'serie' 				=> $serie,
								'token' 				=> $token
							);
							
							break;
						
						case 'F14':
							
							$position = self::ultimaPosition($serie);
							$position = json_decode($position);
							
							$geoVars['estado2'] 		= $base;
							$geoVars['latitud_act'] 	= $position->lat;
							$geoVars['longitud_act'] 	= $position->lng;
							
							$ahora = date("Y-m-d H:i:s");
							$timeFresh = self::minutosDiferencia($position->times,$ahora);
							
							$jumGeoposition = self::jumGeoposition();
							
							$enGeocerca = self::enGeocerca($geoVars);
							
								if(
									(($enGeocerca == 'in')AND($timeFresh < 3)) OR
									(in_array($id_usuario, $jumGeoposition))
								){
									
									$queue['estado2'] 				= $base;
									$queue['id_operador_unidad'] 	= $id_operador_unidad;
									$queue['id_episodio'] 			= $id_episodio;
									$queue['token'] 				= $clave['token'];
									$queue['id_usuario'] 			= $id_usuario;
									
									$ride_1 = array(
										'queue' 	=> true,
										'turno' 	=> self::formarse($queue),
										'cordon' 	=> self::cordon_operadores($id_base),
										'base' 		=> $base
									);
									
									self::storeToSyncRide($id_usuario,$token,122,$id_operador_unidad);
									
								}else{
									$geo_color = ($enGeocerca == 'out')?'#c11313':'#37b25c';
									$geo_state = ($enGeocerca == 'out')?false:true;
									
									$tim_color = ($timeFresh >= 3)?'#c11313':'#37b25c';
									$tim_state = ($timeFresh >= 3)?false:true;
									
									$ses_color = '#37b25c';
									$soc_color = '#37b25c';
									$oc1_color = '#37b25c';
									$f14_color = '#37b25c';
									$ride_1 = array(
										'indicadores'	=> array(
																array('color' => $geo_color, 'indicador' => 'Dentro de geocerca', 'estado' => $geo_state),
																array('color' => $tim_color, 'indicador' => 'Geolocalización vigente', 'estado' => $tim_state),
																array('color' => $ses_color, 'indicador' => 'Sesión activa', 'estado' => true),
																array('color' => $soc_color, 'indicador' => 'WebSockets activos', 'estado' => true),
																array('color' => $oc1_color, 'indicador' => 'Operador en C1', 'estado' => true),
																array('color' => $f14_color, 'indicador' => 'Solicitud F14 recibida', 'estado' => true),
															),
										'queue' 		=> false
									);
								}
							
							break;
							
						case 'F15':
						case 'A19':
							self::storeToSyncRide($id_usuario,$token,116,$id_operador_unidad);
							$ride_1 = array(
								'new' 					=> true,
								'viaje' 				=> self::getDataViaje($id_operador_unidad, 'air'),
								'id_operador_unidad' 	=> $id_operador_unidad,
								'id_episodio' 			=> $id_episodio,
								'serie' 				=> $serie,
								'token' 				=> $token
							);
							
							break;
						case 'C6':
							self::storeToSyncRide($id_usuario,$token,116,$id_operador_unidad);
							$ride_1 = array();
							break;
						case 'A14':
							self::storeToSyncRide($id_usuario,$token,116,$id_operador_unidad);
							$ride_1 = array();
							break;
						case 'F19':
							$ride_1 = array(
								'new' 					=> false,
								'cordon'				=> self::cordon_operadores($id_base),
								'actual'				=> '',
								'turno'					=> self::turno($id_operador_unidad,$id_base)
							);
							$clave['etiqueta'] = self::signCordon($id_operador_unidad);
							break;
							
						case 'F20':

							$ride_1 = array();
							break;
							
						case 'F18':
					
							$ride_1 = array();
							break;
							
						case 'C1':
					
							$ride_1 = array(
								'id_operador_unidad' 	=> $id_operador_unidad,
								'id_episodio' 			=> $id_episodio,
								'serie' 				=> $serie,
								'token' 				=> $token,
								'viaje' 				=> array('id_viaje' => 'IR001')
							);
							self::storeToSyncRide($id_usuario,$token,116,$id_operador_unidad);
							
							break;
						
						case 'R1':
						
							$ride_1 = array(
								'set_page'				=> $clave['valor']
							);
							self::storeToSyncRide($id_usuario,$token,116,$id_operador_unidad);
							break;
						case 'R3':
						
							$ride_1 = array();
							self::storeToSyncRide($id_usuario,$token,116,$id_operador_unidad);
							break;
							
						default:
							$ride_1 = array();
							self::storeToSyncRide($id_usuario,$token,116,$id_operador_unidad);
							break;
							
					}
			
			
			$ride = $ride_0 + $ride_1;
			
			$no_emitir = array('F20','F18','SIGNED');
			if(!in_array($clave['etiqueta'], $no_emitir)){
				self::transmitir(json_encode($ride),$ride['proceso']);
			}
		}
    }
	public function send_msg($id_operador,$clave){
			$ride = array(
				'clave'			=> 'MSG',
				'id_operador'	=> $id_operador,
				'proceso'		=> 'ride'.$id_operador
			);		
			$vmensaje = self::mensajeria($id_operador);
			$omitir = array('F18');
			if(($vmensaje !== false)&&(!in_array($clave, $omitir))){
				$mensaje = array(
					'mensaje' => $vmensaje['mensaje'], 
					'id_mensaje' => $vmensaje['id_mensaje'] 
				);
				$send = $ride + $mensaje;
				self::transmitir(json_encode($send),$send['proceso']);
			}
	}
	function sync_ride(){
		$operadores = (PRESENCE_GET == 'CURL')?self::onLink():self::onLinkWebHook();
		$online = ' AND (';
		$real = 0;
		foreach($operadores as $num => $oper){
			$id_operador_unidad = self::getIdOperadorUnidadEpisode($oper['id_operador'],'id_operador');
			if($id_operador_unidad != ''){
				$online .= "(crou.id_operador_unidad = ".$id_operador_unidad.") OR ";
				$real++;
			}
		}
		/*Elimine la verificacion de estado en c1 para priorizar el id_operador_unidad
		toma el valor de presence sin importar que este en c2*/
		$online = rtrim($online, " OR ");
		$online .= ')';
		if((count($operadores)== 0)OR($real == 0)){$online = 'AND op.id_operador = 0';}
		
		$qry = "
			SELECT
				syr.id_operador_unidad
			FROM
				cr_sync_ride AS syr
				
			INNER JOIN cr_operador_unidad AS crou ON syr.id_operador_unidad = crou.id_operador_unidad
			INNER JOIN cr_operador AS op ON crou.id_operador = op.id_operador
			INNER JOIN cr_sync ON crou.id_sync = cr_sync.id_sync

			WHERE
				syr.procesado = 0 
				AND crou.status_operador_unidad = 198
				
			    $online
				
			GROUP BY
				syr.id_operador_unidad
			ORDER BY
				syr.user_alta DESC,
				syr.id_operador_unidad DESC
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$array = array();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			$num = 0;
			foreach ($data as $row) {
				$array[$num]['id_operador_unidad'] =  $row->id_operador_unidad;
				$num++;
			}
		}
		return $array;
	}
	
	
	/*
	SECCION DE WEBSOCKETS
	*/
	
	
	public function transmitir($emision,$proceso){
		
		if(SOCKET_PROVIDER == 'ABLY'){
			
			require_once ('../vendor/ably/ably-php/ably-loader.php');
			$client = new Ably\AblyRest(ABLY_API_KEY);

			$channel = $client->channel($proceso);
			$channel->publish('BRDCST', $emision);
			
		}else if(SOCKET_PROVIDER == 'PUSHER'){
			
			require_once('../vendor/pusher/Pusher.php');

			$options = array('encrypted' => true);
			$pusher = new Pusher(PUSHER_KEY,PUSHER_SECRET,PUSHER_APP_ID,$options);
			
			$emision = json_decode($emision,true);
			$data['message'] = $emision;
			$pusher->trigger($proceso, 'evento', $data);
		
		}else if(SOCKET_PROVIDER == 'PUBNUB'){
			
			require_once('../vendor/pubnub/autoloader.php');
			$pubnub = new Pubnub(PUBNUB_PUBLISH,PUBNUB_SUSCRIBE,PUBNUB_SECRET,false);
			$emision = json_decode($emision,true);
			$publish_result = $pubnub->publish($proceso,$emision);
			
		}
		
	}
	function inLinkedIn($id_operador){
		$activos = (PRESENCE_GET == 'CURL')?self::onLink():self::onLinkWebHook();
		$enlazados = array();
		
		foreach($activos as $num => $oper){
			$enlazados[] = $oper['id_operador'];
		}
		
		if(in_array($id_operador,$enlazados)){
			$return = true;
		}else{
			$return = false;
		}
		
		return $return;
	}	
	function linkedIn(){
		if(SOCKET_PROVIDER == 'ABLY'){
			
			require_once ('../vendor/ably/ably-php/ably-loader.php');
			$client = new Ably\AblyRest(ABLY_API_KEY);
			$channel = $client->channel(ABLY_PRESENCE);
			$response = $channel->presence->get();
			
		}else if(SOCKET_PROVIDER == 'PUSHER'){
			
			require_once('../vendor/pusher/Pusher.php');
			$options = array('encrypted' => true);
			$pusher = new Pusher(PUSHER_KEY,PUSHER_SECRET,PUSHER_APP_ID,$options);
			$response = $pusher->get( '/channels/'.PUSHER_PRESENCE.'/users' );
			
		}else if(SOCKET_PROVIDER == 'PUBNUB'){
			
			require_once('../vendor/pubnub/autoloader.php');
			$pubnub = new Pubnub(PUBNUB_PUBLISH,PUBNUB_SUSCRIBE,PUBNUB_SECRET,false);
			$response = $pubnub->hereNow(PUSHER_PRESENCE);
			
		}
		return $response;
	}
	public function onLink(){
		$id_operadores = array();
		$num = 0;
		$response = self::linkedIn();
		
		if(SOCKET_PROVIDER == 'ABLY'){
			
			foreach($response->items as $num => $oper){
				$id_operadores[$num]['id_operador'] = substr($oper->clientId, 3);
				$num++;
			}
			
		}else if(SOCKET_PROVIDER == 'PUSHER'){
			
			foreach($response['result']['users'] as $num => $oper){
				foreach($oper as $it){
					$id_operadores[$num]['id_operador'] = $it;
					$num++;
				}
			}
			
		}else if(SOCKET_PROVIDER == 'PUBNUB'){

			foreach($response['uuids'] as $num => $oper){
				$id_operadores[$num]['id_operador'] = $oper;
				$num++;
			}
			
		}
		return $id_operadores;
	}
	function onLinkWebHook(){
		$qry = "SELECT id_operador FROM cr_presence";
		$query = $this->db->prepare($qry);
		$query->execute();
		$id_operadores = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$id_operadores[$num]['id_operador'] = $row->id_operador;
				$num++;
			}
		}
		return $id_operadores;
	}
	function getAllIdenOperadorUnidad($id_operador_unidad){
		$qry = "
			SELECT
				base.id_operador_unidad,
				base.id_operador,
				cr_operador.id_usuario
			FROM
				cr_operador_unidad AS iden
			INNER JOIN cr_operador_unidad AS base ON iden.id_operador = base.id_operador
			INNER JOIN cr_operador ON base.id_operador = cr_operador.id_operador
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
			foreach ($data as $row){
				$ids[$num]['id_operador_unidad'] = $row->id_operador_unidad;
				$ids[$num]['id_operador'] = $row->id_operador;
				$ids[$num]['id_usuario'] = $row->id_usuario;
				$num++;
			}
		}
		return $ids;
	}
	function getVehiculosOperador($id_operador){
		$qry = "
			SELECT
				crou.id_operador_unidad,
				crm.marca,
				cmod.modelo,
				cru.`year`,
				cru.placas,
				cru.color
			FROM
				cr_operador_unidad AS crou
			INNER JOIN cr_unidades AS cru ON cru.id_unidad = crou.id_unidad
			INNER JOIN cr_marcas AS crm ON cru.id_marca = crm.id_marca
			INNER JOIN cr_modelos AS cmod ON cru.id_modelo = cmod.id_modelo
			WHERE
				cru.cat_status_unidad = 14
			AND crou.id_operador = ".$id_operador."	
			AND crou.status_operador_unidad = 198
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$vehiculos = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$vehiculos[$num]['id_operador_unidad'] = $row->id_operador_unidad;
				$vehiculos[$num]['marca'] = $row->marca;
				$vehiculos[$num]['modelo'] = $row->modelo;
				$vehiculos[$num]['year'] = $row->year;
				$vehiculos[$num]['placas'] = $row->placas;
				$vehiculos[$num]['color'] = $row->color;
				$num++;
			}
		}
		return $vehiculos;
	}
}
?>