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
    function historia_viaje($id_viaje){
           $sql="
           SELECT
                      	num.num,
                      	cm1.etiqueta,
                      	cm2.valor,
                      	stt.fecha_alta,
                      	fwu.usuario AS solicita,
                      	fwu2.usuario AS autoriza
           FROM
                      	cr_state AS stt
                      INNER JOIN cr_operador_unidad AS crou ON stt.id_operador_unidad = crou.id_operador_unidad
                      INNER JOIN cr_operador AS crop ON crou.id_operador = crop.id_operador
                      INNER JOIN cr_operador_numeq AS opnum ON opnum.id_operador = crop.id_operador
                      INNER JOIN cr_numeq AS num ON opnum.id_numeq = num.id_numeq
                      INNER JOIN cm_catalogo AS cm1 ON cm1.etiqueta = stt.state
                      INNER JOIN fw_usuarios AS fwu ON crop.id_usuario = fwu.id_usuario
                      INNER JOIN cm_catalogo AS cm2 ON cm1.etiqueta = cm2.etiqueta
                      INNER JOIN fw_usuarios AS fwu2 ON fwu2.id_usuario = stt.user_alta
           WHERE
           stt.id_viaje = $id_viaje AND
              cm1.catalogo = 'clavesitio' AND
              cm2.catalogo = 'clavesitio'
           ORDER BY
              stt.id_state ASC
           LIMIT 0,
                       100

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
    function selectClave(){
           /*TDS = Tipo de salida puede ser A10, F15, F13 o T1*/
       $claves = array(
              array(
                     'clave' => 'A11',
                     'descripcion' => 'En el punto'
              ),
              array(
                     'clave' => 'A14',
                     'descripcion' => 'Abandono'
              ),
              array(
                     'clave' => 'C8',
                     'descripcion' => 'Servicio a bordo'
              ),
              array(
                     'clave' => 'A2',
                     'descripcion' => 'Servicio por tiempo'
              ),
              array(
                     'clave' => 'C9',
                     'descripcion' => 'Servicio concluido'
              ),
              array(
                     'clave' => 'C14',
                     'descripcion' => 'Destino parcial'
              ),
              array(
                     'clave' => 'C10',
                     'descripcion' => 'Inicio de escala'
              ),
              array(
                     'clave' => 'C11',
                     'descripcion' => 'Fin de escala'
              ),
              array(
                     'clave' => 'C12',
                     'descripcion' => 'Cambio de ruta'
              )
       );

       return $claves;
    }
    function diferenciaFechasxx($init,$end){
           $datetime1 = new DateTime($init);
           $datetime2 = new DateTime($end);
           $dteDiff = $datetime1->diff($datetime2);
           return $dteDiff->format("%H:%I:%S");
    }
    function procesarViajeFinalizado($viaje){
           foreach ($viaje as $key => $value) {
                  $this->$key = strip_tags($value);
           }
           //$viaje['id_operador_unidad']['id_episodio']['id_viaje']['id_operador']['num']['id_cliente']['empresa']['georigen']['geodestino']

           //obtener tiempo entre tds(A10,F13,F15,T1,T2) y A11 Tiempo de llegada
                     $init_tds = self::init_tds($this->id_viaje);
                     $time_a11 = self::getDateClave($this->id_viaje,'A11');
                     $time_arribo = Controller::diferenciaFechas($init_tds,$time_a11);
           //obtener tiempo entre A11 y C8 Tiempo de espera
                     $time_c8 = self::getDateClave($this->id_viaje,'C8');
                     $tiempo_espera = Controller::diferenciaFechas($time_a11,$time_c8);
           //obtener tiempo entre C8 y C9 Tiempo del viaje
                     $time_c9 = self::getDateClave($this->id_viaje,'C9');
                     $tiempo_viaje = Controller::diferenciaFechas($time_c8,$time_c9);
           //obtener alternativas de curso
           //obtener tiempo entre origen y destino MAX
           //obtener tiempo entre origen y destino MIN
           //obtener tiempo entre origen y destino PROMEDIO
           //obtener km MAX
           //obtener km MIN
           //obtener km PROMEDIO
           //obtener mapas
           //geocoordenadas de origen
           //geocoordenadas destino
                     $id_statics =  self::insertStatic($viaje,$time_arribo,$tiempo_espera,$tiempo_viaje);
                     $upsSttcs = self::routesalternatives($this->georigen,$this->geodestino,$id_statics,$this->id_viaje);

           //obtener el costo del viaje
                     $costoData = self::getCostViaje($this->id_viaje,$upsSttcs['kmss_max']);

           //ingresar costo adicional por excedente en kilometraje
                     if($costoData['excedente']){
                            $excedente = $costoData['excedente_adicional'];
                            $updca['id_viaje']= $this->id_viaje;
                            $updca['costo']= '$ '.$excedente * $costoData['km_adicional'];
                            $updca['cat_concepto']= 248;
                            $updca['descripcion']= $excedente . ' km adicionales';
                            self::addCostoAdicional($updca);
                     }

           //Insertar costos adicionales automáticos, como tiempo de espera y escalas
                     self::addCostTiempoEspera($this->id_viaje,$tiempo_espera);
                     self::addCostTiempoEscala($this->id_viaje);

           //obtener costos adicionales
                     $adicional = self::getCostosAdicionales($this->id_viaje);
                     self::updateStaticsCosts($costoData['costo'],$adicional,$id_statics,$costoData['id_tarifa_cliente']);

           //ingresar costo en monedero de operador
                     self::insertMonedero($viaje,$costoData['costo']);
    }
    function arrayCostosAdicionales($id_viaje){
           $sql ="
                  SELECT
                  	ca.costo,
                  	cat.etiqueta,
                  	cat.valor
                  FROM
                  	vi_costos_adicionales AS ca
                  INNER JOIN cm_catalogo AS cat ON ca.cat_concepto = cat.id_cat
                  WHERE
                  	ca.id_viaje = $id_viaje
           ";

           $query = $this->db->prepare($sql);
           $query->execute();
           $array = array();
           $num = 0;
           if($query->rowCount()>=1){
                  foreach ($query->fetchAll() as $row) {
                         $array[$num]['costo'] =  $row->costo;
                         $array[$num]['etiqueta'] =  $row->etiqueta;
                         $array[$num]['valor'] =  $row->valor;
                         $num++;
                  }
           }
           return $array;
    }
    function getComision($id_operador){
           $sql="
                  SELECT
                        o.comision
                  FROM
                        cr_operador AS o
                  WHERE
                        o.id_operador = $id_operador
           ";
           $query = $this->db->prepare($sql);
           $query->execute();
           if($query->rowCount()>=1){
                  foreach ($query->fetchAll() as $row) {
                         $comision = $row->comision;
                  }
           }
           if($comision == NULL){
                  //Variables del sistema
                  $comision = (Controlador::getConfig(1,'comision_operadores'))['valor'];
           }
           return $comision;
    }
    function updateMonedero($id_ingreso,$costo,$id_viaje,$id_operador){

           $comision = self::getComision($id_operador);
           $adicionales = self::arrayCostosAdicionales($id_viaje);

           $ad_cgravamen = 0;
           $ad_sgravamen = 0;

           foreach($adicionales as $num => $adicional){
                  if($adicional['valor'] == 257){
                         $ad_cgravamen += $adicional['costo'];
                  }elseif($adicional['valor'] == 256){
                         $ad_sgravamen += $adicional['costo'];
                  }
           }

           $gravado = $costo + $ad_cgravamen;

           $neto = $gravado - (($comision['valor'] * $gravado)/100);
           $qry = "
                  UPDATE `fo_ingresos`
                  SET
                   `monto` = '".$costo."',
                   `ad_cgravamen` = '".$ad_cgravamen."',
                   `ad_sgravamen` = '".$ad_sgravamen."',
                   `comision` = '".$comision['valor']."',
                   `neto` = '".$neto."'

                  WHERE
                         (`id_ingreso` = ".$id_ingreso.");
           ";
           $query = $this->db->prepare($qry);
           $query_resp = $query->execute();
    }
    public function insertMonedero($viaje,$costo){
           foreach ($viaje as $key => $value) {
                  $this->$key = strip_tags($value);
           }

           $comision = self::getComision($viaje['id_operador']);
           $adicionales = self::arrayCostosAdicionales($this->id_viaje);

           $ad_cgravamen = 0;
           $ad_sgravamen = 0;
           foreach($adicionales as $num => $adicional){
                  if($adicional['valor'] == 256){
                         $ad_cgravamen += $adicional['costo'];
                  }elseif($adicional['valor'] == 257){
                         $ad_sgravamen += $adicional['costo'];
                  }
           }

           $gravado = $costo + $ad_cgravamen;

           $neto = ($gravado - (($comision['valor'] * $gravado)/100))+$ad_sgravamen;
           $qry = "
                  INSERT INTO `fo_ingresos` (
                     `id_operador`,
                     `id_viaje`,
                     `cat_status_pago`,
                     `monto`,
                     `ad_cgravamen`,
                     `ad_sgravamen`,
                     `comision`,
                     `neto`,
                     `user_alta`,
                     `fecha_alta`
                  )
                  VALUES
                         (
                                   '".$this->id_operador."',
                                   '".$this->id_viaje."',
                                   '244',
                                   '".$costo."',
                                   '".$ad_cgravamen."',
                                   '".$ad_sgravamen."',
                                   '".$comision['valor']."',
                                   '".$neto."',
                                   '".$_SESSION['id_usuario']."',
                                   '".date("Y-m-d H:i:s")."'
                         );
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
           return $this->db->lastInsertId();
    }
    function updateCostosAdicionales($vars){
           foreach ($vars as $key => $value) {
                  $this->$key = strip_tags($value);
           }
           $costoData = self::getCostViaje($this->id_viaje,$this->kms);
           $adicional = self::getCostosAdicionales($this->id_viaje);
           self::updateStaticsCosts($costoData['costo'],$adicional,$this->id_viaje_statics,$costoData['id_tarifa_cliente']);
           self::updateMonedero($this->id_ingreso,$costoData['costo'],$this->id_viaje,$this->id_operador);
    }
    function addCostTiempoEscala($id_viaje){
              $time_c10 = self::getDateMultipleClave($id_viaje,'C10');
              $time_c11 = self::getDateMultipleClave($id_viaje,'C11');

              if($time_c10['status'] AND $time_c11['status']){
                     if(count($time_c10) == count($time_c11)){
                            for($i = 0; $i < (count($time_c10))-1; $i++) {
                                   $segundos = Controller::diferenciaSegundos($time_c10[$i]['fecha_alta'],$time_c11[$i]['fecha_alta']);

                                   $array = array();
                                   $array['id_viaje'] = $id_viaje;
                                   $array['cat_concepto'] = 253;

                                   //Variables del sistema
                                   $costo_hora = Controlador::getConfig(1,'costo_hora');
                                   $costo_minuto = ($costo_hora['valor']/60);


                                   $minutos = ceil($segundos/60);
                                   $array['descripcion'] = $minutos.' minutos';
                                   $input = round($costo_minuto*$minutos, 2);
                                   $array['costo'] = '$ '.$input;

                                   self::addCostoAdicional($array);
                            }
                     }
              }

    }
    function getDateMultipleClave($viaje,$clave){
           $qry = "
                  SELECT
                  	stt.fecha_alta
                  FROM
                  	cr_state AS stt
                  INNER JOIN cm_catalogo AS cm1 ON cm1.etiqueta = stt.state
                  WHERE
                  	stt.id_viaje = $viaje
                  AND cm1.etiqueta = '".$clave."'
                  ORDER BY
                  	stt.id_state ASC
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
           $array = array();
           $num = 0;
           if($query->rowCount()>=1){
                  $data = $query->fetchAll();
                  $array['status'] = true;
                  foreach ($data as $row) {
                         $array[$num]['fecha_alta'] = $row->fecha_alta;
                         $num++;
                  }
           }else{
                  $array['status'] = false;
           }
           return $array;
    }
    function addCostTiempoEspera($id_viaje,$tiempo_espera){
           $array = array();
           $array['id_viaje'] = $id_viaje;
           $array['descripcion'] = $tiempo_espera;
           $array['cat_concepto'] = 252;

           //Variables del sistema
           $cortesia = Controlador::getConfig(1,'tiempo_cortesia');
           $costo_hora = Controlador::getConfig(1,'costo_hora');
           $costo_minuto = ($costo_hora['valor']/60);

           $segundos = strtotime($tiempo_espera) - strtotime($cortesia['valor']);
           if($segundos > 0){
                  $minutos = ceil($segundos/60);
                  $array['costo'] = '$ '.$costo_minuto*$minutos;
                  self::addCostoAdicional($array);
           }
    }
    public function updateStaticsCosts($costo,$adicional,$id_statics,$id_tarifa_cliente){
           $total = $costo + $adicional;
           $qry = "
                  UPDATE `vi_viaje_statics`
                  SET
                     `id_tarifa_cliente` = '".$id_tarifa_cliente."',
                     `costo_viaje` = '".$costo."',
                     `costos_adicionales` = '".$adicional."',
                     `costo_total` = '".$total."'

                  WHERE
                         (`id_viaje_statics` = $id_statics);
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
    }
    function getCostosAdicionales($id_viaje){
           $sql ="
                  SELECT
                  	Sum(
                  		ca.costo
                  	) AS total
                  FROM
                  	vi_costos_adicionales as ca
                  WHERE
                  	ca.id_viaje = $id_viaje
           ";

           $query = $this->db->prepare($sql);
           $query->execute();
           $array = array();
           if($query->rowCount()>=1){
                  foreach ($query->fetchAll() as $row) {
                         return  $row->total;
                  }
           }
    }
    function getCostViaje($id_viaje,$km){
           $sql ="
                  SELECT
                  	tc.costo_base,
                  	tc.km_adicional,
                  	tc.tabulado,
                  	c2.etiqueta AS tipo,
                     vi.id_tarifa_cliente,
	              tc.cat_tipo_tarifa
                  FROM
                  	vi_viaje AS vi
                  INNER JOIN cl_tarifas_clientes AS tc ON vi.id_tarifa_cliente = tc.id_tarifa_cliente
                  INNER JOIN cm_catalogo AS c2 ON tc.cat_tipo_tarifa = c2.id_cat
                  WHERE
                  	vi.id_viaje = $id_viaje
           ";

           $query = $this->db->prepare($sql);
           $query->execute();
           $array = array();
           if($query->rowCount()>=1){
                  foreach ($query->fetchAll() as $row) {
                         $array['costo_base'] = $row->costo_base;
                         $array['km_adicional'] = $row->km_adicional;
                         $array['tabulado'] = $row->tabulado;
                         $array['tipo'] = $row->tipo;
                         $array['cat_tipo_tarifa']=$row->cat_tipo_tarifa;
                         $array['id_tarifa_cliente'] = $row->id_tarifa_cliente;
                  }
           }

          if($array['tabulado'] == 1){
              $existe_c12 = self::existeEnViaje('C12',$id_viaje);
              $existe_t3 = self::existeEnViaje('T3',$id_viaje);

              if(($existe_c12 >= 1)OR($existe_t3 >= 1)){
                     $array['costo'] = $array['costo_base'];
                     //se setea el viaje para revision manual
                     $this->db->exec("UPDATE vi_viaje SET cat_status_viaje = '247' WHERE id_viaje = ".$id_viaje);

              }else{
                     $array['costo'] = $array['costo_base'];

              }

          }else{
                 //Variables del sistema
                 $km_cortesia = Controlador::getConfig(1,'km_cortesia');
                 $km_perimetro = Controlador::getConfig(1,'km_perimetro');

                 //$kmsc = km iniciales que los cubre el perimetro
                 //255 corresponde a un viaje de cortesía

                 $kmsc = ($array['cat_tipo_tarifa'] == 255)?$km_cortesia['valor']:$km_perimetro['valor'];

                 if($km <= $kmsc){
                        $array['costo'] = $array['costo_base'];

                 }elseif($km > $kmsc ){
                        $array['excedente'] = true;
                        $array['excedente_adicional'] = ceil($km - $kmsc);
                        $array['costo'] =  $array['costo_base'];
                 }
          }
          return $array;
    }
    function existeEnViaje($clave,$id_viaje){
           $sql="
                  SELECT
                  Count( cm1.etiqueta ) AS total
                  FROM
                  cr_state AS stt
                  INNER JOIN cm_catalogo AS cm1 ON cm1.etiqueta = stt.state
                  INNER JOIN cm_catalogo AS cm2 ON cm1.etiqueta = cm2.etiqueta
                  WHERE
                  stt.id_viaje = 1
                  AND cm1.catalogo = 'clavesitio'
                  AND cm2.catalogo = 'clavesitio'
                  AND cm1.etiqueta = '".$clave."'
                  ORDER BY
                  stt.id_state ASC
                  LIMIT 0,
                  100
                  ";
           $query = $this->db->prepare($sql);
           $query->execute();
           $historia = $query->fetchAll();
           $array = array();
           if($query->rowCount()>=1){
                  foreach ($historia as $row) {
                        return $row->total;
                  }
           }
    }
    public function insertStatic($viaje,$t1,$t2,$t3){
           foreach ($viaje as $key => $value) {
                  $this->$key = strip_tags($value);
           }
           $qry = "
                  INSERT INTO `vi_viaje_statics` (
                     `id_viaje`,
                     `cat_status_statics`,
                     `time_arribo`,
                     `time_espera`,
                     `time_viaje`,
                     `geo_origen`,
                     `geo_destino`,
                     `user_alta`,
                     `fecha_alta`
                  )
                  VALUES
                         (
                                   '".$this->id_viaje."',
                                   '222',
                                   '".$t1."',
                                   '".$t2."',
                                   '".$t3."',
                                   '".$this->georigen."',
                                   '".$this->geodestino."',
                                   '".$_SESSION['id_usuario']."',
                                   '".date("Y-m-d H:i:s")."'
                         );
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
           return $this->db->lastInsertId();
    }
    public function routesalternatives($origen,$destino,$id_statics,$id_viaje){
            $url='https://maps.googleapis.com/maps/api/directions/json?origin='.$origen.'&destination='.$destino.'&alternatives=true&key='.GOOGLE_DIRECTIONS;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $result = curl_exec($curl);
            curl_close($curl);
            $decode = json_decode($result);

            $date = date("Y-m-d H:i:s");
            $year = substr($date,0,4);
            $mes = substr($date,5,2);
            $dia = substr($date,8,2);

            $kms = array();
            $time = array();

           foreach($decode->routes as $num=>$val){
                  $kapa = $val->overview_polyline->points;
                  $result = file_get_contents('https://maps.googleapis.com/maps/api/staticmap?&size=650x350&scale=2&path=color:0x000000ff%7Cweight:2%7Cenc:'.$kapa.'&key='.GOOGLE_MAPS);
                  $imagen = Controller::token(6).".png";
                  chdir('../archivo');
                  $dir = $year."/".$mes."/".$dia."/".$id_viaje."/";
                  $alt['ruta_file'] = $dir.$imagen;

                  if(!file_exists($year)){mkdir($year, 0777);}
                  chdir($year);
                  if(!file_exists($mes)){mkdir($mes, 0777);}
                  chdir($mes);
                  if(!file_exists($dia)){mkdir($dia, 0777);}
                  chdir($dia);
                  if(!file_exists($id_viaje)){mkdir($id_viaje, 0777);}
                  chdir('../../../');

                  //if($num == 0){$image_main = $alt['ruta_file'];}
                  $image_main[$num] = $alt['ruta_file'];
                  $fp = fopen($alt['ruta_file'], 'w');
                  fputs($fp, $result);
                  fclose($fp);
                  foreach($val->legs as $elm){
                         $alt['km'] = ($elm->distance->value)/1000;
                         $alt['sec'] = ($elm->duration->value);
                  }
                  $alt['sumario'] = $val->summary;
                  self::insertAlternativa($alt,$id_statics);
                  $kms[$num] = $alt['km'];
                  $time[$num]= $alt['sec'];
                  if($num > 0){$img_main = ($kms[$num] > $kms[$num-1])?$image_main[$num]:$image_main[$num-1];}
           }
           if($num == 0){$img_main = $image_main[$num];}
           $upsSttcs['kmss_max'] = max($kms);
           $upsSttcs['kmss_min'] = min($kms);
           $upsSttcs['kmss_pro'] = self::avg($kms);
           $upsSttcs['time_max'] = max($time);
           $upsSttcs['time_min'] = min($time);
           $upsSttcs['time_pro'] = self::avg($time);
           self::updateStaticsVals($upsSttcs,$img_main,$id_statics);
           return $upsSttcs;
    }
    function avg($arrayData){
           $size = count($arrayData);
           $arrayFinal = array_sum($arrayData);
           return $arrayFinal / $size;
    }
    public function updateStaticsVals($arreglo,$image_main,$id_statics){
           foreach ($arreglo as $key => $value) {
                  $this->$key = strip_tags($value);
           }
           $qry = "
                  UPDATE `vi_viaje_statics`
                  SET
                     `mapa` = '".$image_main."',
                     `time_or_des_max` = SEC_TO_TIME(".$this->time_max."),
                     `time_or_des_min` = SEC_TO_TIME(".$this->time_min."),
                     `time_or_des_pro` = SEC_TO_TIME(".$this->time_pro."),
                     `km_max_maps` = '".$this->kmss_max."',
                     `km_min_maps` = '".$this->kmss_min."',
                     `km_pro_maps` = '".$this->kmss_pro."'
                  WHERE
                         (`id_viaje_statics` = $id_statics);
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
    }
    public function insertAlternativa($arreglo,$id_statics){
           foreach ($arreglo as $key => $value) {
                  $this->$key = strip_tags($value);
           }
           $qry = "
                  INSERT INTO `vi_viaje_alternativas` (
                         `id_viaje_statics`,
                         `ruta_file`,
                         `km`,
                         `minutos`,
                         `sumario`,
                         `user_alta`,
                         `fecha_alta`
                  )
                  VALUES
                         (
                                '".$id_statics."',
                                '".$this->ruta_file."',
                                '".$this->km."',
                                SEC_TO_TIME(".$this->sec."),
                                '".$this->sumario."',
                                '".$_SESSION['id_usuario']."',
                                '".date("Y-m-d H:i:s")."'
                         );
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
    }
    function getDateClave($viaje,$clave){
           $qry = "
                  SELECT
                  	stt.fecha_alta
                  FROM
                  	cr_state AS stt
                  INNER JOIN cm_catalogo AS cm1 ON cm1.etiqueta = stt.state
                  WHERE
                  	stt.id_viaje = $viaje
                  AND cm1.etiqueta = '".$clave."'
                  ORDER BY
                  	stt.id_state ASC
                  LIMIT 0,
                   1
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
           if($query->rowCount()>=1){
                  $data = $query->fetchAll();
                  foreach ($data as $row) {
                         return $row->fecha_alta;
                  }
           }else{
                  return false;
          }
    }

    function init_tds($viaje){
           $qry = "
                  SELECT
                  	stt.fecha_alta
                  FROM
                  	cr_state AS stt
                  WHERE
                  	stt.id_viaje = $viaje
                  ORDER BY
                  	stt.id_state ASC
                  LIMIT 0,
                   1
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
           if($query->rowCount()>=1){
                  $data = $query->fetchAll();
                  foreach ($data as $row) {
                         return $row->fecha_alta;
                  }
           }
    }
    function setClaveOk($id_viaje,$clave,ShareModel $share){
           $viaje = self::idensViaje($id_viaje);
           $tds = self::tipoServicio($viaje['id_operador_unidad']);

           $setStat['id_operador'] = $viaje['id_operador'];
           $setStat['id_operador_unidad'] = $viaje['id_operador_unidad'];
           $setStat['id_episodio'] = $viaje['id_episodio'];
           $setStat['id_viaje'] = $id_viaje;
           $setStat['num'] = $viaje['num'];
           $setStat['state'] = $clave;
           $setStat['flag1'] = 'C1';
           $setStat['flag2'] = $tds;
           $setStat['flag3'] = $clave;
           $setStat['flag4'] = 'NULL';
           $setStat['motivo'] = 'NULL';

           $share->setStatOper($setStat);

           switch($clave){
                  case 'C9':/*Servicio concluido*/
                     $this->db->exec("UPDATE vi_viaje SET cat_status_viaje = '172' WHERE id_viaje = ".$id_viaje);

                     $setStat['id_operador'] = $viaje['id_operador'];
                     $setStat['id_operador_unidad'] = $viaje['id_operador_unidad'];
                     $setStat['id_episodio'] = $viaje['id_episodio'];
                     $setStat['id_viaje'] = 'NULL';
                     $setStat['num'] = $viaje['num'];
                     $setStat['state'] = $clave;
                     $setStat['flag1'] = 'C1';
                     $setStat['flag2'] = $clave;
                     $setStat['flag3'] = 'F11';
                     $setStat['flag4'] = 'NULL';
                     $setStat['motivo'] = 'Viaje Concluido';

                     $share->setStatOper($setStat);
                     self::procesarViajeFinalizado($viaje);
                  break;
                  case 'A14':/*Abandono de servicio*/
                     $this->db->exec("UPDATE vi_viaje SET cat_status_viaje = '188' WHERE id_viaje = ".$id_viaje);

                     $setStat['id_operador'] = $viaje['id_operador'];
                     $setStat['id_operador_unidad'] = $viaje['id_operador_unidad'];
                     $setStat['id_episodio'] = $viaje['id_episodio'];
                     $setStat['id_viaje'] = 'NULL';
                     $setStat['num'] = $viaje['num'];
                     $setStat['state'] = 'C2';
                     $setStat['flag1'] = 'C2';
                     $setStat['flag2'] = 'NULL';
                     $setStat['flag3'] = 'NULL';
                     $setStat['flag4'] = 'NULL';
                     $setStat['motivo'] = 'Abandono';

                     $share->setStatOper($setStat);
                  break;
                  default:
                  break;
          }

    }
    function tipoServicio($id_operador_unidad){
    		$query = "
                     SELECT
                     	cr_state.flag2
                     FROM
                     	cr_state
                     WHERE
                     	cr_state.id_operador_unidad = $id_operador_unidad
                     AND (
                     	cr_state.flag2 = 'A10'
                     	OR cr_state.flag2 = 'F15'
                     	OR cr_state.flag2 = 'F13'
                     	OR cr_state.flag2 = 'T1'
                     	OR cr_state.flag2 = 'T2'
                     )
                     ORDER BY
                     	cr_state.id_state DESC
                     LIMIT 0,
                      1
    		";
    		$query = $this->db->prepare($query);
    		$query->execute();
    		$result = $query->fetchAll();
    		$output = '';
    		if($query->rowCount()>=1){
    			foreach ($result as $row) {
    				$output =  $row->flag2;
    			}
    		}
    		return $output;
    }
    function dataUpdateCosts($id_viaje){
           $qry = "
                  SELECT
                  	v.id_viaje,
                  	vs.id_viaje_statics,
                  	vs.km_max_maps,
                  	o.id_operador,
                  	i.id_ingreso
                  FROM
                  	vi_viaje AS v
                  INNER JOIN vi_viaje_statics AS vs ON vs.id_viaje = v.id_viaje
                  INNER JOIN cr_operador_unidad AS ou ON v.id_operador_unidad = ou.id_operador_unidad
                  INNER JOIN cr_operador AS o ON ou.id_operador = o.id_operador
                  INNER JOIN fo_ingresos AS i ON i.id_viaje = v.id_viaje
                  WHERE
                  	v.id_viaje = $id_viaje
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
           $viaje = array();
           if($query->rowCount()>=1){
                  $data = $query->fetchAll();
                  foreach ($data as $row){
                         $viaje['id_viaje'] 	= $row->id_viaje;
                         $viaje['id_viaje_statics'] = $row->id_viaje_statics;
                         $viaje['kms'] 	= $row->km_max_maps;
                         $viaje['id_operador']= $row->id_operador;
                         $viaje['id_ingreso']= $row->id_ingreso;
                  }
           }
           return $viaje;
    }
    function idensViaje($id_viaje){
           $qry = "
                  SELECT
                  	vi.id_operador_unidad,
                  	vi.id_episodio,
                  	vi.id_viaje,
                  	opu.id_operador,
                  	num.num,
                  	clo.id_cliente,
                  	cli.parent AS empresa,
                  	diro.geocoordenadas AS georigen,
                  	dird.geocoordenadas AS geodestino
                  FROM
                  	vi_viaje AS vi
                  INNER JOIN cr_operador_unidad AS opu ON vi.id_operador_unidad = opu.id_operador_unidad
                  INNER JOIN cr_operador_numeq AS opnum ON opu.id_operador = opnum.id_operador
                  INNER JOIN cr_numeq AS num ON opnum.id_numeq = num.id_numeq
                  INNER JOIN it_cliente_origen AS clo ON clo.id_cliente_origen = vi.id_cliente_origen
                  INNER JOIN cl_clientes AS cli ON clo.id_cliente = cli.id_cliente
                  INNER JOIN it_origenes AS ito ON clo.id_origen = ito.id_origen
                  INNER JOIN it_viaje_destino AS itvd ON itvd.id_viaje = vi.id_viaje
                  INNER JOIN it_cliente_destino AS itcd ON itvd.id_cliente_destino = itcd.id_cliente_destino
                  INNER JOIN it_destinos AS itd ON itcd.id_destino = itd.id_destino
                  INNER JOIN it_direcciones AS diro ON ito.id_direccion = diro.id_direccion
                  INNER JOIN it_direcciones AS dird ON itd.id_direccion = dird.id_direccion
                  WHERE
                  	vi.id_viaje = $id_viaje
           ";
           $query = $this->db->prepare($qry);
           $query->execute();
           $viaje = array();
           if($query->rowCount()>=1){
                  $data = $query->fetchAll();
                  foreach ($data as $row){
                         $viaje['id_operador_unidad'] 	= $row->id_operador_unidad;
                         $viaje['id_episodio'] 		= $row->id_episodio;
                         $viaje['id_viaje'] 	= $row->id_viaje;
                         $viaje['id_operador']= $row->id_operador;
                         $viaje['num']= $row->num;
                         $viaje['id_cliente']= $row->id_cliente;
                         $viaje['empresa']= $row->empresa;
                         $viaje['georigen']= $row->georigen;
                         $viaje['geodestino']= $row->geodestino;
                  }
           }
           return $viaje;
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
                            descripcion,
				user_alta,
				fecha_alta
			) VALUES (
				:id_viaje,
				:cat_concepto,
				:costo,
				:fecha,
                            :descripcion,
				:user_alta,
				:fecha_alta
			)";
              $costo = str_replace(',','',$this->costo);
              $sign = substr($costo, 0,1);
              $input = ($sign == '$')?substr($costo, 2):(substr($costo, 3)*-1);
		$query = $this->db->prepare($sql);
              $vals = array(
			':id_viaje' =>  $this->id_viaje ,
			':cat_concepto' =>  $this->cat_concepto ,
			':costo' =>  $input ,
			':fecha' =>  date("Y-m-d H:i:s") ,
                     ':descripcion' => $this->descripcion,
			':user_alta' =>  $_SESSION['id_usuario'] ,
			':fecha_alta' => date("Y-m-d H:i:s")
		);
              $query_resp = $query->execute($vals);
		if($query_resp){
			$respuesta = array('resp' => true);
		}else{
			$respuesta = array('resp' => false);
		}
		return $respuesta;
	}
       function addCostoAdicionalPost($arreglo,$vars){
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
              $costo = str_replace(',','',$this->costo);
              $sign = substr($costo, 0,1);
              $input = ($sign == '$')?substr($costo, 2):(substr($costo, 3)*-1);
		$query = $this->db->prepare($sql);
		$query_resp = $query->execute(
			array(
				':id_viaje' =>  $this->id_viaje ,
				':cat_concepto' =>  $this->cat_concepto ,
				':costo' =>  $input ,
				':fecha' =>  date("Y-m-d H:i:s") ,
				':user_alta' =>  $_SESSION['id_usuario'] ,
				':user_mod' => $_SESSION['id_usuario'] ,
				':fecha_alta' => date("Y-m-d H:i:s")
			)
		);
		if($query_resp){
                     self::updateCostosAdicionales($vars);
			$respuesta = array('resp' => true);
		}else{
			$respuesta = array('resp' => false);
		}
		return $respuesta;
	}
       function addIncidencia($arreglo){
              foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		$sql = "
			INSERT INTO vi_viaje_incidencia (
				id_viaje,
				cat_incidencias,
				user_alta,
				fecha_alta
			) VALUES (
				:id_viaje,
				:cat_incidencias,
				:user_alta,
				:fecha_alta
			)";
		$query = $this->db->prepare($sql);
		$query_resp = $query->execute(
			array(
				':id_viaje' =>  $this->id_viaje ,
				':cat_incidencias' =>  $this->cat_incidencias ,
				':user_alta' =>  $_SESSION['id_usuario'] ,
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
       function eliminar_incidencia($id_viaje_incidencia){
		$qry = "
			DELETE
			FROM
			vi_viaje_incidencia
			WHERE
			id_viaje_incidencia = '".$id_viaje_incidencia."'
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
       function eliminar_costoAdicionalPost($id_costos_adicionales,$vars){
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
                     self::updateCostosAdicionales($vars);
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
       function cambiar_tarifa_do_post($id_tarifa_cliente,$id_viaje,$vars){
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
                     self::updateCostosAdicionales($vars);
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

	function asignar_apartado($id_viaje,$id_operador_unidad){
		self::relacionar_operador_apartado($id_viaje,$id_operador_unidad);
		self::set_fecha_asignacion($id_viaje);
	}
	function relacionar_operador_apartado($id_viaje,$id_operador_unidad){

		$sql = "
			UPDATE vi_viaje
			SET
			 id_operador_unidad = '".$id_operador_unidad."',
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
	function setear_status_viaje($post, ShareModel $share=NULL){

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
							self::setF6($id_operador);
                                                 $share->setstatlocal($id_operador,$id_operador_unidad,$id_episodio,'F6','F6','NULL','NULL','NULL','VIAJE EN PROCESO',$post['id_viaje']);
						break;
						case 'omitir':
                                                 $share->setstatlocal($id_operador,$id_operador_unidad,$id_episodio,'C19','C1','C19','F11','NULL','CLIENTE REASIGNADO',$post['id_viaje']);
						break;
					}
				break;
                            case '172':
                                   if($post['origen'] == 'cancelados'){
                                          $viaje = self::idensViaje($post['id_viaje']);
                                          self::procesarViajeFinalizado($viaje);
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
       function setCortesia($id_tarifa_cliente,$id_viaje){
		$sql = "
			UPDATE vi_viaje
			SET
			 id_tarifa_cliente = '".$id_tarifa_cliente."'
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
				viv.cat_status_viaje = 170
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
       function setF6($id_operador){
              $this->db->exec("UPDATE cr_operador SET cat_statusoperador = '10' WHERE id_operador = ".$id_operador);
              $this->db->exec("UPDATE cr_operador_unidad SET status_operador_unidad = '199' WHERE id_operador = ".$id_operador);
       }
       function unSetF6($id_operador){
              $this->db->exec("UPDATE cr_operador SET cat_statusoperador = '8' WHERE id_operador = ".$id_operador);
              $this->db->exec("UPDATE cr_operador_unidad SET status_operador_unidad = '198' WHERE id_operador = ".$id_operador);
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
		}else{
                     $output = false;
              }
		return $output;
	}
       function getTarifaCortesia($id_cliente){
		$query = "
                     SELECT
                            tfcl.id_tarifa_cliente
                     FROM
                            cl_tarifas_clientes AS tfcl
                     INNER JOIN cl_clientes AS clc ON tfcl.id_cliente = clc.parent
                     WHERE
                            clc.id_cliente = $id_cliente
                     AND tfcl.cat_statustarifa = 168
                     AND tfcl.cat_tipo_tarifa = 255
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
		}else{
                     $output = false;
              }
		return $output;
	}
       function idClienteViaje($id_viaje){
              $qry = "
                     SELECT
                     	cd.id_cliente
                     FROM
                     	it_cliente_destino AS cd
                     INNER JOIN it_viaje_destino AS vd ON vd.id_cliente_destino = cd.id_cliente_destino
                     INNER JOIN vi_viaje AS vi ON vd.id_viaje = vi.id_viaje
                     WHERE
                     	vi.id_viaje = $id_viaje
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row){
			       return $row->id_cliente;
			}
		}
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
       function update_viajeDestino($service){
              $sql = "
			UPDATE it_viaje_destino
			SET
			 id_cliente_destino 	= '".$service->id_cliente_destino."'
			WHERE
				id_viaje = ".$service->id_viaje."
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
				$output .=  $row->id_viaje.self::getCurrentCveState($row->id_operador_unidad,$row->id_viaje);
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
       function getCurrentCveState($id_operador_unidad,$id_viaje){
		$qry = "
                     SELECT
                     	cr_state.state
                     FROM
                     	cr_state
                     WHERE
                     	cr_state.id_operador_unidad = $id_operador_unidad
                     AND cr_state.id_viaje = $id_viaje
                     ORDER BY
                     	cr_state.id_state DESC
                     LIMIT 0,
                      1
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row){
			       return $row->state;
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
			INNER JOIN fw_usuarios AS usr ON usr.id_usuario = vca.user_alta
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
       function queryIncidencias($array,$id_viaje){
              ini_set('memory_limit', '256M');
              $table = 'vi_viaje_incidencia AS vin';
              $primaryKey = 'id_viaje_incidencia';
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
                            'db' => 'usr.usuario as usuario',
                            'dbj' => 'usr.usuario',
                            'real' => 'usr.usuario',
                            'alias' => 'usuario',
                            'typ' => 'txt',
                            'dt' => 1
                     ),
                     array(
                            'db' => 'vin.fecha_alta as fecha',
                            'dbj' => 'vin.fecha_alta',
                            'real' => 'vin.fecha_alta',
                            'alias' => 'fecha',
                            'typ' => 'int',
                            'dt' => 2
                     ),
                     array(
                            'db' => 'vin.id_viaje_incidencia as id_viaje_incidencia',
                            'dbj' => 'vin.id_viaje_incidencia',
                            'real' => 'vin.id_viaje_incidencia',
                            'alias' => 'id_viaje_incidencia',
                            'typ' => 'int',
                            'acciones' => true,
                            'id_viaje' => $id_viaje,
                            'dt' => 3
                     )
              );
              $inner = '
                     INNER JOIN cm_catalogo AS cat ON vin.cat_incidencias = cat.id_cat
                     INNER JOIN fw_usuarios AS usr ON usr.id_usuario = vin.user_alta
              ';
              $where = '
                     vin.id_viaje = '.$id_viaje.'
              ';
              $orden = '
                     GROUP BY
                            vin.id_viaje_incidencia ASC
              ';
              $render_table = new acciones_incidencias;
              return json_encode(
                     $render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
              );
       }
       function queryCostosAdicionales_post($array,$id_viaje){
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
				'viaje' => $id_viaje,
				'dt' => 4
			)
		);
		$inner = '
			INNER JOIN cm_catalogo AS cat ON vca.cat_concepto = cat.id_cat
			INNER JOIN fw_usuarios AS usr ON usr.id_usuario = vca.user_alta
		';
		$where = '
			vca.id_viaje = '.$id_viaje.'
		';
		$orden = '
			GROUP BY
				vca.id_costos_adicionales ASC
		';
		$render_table = new acciones_costosAdicionalesPost;
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
	}
       function queryCostosAdicionalesShow($array,$id_viaje){
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
				'viaje' => $id_viaje,
				'dt' => 4
			)
		);
		$inner = '
			INNER JOIN cm_catalogo AS cat ON vca.cat_concepto = cat.id_cat
			INNER JOIN fw_usuarios AS usr ON usr.id_usuario = vca.user_alta
		';
		$where = '
			vca.id_viaje = '.$id_viaje.'
		';
		$orden = '
			GROUP BY
				vca.id_costos_adicionales ASC
		';
		$render_table = new SSP;
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner, null, $orden )
		);
	}
}

