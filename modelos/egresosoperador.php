<?php
require_once( '../vendor/mysql_datatable.php' );
class EgresosoperadorModel
{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
        }
    }
    function activarc12t3_do($id_viaje){
              $qry = "UPDATE `vi_viaje` SET `cat_status_viaje` = 172 WHERE (`id_viaje` = $id_viaje);";
              $query = $this->db->prepare($qry);
              $query->execute();
              return json_encode(array('resp' => true));
    }
    function setAplicacionEjecucion($id_concepto,$actualDay,$adeudosGenerados,$monto){
           $qry = "
           INSERT INTO `fo_conceptos_aplicaciones` (
           	`id_concepto`,
           	`fecha_aplicacion`,
           	`adeudos_generados`,
              `monto_generado`,
           	`user_alta`,
           	`fecha_alta`
           )
           VALUES
           	(
           		".$id_concepto.",
           		'".$actualDay."',
           		'".$adeudosGenerados."',
                     '".$monto."',
                     '1',
                     '".date("Y-m-d H:i:s")."'
           	)
          ";
          $query = $this->db->prepare($qry);
          $query->execute();
          return $this->db->lastInsertId();
    }
    function insertDeuda($id_operador_concepto,$monto,$actualDay){
           $qry = "
           INSERT INTO `fo_concepto_adeudo` (
           	`id_operador_conecepto`,
              `cat_status_pago`,
           	`monto`,
           	`fecha_emision`,
           	`user_alta`,
           	`fecha_alta`
           )
           VALUES
           	(
           		".$id_operador_concepto.",
                     '244',
           		'".$monto."',
           		'".$actualDay."',
                     '1',
                     '".date("Y-m-d H:i:s")."'
           	)
          ";
          $query = $this->db->prepare($qry);
          $query->execute();
          return $this->db->lastInsertId();
    }
    function lastEjecucion($id_concepto){
           $sql="
           SELECT
              fo_conceptos_aplicaciones.fecha_aplicacion
           FROM
           fo_conceptos
           INNER JOIN fo_conceptos_aplicaciones ON fo_conceptos_aplicaciones.id_concepto = fo_conceptos.id_concepto
           WHERE
           fo_conceptos.id_concepto = $id_concepto
           ORDER BY
           fo_conceptos_aplicaciones.id_concepto_aplicacion DESC
           LIMIT 0,
           1
           ";
           $query = $this->db->prepare($sql);
           $query->execute();
           $data = $query->fetchAll();
           $array = array();
           if($query->rowCount()>=1){
                  foreach ($data as $row) {
                         return $row->fecha_aplicacion;
                  }
           }
    }

    function deficitarios($id_concepto){
           $sql="
           SELECT
                  oc.id_operador,
                  oc.inicio_cobranza,
                  oc.id_operador_concepto,
                  c.id_concepto,
                  c.monto
           FROM
           fo_operador_conceptos AS oc
           INNER JOIN fo_conceptos AS c ON oc.id_concepto = c.id_concepto
           WHERE
           c.id_concepto = $id_concepto
           AND oc.cat_status_concepto = 240
           ";
           $query = $this->db->prepare($sql);
           $query->execute();
           $data = $query->fetchAll();
           $array = array();
           if($query->rowCount()>=1){
                  $num=0;
                  foreach ($data as $row) {
                         $array[$num]['id_operador'] = $row->id_operador;
                         $array[$num]['inicio_cobranza'] = $row->inicio_cobranza;
                         $array[$num]['id_operador_concepto'] = $row->id_operador_concepto;
                         $array[$num]['id_concepto'] = $row->id_concepto;
                         $array[$num]['monto'] = $row->monto;
                         $num++;
                  }
           }
           return $array ;
    }
    function obtener_trabajos(){
           $sql="
                  SELECT
                  fo_conceptos.id_concepto,
                  cm1.valor,
                  cm1.etiqueta AS ejecucion,
                  fo_conceptos.concepto,
                  fo_conceptos.monto
                  FROM
                  fo_conceptos
                  INNER JOIN cm_catalogo AS cm1 ON fo_conceptos.cat_periodicidad = cm1.id_cat
                  WHERE
                  cm1.catalogo = 'periodicidad'
                  AND fo_conceptos.cat_status_concepto = 240
           ";
           $query = $this->db->prepare($sql);
           $query->execute();
           $data = $query->fetchAll();
           $array = array();
           if($query->rowCount()>=1){
                  $num=0;
                  foreach ($data as $row) {
                         $array[$num]['id_concepto'] = $row->id_concepto;
                         $array[$num]['valor'] = $row->valor;
                         $array[$num]['ejecucion'] = $row->ejecucion;
                         $array[$num]['concepto'] = $row->concepto;
                         $array[$num]['monto'] = $row->monto;
                         $num++;
                  }
           }
           return $array ;
    }
    function fijar_cobro($id_concepto,$id_operador,$estado){
           if($estado == 'true'){
                  $sql = "
                         INSERT INTO fo_operador_conceptos (
                                id_operador,
                                id_concepto,
                                cat_status_concepto,
                                inicio_cobranza,
                                user_alta,
                                fecha_alta
                         ) VALUES (
                                :id_operador,
                                :id_concepto,
                                :cat_status_concepto,
                                :inicio_cobranza,
                                :user_alta,
                                :fecha_alta
                         )";
                  $query = $this->db->prepare($sql);
                  $query_resp = $query->execute(
                         array(
                                ':id_operador' => $id_operador,
                                ':id_concepto' => $id_concepto,
                                ':cat_status_concepto' => 240,
                                ':inicio_cobranza' => date("Y-m-d H:i:s"),
                                ':user_alta' => $_SESSION['id_usuario'],
                                ':fecha_alta' => date("Y-m-d H:i:s")
                         )
                  );
           }else if ($estado == 'false'){
                  $this->db->exec(" UPDATE fo_operador_conceptos SET cat_status_concepto = '241' WHERE id_concepto = $id_concepto AND id_operador = $id_operador ");
                  $query_resp = true;
           }
           if($query_resp){
                  $respuesta = array('resp' => true , 'mensaje' => 'Se fijo el concepto de cobro.' );
           }else{
                  $respuesta = array('resp' => false , 'mensaje' => 'Error en el sistema.' , 'error' => 'Ocurrio un error mienras se ejectutaba la query.' );
           }
           return $respuesta;
    }
    function getStatus($id_concepto,$id_operador){
           $id_concepto = intval($id_concepto);
           $id_operador = intval($id_operador);
           $sql="SELECT count(*) as status FROM fo_operador_conceptos where id_operador = '".$id_operador."' and id_concepto = ".$id_concepto." and cat_status_concepto = 240";
           $query = $this->db->prepare($sql);
           $query->execute();
           $status =  $query->fetchAll();
           return $status[0]->status;
    }
    function getOperadores(){
           $sql="
                  SELECT
                  	CONCAT(
                  		fwu.nombres,
                  		' ',
                  		fwu.apellido_paterno,
                  		' ',
                  		fwu.apellido_materno
                  	) AS nombre,
                  	num.num,
                  	cat.etiqueta AS estado,
                  	cro.id_operador,
                  	cro.id_usuario
                  FROM
                  	cr_operador AS cro
                  INNER JOIN cr_operador_numeq AS cnum ON cnum.id_operador = cro.id_operador
                  INNER JOIN cr_numeq AS num ON cnum.id_numeq = num.id_numeq
                  INNER JOIN fw_usuarios AS fwu ON cro.id_usuario = fwu.id_usuario
                  INNER JOIN cm_catalogo AS cat ON cro.cat_statusoperador = cat.id_cat
                  WHERE
                  	cro.cat_statusoperador <> 9
                  AND cro.cat_statusoperador <> 11
           ";
           $query = $this->db->prepare($sql);
           $query->execute();
           $historia = $query->fetchAll();
           $array = array();
           if($query->rowCount()>=1){
                  $num=0;
                  foreach ($historia as $row) {
                         $array[$num]['id_operador'] = $row->id_operador;
                         $array[$num]['id_usuario'] = $row->id_usuario;
                         $array[$num]['nombre'] = $row->nombre;
                         $array[$num]['num'] = $row->num;
                         $array[$num]['estado'] = $row->estado;
                         $num++;
                  }
           }
           return $array ;
    }
    function editar_cobro_do($arreglo){
           foreach ($arreglo as $key => $value) {
                  $this->$key = strip_tags($value);
           }

           $monto = str_replace(',','',$this->monto);
           $sign = substr($monto, 0,1);
           $input = ($sign == '$')?substr($monto, 2):(substr($monto, 3)*-1);

           $qry = "
                  UPDATE `fo_conceptos`
                  SET
                     `cat_periodicidad` = '".$this->cat_periodicidad."',
                     `concepto` = '".$this->concepto."',
                     `monto` = '".$input."'

                  WHERE
                         (`id_concepto` = $this->id_concepto);
           ";
           $query = $this->db->prepare($qry);
           $ok = $query->execute();
           if($ok){
                  $respuesta = array('resp' => true  );
           }else{
                  $respuesta = array('resp' => false  );
           }
           return $respuesta;
    }
    function dataegreso($id_concepto){
           $sql="
           SELECT
           	con.cat_periodicidad,
           	con.concepto,
           	con.monto
           FROM
           	fo_conceptos AS con
           WHERE
           	con.id_concepto = $id_concepto
           ";
           $query = $this->db->prepare($sql);
           $query->execute();
           $data = $query->fetchAll();
           $array = array();
           if($query->rowCount()>=1){
                  foreach ($data as $row) {
                         $array['cat_periodicidad'] = $row->cat_periodicidad;
                         $array['concepto'] = $row->concepto;
                         $array['monto'] = $row->monto;
                  }
           }
           return $array ;
    }
    function eliminar_cobro_do($id_concepto){
           $di1 = $this->db->exec(" UPDATE fo_conceptos SET cat_status_concepto = '241' WHERE id_concepto = $id_concepto");
           $di2 = $this->db->exec(" UPDATE fo_operador_conceptos SET cat_status_concepto = '241' WHERE id_concepto = $id_concepto");
           if($di1 > 0){/*representa el numero de filas afectadas en este caso se limita a 1 mientras $di2 se limita a tantos como operadores afectados*/
                  $respuesta = array('resp' => true  );
           }else{
                  $respuesta = array('resp' => false  );
           }
           return $respuesta;
    }
    function add_nuevo_cobro_do($arreglo){
           foreach ($arreglo as $key => $value) {
                  $this->$key = strip_tags($value);
           }

           $sql = "
                  INSERT INTO fo_conceptos (
                         cat_periodicidad,
                         cat_status_concepto,
                         concepto,
                         monto,
                         user_alta,
                         fecha_alta
                  ) VALUES (
                         :cat_periodicidad,
                         :cat_status_concepto,
                         :concepto,
                         :monto,
                         :user_alta,
                         :fecha_alta
                  )";

                  $monto = str_replace(',','',$this->monto);
                  $sign = substr($monto, 0,1);
                  $input = ($sign == '$')?substr($monto, 2):(substr($monto, 3)*-1);

           $query = $this->db->prepare($sql);
           $query_resp = $query->execute(
                  array(
                         ':cat_periodicidad' => $this->cat_periodicidad,
                         ':cat_status_concepto' => '240',
                         ':concepto' => $this->concepto,
                         ':monto' => $input,
                         ':user_alta' => $_SESSION['id_usuario'],
                         ':fecha_alta' => date("Y-m-d H:i:s")
                  )
           );
           if($query_resp){
                  $respuesta = array('resp' => true  );
           }else{
                  $respuesta = array('resp' => false );
           }

           return $respuesta;
    }

    function obtenerConceptos($array){
           ini_set('memory_limit', '256M');
           $table = 'fo_conceptos AS con';
           $primaryKey = 'id_concepto';
           $columns = array(
                  array(
                         'db' => 'con.id_concepto as id_concepto',
                         'dbj' => 'con.id_concepto',
                         'real' => 'con.id_concepto',
                         'alias' => 'id_concepto',
                         'typ' => 'int',
                         'dt' => 0
                  ),
                  array(
                         'db' => 'con.concepto as concepto',
                         'dbj' => 'con.concepto',
                         'real' => 'con.concepto',
                         'alias' => 'concepto',
                         'typ' => 'txt',
                         'dt' => 1
                  ),
                  array(
                         'db' => 'con.monto as monto',
                         'dbj' => 'con.monto',
                         'real' => 'con.monto',
                         'alias' => 'monto',
                         'moneda' => true,
                         'typ' => 'int',
                         'dt' => 2
                  ),
                  array(
                         'db' => 'cat.etiqueta AS periodicidad',
                         'dbj' => 'cat.etiqueta',
                         'real' => 'cat.etiqueta',
                         'alias' => 'periodicidad',
                         'typ' => 'txt',
                         'dt' => 3
                  ),
                  array(
                         'db' => 'con.cat_status_concepto AS cat_status_concepto',
                         'dbj' => 'con.cat_status_concepto',
                         'real' => 'con.cat_status_concepto',
                         'alias' => 'cat_status_concepto',
                         'acciones' => true,
                         'typ' => 'int',
                         'dt' => 4
                  )
           );
           $inner = '
              INNER JOIN cm_catalogo AS cat ON con.cat_periodicidad = cat.id_cat
           ';
           $where = '
              con.cat_status_concepto = 240
           ';
           $orden = '
           ';
           $render_table = new accionesConceptos;
           return json_encode(
                  $render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
           );
    }
    function obtener_ejecucionesCobro($array,$id_concepto){
           ini_set('memory_limit', '256M');
           $table = 'fo_conceptos_aplicaciones AS ca';
           $primaryKey = 'id_concepto_aplicacion';
           $columns = array(
                  array(
                         'db' => 'con.concepto as concepto',
                         'dbj' => 'con.concepto',
                         'real' => 'con.concepto',
                         'alias' => 'concepto',
                         'typ' => 'txt',
                         'dt' => 0
                  ),
                  array(
                         'db' => 'con.monto as monto',
                         'dbj' => 'con.monto',
                         'real' => 'con.monto',
                         'alias' => 'monto',
                         'moneda' => true,
                         'typ' => 'int',
                         'dt' => 1
                  ),
                  array(
                         'db' => 'cat.etiqueta AS periodicidad',
                         'dbj' => 'cat.etiqueta',
                         'real' => 'cat.etiqueta',
                         'alias' => 'periodicidad',
                         'typ' => 'txt',
                         'dt' => 2
                  ),
                  array(
                         'db' => 'ca.fecha_aplicacion AS fecha_aplicacion',
                         'dbj' => 'ca.fecha_aplicacion',
                         'real' => 'ca.fecha_aplicacion',
                         'alias' => 'fecha_aplicacion',
                         'typ' => 'int',
                         'dt' => 3
                  ),
                  array(
                         'db' => 'ca.adeudos_generados AS adeudos_generados',
                         'dbj' => 'ca.adeudos_generados',
                         'real' => 'ca.adeudos_generados',
                         'alias' => 'adeudos_generados',
                         'typ' => 'int',
                         'dt' => 4
                  ),
                  array(
                         'db' => 'ca.monto_generado AS monto_generado',
                         'dbj' => 'ca.monto_generado',
                         'real' => 'ca.monto_generado',
                         'alias' => 'monto_generado',
                         'moneda' => true,
                         'typ' => 'int',
                         'dt' => 5
                  ),
                  array(
                         'db' => 'ca.id_concepto_aplicacion as id_concepto_aplicacion',
                         'dbj' => 'ca.id_concepto_aplicacion',
                         'real' => 'ca.id_concepto_aplicacion',
                         'alias' => 'id_concepto_aplicacion',
                         'typ' => 'int',
                         'dt' => 6
                  )
           );
           $inner = '
                  INNER JOIN fo_conceptos AS con ON ca.id_concepto = con.id_concepto
                  INNER JOIN cm_catalogo AS cat ON con.cat_periodicidad = cat.id_cat
           ';
           $where = "
              ca.id_concepto = $id_concepto
           ";
           $orden = '
           ';
           $render_table = new accionesejecucionesCobro;
           return json_encode(
                  $render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
           );
    }
    function tabuladosEnC12Get($array){
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
                  viv.cat_status_viaje = 247
           ';
           $orden = '
                  GROUP BY
                         viv.id_viaje
           ';
           $render_table = new acciones_tabuladosEnC12;
           return json_encode(
                  $render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
           );
    }
}


