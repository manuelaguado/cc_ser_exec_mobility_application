<?php
require( '../vendor/mysql_datatable.php' );
class AsentamientosModel
{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
        }
    }
	function busqueda_colonia($search){
		$qry_ciudad = 'AND cd.id_ciudad = '.$_SESSION['busqueda_ciudad'].'';	
		$query = "
			SELECT
				col.id_asentamiento AS id,
				col.asentamiento AS coln,
				mpo.municipio AS mnpio,
				edo.estado AS est,
				cd.ciudad AS city,
				cp.codigo_postal as cp
			FROM
				it_asentamientos AS col
			INNER JOIN it_ciudades AS cd ON col.id_ciudad = cd.id_ciudad
			INNER JOIN it_estados AS edo ON col.id_estado = edo.id_estado
			INNER JOIN it_municipios AS mpo ON col.id_municipio = mpo.id_municipio
			INNER JOIN it_codigos_postales AS cp ON col.id_codigo_postal = cp.id_codigo_postal
			WHERE
				col.asentamiento LIKE lower('%".$search."%')
				".$qry_ciudad."
			ORDER BY
				col.asentamiento DESC
			LIMIT 0,
			 20
		";
		$query = $this->db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		$output = array('suggestions'=>array());
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				$output['suggestions'][] = array(
					'value'=> utf8_encode($row->coln),
					'cp' => utf8_encode($row->cp),
					'colonia' => utf8_encode($row->coln),
					'estado' => utf8_encode($row->est),
					'municipio' => utf8_encode($row->mnpio),
					'ciudad' => utf8_encode($row->city),
					'data'=>$row->id
				);
			}			
		}
		return json_encode($output);
	}
	function busqueda_cp($search){
		$qry_ciudad = 'AND cd.id_ciudad = '.$_SESSION['busqueda_ciudad'].'';	
		$query = "
			SELECT
				col.id_asentamiento as id,
				cp.codigo_postal as cp,
				col.asentamiento as coln,
				mpo.municipio as mnpio,
				edo.estado as est,
				cd.ciudad as city
			FROM
				it_codigos_postales AS cp
			INNER JOIN it_asentamientos AS col ON col.id_codigo_postal = cp.id_codigo_postal
			INNER JOIN it_ciudades AS cd ON col.id_ciudad = cd.id_ciudad
			INNER JOIN it_estados AS edo ON col.id_estado = edo.id_estado
			INNER JOIN it_municipios AS mpo ON col.id_municipio = mpo.id_municipio
			WHERE
				cp.codigo_postal LIKE '%".$search."%'
				".$qry_ciudad."
			ORDER BY
				cp.codigo_postal DESC
		";
		$query = $this->db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		$output = array('suggestions'=>array());
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				$output['suggestions'][] = array(
					'value'=> $row->cp .'  >  '. utf8_encode($row->coln),
					'cp' => utf8_encode($row->cp),
					'colonia' => utf8_encode($row->coln),
					'estado' => utf8_encode($row->est),
					'municipio' => utf8_encode($row->mnpio),
					'ciudad' => utf8_encode($row->city),
					'data'=>$row->id
				);
			}			
		}
		return json_encode($output);
	}
	function busqueda_ciudad($search){
		$query = "
			SELECT
				it_ciudades.ciudad,
				it_ciudades.id_ciudad,
				it_estados.estado
			FROM
				it_asentamientos
			INNER JOIN it_ciudades ON it_asentamientos.id_ciudad = it_ciudades.id_ciudad
			INNER JOIN it_estados ON it_asentamientos.id_estado = it_estados.id_estado
			WHERE
				it_ciudades.ciudad LIKE lower('%".$search."%')
			GROUP BY
				it_ciudades.id_ciudad
			ORDER BY
				it_estados.estado ASC
		";
		$query = $this->db->prepare($query);
		$query->execute();
		$result = $query->fetchAll();
		$output = array('suggestions'=>array());
		if($query->rowCount()>=1){
			foreach ($result as $row) {
				$output['suggestions'][] = array(
					'value'		=> 	utf8_encode($row->estado.' > '.$row->ciudad),
					'data'		=>	$row->id_ciudad,
					'ciudad'	=> 	utf8_encode($row->ciudad),
					'estado'	=>	utf8_encode($row->estado)
				);
			}			
		}
		return json_encode($output);
	}
}
?>