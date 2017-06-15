<?php
class SistemaModel
{
    function __construct($db,$dbt) {
        try {
            $this->db = $db;
			$this->dbt = $dbt;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexión a la base de datos.');
        }
    }
    function updateSettings($arreglo){
           foreach ($arreglo as $key => $value) {
                  $this->$key = strip_tags($value);
           }
           $this->db->exec("UPDATE fw_config SET valor = $this->costo_hora WHERE id_site = 1 and descripcion = 'costo_hora'");
           $this->db->exec("UPDATE fw_config SET valor = '".$this->tiempo_cortesia."' WHERE id_site = 1 and descripcion = 'tiempo_cortesia'");
           $this->db->exec("UPDATE fw_config SET valor = $this->km_perimetro WHERE id_site = 1 and descripcion = 'km_perimetro'");
           $this->db->exec("UPDATE fw_config SET valor = $this->km_cortesia WHERE id_site = 1 and descripcion = 'km_cortesia'");
           $this->db->exec("UPDATE fw_config SET valor = $this->comision_operadores WHERE id_site = 1 and descripcion = 'comision_operadores'");
    }
}
