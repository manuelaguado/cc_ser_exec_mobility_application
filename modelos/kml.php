<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<?php
class KmlModel
{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
        }
    }
	function getData($serie,$kml){
		$sql="
			SELECT
				*
			FROM
				gps as GPS
			WHERE
				GPS.serie = '".$serie."'
			ORDER BY
				id_gps ASC
		";
		$query = $this->db->prepare($sql);
		$query->execute();
		$marks = $query->fetchAll();
		if($query->rowCount()>=1){
			foreach ($marks as $num => $row) {
			  $kml[] = $row->longitud . ','  . $row->latitud;
			}
		}
		return $kml;
	}
}
?>