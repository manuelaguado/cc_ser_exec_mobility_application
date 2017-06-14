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
    function pdfData($id_operador){
           $qry = "
           SELECT
           	fo_papeletas.url
           FROM
           	fo_papeletas
           WHERE
           	fo_papeletas.id_operador = $id_operador
           ORDER BY
           	fo_papeletas.id_papeletas DESC
           LIMIT 0,
            1
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
           if($query->rowCount()>=1){
                  foreach ($query->fetchAll() as $row) {
                         return $row->url;
                  }
           }
    }
    function savePapeleta($viajes,$id_operador,$token){
           $qry = "
           INSERT INTO `fo_papeletas` (
           	`id_operador`,
           	`url`,
           	`user_alta`,
           	`fecha_alta`
           )
           VALUES
           	(
           		$id_operador,
           		'../archivo/papeletas/".$token.".pdf',
                     '".$_SESSION['id_usuario']."',
                     '".date("Y-m-d H:i:s")."'
           	);
          ";
          $query = $this->db->prepare($qry);
          $query->execute();
          $id_papeletas = $this->db->lastInsertId();

          foreach($viajes as $viaje){
                 $qry = "
                 INSERT INTO `fo_papeletas_viajes` (
                 	`id_papeletas`,
                 	`id_viaje`,
                 	`user_alta`,
                 	`fecha_alta`
                 )
                 VALUES
                 	(
                 		$id_papeletas,
                 		'".$viaje['id_viaje']."',
                            '".$_SESSION['id_usuario']."',
                            '".date("Y-m-d H:i:s")."'
                 	);
                ";
                $query = $this->db->prepare($qry);
                $query->execute();
          }
    }
    function coordenadas($id_viaje){
           $query = $this->db->prepare('SELECT concat(vs.geo_origen,"/",vs.geo_destino) AS coordenadas FROM vi_viaje AS v INNER JOIN vi_viaje_statics AS vs ON vs.id_viaje = v.id_viaje WHERE v.id_viaje = '.$id_viaje.'');
           $query->execute();
           return $query->fetchAll()[0]->coordenadas;
    }
    function variantes($id_viaje){
           $qry = "
           SELECT
           	va.ruta_file,
           	va.km,
           	va.minutos,
           	va.sumario
           FROM
           	vi_viaje_alternativas AS va
           INNER JOIN vi_viaje_statics AS vs ON va.id_viaje_statics = vs.id_viaje_statics
           WHERE
           	vs.id_viaje = $id_viaje
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
           $num =0;
           $array = array();
           if($query->rowCount()>=1){
                  foreach ($query->fetchAll() as $row) {
                         $array[$num]['file'] = self::duplicateMap($row->ruta_file);
                         $array[$num]['km'] = $row->km;
                         $array[$num]['minutos'] = $row->minutos;
                         $array[$num]['sumario'] = $row->sumario;
                         $num++;
                  }
           }
           return $array;
    }
    function duplicateMap($imagen){
           $token = Controller::token();

           $destino = $token.'.png';
           $tmp = '../public/tmp/';

           copy('../archivo/'.$imagen, $tmp.$destino);
           return '<a href="tmp/'.$destino.'" title="Ruta alternativa" data-rel="colorbox"><img src="plugs/timthumb.php?src=tmp/'.$destino.'&w=300"></a>';
    }
    function pausar_viaje_do($id_viaje){
           $query = $this->db->prepare('SELECT v.cat_status_viaje FROM vi_viaje AS v WHERE v.id_viaje = '.$id_viaje);
           $query->execute();
           $status = $query->fetchAll()[0]->cat_status_viaje;
           $newstat = ($status != 251)?251:172;

           $qry = "UPDATE `vi_viaje` SET `cat_status_viaje` = $newstat WHERE (`id_viaje` = $id_viaje);";
           $query = $this->db->prepare($qry);
           $query->execute();
           return json_encode(array('resp' => true));
    }
    function opProcess(){
           $date = date('Y-m-d');
           $qry = "
           SELECT
           o.id_operador,
           u.correo,
           n.num
           FROM
           	vi_viaje AS v
           INNER JOIN cr_operador_unidad AS ou ON ou.id_operador_unidad = v.id_operador_unidad
           INNER JOIN cr_operador AS o ON ou.id_operador = o.id_operador
           INNER JOIN fw_usuarios AS u ON o.id_usuario = u.id_usuario
           INNER JOIN vi_viaje_detalle AS vd ON vd.id_viaje = v.id_viaje
           INNER JOIN cr_operador_numeq AS `on` ON `on`.id_operador = o.id_operador
           INNER JOIN cr_numeq AS n ON `on`.id_numeq = n.id_numeq
           WHERE
           	v.cat_status_viaje = '172'
           AND vd.fecha_requerimiento < '".$date."'
           GROUP BY
           	o.id_operador
           ";
           $query1 = $this->db->prepare($qry);
           $query1->execute();
           $num =0;
           $array = array();
           if($query1->rowCount()>=1){
                  foreach ($query1->fetchAll() as $row) {
                         $array[$num]['id_operador'] = $row->id_operador;
                         $array[$num]['correo'] = $row->correo;
                         $array[$num]['num'] = $row->num;
                         $num++;
                  }
           }
           return $array;
    }
    function proceso249_do(){
           $date = date('Y-m-d');
           $qry = "
           UPDATE vi_viaje AS v
           INNER JOIN vi_viaje_detalle AS vd ON vd.id_viaje = v.id_viaje
           SET v.cat_status_viaje = '249'
           WHERE
           	v.cat_status_viaje = '172'
           AND vd.fecha_requerimiento < '".$date."'
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
    }
    function desglosePapeleta($id_operador) {
           $qry = "
           SELECT
           	vs.mapa AS url_map,
           	v.id_viaje AS idviaje,
           	vs.costo_viaje AS costo,
           	vs.costos_adicionales AS adicional,
           	vs.costo_total AS neto,
           	vs.km_max_maps AS km_max,
           	vs.km_min_maps AS km_min,
           	vs.time_or_des_max AS time_max,
           	vs.time_or_des_min AS time_min,
           	vs.time_viaje AS time_operador,
           	vs.time_espera AS espera,
           	vs.time_arribo AS arribo,
           	v.cat_status_viaje AS cat_status_viaje,
           	vs.geo_origen AS geo_origen,
           	vs.geo_destino AS geo_destino,
           	vd.fecha_requerimiento,
           	vd.redondo,
           	cli1.nombre AS cliente,
           	cli2.nombre AS empresa,
              cat.etiqueta AS tipo,
              tc.cat_tipo_tarifa
           FROM
           	vi_viaje AS v
           INNER JOIN cr_operador_unidad AS ou ON v.id_operador_unidad = ou.id_operador_unidad
           INNER JOIN cr_operador AS o ON ou.id_operador = o.id_operador
           INNER JOIN vi_viaje_statics AS vs ON vs.id_viaje = v.id_viaje
           INNER JOIN vi_viaje_detalle AS vd ON vd.id_viaje = v.id_viaje
           INNER JOIN it_cliente_origen AS co ON v.id_cliente_origen = co.id_cliente_origen
           INNER JOIN cl_clientes AS cli1 ON co.id_cliente = cli1.id_cliente
           LEFT OUTER JOIN cl_clientes AS cli2 ON cli1.parent = cli2.id_cliente
           INNER JOIN cm_catalogo AS cat ON v.cat_tiposervicio = cat.id_cat
           INNER JOIN cl_tarifas_clientes AS tc ON v.id_tarifa_cliente = cl_tarifas_clientes.id_tarifa_cliente
           WHERE
           	o.id_operador = $id_operador
           AND vs.cat_status_statics = 222
           AND v.cat_status_viaje = 172
           ORDER BY
           	idviaje ASC
           ";
           $query1 = $this->db->prepare($qry);
           $query1->execute();
           $array = array();
           $num = 0;
           if($query1->rowCount()>=1){
                  foreach ($query1->fetchAll() as $row) {
                         $array[$num]['url_map'] = $row->url_map;
                         $array[$num]['id_viaje'] = $row->idviaje;
                         $array[$num]['costo'] = $row->costo;
                         $array[$num]['tipo'] = $row->tipo;
                         $array[$num]['neto'] = $row->neto;
                         $array[$num]['adicional'] = $row->adicional;
                         $array[$num]['adicional_desglose'] = self::adicional_desglose($row->idviaje);
                         $array[$num]['km_max'] = $row->km_max;
                         $array[$num]['km_min'] = $row->km_min;
                         $array[$num]['time_max'] = $row->time_max;
                         $array[$num]['time_min'] = $row->time_min;
                         $array[$num]['time_operador'] = $row->time_operador;
                         $array[$num]['espera'] = $row->espera;
                         $array[$num]['arribo'] = $row->arribo;
                         $array[$num]['cat_status_viaje'] = $row->cat_status_viaje;
                         $array[$num]['geo_origen'] = $row->geo_origen;
                         $array[$num]['geo_destino'] = $row->geo_destino;
                         $array[$num]['fecha_requerimiento'] = $row->fecha_requerimiento;
                         $array[$num]['redondo'] = $row->redondo;
                         $array[$num]['cliente'] = $row->cliente;
                         $array[$num]['empresa'] = $row->empresa;
                         $array[$num]['cat_tipo_tarifa'] = $row->cat_tipo_tarifa;
                         $num++;
                  }
           }
           return $array;
    }
    function adicional_desglose($id_viaje){
           $qry = "
           SELECT
           	cat.etiqueta,
           	ca.costo,
           	ca.descripcion
           FROM
           	vi_costos_adicionales AS ca
           INNER JOIN cm_catalogo AS cat ON ca.cat_concepto = cat.id_cat
           WHERE
           	ca.id_viaje = $id_viaje
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
           $array = array();
           $array['empty'] = true;
           $num = 0;
           if($query->rowCount()>=1){
                  foreach ($query->fetchAll() as $row) {
                         $array[$num]['etiqueta'] = $row->etiqueta;
                         $array[$num]['costo'] = $row->costo;
                         $array[$num]['descripcion'] = $row->descripcion;
                         $num++;
                  }
              $array['empty'] = false;
           }
           return $array;
    }
    function periodo($id_operador){
           $date = date('Y-m-d');
           $qry = "
           SELECT
           v.fecha_alta
           FROM
           vi_viaje AS v
           INNER JOIN cr_operador_unidad AS ou ON v.id_operador_unidad = ou.id_operador_unidad
           INNER JOIN cr_operador AS o ON ou.id_operador = o.id_operador
           INNER JOIN vi_viaje_detalle AS vd ON vd.id_viaje = v.id_viaje
           WHERE
           o.id_operador = $id_operador AND
           v.cat_status_viaje = 172
           AND vd.fecha_requerimiento < '".$date."'
           ORDER BY
           	v.id_viaje ASC
           LIMIT 0, 1
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
           if($query->rowCount()>=1){
                  foreach ($query->fetchAll() as $row) {
                         $fecha = $row->fecha_alta;
                  }
           }
           setlocale(LC_TIME,"es_MX.UTF-8");
           $dt_Ayer = date('m/d/Y', strtotime('-1 day'));
           $hasta = strftime("%A %e de %B", strtotime($dt_Ayer));
           $desde = strftime("%A %e de %B", strtotime($fecha));
           return 'desde el '.$desde.' hasta el '.$hasta;
    }
    function head_papeleta($id_operador){
           $qry = "
           SELECT
           	concat(
           		fw_usuarios.nombres,
           		' ',
           		fw_usuarios.apellido_paterno,
           		' ',
           		fw_usuarios.apellido_materno
           	) AS nombre,
           	NOW() AS fecha,
           	cr_numeq.num
           FROM
           	cr_operador
           INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = cr_operador.id_operador
           INNER JOIN cr_numeq ON cr_operador_numeq.id_numeq = cr_numeq.id_numeq
           INNER JOIN fw_usuarios ON cr_operador.id_usuario = fw_usuarios.id_usuario
           WHERE
           	cr_operador.id_operador = $id_operador
           ";
           $query1 = $this->db->prepare($qry);
           $query1->execute();
           $array = array();
           if($query1->rowCount()>=1){
                  foreach ($query1->fetchAll() as $row) {
                         $array['nombre'] = $row->nombre;
                         $array['fecha'] = $row->fecha;
                         $array['num'] = $row->num;
                  }
           }
           return $array;
    }
    function marcar_como_pagado_do($id_operador){
           $ingresos = self::ingresosData($id_operador);
           $egresos = self::egresosData($id_operador);
           $date = date('Y-m-d');
           $qry = "
                  UPDATE vi_viaje AS v
                  INNER JOIN cr_operador_unidad AS ou ON v.id_operador_unidad = ou.id_operador_unidad
                  INNER JOIN fo_ingresos AS fi ON v.id_viaje = fi.id_viaje
                  LEFT OUTER JOIN fo_operador_conceptos AS oc ON ou.id_operador = oc.id_operador
                  LEFT OUTER JOIN fo_concepto_adeudo AS ca ON ca.id_operador_conecepto = oc.id_operador_concepto
                  SET  v.cat_status_viaje = '250',
                       fi.cat_status_pago = '246',
                       ca.cat_status_pago = '246'
                  WHERE
                  	v.cat_status_viaje = '249'
                  AND ou.id_operador = '".$id_operador."'
           ";
           $query = $this->db->prepare($qry);
           $ok = $query->execute();
           if($ok){
                foreach($ingresos as $num => $ingreso){
                       $id_cobro_ingreso = self::insert_ingreso($ingreso['id_ingreso'],$ingreso['monto']);
                       self::registrarMovimiento('ingreso',$id_cobro_ingreso,$ingreso['monto'],$id_operador);
                }
                foreach($egresos as $num => $egreso){
                       $id_pago_concepto = self::insert_egreso($egreso['id_concepto_adeudo'],$egreso['monto']);
                       self::registrarMovimiento('egreso',$id_pago_concepto,$egreso['monto'],$id_operador);
                }
              return json_encode(array('resp' => true));
           }
    }
    public function registrarMovimiento($tipo,$id,$monto,$id_operador){
           $current = self::saldoOperador($id_operador);
           $saldo = ($tipo == 'ingreso')?$current+$monto:$current-$monto;
           switch($tipo){
                  case 'ingreso':
                            self::insertMovimientoI($id,$monto,$saldo,$id_operador);
                  break;
                  case 'egreso':
                            self::insertMovimientoE($id,$monto,$saldo,$id_operador);
                  break;
                  default:
                  break;
          }
    }
    public function saldoOperador($id_operador){
           $qry = "
           SELECT
           	fo_movimientos.saldo
           FROM
           	fo_movimientos
           WHERE
           	fo_movimientos.id_operador = $id_operador
           ORDER BY
           	fo_movimientos.id_movimiento DESC
           LIMIT 0,
            1
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
           if($query->rowCount()>=1){
                  foreach ($query->fetchAll() as $row) {
                         return $row->saldo;
                  }
           }
    }
    public function insertMovimientoE($id_pago_concepto,$monto,$saldo,$id_operador){
          $qry = "
                  INSERT INTO `fo_movimientos` (
                     `id_operador`,
                     `id_pago_concepto`,
                     `egreso`,
                     `saldo`,
                     `user_alta`,
                     `fecha_alta`
                  )
                  VALUES
                         (
                                   '".$id_operador."',
                                   '".$id_pago_concepto."',
                                   '".$monto."',
                                   '".$saldo."',
                                   '".$_SESSION['id_usuario']."',
                                   '".date("Y-m-d H:i:s")."'
                         );
          ";
          $query = $this->db->prepare($qry);
          $query->execute();
    }
    public function insertMovimientoI($id_cobro_ingreso,$monto,$saldo,$id_operador){
          $qry = "
                  INSERT INTO `fo_movimientos` (
                     `id_operador`,
                     `id_cobro_ingreso`,
                     `ingreso`,
                     `saldo`,
                     `user_alta`,
                     `fecha_alta`
                  )
                  VALUES
                         (
                                   '".$id_operador."',
                                   '".$id_cobro_ingreso."',
                                   '".$monto."',
                                   '".$saldo."',
                                   '".$_SESSION['id_usuario']."',
                                   '".date("Y-m-d H:i:s")."'
                         );
          ";
          $query = $this->db->prepare($qry);
          $query->execute();
    }
    public function insert_egreso($id_concepto_adeudo,$monto){
          $qry = "
                  INSERT INTO `fo_pagos_conceptos` (
                     `id_concepto_adeudo`,
                     `cat_tipo_pago`,
                     `monto`,
                     `fecha_pago`,
                     `user_alta`,
                     `fecha_alta`
                  )
                  VALUES
                         (
                                   '".$id_concepto_adeudo."',
                                   '243',
                                   '".$monto."',
                                   '".date("Y-m-d H:i:s")."',
                                   '".$_SESSION['id_usuario']."',
                                   '".date("Y-m-d H:i:s")."'
                         );
          ";
          $query = $this->db->prepare($qry);
          $query->execute();
          return $this->db->lastInsertId();
   }
    public function insert_ingreso($id_ingreso,$monto){
          $qry = "
                  INSERT INTO `fo_cobro_ingresos` (
                     `id_ingreso`,
                     `cat_tipo_pago`,
                     `monto`,
                     `fecha_cobro`,
                     `user_alta`,
                     `fecha_alta`
                  )
                  VALUES
                         (
                                   '".$id_ingreso."',
                                   '243',
                                   '".$monto."',
                                   '".date("Y-m-d H:i:s")."',
                                   '".$_SESSION['id_usuario']."',
                                   '".date("Y-m-d H:i:s")."'
                         );
          ";
          $query = $this->db->prepare($qry);
          $query->execute();
          return $this->db->lastInsertId();
   }
    function egresosData($id_operador){
           $qry = "
           SELECT
           	ca.id_concepto_adeudo,
           	ca.monto
           FROM
           	vi_viaje AS v
           INNER JOIN cr_operador_unidad AS ou ON v.id_operador_unidad = ou.id_operador_unidad
           INNER JOIN cr_operador AS o ON ou.id_operador = o.id_operador
           INNER JOIN fo_operador_conceptos AS oc ON oc.id_operador = o.id_operador
           INNER JOIN fo_concepto_adeudo AS ca ON ca.id_operador_conecepto = oc.id_operador_concepto
           WHERE
           	v.cat_status_viaje = 249
           AND o.id_operador = 1
           GROUP BY
           	ca.id_concepto_adeudo
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
           $num =0;
           $array = array();
           if($query->rowCount()>=1){
                  foreach ($query->fetchAll() as $row) {
                         $array[$num]['id_concepto_adeudo'] = $row->id_concepto_adeudo;
                         $array[$num]['monto'] = $row->monto;
                         $num++;
                  }
           }
           return $array;
    }
    function ingresosData($id_operador){
           $qry = "
           SELECT
           	fi.id_ingreso,
           	fi.monto
           FROM
           	vi_viaje AS v
           INNER JOIN fo_ingresos AS fi ON fi.id_viaje = v.id_viaje
           WHERE
           	v.cat_status_viaje = 249
           AND fi.id_operador = $id_operador
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
           $num =0;
           $array = array();
           if($query->rowCount()>=1){
                  foreach ($query->fetchAll() as $row) {
                         $array[$num]['id_ingreso'] = $row->id_ingreso;
                         $array[$num]['monto'] = $row->monto;
                         $num++;
                  }
           }
           return $array;
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
                         'moneda' => false,
                         'typ' => 'int',
                         'dt' => 3
                  ),
                  array(
                         'db' => 'Sum(vs.costo_viaje) AS costo_viaje',
                         'dbj' => 'Sum(vs.costo_viaje)',
                         'real' => 'Sum(vs.costo_viaje)',
                         'alias' => 'costo_viaje',
                         'moneda' => false,
                         'typ' => 'int',
                         'dt' => 4
                  ),
                  array(
                         'db' => 'Sum(vs.costos_adicionales) AS adicional',
                         'dbj' => 'Sum(vs.costos_adicionales)',
                         'real' => 'Sum(vs.costos_adicionales)',
                         'alias' => 'adicional',
                         'moneda' => false,
                         'typ' => 'int',
                         'dt' => 5
                  ),
                  array(
                         'db' => 'sum(vs.km_max_maps) AS kms',
                         'dbj' => 'sum(vs.km_max_maps)',
                         'real' => 'sum(vs.km_max_maps)',
                         'alias' => 'kms',
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
           INNER JOIN vi_viaje_detalle AS vd ON vd.id_viaje = v.id_viaje
           ';
           $date = date('Y-m-d');
           $where = "
              (v.cat_status_viaje = 172) AND
              vd.fecha_requerimiento < '".$date."'
           ";
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
                         'format' => 'mapsroutes',
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
                         'format' => 'alternativas',
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
                  ),
                  array(
                         'db' => 'v.cat_status_viaje AS cat_status_viaje',
                         'dbj' => 'v.cat_status_viaje',
                         'real' => 'v.cat_status_viaje',
                         'alias' => 'cat_status_viaje',
                         'format' => 'identificador',
                         'typ' => 'int',
                         'dt' => 12
                  ),
                  array(
                         'db' => 'vs.geo_origen AS geo_origen',
                         'dbj' => 'vs.geo_origen',
                         'real' => 'vs.geo_origen',
                         'alias' => 'geo_origen',
                         'typ' => 'int',
                         'dt' => 13
                  ),
                  array(
                         'db' => 'vs.geo_destino AS geo_destino',
                         'dbj' => 'vs.geo_destino',
                         'real' => 'vs.geo_destino',
                         'alias' => 'geo_destino',
                         'typ' => 'int',
                         'dt' => 14
                  )
           );
           $inner = '
           INNER JOIN cr_operador_unidad AS ou ON v.id_operador_unidad = ou.id_operador_unidad
           INNER JOIN cr_operador AS o ON ou.id_operador = o.id_operador
           INNER JOIN vi_viaje_statics AS vs ON vs.id_viaje = v.id_viaje
           ';
           $where = "
                  o.id_operador = $id_operador
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
    function procesadosGroup($array){
           ini_set('memory_limit', '256M');
           $table = 'vi_viaje AS v';
           $primaryKey = 'id_viaje';
           $columns = array(
                  array(
                         'db' => 'o.id_operador AS id_operador',
                         'dbj' => 'o.id_operador',
                         'real' => 'o.id_operador',
                         'alias' => 'id_operador',
                         'typ' => 'int',
                         'dt' => 0
                  ),
                  array(
                         'db' => 'n.num as num',
                         'dbj' => 'n.num',
                         'real' => 'n.num',
                         'alias' => 'num',
                         'typ' => 'int',
                         'dt' => 1
                  ),
                  array(
                         'db' => 'CONCAT(u.nombres, " " ,	u.apellido_paterno, " " ,	u.apellido_materno) AS nombre',
                         'dbj' => 'CONCAT(u.nombres, " " ,	u.apellido_paterno, " " ,	u.apellido_materno)',
                         'real' => 'CONCAT(u.nombres, " " ,	u.apellido_paterno, " " ,	u.apellido_materno)',
                         'alias' => 'nombre',
                         'typ' => 'txt',
                         'dt' => 2
                  ),
                  array(
                         'db' => 'Count(v.id_viaje) AS total',
                         'dbj' => 'Count(v.id_viaje)',
                         'real' => 'Count(v.id_viaje)',
                         'alias' => 'total',
                         'typ' => 'int',
                         'dt' => 3
                  ),
                  array(
                         'db' => 'Sum(vs.costo_total) AS viaje_mas_adicional',
                         'dbj' => 'Sum(vs.costo_total)',
                         'real' => 'Sum(vs.costo_total)',
                         'alias' => 'viaje_mas_adicional',
                         'moneda' => false,
                         'typ' => 'int',
                         'dt' => 4
                  ),
                  array(
                         'db' => 'Sum(vs.costo_viaje) AS costo_viaje',
                         'dbj' => 'Sum(vs.costo_viaje)',
                         'real' => 'Sum(vs.costo_viaje)',
                         'alias' => 'costo_viaje',
                         'moneda' => false,
                         'typ' => 'int',
                         'dt' => 5
                  ),
                  array(
                         'db' => 'Sum(vs.costos_adicionales) AS adicional',
                         'dbj' => 'Sum(vs.costos_adicionales)',
                         'real' => 'Sum(vs.costos_adicionales)',
                         'alias' => 'adicional',
                         'moneda' => false,
                         'typ' => 'int',
                         'dt' => 6
                  ),
                  array(
                         'db' => 'sum(vs.km_max_maps) AS kms',
                         'dbj' => 'sum(vs.km_max_maps)',
                         'real' => 'sum(vs.km_max_maps)',
                         'alias' => 'kms',
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
                         'db' => 'o.id_operador AS idoperador',
                         'dbj' => 'o.id_operador',
                         'real' => 'o.id_operador',
                         'alias' => 'idoperador',
                         'datareal' => 'acciones',
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
           INNER JOIN vi_viaje_detalle AS vd ON vd.id_viaje = v.id_viaje
           ';
           $date = date('Y-m-d');
           $where = "
              (v.cat_status_viaje = 249)
           ";
           $orden = '
           GROUP BY
           o.id_operador,
           n.num
           ORDER BY
           v.id_viaje ASC
           ';
           $render_table = new accionesprocesadosGroup;
           return json_encode(
                  $render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
           );
    }
    function viajes_procesados($array,$id_operador){
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
                         'format' => 'mapsroutes',
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
                         'format' => 'alternativas',
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
                  ),
                  array(
                         'db' => 'v.cat_status_viaje AS cat_status_viaje',
                         'dbj' => 'v.cat_status_viaje',
                         'real' => 'v.cat_status_viaje',
                         'alias' => 'cat_status_viaje',
                         'format' => 'identificador',
                         'typ' => 'int',
                         'dt' => 12
                  ),
                  array(
                         'db' => 'vs.geo_origen AS geo_origen',
                         'dbj' => 'vs.geo_origen',
                         'real' => 'vs.geo_origen',
                         'alias' => 'geo_origen',
                         'typ' => 'int',
                         'dt' => 13
                  ),
                  array(
                         'db' => 'vs.geo_destino AS geo_destino',
                         'dbj' => 'vs.geo_destino',
                         'real' => 'vs.geo_destino',
                         'alias' => 'geo_destino',
                         'typ' => 'int',
                         'dt' => 14
                  )
           );
           $inner = '
           INNER JOIN cr_operador_unidad AS ou ON v.id_operador_unidad = ou.id_operador_unidad
           INNER JOIN cr_operador AS o ON ou.id_operador = o.id_operador
           INNER JOIN vi_viaje_statics AS vs ON vs.id_viaje = v.id_viaje
           ';
           $where = "
                  o.id_operador = $id_operador
           AND vs.cat_status_statics = 222
           AND v.cat_status_viaje = 249
           ";
           $orden = '
           ORDER BY
           	id DESC
           ';
           $render_table = new accionesviajes_procesados;
           return json_encode(
                  $render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
           );
    }
    function archivo_get($array){
           ini_set('memory_limit', '256M');
           $table = 'vi_viaje AS v';
           $primaryKey = 'id_viaje';
           $columns = array(
                  array(
                         'db' => 'o.id_operador AS id_operador',
                         'dbj' => 'o.id_operador',
                         'real' => 'o.id_operador',
                         'alias' => 'id_operador',
                         'typ' => 'int',
                         'dt' => 0
                  ),
                  array(
                         'db' => 'n.num as num',
                         'dbj' => 'n.num',
                         'real' => 'n.num',
                         'alias' => 'num',
                         'typ' => 'int',
                         'dt' => 1
                  ),
                  array(
                         'db' => 'CONCAT(u.nombres, " " ,	u.apellido_paterno, " " ,	u.apellido_materno) AS nombre',
                         'dbj' => 'CONCAT(u.nombres, " " ,	u.apellido_paterno, " " ,	u.apellido_materno)',
                         'real' => 'CONCAT(u.nombres, " " ,	u.apellido_paterno, " " ,	u.apellido_materno)',
                         'alias' => 'nombre',
                         'typ' => 'txt',
                         'dt' => 2
                  ),
                  array(
                         'db' => 'Count(v.id_viaje) AS total',
                         'dbj' => 'Count(v.id_viaje)',
                         'real' => 'Count(v.id_viaje)',
                         'alias' => 'total',
                         'typ' => 'int',
                         'dt' => 3
                  ),
                  array(
                         'db' => 'Sum(vs.costo_total) AS viaje_mas_adicional',
                         'dbj' => 'Sum(vs.costo_total)',
                         'real' => 'Sum(vs.costo_total)',
                         'alias' => 'viaje_mas_adicional',
                         'moneda' => false,
                         'typ' => 'int',
                         'dt' => 4
                  ),
                  array(
                         'db' => 'Sum(vs.costo_viaje) AS costo_viaje',
                         'dbj' => 'Sum(vs.costo_viaje)',
                         'real' => 'Sum(vs.costo_viaje)',
                         'alias' => 'costo_viaje',
                         'moneda' => false,
                         'typ' => 'int',
                         'dt' => 5
                  ),
                  array(
                         'db' => 'Sum(vs.costos_adicionales) AS adicional',
                         'dbj' => 'Sum(vs.costos_adicionales)',
                         'real' => 'Sum(vs.costos_adicionales)',
                         'alias' => 'adicional',
                         'moneda' => false,
                         'typ' => 'int',
                         'dt' => 6
                  ),
                  array(
                         'db' => 'sum(vs.km_max_maps) AS kms',
                         'dbj' => 'sum(vs.km_max_maps)',
                         'real' => 'sum(vs.km_max_maps)',
                         'alias' => 'kms',
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
                         'db' => 'o.id_operador AS idoperador',
                         'dbj' => 'o.id_operador',
                         'real' => 'o.id_operador',
                         'alias' => 'idoperador',
                         'datareal' => 'acciones',
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
           INNER JOIN vi_viaje_detalle AS vd ON vd.id_viaje = v.id_viaje
           ';
           $date = date('Y-m-d');
           $where = "
              v.cat_status_viaje = 250
           ";
           $orden = '
           GROUP BY
           o.id_operador,
           n.num
           ORDER BY
           v.id_viaje ASC
           ';
           $render_table = new accionesarchivo_get;
           return json_encode(
                  $render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
           );
    }
    function ver_viajes_archivados($array,$id_operador){
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
                         'format' => 'mapsroutes',
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
                         'format' => 'alternativas',
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
                  ),
                  array(
                         'db' => 'v.cat_status_viaje AS cat_status_viaje',
                         'dbj' => 'v.cat_status_viaje',
                         'real' => 'v.cat_status_viaje',
                         'alias' => 'cat_status_viaje',
                         'format' => 'identificador',
                         'typ' => 'int',
                         'dt' => 12
                  ),
                  array(
                         'db' => 'vs.geo_origen AS geo_origen',
                         'dbj' => 'vs.geo_origen',
                         'real' => 'vs.geo_origen',
                         'alias' => 'geo_origen',
                         'typ' => 'int',
                         'dt' => 13
                  ),
                  array(
                         'db' => 'vs.geo_destino AS geo_destino',
                         'dbj' => 'vs.geo_destino',
                         'real' => 'vs.geo_destino',
                         'alias' => 'geo_destino',
                         'typ' => 'int',
                         'dt' => 14
                  )
           );
           $inner = '
           INNER JOIN cr_operador_unidad AS ou ON v.id_operador_unidad = ou.id_operador_unidad
           INNER JOIN cr_operador AS o ON ou.id_operador = o.id_operador
           INNER JOIN vi_viaje_statics AS vs ON vs.id_viaje = v.id_viaje
           ';
           $where = "
                  o.id_operador = $id_operador
           AND v.cat_status_viaje = 250
           ";
           $orden = '
           ORDER BY
           	id DESC
           ';
           $render_table = new accionesviajes_archivados;
           return json_encode(
                  $render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
           );
    }
}


