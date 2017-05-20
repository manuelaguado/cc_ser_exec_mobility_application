<?php
use Pubnub\Pubnub;
class MobileModel
{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
        }
}

	/*
	SECCION DE WEBSOCKETS
	*/


	public function transmitir($emision,$proceso){



			require_once('../vendor/pusher/Pusher.php');

			$options = array('encrypted' => true);
			$pusher = new Pusher(PUSHER_KEY,PUSHER_SECRET,PUSHER_APP_ID,$options);

			$emision = json_decode($emision,true);
			$data['message'] = $emision;
			$pusher->trigger($proceso, 'evento', $data);



	}

	function linkedIn(){

		require_once('../vendor/pusher/Pusher.php');
		$options = array('encrypted' => true);
		$pusher = new Pusher(PUSHER_KEY,PUSHER_SECRET,PUSHER_APP_ID,$options);
		$response = $pusher->get( '/channels/'.PUSHER_PRESENCE.'/users' );

		return $response;
	}
	public function onLink(){
		$id_operadores = array();
		$num = 0;
		$response = self::linkedIn();

		foreach($response['result']['users'] as $num => $oper){
			foreach($oper as $it){
				$id_operadores[$num]['id_operador'] = $it;
				$num++;
			}
		}

		return $id_operadores;
	}

	function getAllIdenOperadorUnidad($id_operador_unidad){
		$qry = "
			SELECT
				base.id_operador_unidad,
				base.id_operador,
				cr_operador.id_usuario
			FROM
				cr_operador_unidad AS iden
			INNER JOIN cr_operador_unidad AS base ON iden.id_operador = base.id_operador
			INNER JOIN cr_operador ON base.id_operador = cr_operador.id_operador
			WHERE
				iden.id_operador_unidad = $id_operador_unidad
			AND base.status_operador_unidad = 198
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$ids = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row){
				$ids[$num]['id_operador_unidad'] = $row->id_operador_unidad;
				$ids[$num]['id_operador'] = $row->id_operador;
				$ids[$num]['id_usuario'] = $row->id_usuario;
				$num++;
			}
		}
		return $ids;
	}
	function getVehiculosOperador($id_operador){
		$qry = "
			SELECT
				crou.id_operador_unidad,
				crm.marca,
				cmod.modelo,
				cru.`year`,
				cru.placas,
				cru.color
			FROM
				cr_operador_unidad AS crou
			INNER JOIN cr_unidades AS cru ON cru.id_unidad = crou.id_unidad
			INNER JOIN cr_marcas AS crm ON cru.id_marca = crm.id_marca
			INNER JOIN cr_modelos AS cmod ON cru.id_modelo = cmod.id_modelo
			WHERE
				cru.cat_status_unidad = 14
			AND crou.id_operador = ".$id_operador."
			AND crou.status_operador_unidad = 198
		";
		$query = $this->db->prepare($qry);
		$query->execute();
		$vehiculos = array();
		$num = 0;
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			foreach ($data as $row) {
				$vehiculos[$num]['id_operador_unidad'] = $row->id_operador_unidad;
				$vehiculos[$num]['marca'] = $row->marca;
				$vehiculos[$num]['modelo'] = $row->modelo;
				$vehiculos[$num]['year'] = $row->year;
				$vehiculos[$num]['placas'] = $row->placas;
				$vehiculos[$num]['color'] = $row->color;
				$num++;
			}
		}
		return $vehiculos;
	}
}
?>
