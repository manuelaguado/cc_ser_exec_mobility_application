<?php
require( '../vendor/mysql_datatable.php' );
class TelefoniaModel
{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexi�n a la base de datos.');
        }
    }
	function getDomicilio($id_operador_celular){
		$sql="
			SELECT
				cr_domicilios.domicilio
			FROM
				cr_operador
			INNER JOIN cr_domicilios ON cr_domicilios.id_operador = cr_operador.id_operador
			INNER JOIN cr_operador_celular ON cr_operador_celular.id_operador = cr_operador.id_operador
			WHERE
				cr_operador_celular.id_operador_celular = $id_operador_celular
			AND cr_domicilios.cat_statusdomicilio = 128
			ORDER BY
				cr_domicilios.id_domicilio DESC
			LIMIT 0,
			 1
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$data = $query->fetchAll();
		if($query->rowCount()>=1){
			foreach ($data as $row) {
				return $row->domicilio;
			}
		}else{
			return 'Domicilio no establecido, inactivo o eliminado.';
		}
	}
	function dataResguardo($id_celular){
		$array = array();
		$qry = "
			SELECT
				croc.id_operador_celular,
				fwu.nombres,
				fwu.apellido_paterno,
				fwu.apellido_materno,
				crc.marca,
				crc.modelo,
				crc.imei,
				crc.numero,
				crc.marcacion_corta,
				crc.sim,
				crc.valor
			FROM
				cr_operador_celular AS croc
			INNER JOIN cr_operador AS cro ON croc.id_operador = cro.id_operador
			INNER JOIN cr_celulares AS crc ON croc.id_celular = crc.id_celular
			INNER JOIN fw_usuarios AS fwu ON cro.id_usuario = fwu.id_usuario
			WHERE
				crc.id_celular = $id_celular
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $elm) {
				$array['id_operador_celular']=$elm->id_operador_celular;
				$array['nombre']=utf8_encode($elm->nombres.' '.$elm->apellido_paterno.' '.$elm->apellido_materno);
				$array['marca']=$elm->marca;
				$array['modelo']=$elm->modelo;
				$array['imei']=$elm->imei;
				$array['numero']=$elm->numero;
				$array['marcacion_corta']=$elm->marcacion_corta;
				$array['sim']=$elm->sim;
				$array['valor']=$elm->valor;
			}
		}		
		return $array;
	}	
	function asignarCell($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		$sql = "
			INSERT INTO cr_operador_celular (
				id_operador,
				id_celular,
				cat_status_operador_celular,
				user_alta,
				fecha_alta
			)
			VALUES
				(
					:id_operador,
					:id_celular,
					:cat_status_operador_celular,
					:user_alta,
					:fecha_alta
				)
		";
		$query = $this->db->prepare($sql);
		$result = $query->execute(
			array(
				':id_operador' 					=> $this->id_operador, 
				':id_celular' 					=> $this->id_celular,
				':cat_status_operador_celular' 	=> $this->cat_status_operador_celular, 
				':user_alta' 					=> $_SESSION['id_usuario'], 
				':fecha_alta' 					=> date("Y-m-d H:i:s")
			)
		);
		self::setStatusCell($this->id_celular);
		return $result? array('resp' => true):array('resp' => false);
	}
	function setStatusCell($id_celular){
		$sql = "UPDATE cr_celulares SET 
			cat_status_celular	=	'28', 
			user_mod			=   '".$_SESSION['id_usuario']."'
			where
			id_celular = '".$id_celular."'
		";
		$query = $this->db->prepare($sql);
		$result = $query->execute();
	}
	function operadoresUnassign(){
		$array = array();
		$qry = "
			SELECT
				cro.id_operador,
				fwu.nombres,
				fwu.apellido_paterno,
				fwu.apellido_materno
			FROM
				cr_operador AS cro
			INNER JOIN fw_usuarios AS fwu ON cro.id_usuario = fwu.id_usuario
			WHERE
				NOT EXISTS (
					SELECT
						*
					FROM
						cr_operador_celular AS croc
					WHERE
						cro.id_operador = croc.id_operador
					AND croc.cat_status_operador_celular <> 32
				)	
				AND cro.cat_statusoperador = 8	
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$areas = $query->fetchAll();
			$cont = 0;
			foreach ($areas as $row) {
				$array[$cont]['value']=$row->id_operador;
				$array[$cont]['valor']=utf8_encode($row->nombres.' '.$row->apellido_paterno.' '.$row->apellido_materno);
				$cont++;			
			}
		}		
		return Controller::setOption($array,null);
	}

	function editCell($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		$sql = "UPDATE cr_celulares SET 
			serie				=	'".$this->serie."', 
			imei				=	'".$this->imei."',
			numero				=	'".$this->numero."', 
			marcacion_corta		=	'".$this->marcacion_corta."', 
			marca				=	'".$this->marca."',
			modelo				=	'".$this->modelo."',
			so					=	'".$this->so."',
			version				=	'".$this->version."', 
			cat_status_celular	=	'".$this->cat_status_celular."', 
			sim					=	'".$this->sim."',
			valor				=	'".$this->valor."',
			user_mod			=   '".$_SESSION['id_usuario']."'
			where
			id_celular = '".$this->id_celular."'
		";
		if(
			$this->cat_status_celular == 29 ||
			$this->cat_status_celular == 30 ||
			$this->cat_status_celular == 101
		){self::status_operador_cel($this->id_celular,32);}
		$query = $this->db->prepare($sql);
		$result = $query->execute();
		return $result? array('resp' => true):array('resp' => false);
	}
	function status_operador_cel($id_celular,$stat){
		$sql = "UPDATE cr_operador_celular SET 
			cat_status_operador_celular	= ".$stat."
			where
			id_celular = ".$id_celular."
		";
		$query = $this->db->prepare($sql);
		$result = $query->execute();
	}
	function getDatacell($id_cliente){
		$sql="
			SELECT
				cell.id_celular,
				cell.imei,
				cell.numero,
				cell.marcacion_corta,
				cell.marca,
				cell.modelo,
				cell.so,
				cell.version,
				cell.cat_status_celular,
				cell.sim,
				cell.valor,
				cell.serie
			FROM
				cr_operador_celular AS croc
			INNER JOIN cr_operador AS op ON croc.id_operador = op.id_operador
			INNER JOIN cr_celulares AS cell ON croc.id_celular = cell.id_celular
			WHERE
				op.id_operador = ".$id_cliente." 
			AND croc.cat_status_operador_celular = 31
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$cliente = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($cliente as $row) {
				$array['id_celular'] 		= $row->id_celular;
				$array['imei'] 				= $row->imei;
				$array['numero'] 			= $row->numero;
				$array['marcacion_corta'] 	= $row->marcacion_corta;
				$array['marca'] 			= $row->marca;
				$array['modelo'] 			= $row->modelo;
				$array['so'] 				= $row->so;
				$array['version'] 			= $row->version;
				$array['cat_status_celular']= $row->cat_status_celular;
				$array['sim'] 				= $row->sim;
				$array['valor'] 			= $row->valor;
				$array['serie'] 			= $row->serie;
			}
		}
		return $array;
	}	
	function dataCell($id_celular){
		$sql="SELECT * FROM cr_celulares WHERE id_celular = ".$id_celular.";";
		$query = $this->db->prepare($sql);
		$query->execute();
		$cliente = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($cliente as $row) {
				$array['serie'] 			= $row->serie;
				$array['imei'] 				= $row->imei;
				$array['numero'] 			= $row->numero;
				$array['marcacion_corta'] 	= $row->marcacion_corta;
				$array['marca'] 			= $row->marca;
				$array['modelo'] 			= $row->modelo;
				$array['so'] 				= $row->so;
				$array['version'] 			= $row->version;
				$array['cat_status_celular']= $row->cat_status_celular;
				$array['sim'] 				= $row->sim;
				$array['valor'] 			= $row->valor;
				$array['user_alta'] 		= $row->user_alta;
				$array['user_mod'] 			= $row->user_mod;
				$array['fecha_alta'] 		= $row->fecha_alta;
				$array['fecha_mod'] 		= $row->fecha_mod;
			}
		}
		return $array;
	}
	function insertCell($arreglo){
		foreach ($arreglo as $key => $value) {
			$this->$key = strip_tags($value);
		}
		if($this->externo == true){$externo = 1;}else{$externo = 0;}
		$sql = "
			INSERT INTO cr_celulares (
				serie,
				imei,
				numero,
				marcacion_corta,
				marca,
				modelo,
				so,
				version,
				cat_status_celular,
				sim,
				externo,
				valor,
				user_alta,
				fecha_alta
			)
			VALUES
				(
					:serie,
					:imei,
					:numero,
					:marcacion_corta,
					:marca,
					:modelo,
					:so,
					:version,
					:cat_status_celular,
					:sim,
					:externo,
					:valor,
					:user_alta,
					:fecha_alta
				)
		";
		$query = $this->db->prepare($sql);
		$result = $query->execute(
			array(
				':serie' 				=> $this->serie, 
				':imei' 				=> $this->imei,
				':numero' 				=> $this->numero, 
				':marcacion_corta' 		=> $this->marcacion_corta, 
				':marca' 				=> $this->marca, 
				':modelo' 				=> $this->modelo, 
				':so' 					=> $this->so, 
				':version' 				=> $this->version, 
				':cat_status_celular' 	=> '30', 
				':sim' 					=> $this->sim, 
				':externo' 				=> $externo, 
				':valor' 				=> $this->valor, 
				':user_alta' 			=> $_SESSION['id_usuario'], 
				':fecha_alta' 			=> date("Y-m-d H:i:s")
			)
		);
		return $result? array('resp' => true):array('resp' => false);
	}
	function listaCelulares($array){
		ini_set('memory_limit', '256M');				
		$table = 'cr_celulares as crc';
		$primaryKey = 'crc.id_celular';
		$columns = array(
			array( 
				'db' => 'crc.id_celular as id_cel',
				'dbj' => 'crc.id_celular',
				'real' => 'crc.id_celular',
				'alias' => 'id_cel',
				'typ' => 'int',
				'dt' => 0
			),
			array( 
				'db' => 'crc.serie AS serie',
				'dbj' => 'crc.serie',	
				'alias' => 'serie',
				'real' => 'crc.serie',
				'typ' => 'txt',
				'dt' => 1
			),
			array( 
				'db' => 'crc.imei AS imei',
				'dbj' => 'crc.imei',				
				'real' => 'crc.imei',
				'alias' => 'imei',
				'typ' => 'txt',
				'dt' => 2
			),
			array( 
				'db' => 'crc.numero AS numero',
				'dbj' => 'crc.numero',
				'real' => 'crc.numero',
				'alias' => 'numero',
				'typ' => 'int',
				'dt' => 3				
			),
			array( 
				'db' => 'crc.marcacion_corta AS marcacion_corta',
				'dbj' => 'crc.marcacion_corta',
				'real' => 'crc.marcacion_corta',
				'alias' => 'marcacion_corta',
				'typ' => 'int',
				'dt' => 4				
			),
			array( 
				'db' => 'crc.marca AS marca',
				'dbj' => 'crc.marca',
				'real' => 'crc.marca',
				'alias' => 'marca',
				'typ' => 'txt',
				'dt' => 5				
			),
			array( 
				'db' => 'crc.so AS so',
				'dbj' => 'crc.so',
				'real' => 'crc.so',
				'alias' => 'so',
				'typ' => 'txt',
				'dt' => 6				
			),
			array( 
				'db' => 'crc.version AS version',
				'dbj' => 'crc.version',
				'real' => 'crc.version',
				'alias' => 'version',
				'typ' => 'txt',
				'dt' => 7				
			),
			array( 
				'db' => 'crc.cat_status_celular AS cat_status_celular',
				'dbj' => 'crc.cat_status_celular',
				'real' => 'crc.cat_status_celular',
				'alias' => 'cat_status_celular',
				'typ' => 'txt',
				'acciones' => true,
				'dt' => 8				
			),
			array( 
				'db' => 'crc.externo AS externo',
				'dbj' => 'crc.externo',
				'real' => 'crc.externo',
				'alias' => 'externo',
				'typ' => 'int',
				'dt' => 9				
			)
		);
		$render_table = new acciones_celular;
		$inner = '';
		$where = '';
		return json_encode(
			$render_table->complex( $array, $this->dbt, $table, $primaryKey, $columns, null, $where, $inner )
		);
	}
}
class acciones_celular extends SSP{
	static function data_output ( $columns, $data, $db )
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				$name_column = ( isset($column['alias']) )? $column['alias'] : $column['db'] ;
				if ( isset( $column['acciones'] ) ) {
					
					$salida = '';
					$id_celular = $data[$i][ 'id_cel' ];
					$externo = $data[$i][ 'externo' ];
					$serie = $data[$i][ 'serie' ];
					$status_celular = $data[$i][ 'cat_status_celular' ];
					
					
					if(Controlador::tiene_permiso('Telefonia|editar')){
						$salida .= '<a data-rel="tooltip" data-original-title="Editar equipo" class="green tooltip-success" onclick="modal_edit_celular('.$id_celular.')"><i class="ace-icon fa fa-pencil-square-o bigger-130"></i></a>&nbsp;&nbsp;';
					}
					
					if(Controlador::tiene_permiso('Telefonia|asignar')){
						if($status_celular == 30){//sin asignar
							$salida .= '<a data-rel="tooltip" data-original-title="Asignar equipo" class="green tooltip-success" onclick="modal_asignar_celular('.$id_celular.')"><i class="ace-icon fa fa-user-plus bigger-130"></i></a>&nbsp;&nbsp;';
						}else if($status_celular == 28){//asignado
							$salida .= '<a data-rel="tooltip" data-original-title="Equipo Asignado" class="tooltip-warning"><i class="ace-icon fa fa-user bigger-130"></i></a>&nbsp;&nbsp;';
						}else if($status_celular == 103){//asignado
							$salida .= '<a data-rel="tooltip" data-original-title="Equipo Asignado" class="tooltip-warning"><i class="ace-icon fa fa-user bigger-130"></i></a>&nbsp;&nbsp;';
						}
					}
					
					if(Controlador::tiene_permiso('Pdf|resguardo_telefonico')){
						if( self::resguardo($id_celular,$db) == 1){
							$salida .=  '<a onclick="modal_resguardo_telefonico('.$id_celular.')" data-rel="tooltip" data-original-title="Resguardo" class="blue tooltip-info"><i class="ace-icon fa fa-file-pdf-o bigger-130"></i></a>&nbsp;&nbsp;';
						}
					}
					
					if($externo == 1){//externo
						$salida .= '<a data-rel="tooltip" data-original-title="Teléfono externo" class="red tooltip-info"><i class="ace-icon fa fa-sign-out bigger-130"></i></a>&nbsp;&nbsp;';
					}else if($externo == 0){//interno
						$salida .= '<a data-rel="tooltip" data-original-title="Teléfono interno" class="tooltip-info"><i class="ace-icon fa fa-sign-in bigger-130"></i></a>&nbsp;&nbsp;';
					}
					
					$salida .= self::assign($id_celular,$db);
					

					$row[ $column['dt'] ] = $salida;
				}else{
					$row[ $column['dt'] ] = ( self::detectUTF8($data[$i][$name_column]) )? $data[$i][$name_column] : utf8_encode($data[$i][$name_column]);	
				}
			}
			$out[] = $row;
		}
		return $out;
	}
	static function resguardo($id_celular,$db){	
		$query = "
			SELECT
				count(croc.id_celular) AS total
			FROM
				cr_celulares AS crc
			INNER JOIN cr_operador_celular AS croc ON croc.id_celular = crc.id_celular
			INNER JOIN cr_operador AS cro ON croc.id_operador = cro.id_operador
			WHERE
				crc.id_celular = $id_celular
			AND croc.cat_status_operador_celular = 31
			AND (crc.cat_status_celular = 28 OR crc.cat_status_celular = 103)
			AND (cro.cat_statusoperador = 8 OR cro.cat_statusoperador = 10)
			AND crc.externo = 0
		";
		
		$query = $db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		
		foreach ($result as $row) {
			return  $row['total'];
		}
	}
	static function assign($id_celular,$db){
		$query = "
			SELECT
				fwu.nombres,
				fwu.apellido_paterno,
				fwu.apellido_materno,
				fwu.usuario
			FROM
				cr_celulares AS crc
			INNER JOIN cr_operador_celular AS croc ON croc.id_celular = crc.id_celular
			INNER JOIN cr_operador AS cro ON croc.id_operador = cro.id_operador
			INNER JOIN fw_usuarios AS fwu ON cro.id_usuario = fwu.id_usuario
			WHERE
				crc.id_celular = $id_celular
			AND
				croc.cat_status_operador_celular = 31
		";
		
		$query = $db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				return  $row['nombres'].' '.$row['apellido_paterno'].' '.$row['apellido_materno'];
			}
		}
	}
}
?>
