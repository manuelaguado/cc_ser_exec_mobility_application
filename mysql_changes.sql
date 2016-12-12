-- Cambios para produccion
set FOREIGN_KEY_CHECKS=0;

set FOREIGN_KEY_CHECKS=1;


-- Cambios para cloud9
set FOREIGN_KEY_CHECKS=0;
INSERT INTO `centralcar`.`fw_metodos` (`id_metodo`, `controlador`, `metodo`, `nombre`, `descripcion`, `user_alta`, `user_mod`, `fecha_alta`, `fecha_mod`) VALUES ('143', 'Clientes', 'showtarifas', 'Mostrar Tarifas', 'Muestra una lista con todas las tarifas existentes de todos los clientes, sin acciones', '1', NULL, '2016-12-11 22:38:38', '2016-12-11 22:38:38');

set FOREIGN_KEY_CHECKS=1;