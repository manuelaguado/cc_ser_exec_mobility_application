-- Cambios para produccion
set FOREIGN_KEY_CHECKS=0;

set FOREIGN_KEY_CHECKS=1;


-- Cambios para cloud9
set FOREIGN_KEY_CHECKS=0;
CREATE UNIQUE INDEX `username` ON `centralcar`.`fw_usuarios` (`usuario` ASC) USING BTREE;INSERT INTO `centralcar`.`fw_metodos` (`id_metodo`, `controlador`, `metodo`, `nombre`, `descripcion`, `user_alta`, `user_mod`, `fecha_alta`, `fecha_mod`) VALUES ('145', 'Usuarios', 'posesion', 'Tomar posesion', 'Toma posesiÃ³n de un usuario para tomar control de su cuenta y diagnosticar errores en la aplicaciÃ³n mÃ³vil ', '1', NULL, '2016-12-21 04:06:05', '2016-12-21 04:06:05');
INSERT INTO `centralcar`.`fw_metodos` (`id_metodo`, `controlador`, `metodo`, `nombre`, `descripcion`, `user_alta`, `user_mod`, `fecha_alta`, `fecha_mod`) VALUES ('145', 'Usuarios', 'posesion', 'Tomar posesion', 'Toma posesiÃ³n de un usuario para tomar control de su cuenta y diagnosticar errores en la aplicaciÃ³n mÃ³vil ', '1', NULL, '2016-12-21 04:06:05', '2016-12-21 04:06:05');

ALTER TABLE `centralcar`.`fw_usuarios_config` ADD COLUMN `poseido` int(1) NULL DEFAULT 0 AFTER `fecha_ingreso`;
ALTER TABLE `centralcar`.`fw_usuarios_config` ADD COLUMN `password` varchar(64) NULL AFTER `poseido`;

ALTER TABLE `centralcar`.`cr_tiempo_base` ADD COLUMN `id_operador_unidad` int(32) UNSIGNED NULL AFTER `id_operador`;
ALTER TABLE `centralcar`.`cr_tiempo_base` ADD CONSTRAINT `fk_cr_tiempo_base_cr_operador_unidad_1` FOREIGN KEY (`id_operador_unidad`) REFERENCES `centralcar`.`cr_operador_unidad` (`id_operador_unidad`);


set FOREIGN_KEY_CHECKS=1;