-- Cambios para produccion
set FOREIGN_KEY_CHECKS=0;

set FOREIGN_KEY_CHECKS=1;


-- Cambios para cloud9
set FOREIGN_KEY_CHECKS=0;
INSERT INTO `centralcar`.`fw_metodos` (`id_metodo`, `controlador`, `metodo`, `nombre`, `descripcion`, `user_alta`, `user_mod`, `fecha_alta`, `fecha_mod`) VALUES ('143', 'Clientes', 'showtarifas', 'Mostrar Tarifas', 'Muestra una lista con todas las tarifas existentes de todos los clientes, sin acciones', '1', NULL, '2016-12-11 22:38:38', '2016-12-11 22:38:38');
UPDATE `centralcar`.`fw_config` SET `id_config`='6', `id_site`='1', `descripcion`='status_abandonados', `valor`='188', `tmp_val`='0', `data`='cfcd208495d565ef66e7dff9f98764da', `user_alta`='1', `user_mod`='1', `fecha_alta`=NULL, `fecha_mod`='2016-12-12 20:54:13' WHERE (`id_config`='6');
INSERT INTO `centralcar`.`fw_config` (`id_config`, `id_site`, `descripcion`, `valor`, `tmp_val`, `data`, `user_alta`, `user_mod`, `fecha_alta`, `fecha_mod`) VALUES ('7', '1', 'websockets_control', '1', '0', NULL, '1', '1', '2016-12-12 20:55:10', '2016-12-12 20:55:15');
INSERT INTO `centralcar`.`fw_metodos` (`id_metodo`, `controlador`, `metodo`, `nombre`, `descripcion`, `user_alta`, `user_mod`, `fecha_alta`, `fecha_mod`) VALUES ('144', 'Mobile', 'websockets_control', 'Control de Websockets', 'Permite activar las tareas cronometradas para ahorrar el recurso de websockets, afecta todos los trabajos de cronjob', '1', NULL, '2016-12-12 21:10:18', '2016-12-12 21:10:18');

set FOREIGN_KEY_CHECKS=1;