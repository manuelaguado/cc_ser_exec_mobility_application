<?php
class init extends Controlador
{
       public function index()
       {
   		exit('CentralCar Authorization required Level Developer');
       }
       public function do($auth){
              if($auth != RESTART_KEY){exit('CentralCar Authorization Invalid!');}
              $db = Controlador::direct_connectivity();
              self::truncate_init();
              $sql="
              SELECT
              op.id_operador,
              opu.id_operador_unidad,
              num.num,
              op.cat_statusoperador
              FROM
              cr_operador AS op
              INNER JOIN cr_operador_unidad AS opu ON opu.id_operador = op.id_operador
              INNER JOIN cr_operador_celular AS opcel ON opcel.id_operador = op.id_operador
              INNER JOIN cr_operador_numeq ON cr_operador_numeq.id_operador = op.id_operador
              INNER JOIN cr_numeq AS num ON cr_operador_numeq.id_numeq = num.id_numeq
              GROUP BY
              opu.id_operador
              ORDER BY
              op.id_operador ASC

              ";
              $stmt = $db->prepare($sql);
              $stmt->execute();
              $data = $stmt->fetchAll();

              foreach ($data as $row) {
                     if($row->cat_statusoperador == 10){
                            $db->exec("UPDATE cr_operador SET cat_statusoperador = '10' WHERE id_operador = ".$row->id_operador);
                            $db->exec("UPDATE cr_operador_unidad SET status_operador_unidad = '199' WHERE id_operador = ".$row->id_operador);
                            $clave = 'F6';
                     }else{
                            $clave = 'C2';
                     }
                     $sql = "
                            INSERT INTO `cr_state` (
                                   `id_operador`,
                                   `id_operador_unidad`,
                                   `numeq`,
                                   `state`,
                                   `flag1`,
                                   `activo`
                            )
                            VALUES
                                   (
                                          '".$row->id_operador."',
                                          '".$row->id_operador_unidad."',
                                          '".$row->num."',
                                          '".$clave."',
                                          '".$clave."',
                                          '1'
                                   );
                     ";
                     $populate = $db->prepare($sql);
                     $populate->execute();
              }
              chdir('../archivo');
              self::delTree('2017');
              self::delContent('papeletas');
              echo 'Se reinició el sistema <a href="'.URL_APP.'" target="_self">Volver</a>';
       }
       function delTree($dir) {
              if(file_exists($dir)){
                     $files = array_diff(scandir($dir), array('.','..'));
                     foreach ($files as $file) {
                     (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
                     }
                     return rmdir($dir);
              }
       }
       function delContent($dir) {
              if(file_exists($dir)){
                     $files = array_diff(scandir($dir), array('.','..'));
                     foreach ($files as $file) {
                            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
                     }
                     $fp = fopen($dir.'/.gitkeep', 'w');
                     fwrite($fp, '');
                     fclose($fp);
              }
       }
       public function truncate_init(){
		$db = Controlador::direct_connectivity();
		$sql="
		SET FOREIGN_KEY_CHECKS=0;
		TRUNCATE vi_viaje;
		TRUNCATE vi_costos_adicionales;
		TRUNCATE vi_viaje_clientes;
		TRUNCATE vi_viaje_detalle;
		TRUNCATE vi_viaje_formapago;
		TRUNCATE vi_viaje_incidencia;
		TRUNCATE cr_episodios;
		TRUNCATE cr_cordon;
		TRUNCATE cr_state;
		TRUNCATE cr_apartados;
		TRUNCATE it_direcciones;
		TRUNCATE it_origenes;
		TRUNCATE it_destinos;
		TRUNCATE it_cliente_destino;
		TRUNCATE it_cliente_origen;
		TRUNCATE it_viaje_destino;
		TRUNCATE vi_viaje_alternativas;
		TRUNCATE vi_viaje_statics;
		TRUNCATE fo_conceptos;
		TRUNCATE fo_operador_conceptos;
		TRUNCATE fo_concepto_adeudo;
		TRUNCATE fo_conceptos_aplicaciones;
		TRUNCATE fo_pagos_conceptos;
		TRUNCATE fo_movimientos;
		TRUNCATE fo_ingresos;
		TRUNCATE fo_cobros_ingresos;
		TRUNCATE fo_comisiones;
		TRUNCATE fo_regla_comision;
		TRUNCATE fo_papeletas;
		TRUNCATE fo_papeletas_viajes;
		SET FOREIGN_KEY_CHECKS=1;
		";
		$stmt = $db->prepare($sql);
		$stmt->execute();
	}
}
?>
