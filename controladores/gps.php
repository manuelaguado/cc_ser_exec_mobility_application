<?php
class Gps extends Controlador
{
    public function index()
    {
		$this->se_requiere_logueo(true,'Gps|index');
        require URL_VISTA.'gps/index.php';
    }
	public function modal_geolocalizacion($id_operador){
		$this->se_requiere_logueo(true,'Gps|geolocalizacion');
		$modelo_cell = $this->loadModel('Telefonia');
			$phone = $modelo_cell->getDatacell($id_operador);
		require URL_VISTA.'modales/gps/geolocalizacion.php';
	}
	public function geolocalizacion($id_operador)
    {
		$this->se_requiere_logueo(true,'Gps|geolocalizacion');
		$modelo_cell = $this->loadModel('Telefonia');
			$phone = $modelo_cell->getDatacell($id_operador);
        require URL_VISTA.'gps/geolocalizacion.php';
    }
	public function tracker($id_operador){
		$this->se_requiere_logueo(true,'Gps|geolocalizacion');
		$modelo_gps = $this->loadModel('Gps');
			
			$actual_coords = $modelo_gps->lastPositionById($id_operador);
			$coords = json_decode($actual_coords);
			$num_eq = $modelo_gps->num_eq($id_operador);

			if($coords){
				require URL_VISTA.'gps/tracker.php';
			}else{
				require URL_VISTA.'gps/no_coords.php';
			}
	}
	public function lastPositionById($id_operador){
		$this->se_requiere_logueo(true,'Gps|geolocalizacion');
		$modelo_gps = $this->loadModel('Gps');
		print $modelo_gps->lastPositionById($id_operador);
	}	
	public function gps_activo(){
		$this->se_requiere_logueo(true,'Gps|gps_activo');
		$modelo = $this->loadModel('Gps');
		$activos = $modelo->activos_get();
		require URL_VISTA.'gps/gps_activo.php';
	}
	public function gps_activo_get(){
		$this->se_requiere_logueo(true,'Gps|gps_activo');
        $modelo = $this->loadModel('Gps');
		$data = $modelo->gps_activo_get($_POST);
		print $data;
	}
    public function localizar()
    {
		$this->se_requiere_logueo(true,'Gps|localizar');
        require URL_VISTA.'gps/localizar.php';
    }
	public function localizar_get()
    {
		$this->se_requiere_logueo(true,'Gps|localizar');
        $modelo = $this->loadModel('Gps');
		$data = $modelo->localizar_get($_POST);
		print $data;
    }
	
    public function logger()
    {
		$this->se_requiere_logueo(true,'Gps|logger');
        require URL_VISTA.'gps/logger.php';
    }	
	public function logger_get()
    {
		$this->se_requiere_logueo(true,'Gps|logger');
        $modelo = $this->loadModel('Gps');
		$points = $modelo->pointsGet($_POST);
		print $points;
    }
    public function registrar($vars)
    {	
		$this->se_requiere_logueo(false);
        $datos = explode("|", $vars);
		
		$gps['latitud'] 	= $datos[0];
		$gps['longitud'] 	= $datos[1];
		$gps['tiempo'] 		= $datos[2];
		$gps['bateria'] 	= $datos[3];
		$gps['id_android'] 	= $datos[4];
		$gps['serie']		= $datos[5];
		$gps['acurate'] 	= $datos[6];
		$gps['version'] 	= $datos[7];
		$gps['cc'] 			= $datos[8];
		
		$gps_model = $this->loadModel('Gps');
		$gps_model->save_gps_point($gps);
    }
}
?>