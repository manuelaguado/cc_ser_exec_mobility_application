<?php
class Admision_busquedaModel
{
    function __construct($db) {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('No se ha podido establecer la conexi�n a la base de datos.');
        }
    }
}
?>