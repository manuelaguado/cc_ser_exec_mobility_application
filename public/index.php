<?php
ini_set("session.cookie_lifetime","0");
ini_set("session.gc_maxlifetime","86400");
session_start();
require '../libs/autoload.php';
require '../libs/config.php';
require '../libs/aplicacion.php';
require '../libs/controlador.php';
$app = new Aplicacion();
