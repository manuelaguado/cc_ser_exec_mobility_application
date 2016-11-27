<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<?php
require_once( '../vendor/mysql_datatable.php' );
class GpsModel
{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
        }
    }
	function activos_get(){
		$desde = mktime(date("H"),  date("i")-30, 0,  date("m")  , date("d"), date("Y"));
		$desde = date("Y-m-d H:i:s", $desde );		
		$sql="
			SELECT SQL_CALC_FOUND_ROWS
				crc.serie
			FROM
				gps AS GPS
			INNER JOIN cr_celulares AS crc ON GPS.serie = crc.serie
			INNER JOIN cr_operador_celular AS croc ON croc.id_celular = crc.id_celular
			WHERE
				GPS.`timestamp` >= '".$desde."'
			AND croc.cat_status_operador_celular = 31
			GROUP BY
				crc.serie
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$numeq = $query->fetchAll();
		$array = array();
		return $query->rowCount();
	}
	function num_eq($id_operador){
		$sql="
			SELECT
				cr_numeq.num
			FROM
				cr_operador
			INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = cr_operador.id_operador
			INNER JOIN cr_numeq ON cr_operador_numeq.id_numeq = cr_numeq.id_numeq
			WHERE
				cr_operador.id_operador = $id_operador
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$numeq = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($numeq as $row) {
				return $row->num;
			}
		}
	}
	function lastPositionById($id_operador){
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
				gps.id_operador = '".$id_operador."'
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
				return '{"lat":'.$row->latitud.',"lng":'.$row->longitud.',"time":"'.$row->timestamp.'","bateria":"'.$row->bateria.'"}';
			}
		}
	}	
	function save_gps_point($array){
		$time = mktime(date("H"),  date("i"),  date("s"), date("m")  , date("d"), date("Y"));
		$time = date("Y-m-d H:i:s", $time );
		$sql = "
			INSERT INTO gps (
				latitud,
				longitud,
				tiempo,
				timestamp,
				bateria,
				id_android,
				serie,
				acurate,
				version,
				cc
			)
			VALUES
			(
				:latitud,
				:longitud,
				:tiempo,
				:timestamp,
				:bateria,
				:id_android,
				:serie,
				:acurate,
				:version,
				:cc
			)
		";
		$stmt = $this->db->prepare($sql);
		$result = $stmt->execute(
		array(
			':latitud' => 	$array['latitud'],
			':longitud' => 	$array['longitud'],
			':tiempo' => 	$array['tiempo'],
			':timestamp' => $time,
			':bateria' => 	$array['bateria'],
			':id_android' =>$array['id_android'],
			':serie' => 	$array['serie'],
			':acurate' => 	$array['acurate'],
			':version' => 	'4.0',
			':cc' => 		$array['cc']
		));
	}
	function pointsGet($array){
		ini_set('memory_limit', '256M');				
		$table = 'gps as agps';
		$primaryKey = 'id_gps';
		$columns = array(
			array( 
				'db' => 'agps.id_gps AS id_gps',
				'dbj' => 'agps.id_gps',
				'real' => 'agps.id_gps',
				'alias' => 'id_gps',				
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
				'db' => 'crc.marcacion_corta AS marcacion_corta',
				'dbj' => 'crc.marcacion_corta',
				'real' => 'crc.marcacion_corta',
				'alias' => 'marcacion_corta',
				'typ' => 'int',
				'dt' => 2	
			),
			array( 
				'db' => 'agps.latitud AS latitud',
				'dbj' => 'agps.latitud',
				'real' => 'agps.latitud',
				'alias' => 'latitud',				
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'agps.longitud AS longitud',
				'dbj' => 'agps.longitud',
				'real' => 'agps.longitud',
				'alias' => 'longitud',					
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'agps.tiempo AS tiempo',
				'dbj' => 'agps.tiempo',
				'real' => 'agps.tiempo',
				'alias' => 'tiempo',					
				'typ' => 'txt',
				'dt' => 5				
			),
			array( 
				'db' => 'agps.timestamp AS timestamp',
				'dbj' => 'agps.timestamp',
				'real' => 'agps.timestamp',
				'alias' => 'timestamp',				
				'typ' => 'txt',
				'dt' => 6				
			),
			array( 
				'db' => 'agps.serie AS serie',
				'dbj' => 'agps.serie',
				'real' => 'agps.serie',
				'alias' => 'serie',				
				'typ' => 'txt',
				'dt' => 7				
			),
			array( 
				'db' => 'agps.acurate AS acurate',
				'dbj' => 'agps.acurate',
				'real' => 'agps.acurate',
				'alias' => 'acurate',					
				'typ' => 'txt',
				'dt' => 8				
			)
		);
		$render_table = new SSP;
		$inner = '
			INNER JOIN cr_operador AS cro ON agps.id_operador = cro.id_operador
			INNER JOIN cr_operador_numeq AS cron ON cron.id_operador = cro.id_operador
			INNER JOIN cr_numeq AS crn ON cron.id_numeq = crn.id_numeq
			INNER JOIN cr_operador_celular AS croc ON croc.id_operador = cro.id_operador
			INNER JOIN cr_celulares AS crc ON croc.id_celular = crc.id_celular	
		';
		$where = '';
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
	function localizar_get($array){
		ini_set('memory_limit', '256M');				
		$table = 'cr_operador AS cro';
		$primaryKey = 'id_operador';
		$columns = array(
			array( 
				'db' => 'cro.id_operador AS id_op',
				'dbj' => 'cro.id_operador',
				'real' => 'cro.id_operador',
				'alias' => 'id_op',
				'typ' => 'int',
				'dt' => 0
			),
			array( 
				'db' => 'crn.num AS numero_economico',
				'dbj' => 'crn.num',
				'real' => 'crn.num',
				'alias' => 'numero_economico',
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
				'db' => 'crmr.marca AS marca',
				'dbj' => 'crmr.marca',	
				'alias' => 'marca',
				'real' => 'crmr.marca',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'crmd.modelo AS modelo',
				'dbj' => 'crmd.modelo',				
				'real' => 'crmd.modelo',
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
				'db' => 'cru.placas AS placas',
				'dbj' => 'cru.placas',
				'real' => 'cru.placas',
				'alias' => 'placas',
				'typ' => 'txt',
				'dt' => 6			
			),
			array( 
				'db' => 'cru.year AS year',
				'dbj' => 'cru.year',
				'real' => 'cru.year',
				'alias' => 'year',
				'typ' => 'int',
				'dt' => 7			
			),
			array( 
				'db' => 'crc.serie AS serie',
				'dbj' => 'crc.serie',
				'real' => 'crc.serie',
				'alias' => 'serie',
				'typ' => 'txt',
				'dt' => 8			
			),
			array( 
				'db' => 'crc.marcacion_corta AS mc',
				'dbj' => 'crc.marcacion_corta',
				'real' => 'crc.marcacion_corta',
				'alias' => 'mc',
				'typ' => 'int',
				'dt' => 9			
			),
			array( 
				'db' => 'crc.marca AS mrk_cel',
				'dbj' => 'crc.marca',
				'real' => 'crc.marca',
				'alias' => 'mrk_cel',
				'typ' => 'txt',
				'dt' => 10			
			),
			array( 
				'db' => 'crc.modelo AS mod_cel',
				'dbj' => 'crc.modelo',
				'real' => 'crc.modelo',
				'alias' => 'mod_cel',
				'typ' => 'txt',
				'dt' => 11			
			),
			array( 
				'db' => 'cro.id_operador as id_operador',
				'dbj' => 'cro.id_operador',
				'real' => 'cro.id_operador',
				'alias' => 'id_operador',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 12			
			)
		);
		$render_table = new acciones_localizar;
		$inner = '
			INNER JOIN cr_operador_numeq AS cron ON cron.id_operador = cro.id_operador
			INNER JOIN cr_numeq AS crn ON cron.id_numeq = crn.id_numeq
			INNER JOIN fw_usuarios AS fwu ON cro.id_usuario = fwu.id_usuario
			INNER JOIN cr_operador_unidad ON cr_operador_unidad.id_operador = cro.id_operador
			INNER JOIN cr_unidades AS cru ON cr_operador_unidad.id_unidad = cru.id_unidad
			INNER JOIN cr_marcas AS crmr ON cru.id_marca = crmr.id_marca
			INNER JOIN cr_modelos AS crmd ON cru.id_modelo = crmd.id_modelo
			INNER JOIN cr_operador_celular AS croc ON croc.id_operador = cro.id_operador
			INNER JOIN cr_celulares AS crc ON croc.id_celular = crc.id_celular
		';
		$where = "
			croc.cat_status_operador_celular = 31
		";
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
	function gps_activo_get($array){
		ini_set('memory_limit', '256M');				
		$table = 'gps AS GPS';
		$primaryKey = 'id_gps';
		$columns = array(
			array( 
				'db' => 'crn.num AS num_eq',
				'dbj' => 'crn.num',
				'real' => 'crn.num',
				'alias' => 'num_eq',
				'typ' => 'int',
				'dt' => 0
			),
			array( 
				'db' => 'CONCAT(fwu.nombres, " " ,	fwu.apellido_paterno, " " ,	fwu.apellido_materno) AS nombre',
				'dbj' => 'CONCAT(fwu.nombres, " " ,	fwu.apellido_paterno, " " ,	fwu.apellido_materno)',
				'real' => 'CONCAT(fwu.nombres, " " ,	fwu.apellido_paterno, " " ,	fwu.apellido_materno)',
				'alias' => 'nombre',
				'typ' => 'txt',
				'dt' => 1				
			),
			array(
				'db' => 'GPS.bateria AS batt',
				'dbj' => 'GPS.bateria',	
				'real' => 'GPS.bateria',
				'alias' => 'batt',
				'typ' => 'txt',
				'dt' => 2
			),
			array( 
				'db' => 'crc.serie AS serial',
				'dbj' => 'crc.serie',				
				'real' => 'crc.serie',
				'alias' => 'serial',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'crc.numero AS tel',
				'dbj' => 'crc.numero',
				'real' => 'crc.numero',
				'alias' => 'tel',
				'typ' => 'int',
				'dt' => 4
			),
			array( 
				'db' => 'crc.marcacion_corta AS mrc_short',
				'dbj' => 'crc.marcacion_corta',
				'real' => 'crc.marcacion_corta',
				'alias' => 'mrc_short',
				'typ' => 'int',
				'dt' => 5			
			),
			array( 
				'db' => 'crm.marca AS marca',
				'dbj' => 'crm.marca',
				'real' => 'crm.marca',
				'alias' => 'marca',
				'typ' => 'txt',
				'dt' => 6			
			),
			array( 
				'db' => 'crmo.modelo AS modelo',
				'dbj' => 'crmo.modelo',
				'real' => 'crmo.modelo',
				'alias' => 'modelo',
				'typ' => 'txt',
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
				'db' => 'croc.id_operador AS id_operador',
				'dbj' => 'croc.id_operador',				
				'real' => 'croc.id_operador',
				'alias' => 'id_operador',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 9
			)
		);
		$render_table = new acciones_gps_activo;
		$inner = '
			INNER JOIN cr_celulares AS crc ON GPS.serie = crc.serie
			INNER JOIN cr_operador_celular AS croc ON croc.id_celular = crc.id_celular
			INNER JOIN cr_operador AS crop ON croc.id_operador = crop.id_operador
			INNER JOIN fw_usuarios AS fwu ON crop.id_usuario = fwu.id_usuario
			INNER JOIN cr_operador_numeq AS cron ON cron.id_operador = crop.id_operador
			INNER JOIN cr_numeq AS crn ON cron.id_numeq = crn.id_numeq
			INNER JOIN cr_operador_unidad AS crou ON crou.id_operador = crop.id_operador
			INNER JOIN cr_unidades AS crun ON crou.id_unidad = crun.id_unidad
			INNER JOIN cr_marcas AS crm ON crun.id_marca = crm.id_marca
			INNER JOIN cr_modelos AS crmo ON crun.id_modelo = crmo.id_modelo
		';
		$desde = mktime(date("H"),  date("i")-30, 0,  date("m")  , date("d"), date("Y"));
		$desde = date("Y-m-d H:i:s", $desde );		
		$where = "
			GPS.`timestamp` >= '".$desde."' 
			AND croc.cat_status_operador_celular = 31 GROUP BY crc.serie
		";
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}	
}
class acciones_gps_activo extends SSP{
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
					
					$salida = '';
						if(Controlador::tiene_permiso('Gps|geolocalizacion')){
							$salida .= '<a onclick="modal_geolocalizacion('.$id_operador.');" data-rel="tooltip" data-original-title="Geolocalizar Unidad">
							<i class="icon-centralcar_geolocalizacion" style="font-size:2em; color:green;"></i>
							</a>&nbsp;&nbsp;';
						}
						if(Controlador::tiene_permiso('Kml|genKml')){
							$salida .= '<a onclick="modal_ruta('.$id_operador.');" data-rel="tooltip" data-original-title="Observar ruta diaria">
							<i class="icon-centralcar_path" style="font-size:1.5em; color:green;"></i>
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
class acciones_localizar extends SSP{
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
					
					$salida = '';
						if(Controlador::tiene_permiso('Gps|geolocalizacion')){
							$salida .= '<a onclick="modal_geolocalizacion('.$id_operador.');" data-rel="tooltip" data-original-title="Geolocalizar Unidad">
							<i class="icon-centralcar_geolocalizacion" style="font-size:2em; color:green;"></i>
							</a>&nbsp;&nbsp;';
						}
						if(Controlador::tiene_permiso('Kml|genKml')){
							$salida .= '<a onclick="modal_ruta('.$id_operador.');" data-rel="tooltip" data-original-title="Observar ruta diaria">
							<i class="icon-centralcar_path" style="font-size:1.5em; color:green;"></i>
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