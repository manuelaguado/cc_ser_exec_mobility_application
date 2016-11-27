<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<?php
class Desarrollador extends Controlador
{
    public function index()
    {	
		$this->se_requiere_logueo(false);
		include (URL_TEMPLATE.'404_full.php');
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
			'hospital'		=>	'Belisario Domínguez'
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
}
?>