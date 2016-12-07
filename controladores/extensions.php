<?php
class Extensions extends Controlador
{
    private $input_extensions = null;
    private $extension = null;
    private $controlador = null;
    private $metodo = null;
    private $param_extension_1 = null;
	private $param_extension_2 = null;
	private $param_extension_3 = null;
	private $param_extension_4 = null;
	private $param_extension_5 = null;
	private $url;
    protected function autorizacion_requerida($permisos){
		if(!in_array($permisos,$_SESSION['permisos_acl'])){
			require URL_TEMPLATE.'restringido.php';
			exit();
		}
    }
	public function init($url)
	{
		$this->se_requiere_logueo(true,'Extensions|init');
		$this->url = $url;
		$this->mapearUrl($this->url);
		self::verificarEstructura();
		if (file_exists(URL_EXTENSIONS . $this->extension . '/controlador/' . $this->controlador . '.php')) {
			
			require URL_EXTENSIONS . $this->extension . '/controlador/' . $this->controlador . '.php';
			$this->controlador = new $this->controlador(null);
			
			define('URL_NAME_EXTENSION', URL_EXTENSIONS . $this->extension ,true);
			define('THIS_EXTENSION', URL_CONTROLLER_EXT . $this->extension ,true);
			
			if (method_exists($this->controlador, $this->metodo)) {
				if (isset($this->param_extension_5)) {
					$this->controlador->{$this->metodo}($this->param_extension_1, $this->param_extension_2, $this->param_extension_3, $this->param_extension_4, $this->param_extension_5);
				} elseif (isset($this->param_extension_4)) {
					$this->controlador->{$this->metodo}($this->param_extension_1, $this->param_extension_2, $this->param_extension_3, $this->param_extension_4);
				} elseif (isset($this->param_extension_3)) {
					$this->controlador->{$this->metodo}($this->param_extension_1, $this->param_extension_2, $this->param_extension_3);
				} elseif (isset($this->param_extension_2)) {
					$this->controlador->{$this->metodo}($this->param_extension_1, $this->param_extension_2);
				} elseif (isset($this->param_extension_1)) {
					$this->controlador->{$this->metodo}($this->param_extension_1);
				} else {
					$this->controlador->{$this->metodo}();
				}
			} else {
				if(!$this->metodo){
					$this->controlador->index();
				}else{
					require URL_TEMPLATE.'404_full.php';
				}
			}
			
		} else {
			if(!$this->controlador){
				require URL_CONTROLADOR.'inicio.php';
				$home = new inicio();
				$home->index();
			}else{
				require URL_TEMPLATE.'404_full.php';
			}
		}
		
	}
    private function verificarEstructura()
    {
		$valido = true;		
		if ((!file_exists(URL_EXTENSIONS . $this->extension))||(! $this->extension)){
				require URL_TEMPLATE.'404_full.php';
				exit();
		}
        if (!file_exists(URL_EXTENSIONS . $this->extension . '/controlador')){$valido = false;}
		if (!file_exists(URL_EXTENSIONS . $this->extension . '/modelo')){$valido = false;}
		if (!file_exists(URL_EXTENSIONS . $this->extension . '/vista')){$valido = false;}
		if (!file_exists(URL_EXTENSIONS . $this->extension . '/public')){$valido = false;}
		if(!$valido){echo "La estructura de la extensi&oacute;n no es correcta, verifiquela antes de continuar"; exit();}
    }
    private function mapearUrl($url)
    {
        if (isset($url)) {
            $url = rtrim($url, '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $this->input_extensions = (isset($url[0]) ? $url[0] : null);
            $this->extension 		= (isset($url[1]) ? $url[1] : null);
            $this->controlador 		= (isset($url[2]) ? $url[2] : null);
            $this->metodo 			= (isset($url[3]) ? $url[3] : null);
            $this->param_extension_1= (isset($url[4]) ? $url[4] : null);
			$this->param_extension_2= (isset($url[5]) ? $url[5] : null);
			$this->param_extension_3= (isset($url[6]) ? $url[6] : null);
			$this->param_extension_4= (isset($url[7]) ? $url[7] : null);
			$this->param_extension_5= (isset($url[8]) ? $url[8] : null);
        }
    }
    public function loadModel($nombre_del_modelo)
    {
        require URL_NAME_EXTENSION . '/modelo/' . strtolower($nombre_del_modelo) . '.php';
		$modelo = $nombre_del_modelo.'Model';
        return new $modelo($this->db);
    }
	public function metodosdeExtension($extension)
	{
		$menu_construct = array();
		$elemento = pathinfo($extension);
		$basename = $elemento['basename'];
		if(($basename != '.')&&($basename != '..'))
		{	
			if(!$file){
				$controladores = new RecursiveIteratorIterator($obj_Directory = new RecursiveDirectoryIterator(URL_EXTENSIONS . $basename . '/' . 'controlador/' ),RecursiveIteratorIterator::SELF_FIRST);
				$controladores->setMaxDepth(0);
				foreach ($controladores as $controlador) 
				{
					$elemento = pathinfo($controlador);
					$basename2 = $elemento['basename'];
					if(($basename2 != '.')&&($basename2 != '..'))
					{
						include_once (URL_CONTROLADOR . 'extensions.php');
						include_once (URL_EXTENSIONS . $basename . '/' . 'controlador/' . $basename2);
						
						$controller = explode('.',$basename2);
						$controller = ucfirst($controller[0]);
						$inst_cont = $controller;
						$inst_cont = new $inst_cont;
						$menues = $inst_cont->menu;
						$metodos_clase = array_diff(get_class_methods($inst_cont), get_class_methods(get_parent_class($inst_cont)));
						foreach ($metodos_clase as $nombre_metodo) {
							foreach($menues as $menu){
								if(in_array($nombre_metodo,$menu)){
									$menu_construct[] = $extension.'|'.$controller.'|'.$nombre_metodo;
								}
							}
						}
					}
				}
			}
		}
		return $menu_construct;
	}	
	public function metodosdeControlador($extension,$controlador)
	{
		$file_controller = $controlador.'.php';
		include_once (URL_CONTROLADOR . 'extensions.php');
		include_once (URL_EXTENSIONS . $extension . '/' . 'controlador/' . $file_controller);
		$controller = ucfirst($controlador);
		$inst_cont = $controller;
		$inst_cont = new $inst_cont;
		$menues = $inst_cont->menu;
		$metodos_clase = array_diff(get_class_methods($inst_cont), get_class_methods(get_parent_class($inst_cont)));
		foreach ($metodos_clase as $nombre_metodo) {
			foreach($menues as $menu){
				if(in_array($nombre_metodo,$menu)){
					$metodos[] = $extension.'|'.$controller.'|'.$nombre_metodo;
				}
			}
		}
		return $metodos;
	}	
	public function listarMetodos()
	{
		$extensiones = new RecursiveIteratorIterator($obj_Directory = new RecursiveDirectoryIterator(URL_EXTENSIONS),RecursiveIteratorIterator::SELF_FIRST);
		$extensiones->setMaxDepth(0);
		$menu_construct = array();
		$count = 0;
		foreach ($extensiones as $extension) 
		{
			$elemento = pathinfo($extension);
			$basename = $elemento['basename'];
			if(($basename != '.')&&($basename != '..'))
			{	
				if(!is_file($extension)){
					$controladores = new RecursiveIteratorIterator($obj_Directory = new RecursiveDirectoryIterator(URL_EXTENSIONS . $basename . '/' . 'controlador/' ),RecursiveIteratorIterator::SELF_FIRST);
					$controladores->setMaxDepth(0);
					$inc = 0;
					foreach ($controladores as $controlador) 
					{
						$elemento = pathinfo($controlador);
						$basename2 = $elemento['basename'];
						if(($basename2 != '.')&&($basename2 != '..'))
						{
							include_once (URL_CONTROLADOR . 'extensions.php');
							include_once (URL_EXTENSIONS . $basename . '/' . 'controlador/' . $basename2);
							
								$controller = explode('.',$basename2);
								$controller = ucfirst($controller[0]);
								
								$inst_cont = $controller;
								$inst_cont = new $inst_cont;
								$menues = $inst_cont->menu;
								$metodos_clase = array_diff(get_class_methods($inst_cont), get_class_methods(get_parent_class($inst_cont)));

								if($inc==0){$menu_construct[$count]['extension'] = [$basename,$menues[0][1],$menues[0][2]];}
								$menu_construct[$count][$inc]['controlador'] = [$controller,$menues[1][1],$menues[1][2]];

								foreach ($metodos_clase as $nombre_metodo) {
									foreach($menues as $menu){
										if(in_array($nombre_metodo,$menu)){
											$menu_construct[$count][$inc]['metodo'][] = [$nombre_metodo,$menu[1],$menu[2]];
										}
									}
								}
						$inc++;	
						}
					}
				}
			$count++;
			}
		}
		return $menu_construct;
	}
}
?>