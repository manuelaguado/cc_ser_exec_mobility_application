<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<?php
class Admision_busqueda extends Extensions
{
	public $menu = array();
    function __construct()
    {
		// el primer arreglo define la extension, 
		// el segundo la clase y los siguientes los metodos
		// metodo , Nombre, Icono
        $this->menu = array(
			array('admision','Admision','icon-sign-in'),
			array('Admision_busqueda','Operaciones',''),
			array('index','Buscar','icon-search'),
		);
    }
    public function index()
    {
		$this->autorizacion_requerida('admision|Admision_busqueda|index');
        require URL_NAME_EXTENSION . '/vista/busqueda/index.php';
    }

}