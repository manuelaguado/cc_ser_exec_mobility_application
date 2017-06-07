<?php
require_once( '../vendor/mysql_datatable.php' );
class IngresosoperadorModel
{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
        }
    }
    function operadorGroup($array){
           ini_set('memory_limit', '256M');
           $table = 'vi_viaje AS v';
           $primaryKey = 'id_viaje';
           $columns = array(
                  array(
                         'db' => 'n.num as num',
                         'dbj' => 'n.num',
                         'real' => 'n.num',
                         'alias' => 'num',
                         'typ' => 'int',
                         'dt' => 0
                  ),
                  array(
                         'db' => 'CONCAT(u.nombres, " " ,	u.apellido_paterno, " " ,	u.apellido_materno) AS nombre',
                         'dbj' => 'CONCAT(u.nombres, " " ,	u.apellido_paterno, " " ,	u.apellido_materno)',
                         'real' => 'CONCAT(u.nombres, " " ,	u.apellido_paterno, " " ,	u.apellido_materno)',
                         'alias' => 'nombre',
                         'typ' => 'txt',
                         'dt' => 1
                  ),
                  array(
                         'db' => 'Count(v.id_viaje) AS total',
                         'dbj' => 'Count(v.id_viaje)',
                         'real' => 'Count(v.id_viaje)',
                         'alias' => 'total',
                         'typ' => 'int',
                         'dt' => 2
                  ),
                  array(
                         'db' => 'Sum(vs.costo_total) AS viaje_mas_adicional',
                         'dbj' => 'Sum(vs.costo_total)',
                         'real' => 'Sum(vs.costo_total)',
                         'alias' => 'viaje_mas_adicional',
                         'moneda' => true,
                         'typ' => 'int',
                         'dt' => 3
                  ),
                  array(
                         'db' => 'Sum(vs.costo_viaje) AS costo_viaje',
                         'dbj' => 'Sum(vs.costo_viaje)',
                         'real' => 'Sum(vs.costo_viaje)',
                         'alias' => 'costo_viaje',
                         'moneda' => true,
                         'typ' => 'int',
                         'dt' => 4
                  ),
                  array(
                         'db' => 'Sum(vs.costos_adicionales) AS adicional',
                         'dbj' => 'Sum(vs.costos_adicionales)',
                         'real' => 'Sum(vs.costos_adicionales)',
                         'alias' => 'adicional',
                         'moneda' => true,
                         'typ' => 'int',
                         'dt' => 5
                  ),
                  array(
                         'db' => 'sum(vs.km_max_maps) AS kms',
                         'dbj' => 'sum(vs.km_max_maps)',
                         'real' => 'sum(vs.km_max_maps)',
                         'alias' => 'kms',
                         'unit' => 'km',
                         'typ' => 'int',
                         'dt' => 6
                  ),
                  array(
                         'db' => 'o.id_operador AS id_operador',
                         'dbj' => 'o.id_operador',
                         'real' => 'o.id_operador',
                         'alias' => 'id_operador',
                         'datareal' => 'programado',
                         'typ' => 'int',
                         'dt' => 7
                  ),
                  array(
                         'db' => 'vs.id_viaje_statics AS id_viaje_statics',
                         'dbj' => 'vs.id_viaje_statics',
                         'real' => 'vs.id_viaje_statics',
                         'alias' => 'id_viaje_statics',
                         'datareal' => 'deuda',
                         'typ' => 'int',
                         'dt' => 8
                  ),
                  array(
                         'db' => 'u.id_usuario AS id_usuario',
                         'dbj' => 'u.id_usuario',
                         'real' => 'u.id_usuario',
                         'alias' => 'id_usuario',
                         'datareal' => 'pagar',
                         'typ' => 'int',
                         'dt' => 9
                  ),
                  array(
                         'db' => 'o.id_operador AS id_operador',
                         'dbj' => 'o.id_operador',
                         'real' => 'o.id_operador',
                         'alias' => 'id_operador',
                         'typ' => 'int',
                         'dt' => 10
                  )
           );
           $inner = '
           INNER JOIN cr_operador_unidad AS ou ON v.id_operador_unidad = ou.id_operador_unidad
           INNER JOIN cr_operador AS o ON ou.id_operador = o.id_operador
           INNER JOIN cr_operador_numeq AS `on` ON `on`.id_operador = o.id_operador
           INNER JOIN cr_numeq AS n ON `on`.id_numeq = n.id_numeq
           INNER JOIN fw_usuarios AS u ON o.id_usuario = u.id_usuario
           INNER JOIN vi_viaje_statics AS vs ON vs.id_viaje = v.id_viaje
           ';
           $where = '
              (v.cat_status_viaje = 172 or v.cat_status_viaje = 247)
           ';
           $orden = '
           GROUP BY
           o.id_operador,
           n.num
           ORDER BY
           v.id_viaje ASC
           ';
           $render_table = new accionesoperadorGroup;
           return json_encode(
                  $render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
           );
    }
    function viajes_operador($array,$id_operador){
           ini_set('memory_limit', '256M');
           $table = 'vi_viaje AS v';
           $primaryKey = 'id_viaje';
           $columns = array(
                  array(
                         'db' => 'vs.mapa AS url_map',
                         'dbj' => 'vs.mapa',
                         'real' => 'vs.mapa',
                         'alias' => 'url_map',
                         'img' => true,
                         'typ' => 'txt',
                         'dt' => 0
                  ),
                  array(
                         'db' => 'v.id_viaje as id',
                         'dbj' => 'v.id_viaje',
                         'real' => 'v.id_viaje',
                         'alias' => 'id',
                         'typ' => 'int',
                         'dt' => 1
                  ),
                  array(
                         'db' => 'vs.costo_viaje AS costo',
                         'dbj' => 'vs.costo_viaje',
                         'real' => 'vs.costo_viaje',
                         'alias' => 'costo',
                         'format' => 'money',
                         'typ' => 'int',
                         'dt' => 2
                  ),
                  array(
                         'db' => 'vs.costos_adicionales AS adicional',
                         'dbj' => 'vs.costos_adicionales',
                         'real' => 'vs.costos_adicionales',
                         'alias' => 'adicional',
                         'moneda' => true,
                         'typ' => 'int',
                         'dt' => 3
                  ),
                  array(
                         'db' => 'vs.costo_total AS neto',
                         'dbj' => 'vs.costo_total',
                         'real' => 'vs.costo_total',
                         'alias' => 'neto',
                         'moneda' => true,
                         'typ' => 'int',
                         'dt' => 4
                  ),
                  array(
                         'db' => 'vs.km_max_maps AS km_max',
                         'dbj' => 'vs.km_max_maps',
                         'real' => 'vs.km_max_maps',
                         'alias' => 'km_max',
                         'format' => 'kms',
                         'typ' => 'int',
                         'dt' => 5
                  ),
                  array(
                         'db' => 'vs.km_min_maps AS km_min',
                         'dbj' => 'vs.km_min_maps',
                         'real' => 'vs.km_min_maps',
                         'alias' => 'km_min',
                         'unit' => 'km',
                         'typ' => 'int',
                         'dt' => 6
                  ),
                  array(
                         'db' => 'vs.time_or_des_max AS time_max',
                         'dbj' => 'vs.time_or_des_max',
                         'real' => 'vs.time_or_des_max',
                         'alias' => 'time_max',
                         'unit' => 'H:M:S',
                         'typ' => 'int',
                         'dt' => 7
                  ),
                  array(
                         'db' => 'vs.time_or_des_min AS time_min',
                         'dbj' => 'vs.time_or_des_min',
                         'real' => 'vs.time_or_des_min',
                         'alias' => 'time_min',
                         'unit' => 'H:M:S',
                         'typ' => 'int',
                         'dt' => 8
                  ),
                  array(
                         'db' => 'vs.time_viaje AS time_operador',
                         'dbj' => 'vs.time_viaje',
                         'real' => 'vs.time_viaje',
                         'alias' => 'time_operador',
                         'format' => 'oper_data',
                         'typ' => 'int',
                         'dt' => 9
                  ),
                  array(
                         'db' => 'vs.time_espera AS espera',
                         'dbj' => 'vs.time_espera',
                         'real' => 'vs.time_espera',
                         'alias' => 'espera',
                         'unit' => 'H:M:S',
                         'typ' => 'int',
                         'dt' => 10
                  ),
                  array(
                         'db' => 'vs.time_arribo AS arribo',
                         'dbj' => 'vs.time_arribo',
                         'real' => 'vs.time_arribo',
                         'alias' => 'arribo',
                         'format' => 'acciones',
                         'typ' => 'int',
                         'dt' => 11
                  )
           );
           $inner = '
           INNER JOIN cr_operador_unidad AS ou ON v.id_operador_unidad = ou.id_operador_unidad
           INNER JOIN cr_operador AS o ON ou.id_operador = o.id_operador
           INNER JOIN vi_viaje_statics AS vs ON vs.id_viaje = v.id_viaje
           ';
           $where = "
                  o.id_operador = $id_operador
           AND (
           	v.cat_status_viaje = 172
           	OR v.cat_status_viaje = 247
           )
           AND vs.cat_status_statics = 222
           AND (
           	v.cat_status_viaje = 172
           	OR v.cat_status_viaje = 247
           	OR v.cat_status_viaje = 251
           )
           ";
           $orden = '
           ORDER BY
           	id DESC
           ';
           $render_table = new accionesviajes_operador;
           return json_encode(
                  $render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
           );
    }
}

