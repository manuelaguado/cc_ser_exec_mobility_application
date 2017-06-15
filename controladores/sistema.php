<?php
class Sistema extends Controlador
{
    public function index()
    {
              $this->se_requiere_logueo(true,'Sistema|index');
              require URL_VISTA.'sistema/index.php';
    }
    public function updateSettings(){
              $this->se_requiere_logueo(true,'Sistema|index');
              $sistema = $this->loadModel('Sistema');
              $sistema->updateSettings($_POST);
              print json_encode(array('resp' => true , 'mensaje' => 'Actualizó las variables del sistema satisfactoriamente.' ));
    }
}
