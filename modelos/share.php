<?php
class ShareModel
{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
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
    public function transmitir($emision,$proceso){
       require_once('../vendor/pusher/Pusher.php');
       $options = array('encrypted' => true);
       $pusher = new Pusher(PUSHER_KEY,PUSHER_SECRET,PUSHER_APP_ID,$options);
       $emision = json_decode($emision,true);
       $data['message'] = $emision;
       $pusher->trigger($proceso, 'evento', $data);
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
    public function minutosDiferencia($init,$ahora){
           $res = strtotime($ahora) - strtotime($init);
           return round($res/60);
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
    public function onLink(){
           $id_operadores = array();
           $num = 0;
           $response = self::linkedIn();

           foreach($response['result']['users'] as $num => $oper){
                  foreach($oper as $it){
                         $id_operadores[$num]['id_operador'] = $it;
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
    function linkedIn(){

           require_once('../vendor/pusher/Pusher.php');
           $options = array('encrypted' => true);
           $pusher = new Pusher(PUSHER_KEY,PUSHER_SECRET,PUSHER_APP_ID,$options);
           $response = $pusher->get( '/channels/'.PUSHER_PRESENCE.'/users' );

           return $response;
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
                         ':id_episodio' 		=> 	self::getIdEpisodio($id_operador_unidad),
                         ':id_base' 		=> 	$id_base,
                         ':cat_statuscordon' 	=> 	$statuscordon,
                         ':llegada' 		=> 	date("Y-m-d H:i:s"),
                         ':token' 			=> 	$token,
                         ':user_alta' 		=> 	$_SESSION['id_usuario'],
                         ':fecha_alta' 		=> 	date("Y-m-d H:i:s")
                  )
           );
           self::solicitarAcuseCordon();
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
}
