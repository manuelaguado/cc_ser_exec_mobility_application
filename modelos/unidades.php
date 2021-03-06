<?php
require_once( '../vendor/mysql_datatable.php' );
class UnidadesModel
{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
        }
    }
	function getPermisos($id_operador,$id_unidad){
		$id_operador = intval($id_operador);
		$id_unidad = intval($id_unidad);
		$sql="SELECT count(*) as permiso, id_operador_unidad FROM cr_operador_unidad where id_operador = '".$id_operador."' and id_unidad = ".$id_unidad." AND cr_operador_unidad.status_operador_unidad = 198";
		$query = $this->db->prepare($sql);
		$query->execute();
		$unidad = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($unidad as $row) {
					$array['permiso'] = $row->permiso;
					$array['id_operador_unidad'] 	=  $row->id_operador_unidad;
			}
		}
		return $array;
	}
	function existIdOperadorUnidad($id_operador,$id_unidad){
		$qry = "SELECT id_operador_unidad FROM cr_operador_unidad WHERE id_operador = ".$id_operador." and id_unidad = ".$id_unidad.";";
		$query = $this->db->prepare($qry);
		$query->execute();
		$array = array();
		if($query->rowCount()==1){
			$par = $query->fetchAll();
			foreach ($par as $row) {
				$array['id_operador_unidad']=$row->id_operador_unidad;
				$array['exist']=true;
			}
		}else{
			$array['exist']=false;
		}
		return $array;
	}
	function asignarAutomovil( $id_operador,$id_unidad,$estado){
		if(Controlador::tiene_permiso('Bases|asignar_bases')){$permiso = true;}else{$permiso = false;}
		$par = self::existIdOperadorUnidad($id_operador,$id_unidad);
		$array = array();
		if($estado == 'true'){
			if($par['exist']){
				self::updateOperadorUnidad($par['id_operador_unidad'], 198);				
				$respuesta = array('resp' => true, 'id_operador_unidad' => $par['id_operador_unidad'], 'estado' => $estado, 'permiso' => $permiso);
			}else{
				self::insertOperadorUnidad($id_operador,$id_unidad);
				$respuesta = array('resp' => true, 'id_operador_unidad' => $this->db->lastInsertId(), 'estado' => $estado, 'permiso' => $permiso);
			}
		}else if ($estado == 'false'){
			if($par['exist']){
				self::deleteBaseOperadorUnidad($par['id_operador_unidad']);
				self::updateOperadorUnidad($par['id_operador_unidad'], 199);
				$respuesta = array('resp' => true, 'id_operador_unidad' => $par['id_operador_unidad'], 'estado' => $estado, 'permiso' => $permiso);
			}else{
				$respuesta = array('resp' => false, 'id_operador_unidad' => 'Inexistente', 'estado' => $estado, 'permiso' => false);
			}
		}
		return $respuesta;
	}
	function insertOperadorUnidad($id_operador,$id_unidad){
		$sql = "
			INSERT INTO cr_operador_unidad (
				id_operador,
				id_unidad,
				status_operador_unidad,
				user_alta,
				fecha_alta
			) VALUES (
				:id_operador,
				:id_unidad,
				:status_operador_unidad,
				:user_alta,
				:fecha_alta
			)";
		$query = $this->db->prepare($sql);
		$ret = $query_resp = $query->execute(
			array(
				':id_operador' => $id_operador,
				':id_unidad' => $id_unidad,
				':status_operador_unidad' => 198,
				':user_alta' => $_SESSION['id_usuario'],
				':fecha_alta' => date("Y-m-d H:i:s")
			)
		);
		return $ret;		
	}
	function updateOperadorUnidad($id_operador_unidad, $stat){
		$sql = "UPDATE cr_operador_unidad SET status_operador_unidad = '".$stat."' WHERE id_operador_unidad = '".$id_operador_unidad."'";
		$query = $this->db->prepare($sql);
		$query->execute();	
	}
	function deleteBaseOperadorUnidad($id_operador_unidad){
        $sql = "DELETE FROM cr_bases_operador_unidad WHERE id_operador_unidad = ".$id_operador_unidad."";
        $query = $this->db->prepare($sql);
		$query->execute();
	}
	function listarUnidades(){
		$sql="
			SELECT
				cr_unidades.id_unidad,
				cr_unidades.`year`,
				cr_unidades.placas,
				cr_unidades.motor,
				cr_unidades.color,
				cr_unidades.cat_status_unidad,
				cr_modelos.modelo,
				cr_marcas.marca
			FROM
				cr_unidades
			INNER JOIN cr_modelos ON cr_unidades.id_modelo = cr_modelos.id_modelo
			INNER JOIN cr_marcas ON cr_unidades.id_marca = cr_marcas.id_marca
			WHERE
				cr_unidades.cat_status_unidad = 14		
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$array =  $query->fetchAll();
		if($query->rowCount()>=1){
			return $array;
		}
	}
	function selectMarca($id_marca){
		$array = array();
		$qry = "SELECT * FROM cr_marcas order by marca asc;";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$areas = $query->fetchAll();
			$cont = 0;
			foreach ($areas as $row) {
				$array[$cont]['value']=$row->id_marca;
				$array[$cont]['valor']=$row->marca;
				$cont++;			
			}
		}		
		return Controller::setOption($array,$id_marca);
	}
	function selectModelo($id_marca,$id_modelo){
		$array = array();
		$qry = "SELECT * FROM cr_modelos where id_marca = ".$id_marca." order by modelo asc;";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$areas = $query->fetchAll();
			$cont = 0;
			foreach ($areas as $row) {
				$array[$cont]['value']=$row->id_modelo;
				$array[$cont]['valor']=$row->modelo;
				$cont++;			
			}
		}		
		return Controller::setOption($array,$id_modelo);
	}

	function edita_unidad_do($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		$sql = "
		UPDATE cr_unidades
		SET 
				id_marca 	= 	'".$this->id_marca."',
				id_modelo 	= 	'".$this->id_modelo."', 
				year 	= 	'".$this->year."', 
				placas 	= 	'".$this->placas."', 
				motor 	= 	'".$this->motor."', 
				color 	= 	'".$this->color."',
				cat_status_unidad 	= 	'".$this->cat_status_unidad."',
				user_mod		=   '".$_SESSION['id_usuario']."'
		
		WHERE
			id_unidad = '".$this->id_unidad."'		
		";
		$query = $this->db->prepare($sql);
		$query_resp = $query->execute();
		if($query_resp){
			$respuesta = array('resp' => true  );
		}else{
			$respuesta = array('resp' => false );
		}
		return $respuesta;
	}
	function data_unidad($id_unidad){
		$id_unidad = intval($id_unidad);
		$sql="SELECT * FROM cr_unidades WHERE id_unidad = ".$id_unidad."";
		$query = $this->db->prepare($sql);
		$query->execute();
		$unidad = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($unidad as $row) {
					$array['id_unidad'] =  $id_unidad;
					$array['id_marca'] 	=  $row->id_marca;
					$array['id_modelo'] =  $row->id_modelo;
					$array['year' ]		=  $row->year;
					$array['placas'] 	=  $row->placas;
					$array['motor'] 	=  $row->motor;
					$array['color'] 	=  $row->color;
					$array['cat_status_unidad'] 	=  $row->cat_status_unidad;
			}
		}
		return $array;
	}
	function add_unidad_do($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
			
		$sql = "
			INSERT INTO cr_unidades (
				id_marca,
				id_modelo, 
				year, 
				placas, 
				motor, 
				color,
				cat_status_unidad,
				user_alta,
				fecha_alta
			) VALUES (
				:id_marca,
				:id_modelo, 
				:year, 
				:placas, 
				:motor, 
				:color,
				:cat_status_unidad,
				:user_alta,
				:fecha_alta
			)";
		$query = $this->db->prepare($sql);
		$query_resp = $query->execute(
			array(
				':id_marca' => $this->id_marca,
				':id_modelo' => $this->id_modelo, 
				':year' => $this->year, 
				':placas' => $this->placas, 
				':motor' => $this->motor, 
				':color' => $this->color,
				':cat_status_unidad' => $this->cat_status_unidad,
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
	function obtenerUnidades($array){
		ini_set('memory_limit', '256M');				
		$table = 'cr_unidades AS unit';
		$primaryKey = 'id_unidad';
		$columns = array(
			array( 
				'db' => 'id_unidad',
				'dbj' => 'id_unidad',
				'real' => 'id_unidad',
				'typ' => 'int',
				'dt' => 0
			),
			array( 
				'db' => 'mrc.marca AS marca',
				'dbj' => 'mrc.marca',	
				'alias' => 'marca',
				'real' => 'mrc.marca',
				'typ' => 'txt',
				'dt' => 1
			),
			array( 
				'db' => 'mdl.modelo AS modelo',
				'dbj' => 'mdl.modelo',				
				'real' => 'mdl.modelo',
				'alias' => 'modelo',
				'typ' => 'txt',
				'dt' => 2
			),
			array( 
				'db' => 'year',
				'dbj' => 'year',
				'real' => 'year',
				'alias' => 'year',
				'typ' => 'int',
				'dt' => 3				
			),
			array( 
				'db' => 'placas',
				'dbj' => 'placas',
				'real' => 'placas',
				'alias' => 'placas',
				'typ' => 'txt',
				'dt' => 4				
			),
			array( 
				'db' => 'motor',
				'dbj' => 'motor',
				'real' => 'motor',
				'alias' => 'motor',
				'typ' => 'txt',
				'dt' => 5				
			),
			array( 
				'db' => 'color',
				'dbj' => 'color',
				'real' => 'color',
				'alias' => 'color',
				'typ' => 'txt',
				'dt' => 6				
			),
			array( 
				'db' => 'id_unidad',
				'dbj' => 'id_unidad',
				'real' => 'id_unidad',
				'typ' => 'int',
				'acciones' => true,
				'dt' => 7				
			)
		);
		$render_table = new acciones_unidades;
		$inner = '
			INNER JOIN cr_modelos AS mdl ON unit.id_modelo = mdl.id_modelo
			INNER JOIN cr_marcas AS mrc ON unit.id_marca = mrc.id_marca	
		';
		$where = '
			unit.cat_status_unidad = 14
		';
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
}
class acciones_unidades extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				if ( isset( $column['acciones'] ) ) {
					
					$assign = self::statusUnidad($data[$i][ 'id_unidad' ],$db);
					
					$salida = '';
					if($assign == 0){//sin asignar
						$salida .= '<a data-rel="tooltip" data-original-title="Sin asignar" class="red tooltip-danger"><i class="ace-icon fa fa-user-times bigger-130"></i></a>&nbsp;&nbsp;';
					}else if($assign > 0){//asignado
						$salida .= '<a data-rel="tooltip" data-original-title="Unidad Asignada" class="tooltip-info"><i class="ace-icon fa fa-user bigger-130"></i></a>&nbsp;&nbsp;';
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
	static function statusUnidad($id_unidad,$db){
		$query = "
			SELECT
				count(id_operador_unidad) as total
			FROM
				cr_operador_unidad
			WHERE
				id_unidad = $id_unidad
				AND
				cr_operador_unidad.status_operador_unidad = 198
		";
		
		$query = $db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				return  $row['total'];
			}
		}
	}	
}
?>