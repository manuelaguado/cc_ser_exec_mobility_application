-- Cambios para produccion

SET FOREIGN_KEY_CHECKS = 0;
INSERT INTO `centralcar`.`cm_catalogo` (`id_cat`, `id_padre`, `catalogo`, `etiqueta`, `activo`, `orden`, `valor`, `user_alta`, `user_mod`, `fecha_alta`, `fecha_mod`) VALUES ('192', NULL, 'costos_adicionales', 'Caseta', '1', '1', NULL, '1', '1', '2016-12-04 16:26:16', '2016-12-04 16:26:20');
INSERT INTO `centralcar`.`cm_catalogo` (`id_cat`, `id_padre`, `catalogo`, `etiqueta`, `activo`, `orden`, `valor`, `user_alta`, `user_mod`, `fecha_alta`, `fecha_mod`) VALUES ('193', NULL, 'costos_adicionales', 'Estacionamiento', '1', '2', NULL, '1', '1', '2016-12-04 16:26:16', '2016-12-04 16:26:25');
ALTER TABLE `centralcar`.`vi_costos_adicionales` MODIFY COLUMN `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `costo`;

SET FOREIGN_KEY_CHECKS = 1;

-- Cambios para cloud9

SET FOREIGN_KEY_CHECKS = 0;
INSERT INTO `centralcar`.`cm_catalogo` (`id_cat`, `id_padre`, `catalogo`, `etiqueta`, `activo`, `orden`, `valor`, `user_alta`, `user_mod`, `fecha_alta`, `fecha_mod`) VALUES ('192', NULL, 'costos_adicionales', 'Caseta', '1', '1', NULL, '1', '1', '2016-12-04 16:26:16', '2016-12-04 16:26:20');
INSERT INTO `centralcar`.`cm_catalogo` (`id_cat`, `id_padre`, `catalogo`, `etiqueta`, `activo`, `orden`, `valor`, `user_alta`, `user_mod`, `fecha_alta`, `fecha_mod`) VALUES ('193', NULL, 'costos_adicionales', 'Estacionamiento', '1', '2', NULL, '1', '1', '2016-12-04 16:26:16', '2016-12-04 16:26:25');
ALTER TABLE `centralcar`.`vi_costos_adicionales` MODIFY COLUMN `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `costo`;

SET FOREIGN_KEY_CHECKS = 1;
