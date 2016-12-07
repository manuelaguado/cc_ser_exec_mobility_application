<?php
class SidebarModel
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
	funciones para validar el menu en tiempo real (en deshuso)
	function accesoExtension($user,$extension){
		$user = intval($user);
		$sql="SELECT count(*) as permiso FROM fw_dac_acl where id_usuario = '".$user."' and tercio LIKE '".$extension."%'";
		$query = $this->db->prepare($sql);
		$query->execute();
		$permiso =  $query->fetchAll();
		return $permiso[0]->permiso > 0 ?  true :  false;
	}
	function accesoControlador($user,$par){
		$user = intval($user);
		$sql="SELECT count(*) as permiso FROM fw_dac_acl where id_usuario = '".$user."' and tercio LIKE '".$par."%'";
		$query = $this->db->prepare($sql);
		$query->execute();
		$permiso =  $query->fetchAll();
		return $permiso[0]->permiso > 0 ?  true :  false;
	}
	function accesoMetodo($user,$tercio){
		$user = intval($user);
		$sql="SELECT count(*) as permiso FROM fw_dac_acl where id_usuario = '".$user."' and tercio LIKE '".$tercio."'";
		$query = $this->db->prepare($sql);
		$query->execute();
		$permiso =  $query->fetchAll();
		return $permiso[0]->permiso > 0 ?  true :  false;
	}
	*/
	
}