class accionesviajes_archivados extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
                            $id_viaje = $data[$i][ 'id' ];
                            $status_viaje = $data[$i][ 'cat_status_viaje' ];

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
                                          case 'identificador':
                                          $salida = '
                                          <div class="infobox infobox-brown infobox-alter infobox-dark" style="width: 100px;" >
                                                 <div style="text-align:center; font-size:2em; height:65px; vertical-align:middle;">
                                                        '.$id_viaje.'
                                                 </div>
                                          </div>';
                                          break;
                                          case 'mapsroutes':
                                          $salida = '
                                          <div class="infobox infobox-red infobox-alter infobox-dark" >
							<div class="infobox-icon center" style="width: 60px;" data-rel="tooltip" data-original-title="Costos adicionales">
                                                        <a href="https://www.google.com.mx/maps/dir/'.$id_viaje = $data[$i][ 'geo_origen' ].'/'.$id_viaje = $data[$i][ 'geo_destino' ].'/" target="_blank">
                                                               <i class="ace-icon fa fa-map-marker"></i>
                                                        </a>
							</div>
                                                 <div style="text-align:center;">
                                                        GMAPS
                                                 </div>
						</div>';
                                          break;
                                          case 'alternativas':
                                          $salida = '
                                          <div class="infobox infobox-pink infobox-alter infobox-dark" >
							<div class="infobox-icon center" style="width: 60px;" onclick="variantes_viaje('.$id_viaje.')" data-rel="tooltip" data-original-title="Costos adicionales">
                                                        <i class="ace-icon fa fa-arrows-alt"></i>
							</div>
                                                 <div style="text-align:center;">
                                                        VARIANTES
                                                 </div>
						</div>';
                                          break;
                                          case 'acciones':
                                          $salida = '
                                          <div class="infobox infobox-purple infobox-alter infobox-dark" >
                                                 <div class="infobox-icon center" style="width: 60px;" onclick="historia_viaje('.$id_viaje.')" data-rel="tooltip" data-original-title="Historia">
                                                        <i class="ace-icon fa fa-clock-o"></i>
                                                 </div>
                                                 <div style="text-align:center;">
                                                        HISTORIA
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
class accionesarchivo_get extends SSP{
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