class acciones_costosAdicionalesPost extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;

				if ( isset( $column['acciones'] ) ) {
					$id_viaje = $column['viaje'];
					$id_costos_adicionales = ($data[$i][ $column['alias'] ]);

					$salida = '';
					$salida .= '<a onclick="eliminar_costoAdicionalPost('.$id_costos_adicionales.','.$id_viaje.')" data-rel="tooltip" data-original-title="Eliminar costo"><i class="fa fa-trash" style="font-size:1.4em; color:#c40b0b;"></i></a>&nbsp;&nbsp;';

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
class acciones_incidencias extends SSP{
       static function data_output ( $columns, $data, $db )
       {
              $out = array();
              for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
                     $row = array();

                     for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
                            $column = $columns[$j];
                            $name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;

                            if ( isset( $column['acciones'] ) ) {
                                   $id_viaje_incidencia = ($data[$i][ $column['alias'] ]);

                                   $salida = '';
                                   $salida .= '<a onclick="eliminar_incidencia('.$id_viaje_incidencia.')" data-rel="tooltip" data-original-title="Eliminar incidencia"><i class="fa fa-trash" style="font-size:1.4em; color:#c40b0b;"></i></a>&nbsp;&nbsp;';

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

                                   if(Controlador::tiene_permiso('Operacion|costos_adicionales')){
					       $salida .= '<a href="javascript:;" onclick="costos_adicionales('.$id_viaje.')" data-rel="tooltip" data-original-title="Costos adicionales"><i class="icofont icofont-money-bag" style="font-size:1.4em; color:#008c23;"></i></a>&nbsp;&nbsp;';
                                   }
                                   $salida .= '<a href="javascript:;" onclick="nueva_incidencia('.$id_viaje.')" data-rel="tooltip" data-original-title="Incidencia"><i class="fa fa-exclamation-triangle" style="font-size:1.4em; color:#c39800;"></i></a>&nbsp;&nbsp;';

					$salida .= '<a href="javascript:;" onclick="cambiar_tarifa('.$id_viaje.')" data-rel="tooltip" data-original-title="Cambiar tarifa"><i class="icofont icofont-exchange" style="font-size:1.4em; color:#008c23;"></i></a>&nbsp;&nbsp;';

					$salida .= '<a onclick="dataViaje('.$id_viaje.')" href="javascript:;" data-rel="tooltip" data-original-title="Datos del viaje"><i class="fa fa-question-circle" style="font-size:1.4em; color:#0080ff;"></i></a>&nbsp;&nbsp;';

                                   $salida .= '<a onclick="selectClave('.$id_viaje.')" href="javascript:;" data-rel="tooltip" data-original-title="Establecer Clave"><i class="fa fa-sliders" style="font-size:1.4em; color:#b16500;"></i></a>&nbsp;&nbsp;';






                                   $cveStat = self::getCurrentCveOperador($id_operador_unidad,$db);

					switch ($cveStat['clave']){
						case 'A10':	$color = '#9DBF00';	break;
						case 'F15':	$color = '#697F00';	break;
						case 'F13':	$color = '#001A40';	break;
						case 'T1':	$color = '#344000';	break;
						case 'T2':	$color = '#1a6600';	break;
						case 'A11':	$color = '#BF9A16';	break;
						case 'A14':	$color = '#403307';	break;
						case 'C8':	$color = '#E5B81A';	break;
						case 'A2':	$color = '#BF3000';	break;
						case 'C9':	$color = '#7F2000';	break;
						case 'C14':	$color = '#401000';	break;
						case 'C10':	$color = '#E53A00';	break;
						case 'C11':	$color = '#004EBF';	break;
						case 'C12':	$color = '#00347F';	break;
						default:	$color = '#000000';	break;
					}

					$salida .= '<a href="javascript:;" class="circle_num" data-rel="tooltip"  style="background:'.$color.';" data-original-title="'.$cveStat['clave'].' - '.$cveStat['valor'].'">'.$cveStat['clave'].'</a>&nbsp;&nbsp;';



                                   $salida .= "
                                          <a onclick='historia_viaje(".$id_viaje.")' data-rel='tooltip' data-original-title='Historia'>
                                                 <i class='fa fa-clock-o' style='font-size:1.8em; color:green;'></i>
                                          </a>
                                   ";


                                   $salida .= "
                                          <a onclick='modificar_destino(".$id_viaje.")' data-rel='tooltip' data-original-title='Modificar destino'>
                                                 <i class='fa fa-map-o' style='font-size:1.4em; color:green; position:relative; top:-5px;'><i class='fa-location-arrow fa_asub red'></i></i>
                                          </a>
                                   ";



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
       static function getCurrentCveOperador($id_operador_unidad,$db){
		$qry = "
                     SELECT
                     	cr_state.state,
                     	cm_catalogo.valor
                     FROM
                     	cr_state
                     INNER JOIN cm_catalogo ON cr_state.state = cm_catalogo.etiqueta
                     WHERE
                     	cr_state.id_operador_unidad = $id_operador_unidad
                     AND (
                     	cr_state.flag2 = 'A10'
                     	OR cr_state.flag2 = 'F15'
                     	OR cr_state.flag2 = 'F13'
                     	OR cr_state.flag2 = 'T1'
                     	OR cr_state.flag2 = 'T2'
                     )
                     AND cm_catalogo.catalogo = 'clavesitio'
                     ORDER BY
                     	cr_state.id_state DESC
                     LIMIT 0,
                      1
		";
		$query = $db->prepare($qry);
		$query->execute();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($query->fetchAll() as $row){
				$array['clave']	=	$row['state'];
				$array['valor']	=	$row['valor'];
			}
		}
		return $array;
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

					$salida .= '<a href="javascript:;" onclick="costos_adicionales_post('.$id_viaje.')" data-rel="tooltip" data-original-title="Costos adicionales"><i class="icofont icofont-money-bag" style="font-size:1.4em; color:#008c23;"></i></a>&nbsp;&nbsp;';

					$salida .= '<a href="javascript:;" onclick="cambiar_tarifa_post('.$id_viaje.')" data-rel="tooltip" data-original-title="Cambiar tarifa"><i class="icofont icofont-exchange" style="font-size:1.4em; color:#008c23;"></i></a>&nbsp;&nbsp;';

					$salida .= '<a onclick="dataViaje('.$id_viaje.')" href="javascript:;" data-rel="tooltip" data-original-title="Datos del viaje"><i class="fa fa-question-circle" style="font-size:1.4em; color:#0080ff;"></i></a>&nbsp;&nbsp;';

                                   $salida .= "
                                          <a onclick='historia_viaje(".$id_viaje.")' data-rel='tooltip' data-original-title='Historia'>
                                                 <i class='fa fa-clock-o' style='font-size:1.8em; color:green;'></i>
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

                                   $salida .= "<a onclick='historia_viaje(".$id_viaje.")' data-rel='tooltip' data-original-title='Historia'><i class='fa fa-clock-o' style='font-size:1.4em; color:green;'></i></a>";

                                   $salida .= "&nbsp;&nbsp;<a onclick='set_status_viaje(".$id_viaje.",172,\"cancelados\")' data-rel='tooltip' data-original-title='Activar para cobro'><i class='fa fa-recycle' style='font-size:1.4em; color:green;'></i></a>";

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

                                   $salida .= "
                                          <a onclick='historia_operador(".$id_operador.")' data-rel='tooltip' data-original-title='Historia'>
                                                 <i class='fa fa-clock-o' style='font-size:1.8em; color:green;'></i>
                                          </a>
                                   ";


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

                                          $salida .= "
                                                 <a onclick='historia_operador(".$id_operador.")' data-rel='tooltip' data-original-title='Historia'>
                                                        <i class='fa fa-clock-o' style='font-size:1.8em; color:green;'></i>
                                                 </a>
                                          ";

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
