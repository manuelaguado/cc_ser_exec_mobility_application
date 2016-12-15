<?php
require_once( '../vendor/mysql_datatable.php' );
class BasesModel
{
	function getBaseData($id_base){
		$sql="SELECT * FROM cr_bases WHERE id_base = ".$id_base;
		$query = $this->db->prepare($sql);
		$query->execute();
		$base = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($base as $row) {
					$array['cat_tipobase'] = $row->cat_tipobase;
					$array['descripcion'] = $row->descripcion;
					$array['ubicacion'] = $row->ubicacion;
					$array['clave'] = $row->clave;
					$array['latitud'] = $row->latitud;
					$array['longitud'] = $row->longitud;
					$array['geocerca'] = $row->geocerca;
			}
		}
		return $array;	
	}
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
        }
    }
	function asignarBase($operador_unidad,$id_base,$estado){
		if($estado == 'true'){
			$sql = "
				INSERT INTO cr_bases_operador_unidad (
					id_base,
					id_operador_unidad,
					user_alta,
					fecha_alta
				) VALUES (
					:id_base,
					:id_operador_unidad,
					:user_alta,
					:fecha_alta
				)";
			$query = $this->db->prepare($sql);
			$query_resp = $query->execute(
				array(
					':id_base' => $id_base,
					':id_operador_unidad' => $operador_unidad,
					':user_alta' => $_SESSION['id_usuario'],
					':fecha_alta' => date("Y-m-d H:i:s")
				)
			);
		}else if ($estado == 'false'){
			$clean = "DELETE FROM cr_bases_operador_unidad WHERE id_operador_unidad = :id_operador_unidad and id_base = :id_base";
			$query = $this->db->prepare($clean);
			$query_resp = $query->execute(array(':id_operador_unidad' => $operador_unidad, ':id_base' => $id_base));
		}
		if($query_resp){
			$respuesta = array('resp' => true, 'id_operador_unidad' => $this->db->lastInsertId(), 'estado' => $estado);
		}else{
			$respuesta = array('resp' => false);
		}
		return $respuesta;
	}
	function user_movil($operador_unidad){	
		$operador_unidad = intval($operador_unidad);
		$sql="
			SELECT
				fu.nombres,
				fu.apellido_paterno,
				fu.apellido_materno,
				cr_modelos.modelo,
				cr_marcas.marca,
				cu.color
			FROM
				cr_operador_unidad AS cou
			INNER JOIN cr_operador AS co ON cou.id_operador = co.id_operador
			INNER JOIN fw_usuarios AS fu ON co.id_usuario = fu.id_usuario
			INNER JOIN cr_unidades AS cu ON cou.id_unidad = cu.id_unidad
			INNER JOIN cr_modelos ON cu.id_modelo = cr_modelos.id_modelo
			INNER JOIN cr_marcas ON cu.id_marca = cr_marcas.id_marca
			WHERE
				cou.id_operador_unidad = ".$operador_unidad."		
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$unidad = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($unidad as $row) {
					$array['nombres'] = $row->nombres;
					$array['apellido_paterno'] = $row->apellido_paterno;
					$array['apellido_materno'] = $row->apellido_materno;
					$array['modelo'] = $row->modelo;
					$array['marca'] = $row->marca;
					$array['color'] = $row->color;
			}
		}
		return $array;	
	}
	function listarBases(){
		$sql="
			SELECT
				id_base,
				descripcion
			FROM
				cr_bases	
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$array =  $query->fetchAll();
		if($query->rowCount()>=1){
			return $array;
		}
	}
	function getPermisos($operador_unidad,$id_base){
		$operador_unidad = intval($operador_unidad);
		$id_base = intval($id_base);
		$sql="SELECT count(*) as permiso, id_base_operador_unidad FROM cr_bases_operador_unidad where id_operador_unidad = '".$operador_unidad."' and id_base = ".$id_base."";
		$query = $this->db->prepare($sql);
		$query->execute();
		$unidad = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($unidad as $row) {
					$array['permiso'] = $row->permiso;
					$array['id_operador_unidad'] 	=  $operador_unidad;
					$array['id_base_operador_unidad'] 	=  $row->id_base_operador_unidad;
			}
		}
		return $array;
	}
	function editaBase($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		$sql = "
			UPDATE `cr_bases`
			SET 
			 `cat_tipobase` = '".$this->cat_tipobase."',
			 `descripcion` = '".$this->descripcion."',
			 `ubicacion` = '".$this->ubicacion."',
			 `clave` = '".$this->clave."',
			 `latitud` = '".$this->latitud."',
			 `longitud` = '".$this->longitud."',
			 `geocerca` = '".$this->geocerca."',
			 `user_mod` = ".$_SESSION['id_usuario'].",
			 `fecha_mod` = NOW()
			WHERE
				(`id_base` = '".$this->id_base."');
		";
		$query = $this->db->prepare($sql);
		$result = $query->execute();
		return $result? array('resp' => true):array('resp' => false);
	}
	function insertBase($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		$sql = "INSERT INTO cr_bases (
					cat_tipobase, 
					descripcion, 
					ubicacion,
					clave,
					latitud, 
					longitud,
					geocerca,
					user_alta, 
					fecha_alta
				) VALUES (
					:cat_tipobase, 
					:descripcion,
					:latitud, 
					:longitud,
					:geocerca,
					:ubicacion, 
					:user_alta, 
					:fecha_alta
				)";
		$query = $this->db->prepare($sql);
		$result = $query->execute(array(':cat_tipobase' => $this->cat_tipobase, ':descripcion' => $this->descripcion, ':ubicacion' => $this->ubicacion, ':clave' => $this->clave, ':latitud' => $this->latitud, ':longitud' => $this->longitud,  ':geocerca' => $this->geocerca, ':user_alta' => $_SESSION['id_usuario'], ':fecha_alta' => date("Y-m-d H:i:s")));
		return $result? array('resp' => true):array('resp' => false);
	}
	function obtenerBases($array){
		ini_set('memory_limit', '256M');				
		$table = 'cr_bases as bas';
		$primaryKey = 'id_base';
		$columns = array(
			array( 
				'db' => 'id_base',
				'dbj' => 'id_base',
				'real' => 'id_base',
				'typ' => 'int',
				'dt' => 0
			),
			array( 
				'db' => 'descripcion AS descripcion',
				'dbj' => 'descripcion',	
				'alias' => 'descripcion',
				'real' => 'descripcion',
				'typ' => 'txt',
				'dt' => 1
			),
			array( 
				'db' => 'ubicacion AS ubicacion',
				'dbj' => 'ubicacion',				
				'real' => 'ubicacion',
				'alias' => 'ubicacion',
				'typ' => 'txt',
				'dt' => 2
			),
			array( 
				'db' => 'latitud AS latitud',
				'dbj' => 'latitud',				
				'real' => 'latitud',
				'alias' => 'latitud',
				'typ' => 'txt',
				'dt' => 3
			),
			array( 
				'db' => 'longitud AS longitud',
				'dbj' => 'longitud',				
				'real' => 'longitud',
				'alias' => 'longitud',
				'typ' => 'txt',
				'dt' => 4
			),
			array( 
				'db' => 'cat.etiqueta AS etiqueta',
				'dbj' => 'cat.etiqueta',
				'real' => 'cat.etiqueta',
				'alias' => 'etiqueta',
				'typ' => 'txt',
				'dt' => 5				
			)
		);
		$render_table = new SSP;
		$inner = '
			INNER JOIN cm_catalogo AS cat ON bas.cat_tipobase = cat.id_cat
		';
		$where = '';
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
}
