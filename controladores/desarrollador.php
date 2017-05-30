<?php
class Desarrollador extends Controlador
{
	function __construct(){
		if(DEVELOPMENT == false){exit();}
	}
	function adquirirTiemposBase(){
		$coordBase = '19.434830,-99.211976';
		$coordsUnits = substr($coordsUnits, 0, -1);
		$resultado = self::distancematrix($coordsUnits,$coordBase);
		$array = array();
		foreach($resultado->rows as $n => $tb_units){
			$array['distancia'] = $tb_units->elements[0]->distance->value;
			$array['min'] = $tb_units->elements[0]->duration->value;
			$array['max'] = $tb_units->elements[0]->duration_in_traffic->value;
			$array['id_operador'] = $oper[$n];
			$array['id_operador_unidad'] = $oper_unit[$n];
			$array['latLng'] = $latLng[$n];
			self::storeTB($array);
		}
	}
	public function minimap(){

		$url='https://maps.googleapis.com/maps/api/directions/json?origin=19.375358,%20-99.061929&destination=19.430293,%20-99.218078&key=AIzaSyDXtWmQa-KeoTv_VopVhSJ-hPCTx92GMXE';

	        $curl = curl_init();
	        curl_setopt($curl, CURLOPT_URL, $url);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	        $result = curl_exec($curl);
	        curl_close($curl);
	        $decode = json_decode($result);
		 	/*
			foreach($decode->routes as $num=>$val){
				$kapa = $val->overview_polyline->points;
			}
			*/
			$kapa = $decode->routes[0]->overview_polyline->points;

		$result = file_get_contents('https://maps.googleapis.com/maps/api/staticmap?&size=650x350&scale=2&path=color:0x000000ff%7Cweight:2%7Cenc:'.$kapa.'&key='.GOOGLE_MAPS);
		$imagen = $this->token(6).".png";
		$name = "../public/tmp/".$imagen;
		$fp = fopen($name, 'w');
		fputs($fp, $result);
		fclose($fp);
		echo '<img src="../plugs/timthumb.php?src=tmp/'.$imagen.'&w=150&a=c">';
		//echo '<img src="../tmp/'.$imagen.'">';
	}
	public function directions(){

		$url='https://maps.googleapis.com/maps/api/directions/json?origin=19.375358,%20-99.061929&destination=19.430293,%20-99.218078&key=AIzaSyDXtWmQa-KeoTv_VopVhSJ-hPCTx92GMXE';

	        $curl = curl_init();
	        curl_setopt($curl, CURLOPT_URL, $url);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	        $result = curl_exec($curl);
	        curl_close($curl);
	        $decode = json_decode($result);
		 $map = '';
			foreach($decode->routes as $num=>$val){
				foreach($val->legs as $n=>$v){
					$array[] = array($v->start_location->lat,$v->start_location->lng);
					foreach($v->steps as $m=>$k){
						$array[] = array($k->end_location->lat,$k->end_location->lng);

					}
				}
			}
		$encoded = EncodedPolylineAlgorithm::encode($array);
		echo '<br><br>'.$encoded;
		$result = file_get_contents('https://maps.googleapis.com/maps/api/staticmap?&size=650x350&scale=2&path=color:0x000000ff%7Cweight:4%7Cenc:'.$encoded.'&key='.GOOGLE_MAPS);
		$imagen = $this->token(6).".png";
		$name = "../public/tmp/".$imagen;
		$fp = fopen($name, 'w');
		fputs($fp, $result);
		fclose($fp);
		//echo '<img src="../plugs/timthumb.php?src=tmp/'.$imagen.'&w=150&a=c">';
		echo '<img src="../tmp/'.$imagen.'">';
	}
	public function initstate(){
		$db = Controlador::direct_connectivity();
		$sql="
		SELECT
		op.id_operador,
		opu.id_operador_unidad,
		num.num
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
						'C2',
						'C2',
						'1'
					);
			";
			D::bug($sql);
			$populate = $db->prepare($sql);
			$populate->execute();
		}
	}
	public function image(){
		$pointsToEncoded = self::PolylineToEncoded();
		$encoded = EncodedPolylineAlgorithm::encode($pointsToEncoded);
		$imagen = self::saveImage($encoded);
		//echo '<img src="../plugs/timthumb.php?src=tmp/'.$imagen.'&w=150&a=c">';
		echo '<img src="../tmp/'.$imagen.'">';
	}
	public function saveImage($encoded){
		$result = file_get_contents('https://maps.googleapis.com/maps/api/staticmap?&size=600x300&scale=2&path=color:0x000000ff%7Cweight:2%7Cenc:'.$encoded.'&key='.GOOGLE_MAPS);
		$file = $this->token(6).".png";
		$name = "../public/tmp/".$file;
		$fp = fopen($name, 'w');
		fputs($fp, $result);
		fclose($fp);
		return $file;
	}
	public function PolylineToEncoded(){
		$db = Controlador::direct_connectivity();

		$stmt = $db->prepare("SELECT count(id_gps) as total from gpstmp");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$puntos = $rows[0]->total;
		$bucles = ceil($puntos/100);

		for($i = 1; $i <= $bucles; $i++){
			$sql = "select latitud,longitud  from gpstmp LIMIT " . (($i*100)-100) . ",100;";
			$qry = $db->prepare($sql);
			$qry->execute();
			$data = $qry->fetchAll();
			$coords{$i} = '';
			if($qry->rowCount()>=1){
				foreach ($data as $num=>$row) {
					$coords{$i} .= $row->latitud.','.$row->longitud.'|';
				}
			}
			$coords{$i} = substr($coords{$i}, 0, -1);
		}
		$tms = 0;
		for($i = 1; $i <= $bucles; $i++){
			$snap = self::GetSnailTrail($coords{$i});
			$decode = json_decode($snap);
			$origin = 0;
			foreach($decode->snappedPoints as $num=>$val){
				$array[] = array($val->location->latitude,$val->location->longitude);
			}
		}
		return $array;
	}
	public function distanceMatrix(){
        $url='https://maps.googleapis.com/maps/api/distancematrix/json?origins=19.375489,-99.062057&destinations=20.670867,-103.367144&key=AIzaSyDS6f8F5bj6kKdsfO57Y_2GZQShg79rgnk';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);
        curl_close($curl);

		print $result;
		echo '<br><br>';

        $decode = json_decode($result);
		foreach($decode->rows as $num=>$val){
			foreach($val->elements as $n=>$v){
				echo 'distancia: '.$v->distance->text;
				echo '<br>';
				echo 'duracion: '.$v->duration->text;
			}
		}
	}
	public function gps(){
		$db = Controlador::direct_connectivity();

		$stmt = $db->prepare("SELECT count(id_gps) as total from gpstmp");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$puntos = $rows[0]->total;
		$bucles = ceil($puntos/100);

		for($i = 1; $i <= $bucles; $i++){
			$sql = "select latitud,longitud  from gpstmp LIMIT " . (($i*100)-100) . ",100;";
			$qry = $db->prepare($sql);
			$qry->execute();
			$data = $qry->fetchAll();
			$coords{$i} = '';
			if($qry->rowCount()>=1){
				foreach ($data as $num=>$row) {
					$coords{$i} .= $row->latitud.','.$row->longitud.'|';
				}
			}
			$coords{$i} = substr($coords{$i}, 0, -1);
		}
		$allcoords = '';
		$tms = 0;
		for($i = 1; $i <= $bucles; $i++){
			$snap = self::GetSnailTrail($coords{$i});
			$decode = json_decode($snap);
			$origin = 0;
			foreach($decode->snappedPoints as $num=>$val){
				$allcoords .= $val->location->longitude.','.$val->location->latitude.'
';
				if($origin == 0){
					$init = [$val->location->latitude,$val->location->longitude];
				}

				$mts = self::haversine($init,[$val->location->latitude,$val->location->longitude]);
				$tms = $tms + $mts;

				$init = [$val->location->latitude,$val->location->longitude];
				$origin = 1;
			}
		}
		$out = self::genKml($allcoords);
		echo $out .'  mts totales: '. ceil($tms);
	}
	public function haversine($init,$end){
		$lat1 = $init[0];
		$lon1 = $init[1];

		$lat2 = $end[0];
		$lon2 = $end[1];

		$latFrom = deg2rad($lat1);
		$lonFrom = deg2rad($lon1);
		$latTo = deg2rad($lat2);
		$lonTo = deg2rad($lon2);

		$latDelta = $latTo - $latFrom;
		$lonDelta = $lonTo - $lonFrom;

		$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
		cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
		return $angle * 6371000;
	}
    function GetSnailTrail( $paths ){
        $key='AIzaSyDRlfacNyHn7ZOsC0FzufqZ_rtQYfZD6wA';
        $url='https://roads.googleapis.com/v1/snapToRoads?path='.$paths.'&interpolate=true&key='.$key;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }
	public function genKml($coords){

		$kml = array('<?xml version=\'1.0\' encoding=\'UTF-8\'?>');
		$kml[] = ' <kml xmlns=\'http://www.opengis.net/kml/2.2\'>';
		$kml[] = ' <Document>';
		$kml[] = ' <name>Viaje</name>';
		$kml[] = ' <description><![CDATA[]]></description>';
		$kml[] = ' <Folder>';
		$kml[] = ' <name>Viaje</name>';
		$kml[] = ' </Folder>';
		$kml[] = ' <Folder>';
		$kml[] = ' <name>GPS Logger</name>';
		$kml[] = ' <Placemark>';
		$kml[] = ' <name>GPS Logger</name>';
		$kml[] = ' <styleUrl>#line-1267FF-5-nodesc</styleUrl>';
		$kml[] = ' <ExtendedData>';
		$kml[] = ' </ExtendedData>';
		$kml[] = ' <LineString>';
		$kml[] = ' <tessellate>1</tessellate>';
		$kml[] = ' <coordinates>';

		$kml[] = $coords;

		$kml[] = '</coordinates>';
		$kml[] = '</LineString>';
		$kml[] = '</Placemark>';
		$kml[] = '</Folder>';
		$kml[] = '<StyleMap id=\'icon-503-DB4436-nodesc\'>';
		$kml[] = '<Pair>';
		$kml[] = '<key>normal</key>';
		$kml[] = '<styleUrl>#icon-503-DB4436-nodesc-normal</styleUrl>';
		$kml[] = '</Pair>';
		$kml[] = '<Pair>';
		$kml[] = '<key>highlight</key>';
		$kml[] = '<styleUrl>#icon-503-DB4436-nodesc-highlight</styleUrl>';
		$kml[] = '</Pair>';
		$kml[] = '</StyleMap>';
		$kml[] = '<Style id=\'line-1267FF-5-nodesc-normal\'>';
		$kml[] = '<LineStyle>';
		$kml[] = '<color>ffFF6712</color>';
		$kml[] = '<width>5</width>';
		$kml[] = '</LineStyle>';
		$kml[] = '<BalloonStyle>';
		$kml[] = '<text><![CDATA[<h3>$[name]</h3>]]></text>';
		$kml[] = '</BalloonStyle>';
		$kml[] = '</Style>';
		$kml[] = '<Style id=\'line-1267FF-5-nodesc-highlight\'>';
		$kml[] = '<LineStyle>';
		$kml[] = '<color>ffFF6712</color>';
		$kml[] = '<width>8.0</width>';
		$kml[] = '</LineStyle>';
		$kml[] = '<BalloonStyle>';
		$kml[] = '<text><![CDATA[<h3>$[name]</h3>]]></text>';
		$kml[] = '</BalloonStyle>';
		$kml[] = '</Style>';
		$kml[] = '<StyleMap id=\'line-1267FF-5-nodesc\'>';
		$kml[] = '<Pair>';
		$kml[] = '<key>normal</key>';
		$kml[] = '<styleUrl>#line-1267FF-5-nodesc-normal</styleUrl>';
		$kml[] = '</Pair>';
		$kml[] = '<Pair>';
		$kml[] = '<key>highlight</key>';
		$kml[] = '<styleUrl>#line-1267FF-5-nodesc-highlight</styleUrl>';
		$kml[] = '</Pair>';
		$kml[] = '</StyleMap>';
		$kml[] = '</Document>';
		$kml[] = '</kml>';
		$kmlOutput = join("\n", $kml);

		$file = $this->token(6).".kml";
		$name = "../public/tmp/".$file;
		$fp = fopen($name, 'w');
		fputs($fp, $kmlOutput);
		fclose($fp);
		return $file;
	}

    public function index()
    {
		$this->se_requiere_logueo(false);
		include (URL_TEMPLATE.'404_full.php');
    }
	function pusherPresence(){

			require_once('../vendor/pusher/Pusher.php');
			$options = array('encrypted' => true);
			$pusher = new Pusher(PUSHER_KEY,PUSHER_SECRET,PUSHER_APP_ID,$options);
			$response = $pusher->get( '/channels/'.PUSHER_PRESENCE.'/users' );
			foreach($response['result']['users'] as $num => $oper){
				foreach($oper as $it){
					echo $it.'<br>';
				}
			}
	}
	function gidous(){
		$id_operador_unidad = '';
		if(file_exists(session_save_path().'/sess_uOA28hWS3sFx1KpBHR70gdyZDIJ9vfQk')){
			$fp = fopen(session_save_path().'/sess_uOA28hWS3sFx1KpBHR70gdyZDIJ9vfQk', "r");
			$content = '';
			while(!feof($fp)) {
				$content .= fgets($fp);
			}
				$regex = '#.*(id_operador_unidad\|).{5}#';
				$replacement = '';
				$result = preg_replace($regex, $replacement, $content);

				$regex = '#(";cat_statusoperador).*#';
				$replacement = '';
				$id_operador_unidad = preg_replace($regex, $replacement, $result);

			fclose($fp);
		}
		print $id_operador_unidad;
	}
	function gidops(){
		$id_operador_unidad = '';
		if(file_exists(session_save_path().'/sess_h1zQmnFfsj7ro0eGbgEBlMLA6U84iOwa')){
			$fp = fopen(session_save_path().'/sess_h1zQmnFfsj7ro0eGbgEBlMLA6U84iOwa', "r");
			$content = '';
			while(!feof($fp)) {
				$content .= fgets($fp);
			}
				$regex = '#.*(id_operador\|).{5}#';
				$replacement = '';
				$result = preg_replace($regex, $replacement, $content);

				$regex = '#(";id_operador_unidad).*#';
				$replacement = '';
				$id_operador_unidad = preg_replace($regex, $replacement, $result);

			fclose($fp);
		}
		print $id_operador_unidad;
	}
	function ssp(){
		echo session_save_path();
	}
	function cp_import(){
		$db = Controlador::direct_connectivity();

		/*
		self::crear_estructura($db);
		self::cp_clean($db);
		self::cp_order($db);
		self::fix_structure($db);
		self::cp_zonas($db);
		self::cp_estados($db);
		self::cp_tipo_asent($db);
		self::cp_ciudades($db);
		self::cp_municipios($db);
		self::cp_cp($db);
		self::rename_db($db);
		*/

	}

	private function rename_db($db){
		$fix = "RENAME TABLE CPdescarga TO cp_asentamientos;";
		$qry = $db->prepare($fix);
		$qry->execute();
	}
	private function fix_structure($db){
		$fix = "
			ALTER TABLE `CPdescarga`
			MODIFY COLUMN `D_mnpio`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `d_codigo`,
			MODIFY COLUMN `d_ciudad`  varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `D_mnpio`,
			ADD COLUMN `id`  int(6) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ,
			ADD PRIMARY KEY (`id`);
		";
		$qry = $db->prepare($fix);
		$qry->execute();
	}

	private function crear_estructura($db){
		$clean = "CREATE TABLE `cp_zn` (`id_zona`  int(6) UNSIGNED NOT NULL AUTO_INCREMENT ,`zona`  varchar(255) NULL ,PRIMARY KEY (`id_zona`));";
		$qry = $db->prepare($clean);
		$qry->execute();

		$clean = "CREATE TABLE `cp_ed` (`id_estado`  int(6) UNSIGNED NOT NULL AUTO_INCREMENT ,`estado`  varchar(255) NULL ,PRIMARY KEY (`id_estado`));";
		$qry = $db->prepare($clean);
		$qry->execute();

		$clean = "CREATE TABLE `cp_ta` (`id_tipo_asentamiento`  int(6) UNSIGNED NOT NULL AUTO_INCREMENT ,`tipo_asentamiento`  varchar(255) NULL ,PRIMARY KEY (`id_tipo_asentamiento`));";
		$qry = $db->prepare($clean);
		$qry->execute();

		$clean = "CREATE TABLE `cp_ci` (`id_ciudad`  int(6) UNSIGNED NOT NULL AUTO_INCREMENT ,`ciudad`  varchar(255) NULL ,PRIMARY KEY (`id_ciudad`));";
		$qry = $db->prepare($clean);
		$qry->execute();

		$clean = "CREATE TABLE `cp_mu` (`id_municipio`  int(6) UNSIGNED NOT NULL AUTO_INCREMENT ,`municipio`  varchar(255) NULL ,PRIMARY KEY (`id_municipio`));";
		$qry = $db->prepare($clean);
		$qry->execute();

		$clean = "CREATE TABLE `cp_cp` (`id_cp`  int(6) UNSIGNED NOT NULL AUTO_INCREMENT ,`cp`  varchar(255) NULL ,PRIMARY KEY (`id_cp`));";
		$qry = $db->prepare($clean);
		$qry->execute();
	}
	private function cp_order($db){
		$clean = "ALTER TABLE CPdescarga MODIFY COLUMN d_asenta VARCHAR(255) FIRST;";
		$qry = $db->prepare($clean);
		$qry->execute();
	}
	private function cp_clean($db){
		$clean = "
			alter table CPdescarga
			drop c_CP,
			drop c_estado,
			drop c_oficina,
			drop d_CP,
			drop c_tipo_asenta,
			drop c_mnpio,
			drop id_asenta_cpcons,
			drop c_cve_ciudad;
		";
		$qry = $db->prepare($clean);
		$qry->execute();
	}
	private function cp_cp($db){
		$primera = "
			SELECT
				CPdescarga.d_codigo as cp
			FROM
				CPdescarga
			GROUP BY
				CPdescarga.d_codigo
			ORDER BY
				CPdescarga.d_codigo ASC
		";
		$primera_q = $db->prepare($primera);
		$primera_q->execute();
		$primera_data = $primera_q->fetchAll();
		foreach ($primera_data as $row) {
			$segunda = "
				INSERT INTO `cp_cp` (
					`cp`
				)
				VALUES
					(
						'".$row->cp."'
					);
			";
			$segunda_q = $db->prepare($segunda);
			$segunda_q->execute();
			$lastInsertId = $db->lastInsertId();

			$tercera = "UPDATE CPdescarga SET d_codigo = ".$lastInsertId." WHERE d_codigo = '".$row->cp."'";
			$tercera_q = $db->prepare($tercera);
			$tercera_q->execute();
			echo "import ".$row->cp."<br>";
		}
	}

	private function cp_municipios($db){
		$primera = "
			SELECT
				CPdescarga.D_mnpio as municipio
			FROM
				CPdescarga
			GROUP BY
				CPdescarga.D_mnpio
			ORDER BY
				CPdescarga.D_mnpio ASC
		";
		$primera_q = $db->prepare($primera);
		$primera_q->execute();
		$primera_data = $primera_q->fetchAll();
		foreach ($primera_data as $row) {
			$segunda = "
				INSERT INTO `cp_mu` (
					`municipio`
				)
				VALUES
					(
						'".$row->municipio."'
					);
			";
			$segunda_q = $db->prepare($segunda);
			$segunda_q->execute();
			$lastInsertId = $db->lastInsertId();

			$tercera = "UPDATE CPdescarga SET D_mnpio = ".$lastInsertId." WHERE D_mnpio = '".$row->municipio."'";
			$tercera_q = $db->prepare($tercera);
			$tercera_q->execute();
			echo "import ".$row->municipio."<br>";
		}
	}

	private function cp_ciudades($db){
		$primera = "
			SELECT
				CPdescarga.d_ciudad as ciudad
			FROM
				CPdescarga
			GROUP BY
				CPdescarga.d_ciudad
			ORDER BY
				CPdescarga.d_ciudad ASC
		";
		$primera_q = $db->prepare($primera);
		$primera_q->execute();
		$primera_data = $primera_q->fetchAll();
		foreach ($primera_data as $row) {
			$segunda = "
				INSERT INTO `cp_ci` (
					`ciudad`
				)
				VALUES
					(
						'".$row->ciudad."'
					);
			";
			$segunda_q = $db->prepare($segunda);
			$segunda_q->execute();
			$lastInsertId = $db->lastInsertId();

			$tercera = "UPDATE CPdescarga SET d_ciudad = ".$lastInsertId." WHERE d_ciudad = '".$row->ciudad."'";
			$tercera_q = $db->prepare($tercera);
			$tercera_q->execute();
			echo "import ".$row->ciudad."<br>";
		}
	}
	private function cp_tipo_asent($db){
		$primera = "
			SELECT
				CPdescarga.d_tipo_asenta as tipo_asentamiento
			FROM
				CPdescarga
			GROUP BY
				CPdescarga.d_tipo_asenta
			ORDER BY
				CPdescarga.d_tipo_asenta ASC
		";
		$primera_q = $db->prepare($primera);
		$primera_q->execute();
		$primera_data = $primera_q->fetchAll();
		foreach ($primera_data as $row) {
			$segunda = "
				INSERT INTO `cp_ta` (
					`tipo_asentamiento`
				)
				VALUES
					(
						'".$row->tipo_asentamiento."'
					);
			";
			$segunda_q = $db->prepare($segunda);
			$segunda_q->execute();
			$lastInsertId = $db->lastInsertId();

			$tercera = "UPDATE CPdescarga SET d_tipo_asenta = ".$lastInsertId." WHERE d_tipo_asenta = '".$row->tipo_asentamiento."'";
			$tercera_q = $db->prepare($tercera);
			$tercera_q->execute();
			echo "import ".$row->tipo_asentamiento."<br>";
		}
	}
	private function cp_estados($db){
		$primera = "
			SELECT
				CPdescarga.d_estado as estado
			FROM
				CPdescarga
			GROUP BY
				CPdescarga.d_estado
			ORDER BY
				CPdescarga.d_estado ASC
		";
		$primera_q = $db->prepare($primera);
		$primera_q->execute();
		$primera_data = $primera_q->fetchAll();
		foreach ($primera_data as $row) {
			$segunda = "
				INSERT INTO `cp_ed` (
					`estado`
				)
				VALUES
					(
						'".$row->estado."'
					);
			";
			$segunda_q = $db->prepare($segunda);
			$segunda_q->execute();
			$lastInsertId = $db->lastInsertId();

			$tercera = "UPDATE CPdescarga SET d_estado = ".$lastInsertId." WHERE d_estado = '".$row->estado."'";
			$tercera_q = $db->prepare($tercera);
			$tercera_q->execute();
			echo "import ".$row->estado."<br>";
		}
	}
	private function cp_zonas($db){
		$primera = "
			SELECT
				CPdescarga.d_zona as zona
			FROM
				CPdescarga
			GROUP BY
				CPdescarga.d_zona
			ORDER BY
				CPdescarga.d_zona ASC
		";
		$primera_q = $db->prepare($primera);
		$primera_q->execute();
		$primera_data = $primera_q->fetchAll();
		foreach ($primera_data as $row) {
			$segunda = "
				INSERT INTO `cp_zn` (
					`zona`
				)
				VALUES
					(
						'".$row->zona."'
					);
			";
			$segunda_q = $db->prepare($segunda);
			$segunda_q->execute();
			$lastInsertId = $db->lastInsertId();

			$tercera = "UPDATE CPdescarga SET d_zona = ".$lastInsertId." WHERE d_zona = '".$row->zona."'";
			$tercera_q = $db->prepare($tercera);
			$tercera_q->execute();
			echo "import ".$row->zona."<br>";
		}
	}
	function mail(){
		$datamail = array();
		$datamail['destinatarios'] = array(
			'manuelaguado@gmail.com'
		);
		$datamail['plantilla'] 	= 'basica';
		$datamail['subject'] 	= 'Informe';
		$datamail['body'] 		= array(
			'fecha'			=>	'México D.F. a 16 de Noviembre de 2015',
			'asunto'		=>	'Se le informa la finalización del plan',
			'firma'			=>	'Ing Pocoyó',
			'hospital'		=>	'Belisario Domíguez'
		);
		$this->sendMail($datamail);
	}
	function numeq(){
		$conn = Controlador::direct_connectivity();
		for($i = 1; $i <= 100; $i++){
			$sql = "INSERT INTO cr_numeq (num,eq_status) VALUES (".$i.",'6')";
			$query = $conn->prepare($sql);
			$query->execute();
			echo $i.'-6<br>';
		}

	}
	public function mismodia(){
		$date = '2016-07-26 03:44:18';
		$ahora = date("Y-m-d H:i:s");
		$date_mes = substr($date,0,7);
		$ahora_mes = substr($ahora,0,7);
		$date_año = substr($date,0,4);
		$ahora_año = substr($ahora,0,4);
		echo $date_mes.'<br>';
		echo $ahora_mes.'<br>';
		echo $date_año.'<br>';
		echo $ahora_año.'<br>';
		if($date_año == $ahora_año){echo 'igual<br>';}else{echo 'diferente<br>';}
	}
	public function espera(){
		for($i=0;$i<=5;$i++){
			D::bug( date('h:i:s'));
			//sleep(2);
		}
	}
	public function decode(){
		$ride = array(
			'id_operador_unidad' 	=> '305',
			'id_episodio' 			=> '306',
			'serie' 				=> '307',
			'token' 				=> '308',
			'id_viaje' 				=> '309'
		);
		$entry = json_encode($ride);
		$entryData = json_decode($entry, true);

		echo '<pre>';
		print_r($ride);
		echo '<br><br><br>';
		print_r($entry);
		echo '<br><br><br>';
		print_r($entryData);
		echo '</pre>';
	}
	public function times(){
		$ahora = date("Y-m-d H:i:s");
		$init = '2016-05-02 03:38:20';
		$res = strtotime($ahora) - strtotime($init);
		echo ($res/60).'<br>';
		if($res > 8){
			echo 'mayor';
		}else{
			echo 'menor';
		}
	}
	public function inarray(){
		$clave['etiqueta'] = 'OK';
		$no_emitir = array('F20','SIGNED');
		if(!in_array($clave['etiqueta'], $no_emitir)){
			echo 'INGRESO, '.$clave['etiqueta'];
		}else{
			echo 'FUERA, '.$clave['etiqueta'];
		}
	}

	public function crear_perfiles(){
		$db = Controlador::direct_connectivity();

		$qry = 'select * from fw_usuarios';
		$usr = $db->prepare($qry);
		$usr->execute();
		if($usr->rowCount()>=1){
			$data = $usr->fetchAll();
			foreach ($data as $row) {

				$sql="SELECT * FROM fw_usuarios_config where id_usuario = '".$row->id_usuario."'";
				$total = $db->prepare($sql);
				$total->execute();
				if($total->rowCount()<1){

							$sql = "
								INSERT INTO fw_usuarios_config (
									id_usuario,
									user_alta,
									fecha_alta
								)
								VALUES
								(
									:id_usuario,
									:user_alta,
									:fecha_alta
								);
							";
							$query = $db->prepare($sql);
							$query_resp = $query->execute(array(
								':id_usuario' => $row->id_usuario,
								':user_alta' => $row->id_usuario,
								':fecha_alta' => date("Y-m-d H:i:s")
							));
							error_log('tt');

				}
			}
		}
	}
	public function difechas(){
		$init = '2016-12-16 09:27:02';
		$end  = '2016-12-22 10:27:06';
		$datetime1 = new DateTime($init);
		$datetime2 = new DateTime($end);
		$dteDiff = $datetime1->diff($datetime2);
		echo $dteDiff->format("0000-%M-%D %H:%I:%S");

	}
	public function difEnSegundos(){
		$fechaInicial = '2013-04-11 00:00:00';
		$fechaFinal = 	'2014-04-11 00:35:50';
		$segundos = strtotime($fechaFinal) - strtotime($fechaInicial);
		print round($segundos/60);
	}
	function turno($id_operador_unidad){
		$db = Controlador::direct_connectivity();
		$qry = "
			SELECT
				id_cordon,
				id_operador_unidad,
				cat_statuscordon
			FROM
				cr_cordon
			WHERE
				cr_cordon.id_base = 1
			AND (
				cr_cordon.cat_statuscordon = 113
				OR cr_cordon.cat_statuscordon = 114
			)
			ORDER BY
				cr_cordon.cat_statuscordon DESC,
				cr_cordon.id_cordon ASC
		";
		$query = $db->prepare($qry);
		$query->execute();
		if($query->rowCount()>=1){
			$data = $query->fetchAll();
			$count = 1;
			$numero = 0;
			foreach ($data as $row) {
				if($row->id_operador_unidad == $id_operador_unidad){
					$numero = $count;
				}
				$count++;
			}
		}
		if($numero != 0){
			echo $numero;
		}else{
			echo 'No formado';
		}
	}
	public function session(){
		$db = Controlador::direct_connectivity();
		$sql = "
			SELECT
				crou.id_operador,
				crou.id_operador_unidad
			FROM
				cr_operador_unidad AS crou
			INNER JOIN cr_operador AS cro ON crou.id_operador = cro.id_operador
			INNER JOIN fw_usuarios AS fwu ON cro.id_usuario = fwu.id_usuario
			WHERE
				fwu.id_usuario = 55
		";
		$query = $db->prepare($sql);
		$query->execute();
		$result = $query->fetchAll();
		$array = array();
		if($query->rowCount()>=1){
			foreach ($result as $num => $row) {
				echo $row->id_operador.'<br>';
				echo $row->id_operador_unidad;
			}
		}
	}
	public function setlocale(){

		echo "<meta charset='utf-8'>";

		$miFecha= gmmktime(12,0,0,1,15,2089);
		echo 'Antes de setlocale strftime devuelve: '.strftime("%A, %d de %B de %Y", $miFecha).'<br/>';
		echo 'Antes de setlocale date devuelve: '.date("l, d-m-Y (H:i:s)", $miFecha).'<br/>';
		setlocale(LC_TIME,"es_ES");
		echo 'Después de setlocale es_ES date devuelve: '.date("l, d-m-Y (H:i:s)", $miFecha).'<br/>';
		echo 'Después de setlocale es_ES strftime devuelve: '.strftime("%A, %d de %B de %Y", $miFecha).'<br/>';
		setlocale(LC_TIME, 'es_ES.UTF-8');
		echo 'Después de setlocale es_ES.UTF-8 date devuelve: '.date("l, d-m-Y (H:i:s)", $miFecha).'<br/>';
		echo 'Después de setlocale es_ES.UTF-8 strftime devuelve: '.strftime("%A, %d de %B de %Y", $miFecha).'<br/>';
		setlocale(LC_TIME, 'de_DE.UTF-8');
		echo 'Después de setlocale de_DE.UTF-8 date devuelve: '.date("l, d-m-Y (H:i:s)", $miFecha).'<br/>';
		echo 'Después de setlocale de_DE.UTF-8 strftime devuelve: '.strftime("%A, %d de %B de %Y", $miFecha).'<br/>';

	}
	public function geocerca(){
		require_once( '../vendor/point-in-polygon.php' );
		$geoCerca = new geoCerca();
		$puntos = array("19.434516, -99.212599");
		$poligono = array("-99.2210591 19.431014500000003","-99.2207265 19.4292009","-99.2206058 19.4290213","-99.2193237 19.4292389","-99.2178913 19.4296283","-99.2173121 19.4300078","-99.2157322 19.4309323","-99.2147103 19.4317911","-99.2134952 19.4327485","-99.2126101 19.4329318","-99.2111899 19.4331177","-99.2100379 19.4332961","-99.2103302 19.433541399999996","-99.2114393 19.433323900000005","-99.2125645 19.4331721","-99.2127724 19.4333391","-99.2129776 19.4334908","-99.2128274 19.433931","-99.2127577 19.434398899999998","-99.2128314 19.435025500000002","-99.2127201 19.435083","-99.212246 19.4351444","-99.212249 19.4349133","-99.212468 19.434003","-99.2119892 19.4338311","-99.211723 19.434699899999995","-99.2119743 19.4349291","-99.21229 19.435304999999996","-99.2123661 19.435330999999998","-99.2126598 19.4353202","-99.2129373 19.4352488","-99.21322700000002 19.4349042","-99.2134416 19.434698600000004","-99.2137581 19.434505200000004","-99.2157617 19.4337261","-99.2159629 19.433571799999996","-99.2170518 19.4331368","-99.2169687 19.432731999999998","-99.2171548 19.4326905","-99.2177513 19.432477","-99.2184131 19.4322017","-99.218975 19.4320712","-99.21940150000002 19.431815100000005","-99.2198628 19.431591200000003","-99.2210913 19.4310929","-99.2210591 19.431014500000003");
		// el primer y ultimo punto de la geocerca deben de ser iguales para cerrar el poligono
		foreach($puntos as $key => $punto) {
			echo "punto " . ($key+1) . " ($punto): " . $geoCerca->puntoEnPoligono($punto, $poligono) . "<br>";
		}
	}
	public function geocerca2($punto){
		require_once( '../vendor/point-in-polygon.php' );
		$geoCerca = new geoCerca();
		$poligono = array("-99.2210591 19.431014500000003","-99.2207265 19.4292009","-99.2206058 19.4290213","-99.2193237 19.4292389","-99.2178913 19.4296283","-99.2173121 19.4300078","-99.2157322 19.4309323","-99.2147103 19.4317911","-99.2134952 19.4327485","-99.2126101 19.4329318","-99.2111899 19.4331177","-99.2100379 19.4332961","-99.2103302 19.433541399999996","-99.2114393 19.433323900000005","-99.2125645 19.4331721","-99.2127724 19.4333391","-99.2129776 19.4334908","-99.2128274 19.433931","-99.2127577 19.434398899999998","-99.2128314 19.435025500000002","-99.2127201 19.435083","-99.212246 19.4351444","-99.212249 19.4349133","-99.212468 19.434003","-99.2119892 19.4338311","-99.211723 19.434699899999995","-99.2119743 19.4349291","-99.21229 19.435304999999996","-99.2123661 19.435330999999998","-99.2126598 19.4353202","-99.2129373 19.4352488","-99.21322700000002 19.4349042","-99.2134416 19.434698600000004","-99.2137581 19.434505200000004","-99.2157617 19.4337261","-99.2159629 19.433571799999996","-99.2170518 19.4331368","-99.2169687 19.432731999999998","-99.2171548 19.4326905","-99.2177513 19.432477","-99.2184131 19.4322017","-99.218975 19.4320712","-99.21940150000002 19.431815100000005","-99.2198628 19.431591200000003","-99.2210913 19.4310929","-99.2210591 19.431014500000003");
		// el primer y ultimo punto de la geocerca deben de ser iguales para cerrar el poligono
		echo "punto ($punto): " . $geoCerca->puntoEnPoligono($punto, $poligono);
	}
	public function tiempo(){
		$time=time();
		$horas = +0;
		$time += ($horas * 60 * 60);
		$time = date("Y-m-d H:i:s", $time );
		echo $time;
		echo '<br><br>';

		$mañana = mktime(date("H"),  date("i")-5, 0,  date("m")  , date("d"), date("Y"));
		echo date("Y-m-d H:i:s", $mañana );
		echo '<br><br>';

		echo $time;
		echo '<br><br>';

		$las_cero_de_hoy = mktime(0,  0,  0, date("m")  , date("d"), date("Y"));
		echo date("Y-m-d H:i:s", $las_cero_de_hoy );
		echo '<br><br>';


		echo '>>>>'.date("Y-m-d H:i:s", time());
	}
	public function getAutoCatalog(){
		$url = 'http://www.autocosmos.com.mx/clasificados/getmarcas';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$marcas=curl_exec($ch);
		$marcas = json_decode($marcas);
		self::insertMarcas($marcas);
	}
	private function insertMarcas($marcas){
		$conn = Controlador::direct_connectivity();
		foreach($marcas as $marca){
			$sql = "INSERT INTO AAAAMarca (marca, slug) VALUES (:marca, :slug)";
			$query = $conn->prepare($sql);
			$result = $query->execute(array(':marca' => $marca->Text, ':slug' => self::slug($marca->Text)));
			$id_marca = $conn->lastInsertId();
			echo $marca->Text.'Init....<br>';
			$url = "http://www.autocosmos.com.mx/clasificados/getmodelos/".$marca->Value."";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL,$url);
			$modelos=curl_exec($ch);
			$modelos = json_decode($modelos);
			foreach($modelos as $modelo){
				$sql = "INSERT INTO AAAAModelo (id_marca, modelo, slug) VALUES (:id_marca, :modelo, :slug)";
				$query = $conn->prepare($sql);
				$query->execute(array(':id_marca' => $id_marca, ':modelo' => $modelo->Text, ':slug' => self::slug($modelo->Text)));
			}
			echo $marca->Text.'Finish....<br>';
		}
	}
	static public function slug($text)
	{
	  $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
	  $text = trim($text, '-');
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	  $text = strtolower($text);
	  $text = preg_replace('~[^-\w]+~', '', $text);
	  if (empty($text))
	  {
		return 'n-a';
	  }
	  return $text;
	}
	public function generar_numeros_economicos($cantidad){
		for($i=1;$i<=$cantidad;$i++){
			$sql = "
				INSERT INTO cr_numeq (
					num,
					eq_status,
					user_alta,
					fecha_alta
				) VALUES (
					:num,
					:eq_status,
					:user_alta,
					:fecha_alta
				)";
			$query = $this->db->prepare($sql);
			$query_resp = $query->execute(
				array(
					':num' => $i,
					':eq_status' => '6',
					':user_alta' => $_SESSION['id_usuario'],
					':fecha_alta' => date("Y-m-d H:i:s")
				)
			);
			echo 'Generando: '.$i.'<br>';
		}
	}
	public function distanciaEntrePuntos($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'Mi') {
		$theta = $longitude1 - $longitude2;
		$distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
		$distance = acos($distance);
		$distance = rad2deg($distance);
		$distance = $distance * 60 * 1.1515;
		switch($unit) {
			case 'Mi':
			break;

			case 'Km' :
			$distance = $distance * 1.609344;
		}
		return (round($distance,2));
	}
}
class EncodedPolylineAlgorithm
{
	protected static $precision = 5;
	final public static function encode( $points )
	{
		$points = self::flatten($points);
		$encodedString = '';
		$index = 0;
		$previous = array(0,0);
		foreach ( $points as $number ) {
			$number = (float)($number);
			$number = (int)round($number * pow(10, static::$precision));
			$diff = $number - $previous[$index % 2];
			$previous[$index % 2] = $number;
			$number = $diff;
			$index++;
			$number = ($number < 0) ? ~($number << 1) : ($number << 1);
			$chunk = '';
			while ( $number >= 0x20 ) {
				$chunk .= chr((0x20 | ($number & 0x1f)) + 63);
				$number >>= 5;
			}
			$chunk .= chr($number + 63);
			$encodedString .= $chunk;
		}
		return $encodedString;
	}
	final public static function decode( $string )
	{
		$points = array();
		$index = $i = 0;
		$previous = array(0,0);
		while ($i < strlen($string)) {
			$shift = $result = 0x00;
			do {
				$bit = ord(substr($string, $i++)) - 63;
				$result |= ($bit & 0x1f) << $shift;
				$shift += 5;
			} while ($bit >= 0x20);
			$diff = ($result & 1) ? ~($result >> 1) : ($result >> 1);
			$number = $previous[$index % 2] + $diff;
			$previous[$index % 2] = $number;
			$index++;
			$points[] = $number * 1 / pow(10, static::$precision);
		}
		return $points;
	}
	final public static function flatten( $array )
	{
		$flatten = array();
		array_walk_recursive(
			$array,
			function ($current) use (&$flatten) {
				$flatten[] = $current;
			}
		);
		return $flatten;
	}
	final public static function pair( $list )
	{
		return is_array($list) ? array_chunk($list, 2) : array();
	}
}
?>