					$salida = ($data[$i][ $column['alias'] ]);

					$row[ $column['dt'] ] = $salida;
				}else if (isset($column['datareal'])){
                                   switch($column['datareal']){

                                          case 'deuda':
                                          $salida = self::deuda($id_operador,$db);
                                          break;

                                          case 'pagar':
                                          $deuda = self::deuda($id_operador,$db);
                                          $costo = $data[$i][ 'viaje_mas_adicional' ];
                                          $salida = $costo - $deuda;
                                          break;

                                          case 'acciones':
                                          $salida = '
                                          <div style="width:100px !important;">
                                                 <i onclick="ver_viajes_archivados('.$data[$i][ 'id_operador' ].')" data-rel="tooltip" data-original-title="Ver viajes" class="ace-icon fa fa-tachometer blue" style="font-size:2em;"></i>
                                          </div>
                                          ';
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
}
class accionesviajes_procesados extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
                            $id_viaje = $data[$i][ 'id' ];
                            $status_viaje = $data[$i][ 'cat_status_viaje' ];

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
                                          case 'mapsroutes':
                                          $salida = '
                                          <div class="infobox infobox-red infobox-alter infobox-dark" >
							<div class="infobox-icon center" style="width: 60px;" data-rel="tooltip" data-original-title="Costos adicionales">
                                                        <a href="https://www.google.com.mx/maps/dir/'.$id_viaje = $data[$i][ 'geo_origen' ].'/'.$id_viaje = $data[$i][ 'geo_destino' ].'/" target="_blank">
                                                               <i class="ace-icon fa fa-map-marker"></i>
                                                        </a>
							</div>
                                                 <div style="text-align:center;">
                                                        GMAPS
                                                 </div>
						</div>';
                                          break;
                                          case 'identificador':
                                          $salida = '
                                          <div class="infobox infobox-brown infobox-alter infobox-dark" style="width: 100px;" >
                                                 <div style="text-align:center; font-size:2em; height:65px; vertical-align:middle;">
                                                        '.$id_viaje.'
                                                 </div>
                                          </div>';
                                          break;
                                          case 'alternativas':
                                          $salida = '
                                          <div class="infobox infobox-pink infobox-alter infobox-dark" >
							<div class="infobox-icon center" style="width: 60px;" onclick="variantes_viaje('.$id_viaje.')" data-rel="tooltip" data-original-title="Costos adicionales">
                                                        <i class="ace-icon fa fa-arrows-alt"></i>
							</div>
                                                 <div style="text-align:center;">
                                                        VARIANTES
                                                 </div>
						</div>';
                                          break;
                                          case 'acciones':
                                          $salida = '
                                          <div class="infobox infobox-purple infobox-alter infobox-dark" >
                                                 <div class="infobox-icon center" style="width: 60px;" onclick="historia_viaje('.$id_viaje.')" data-rel="tooltip" data-original-title="Historia">
                                                        <i class="ace-icon fa fa-clock-o"></i>
                                                 </div>
                                                 <div style="text-align:center;">
                                                        HISTORIA
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
class accionesprocesadosGroup extends SSP{
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

					$salida = ($data[$i][ $column['alias'] ]);

					$row[ $column['dt'] ] = $salida;
				}else if (isset($column['datareal'])){
                                   switch($column['datareal']){

                                          case 'deuda':
                                          $salida = self::deuda($id_operador,$db);
                                          break;

                                          case 'pagar':
                                          $deuda = self::deuda($id_operador,$db);
                                          $costo = $data[$i][ 'viaje_mas_adicional' ];
                                          $salida = $costo - $deuda;
                                          break;

                                          case 'acciones':
                                          $salida = '
                                          <div style="width:100px !important;">
                                                 <i onclick="accion_procesadosGroup('.$data[$i][ 'id_operador' ].')" data-rel="tooltip" data-original-title="Ver viajes" class="ace-icon fa fa-tachometer blue" style="font-size:2em;"></i>
                                                 <i onclick="marcar_como_pagado('.$data[$i][ 'id_operador' ].')" data-rel="tooltip" data-original-title="Marcar como pagado" class="ace-icon fa fa-credit-card-alt green" style="font-size:2em;"></i>
                                                 <i onclick="ver_papeleta('.$data[$i][ 'id_operador' ].')" data-rel="tooltip" data-original-title="Papeleta" class="ace-icon fa fa-file-pdf-o red" style="font-size:2em;"></i>
                                          </div>
                                          ';
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
                            $status_viaje = $data[$i][ 'cat_status_viaje' ];

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
                                          case 'mapsroutes':
                                          $salida = '
                                          <div class="infobox infobox-red infobox-alter infobox-dark" >
							<div class="infobox-icon center" style="width: 60px;" data-rel="tooltip" data-original-title="Costos adicionales">
                                                        <a href="https://www.google.com.mx/maps/dir/'.$id_viaje = $data[$i][ 'geo_origen' ].'/'.$id_viaje = $data[$i][ 'geo_destino' ].'/" target="_blank">
                                                               <i class="ace-icon fa fa-map-marker"></i>
                                                        </a>
							</div>
                                                 <div style="text-align:center;">
                                                        GMAPS
                                                 </div>
						</div>';
                                          break;
                                          case 'identificador':
                                          $salida = '
                                          <div class="infobox infobox-brown infobox-alter infobox-dark" style="width: 100px;" >
                                                 <div style="text-align:center; font-size:2em; height:65px; vertical-align:middle;">
                                                        '.$id_viaje.'
                                                 </div>
						</div>';
                                          break;
                                          case 'alternativas':
                                          $salida = '
                                          <div class="infobox infobox-pink infobox-alter infobox-dark" >
							<div class="infobox-icon center" style="width: 60px;" onclick="variantes_viaje('.$id_viaje.')" data-rel="tooltip" data-original-title="Costos adicionales">
                                                        <i class="ace-icon fa fa-arrows-alt"></i>
							</div>
                                                 <div style="text-align:center;">
                                                        VARIANTES
                                                 </div>
						</div>';
                                          break;
                                          case 'acciones':
                                          if($status_viaje == 172){
                                                 $icon = "fa fa-pause";
                                                 $t = 'Activo y listo para procesarse';
                                          }elseif($status_viaje == 247){
                                                 $icon = "fa fa-play-circle-o";
                                                 $t = 'Tabulado en pausa para revision con el operador';
                                          }elseif($status_viaje == 251){
                                                 $icon = "fa fa-play";
                                                 $t = 'Viaje pausado, no se procesará para pago';
                                          }
                                          $salida = '
                                          <div class="infobox infobox-green infobox-actions infobox-dark" >
							<div class="infobox-icon" onclick="costos_adicionales_post('.$id_viaje.')" data-rel="tooltip" data-original-title="Costos adicionales">
								<i class="ace-icon icofont icofont-money-bag"></i>
							</div>
                                                 <div class="infobox-icon" onclick="cambiar_tarifa_post('.$id_viaje.')" data-rel="tooltip" data-original-title="Cambiar tarifa">
                                                        <i class="ace-icon icofont icofont-exchange"></i>
                                                 </div>
                                                 <div class="infobox-icon" onclick="historia_viaje('.$id_viaje.')" data-rel="tooltip" data-original-title="Historia">
                                                        <i class="ace-icon fa fa-clock-o"></i>
                                                 </div>
                                                 <div class="infobox-icon" onclick="pausar_viaje('.$id_viaje.')" data-rel="tooltip" data-original-title="'.$t.'">
                                                        <i class="ace-icon '.$icon.'"></i>
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

					$salida = ($data[$i][ $column['alias'] ]);

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