class acciones_tabuladosEnC12 extends SSP{
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

					$salida .= '<a href="javascript:;" onclick="costos_adicionales_post('.$id_viaje.')" data-rel="tooltip" data-original-title="Costos adicionales"><i class="icofont icofont-money-bag" style="font-size:1.4em; color:#008c23;"></i></a>&nbsp;&nbsp;';

					$salida .= '<a href="javascript:;" onclick="cambiar_tarifa_post('.$id_viaje.')" data-rel="tooltip" data-original-title="Cambiar tarifa"><i class="icofont icofont-exchange" style="font-size:1.4em; color:#008c23;"></i></a>&nbsp;&nbsp;';

					$salida .= '<a onclick="dataViaje('.$id_viaje.')" href="javascript:;" data-rel="tooltip" data-original-title="Datos del viaje"><i class="fa fa-question-circle" style="font-size:1.4em; color:#0080ff;"></i></a>&nbsp;&nbsp;';

                                   $salida .= "
                                          <a onclick='historia_viaje(".$id_viaje.")' data-rel='tooltip' data-original-title='Historia'>
                                                 <i class='fa fa-clock-o' style='font-size:1.8em; color:green;'></i>
                                          </a>
                                   ";
                                   $salida .= "
                                          <a onclick='activarc12t3(".$id_viaje.")' data-rel='tooltip' data-original-title='Activar'>
                                                 <i class='fa fa-play-circle-o' style='font-size:1.8em; color:green;'></i>
                                          </a>
                                   ";


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
class accionesejecucionesCobro extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;

