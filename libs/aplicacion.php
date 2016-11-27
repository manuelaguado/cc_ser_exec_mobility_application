<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<?php
class Aplicacion
{
    private $url_controladores = null;
    private $url_metodo = null;
    private $parametro_url_1 = null;
    private $parametro_url_2 = null;
    private $parametro_url_3 = null;
	private $parametro_url_4 = null;
	private $parametro_url_5 = null;
	public function __construct()
	{
		$this->mapearUrl();
		if (file_exists(URL_CONTROLADOR . $this->url_controladores . '.php')) {
			if($this->url_controladores == 'extensions'){
				require URL_CONTROLADOR . $this->url_controladores . '.php';
				$this->url_controladores = new $this->url_controladores();
				$this->url_controladores->init($_GET['url']);
			}else{
				require URL_CONTROLADOR . $this->url_controladores . '.php';
				$this->url_controladores = new $this->url_controladores();

				if (method_exists($this->url_controladores, $this->url_metodo)) {
					 if (isset($this->parametro_url_5)) {
						$this->url_controladores->{$this->url_metodo}($this->parametro_url_1, $this->parametro_url_2, $this->parametro_url_3, $this->parametro_url_4, $this->parametro_url_5);
					} elseif (isset($this->parametro_url_4)) {
						$this->url_controladores->{$this->url_metodo}($this->parametro_url_1, $this->parametro_url_2, $this->parametro_url_3, $this->parametro_url_4);
					} elseif (isset($this->parametro_url_3)) {
						$this->url_controladores->{$this->url_metodo}($this->parametro_url_1, $this->parametro_url_2, $this->parametro_url_3);
					} elseif (isset($this->parametro_url_2)) {
						$this->url_controladores->{$this->url_metodo}($this->parametro_url_1, $this->parametro_url_2);
					} elseif (isset($this->parametro_url_1)) {
						$this->url_controladores->{$this->url_metodo}($this->parametro_url_1);
					} else {
						$this->url_controladores->{$this->url_metodo}();
					}
				} else {
					if(!$this->url_metodo){
						$this->url_controladores->index();
					}else{
						require URL_TEMPLATE.'404_full.php';
					}
				}
			}
		} else {
			if(!$this->url_controladores){
				require URL_CONTROLADOR.'inicio.php';
				$home = new Inicio();
				$home->index();
			}else{
				require URL_TEMPLATE.'404_full.php';
			}
		}
	}
    private function mapearUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $this->url_controladores= (isset($url[0]) ? $url[0] : null);
            $this->url_metodo 		= (isset($url[1]) ? $url[1] : null);
            $this->parametro_url_1 	= (isset($url[2]) ? $url[2] : null);
            $this->parametro_url_2 	= (isset($url[3]) ? $url[3] : null);
            $this->parametro_url_3 	= (isset($url[4]) ? $url[4] : null);
			$this->parametro_url_4 	= (isset($url[5]) ? $url[5] : null);
			$this->parametro_url_5 	= (isset($url[6]) ? $url[6] : null);
        }
    }
}
?>