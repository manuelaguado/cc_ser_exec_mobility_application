-- Cambios para produccion
set FOREIGN_KEY_CHECKS=0;
ALTER TABLE `centralcar`.`vi_viaje_detalle` ADD COLUMN `apartado` int(1) NULL DEFAULT 0 AFTER `redondo`;
INSERT INTO `centralcar`.`cm_catalogo` (`id_cat`, `id_padre`, `catalogo`, `etiqueta`, `activo`, `orden`, `valor`, `user_alta`, `user_mod`, `fecha_alta`, `fecha_mod`) VALUES ('195', NULL, 'status_viaje', 'Apartado', '1', '7', NULL, '1', '1', '2016-12-09 02:27:46', '2016-12-09 02:27:58');

set FOREIGN_KEY_CHECKS=1;


-- Cambios para cloud9
