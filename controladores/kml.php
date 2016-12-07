<?php
class Kml extends Controlador
{
    public function index()
    {
		$this->se_requiere_logueo(true,'Kml|index');
        require URL_VISTA.'kml/index.php';
    }
	public function modal_ruta($id_operador){
		$this->se_requiere_logueo(true,'Kml|genKml');
		$modelo_cell = $this->loadModel('Telefonia');
			$phone = $modelo_cell->getDatacell($id_operador);
			$serie = $phone['serie'];
			$file = self::genKml($serie);
		require URL_VISTA.'modales/kml/ruta.php';
	}
	public function path($file){
		$this->se_requiere_logueo(true,'Kml|genKml');
		require URL_VISTA.'kml/kml.php';

	}	
	public function genKml($serie){
		$this->se_requiere_logueo(true,'Kml|genKml');
		$kml = array('<?xml version=\'1.0\' encoding=\'UTF-8\'?>');
		$kml[] = ' <kml xmlns=\'http://www.opengis.net/kml/2.2\'>';
		$kml[] = ' <Document>';
		$kml[] = ' <name>'.$serie.'</name>';
		$kml[] = ' <description><![CDATA[]]></description>';
		$kml[] = ' <Folder>';
		$kml[] = ' <name>'.$serie.'</name>';
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
		
			$modelo_kml = $this->loadModel('Kml');
			$kml = $modelo_kml->getData($serie, $kml);
		
		// End XML file
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
}
?>