class accionesviajes_operador extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
                            $id_viaje = $data[$i][ 'id' ];

                            if ( isset( $column['img'] ) ){

					$img = ($data[$i][ $column['alias'] ]);
                                   $salida = self::duplicateMap($img);

					$row[ $column['dt'] ] = $salida;
				}else if ( isset( $column['unit'] ) ){

					$valor = ($data[$i][ $column['alias'] ]);
					$valor = $valor.' '.$column['unit'];
					$salida = $valor;

					$row[ $column['dt'] ] = $salida;
				}else if ( isset( $column['format'] ) ){
                                   switch($column['format']){
                                          case 'money';
                                          $salida = '
                                          <div class="infobox infobox-green infobox-small infobox-dark">
							<div class="infobox-data">
								<div class="infobox-content">Costo: '.money_format('%i',$data[$i][ 'costo' ]).'</div>
								<div class="infobox-content">Extra: '.money_format('%i',$data[$i][ 'adicional' ]).'</div>
                                                        <div class="infobox-content">Neto: '.money_format('%i',$data[$i][ 'neto' ]).'</div>
							</div>
						</div>';
                                          break;
                                          case 'kms':
                                          $salida = '
                                          <div class="infobox infobox-blue infobox-300 infobox-dark">
							<div class="infobox-data">
								<div class="infobox-float-left">Max Km: '.$data[$i][ 'km_max' ].'</div><br>
								<div class="infobox-float-left">Min Km: '.$data[$i][ 'km_min' ].'</div>
                                                 </div>
                                                 <div class="infobox-data">
                                                        <div class="infobox-float-right">Time Max: '.$data[$i][ 'time_max' ].'</div><br>
                                                        <div class="infobox-float-right">Time Min: '.$data[$i][ 'time_min' ].'</div>
							</div>
						</div>';
                                          break;
                                          case 'oper_data':
                                          $salida = '
                                          <div class="infobox infobox-grey infobox-small infobox-dark">
							<div class="infobox-data">
								<div class="infobox-content">Viaje: '.$data[$i][ 'time_operador' ].'</div>
								<div class="infobox-content">Espera: '.$data[$i][ 'espera' ].'</div>
                                                        <div class="infobox-content">Arribo: '.$data[$i][ 'arribo' ].'</div>
							</div>
						</div>';
                                          break;
                                          case 'acciones':
                                          $salida = '
                                          <div class="infobox infobox-green infobox-small infobox-dark" >
							<div class="infobox-icon" onclick="costos_adicionales_post('.$id_viaje.')" data-rel="tooltip" data-original-title="Costos adicionales">
								<i class="ace-icon icofont icofont-money-bag"></i>
							</div>
                                                 <div class="infobox-icon" onclick="cambiar_tarifa_post('.$id_viaje.')" data-rel="tooltip" data-original-title="Cambiar tarifa">
                                                        <i class="ace-icon icofont icofont-exchange"></i>
                                                 </div>
                                                 <div class="infobox-icon" onclick="historia_viaje('.$id_viaje.')" data-rel="tooltip" data-original-title="Historia">
                                                        <i class="ace-icon fa fa-clock-o"></i>
                                                 </div>
                                                 <div style="text-align:center;">
                                                        ACCIONES
                                                 </div>
						</div>';
                                          break;
                                          default:
                                          break;
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
       static function duplicateMap($imagen){
		$token = Controller::token();

		$destino = $token.'.png';
		$tmp = '../public/tmp/';

		copy('../archivo/'.$imagen, $tmp.$destino);
		return '<a href="tmp/'.$destino.'" title="Ruta primaria" data-rel="colorbox"><img src="plugs/timthumb.php?src=tmp/'.$destino.'&w=136"></a>';
	}
}
class accionesoperadorGroup extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
                            $id_operador = $data[$i][ 'id_operador' ];

                            if ( isset( $column['moneda'] ) ){

					$cantidad = ($data[$i][ $column['alias'] ]);
					$cantidad = money_format('%i',$cantidad);
					$salida = $cantidad;

					$row[ $column['dt'] ] = $salida;
				}else if (isset($column['datareal'])){
                                   switch($column['datareal']){

                                          case 'programado':
                                          $salida = self::programado($id_operador,$db);
                                          break;

                                          case 'deuda':
                                          $salida = self::deuda($id_operador,$db);
                                          break;

                                          case 'pagar':
                                          $programado = self::programado($id_operador,$db);
                                          $deuda = self::deuda($id_operador,$db);
                                          $costo = $data[$i][ 'viaje_mas_adicional' ];
                                          $salida = $costo - $deuda - $programado;
                                          break;

                                          default:
                                          break;
                                   }

					$row[ $column['dt'] ] = $salida;
				}else if ( isset( $column['unit'] ) ){

					$valor = ($data[$i][ $column['alias'] ]);
					$valor = $valor.' '.$column['unit'];
					$salida = $valor;

					$row[ $column['dt'] ] = $salida;
				}else{
					$row[ $column['dt'] ] = $data[$i][$name_column];
				}

			}
			$out[] = $row;
		}
		return $out;
	}
       static function deuda($id_operador,$db){
		$qry = "
              SELECT
              	IFNULL(Sum(ca.monto), 0) AS total
              FROM
              	fo_operador_conceptos AS oc
              INNER JOIN fo_concepto_adeudo AS ca ON ca.id_operador_conecepto = oc.id_operador_concepto
              WHERE
              	oc.id_operador = $id_operador
              AND ca.cat_status_pago = 244
		";
		$query = $db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row) {
				return $row['total'];
			}
		}
	}
       static function programado($id_operador,$db){
		$qry = "
              SELECT
              	IFNULL(Sum(c.monto), 0) as total
              FROM
              	fo_operador_conceptos AS oc
              INNER JOIN fo_conceptos AS c ON oc.id_concepto = c.id_concepto
              WHERE
              	oc.id_operador = $id_operador
		";
		$query = $db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row) {
				return $row['total'];
			}
		}
	}
}
?>
