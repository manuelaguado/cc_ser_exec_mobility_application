<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<?php
class Tools extends Controlador
{
	function __construct(){
		if(DEVELOPMENT == false){exit();}
	}	
}
?>