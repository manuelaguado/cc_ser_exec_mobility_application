-- Cambios para produccion
set FOREIGN_KEY_CHECKS=0;

ALTER TABLE `centralcar`.`cl_tarifas_clientes` ADD COLUMN `tabulado` int(1) NULL AFTER `cat_tipo_tarifa`;

set FOREIGN_KEY_CHECKS=1;



-- Cambios para cloud9
set FOREIGN_KEY_CHECKS=0;

ALTER TABLE `centralcar`.`cl_tarifas_clientes` ADD COLUMN `tabulado` int(1) NULL AFTER `cat_tipo_tarifa`;

set FOREIGN_KEY_CHECKS=1;