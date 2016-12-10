-- Cambios para produccion
set FOREIGN_KEY_CHECKS=0;
ALTER TABLE `centralcar`.`vi_viaje_detalle` ADD COLUMN `apartado` int(1) NULL DEFAULT 0 AFTER `redondo`;
INSERT INTO `centralcar`.`cm_catalogo` (`id_cat`, `id_padre`, `catalogo`, `etiqueta`, `activo`, `orden`, `valor`, `user_alta`, `user_mod`, `fecha_alta`, `fecha_mod`) VALUES ('195', NULL, 'status_viaje', 'Apartado', '1', '7', NULL, '1', '1', '2016-12-09 02:27:46', '2016-12-09 02:27:58');
UPDATE `centralcar`.`cm_catalogo` SET `id_cat`='50', `id_padre`=NULL, `catalogo`='clavesitio', `etiqueta`='A15', `activo`='1', `orden`='15', `valor`='Servicio por km', `user_alta`='0', `user_mod`='0', `fecha_alta`='0000-00-00 00:00:00', `fecha_mod`='2016-12-10 00:40:24' WHERE (`id_cat`='50');
UPDATE `centralcar`.`cm_catalogo` SET `id_cat`='51', `id_padre`=NULL, `catalogo`='clavesitio', `etiqueta`='A16', `activo`='1', `orden`='16', `valor`='Servicio tabulado', `user_alta`='0', `user_mod`='0', `fecha_alta`='0000-00-00 00:00:00', `fecha_mod`='2016-12-10 00:40:26' WHERE (`id_cat`='51');


set FOREIGN_KEY_CHECKS=1;


-- Cambios para cloud9
set FOREIGN_KEY_CHECKS=0;
UPDATE `centralcar`.`cm_catalogo` SET `id_cat`='50', `id_padre`=NULL, `catalogo`='clavesitio', `etiqueta`='A15', `activo`='1', `orden`='15', `valor`='Servicio por km', `user_alta`='0', `user_mod`='0', `fecha_alta`='0000-00-00 00:00:00', `fecha_mod`='2016-12-10 00:40:24' WHERE (`id_cat`='50');
UPDATE `centralcar`.`cm_catalogo` SET `id_cat`='51', `id_padre`=NULL, `catalogo`='clavesitio', `etiqueta`='A16', `activo`='1', `orden`='16', `valor`='Servicio tabulado', `user_alta`='0', `user_mod`='0', `fecha_alta`='0000-00-00 00:00:00', `fecha_mod`='2016-12-10 00:40:26' WHERE (`id_cat`='51');

set FOREIGN_KEY_CHECKS=1;