				if ( isset( $column['moneda'] ) ){

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
class accionesConceptos extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;

				if ( isset( $column['acciones'] ) ) {
					$id_concepto = $data[$i][ 'id_concepto' ];

					$salida = '';
                                   //eliminar un cobro
                                          $salida .= '<a onclick="eliminar_cobro('.$id_concepto.')" data-rel="tooltip" data-original-title="Eliminar cobro"><i class="fa fa-trash" style="font-size:1.4em; color:#c40b0b;"></i></a>&nbsp;&nbsp;';
                                   //editar un cobro
                                          $salida .= '<a onclick="editar_cobro('.$id_concepto.')" data-rel="tooltip" data-original-title="Editar cobro"><i class="fa fa-pencil" style="font-size:1.4em; color:#008301;"></i></a>&nbsp;&nbsp;';
                                   //relacionar un cobro
                                          $salida .= '<a onclick="carga_archivo(\'contenedor_principal\',\'egresosoperador/relacionar_cobro/'.$id_concepto.'\')" data-rel="tooltip" data-original-title="Relacionar cobro"><i class="fa fa-users" aria-hidden="true"></i></a>&nbsp;&nbsp;';
                                   //ver aplicaciones de un cobro
                                          $salida .= '<a onclick="carga_archivo(\'contenedor_principal\',\'egresosoperador/ejecucionesCobro/'.$id_concepto.'\')" data-rel="tooltip" data-original-title="Ver aplicaciones del cobro"><i class="fa fa-history" style="font-size:1.4em; color:#000;"></i></a>&nbsp;&nbsp;';

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

?>
