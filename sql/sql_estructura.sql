/*
Navicat MySQL Data Transfer

Source Server         : CentralCar Development
Source Server Version : 50716
Source Host           : localhost:3306
Source Database       : centralcar

Target Server Type    : MYSQL
Target Server Version : 50716
File Encoding         : 65001

Date: 2017-01-02 20:52:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cl_anticipo_aplicacion
-- ----------------------------
DROP TABLE IF EXISTS `cl_anticipo_aplicacion`;
CREATE TABLE `cl_anticipo_aplicacion` (
  `id_anticipo_aplicacion` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_anticipo` int(32) unsigned DEFAULT NULL,
  `id_pago` int(32) unsigned DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `id_factura` int(32) unsigned DEFAULT NULL,
  `parcial` int(1) DEFAULT NULL,
  `fecha_aplicacion` datetime DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_anticipo_aplicacion`),
  KEY `fk_anticipo_aplicacion_ae_facturas_1` (`id_factura`) USING BTREE,
  KEY `fk_anticipo_aplicacion_anticipos_1` (`id_anticipo`) USING BTREE,
  KEY `fk_anticipo_aplicacion_pagos_1` (`id_pago`) USING BTREE,
  CONSTRAINT `fk_anticipo_aplicacion_anticipos_1` FOREIGN KEY (`id_anticipo`) REFERENCES `cl_anticipos` (`id_anticipo`),
  CONSTRAINT `fk_anticipo_aplicacion_pagos_1` FOREIGN KEY (`id_pago`) REFERENCES `cl_pagos` (`id_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cl_anticipos
-- ----------------------------
DROP TABLE IF EXISTS `cl_anticipos`;
CREATE TABLE `cl_anticipos` (
  `id_anticipo` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int(32) unsigned DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `saldo` decimal(10,2) DEFAULT NULL,
  `fecha_ingreso` datetime DEFAULT NULL,
  `finalizado` int(1) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_anticipo`),
  KEY `fk_anticipos_ae_clientes_1` (`id_cliente`) USING BTREE,
  CONSTRAINT `fk_anticipos_ae_clientes_1` FOREIGN KEY (`id_cliente`) REFERENCES `cl_clientes` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cl_cargos_directos
-- ----------------------------
DROP TABLE IF EXISTS `cl_cargos_directos`;
CREATE TABLE `cl_cargos_directos` (
  `id_cargo_directo` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_factura` int(32) unsigned DEFAULT NULL,
  `cantidad` decimal(10,0) DEFAULT NULL,
  `unidad` varchar(255) DEFAULT NULL,
  `concepto` text,
  `costo_unitario` decimal(10,2) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cargo_directo`),
  KEY `fk_cl_cargos_directos_cl_facturas_1` (`id_factura`) USING BTREE,
  CONSTRAINT `fk_cl_cargos_directos_cl_facturas_1` FOREIGN KEY (`id_factura`) REFERENCES `cl_facturas` (`id_factura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cl_cliente_operador
-- ----------------------------
DROP TABLE IF EXISTS `cl_cliente_operador`;
CREATE TABLE `cl_cliente_operador` (
  `id_cliente_operador` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int(32) unsigned DEFAULT NULL,
  `id_operador` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cliente_operador`),
  KEY `fk_ae_cliente_operador_ae_clientes_1` (`id_cliente`) USING BTREE,
  KEY `fk_ae_cliente_operador_ae_operador_1` (`id_operador`) USING BTREE,
  CONSTRAINT `fk_ae_cliente_operador_ae_clientes_1` FOREIGN KEY (`id_cliente`) REFERENCES `cl_clientes` (`id_cliente`),
  CONSTRAINT `fk_ae_cliente_operador_ae_operador_1` FOREIGN KEY (`id_operador`) REFERENCES `cr_operador` (`id_operador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cl_clientes
-- ----------------------------
DROP TABLE IF EXISTS `cl_clientes`;
CREATE TABLE `cl_clientes` (
  `id_cliente` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(32) unsigned DEFAULT NULL,
  `id_rol` int(32) unsigned DEFAULT NULL,
  `cat_tipocliente` int(32) unsigned DEFAULT NULL,
  `cat_statuscliente` int(32) unsigned DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cliente`),
  KEY `fk_ae_clientes_ae_catalogo_2` (`cat_tipocliente`) USING BTREE,
  KEY `fk_cl_clientes_cl_clientes_1` (`parent`) USING BTREE,
  KEY `fk_cl_clientes_cm_catalogo_intermedio_1` (`cat_statuscliente`) USING BTREE,
  KEY `fk_cl_clientes_fw_roles_1` (`id_rol`) USING BTREE,
  CONSTRAINT `fk_ae_clientes_ae_catalogo_2` FOREIGN KEY (`cat_tipocliente`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_cl_clientes_fw_roles_1` FOREIGN KEY (`id_rol`) REFERENCES `fw_roles` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cl_cobros
-- ----------------------------
DROP TABLE IF EXISTS `cl_cobros`;
CREATE TABLE `cl_cobros` (
  `id_cobro` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `concepto` varchar(255) DEFAULT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cobro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cl_datos_fiscales
-- ----------------------------
DROP TABLE IF EXISTS `cl_datos_fiscales`;
CREATE TABLE `cl_datos_fiscales` (
  `id_datos_fiscales` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int(32) unsigned DEFAULT NULL,
  `id_asentamiento` int(32) unsigned DEFAULT NULL,
  `predeterminar` int(1) DEFAULT '0',
  `eliminado` int(1) DEFAULT '0',
  `rfc` varchar(15) DEFAULT NULL,
  `calle` varchar(255) DEFAULT NULL,
  `num_ext` varchar(255) DEFAULT NULL,
  `num_int` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `celular` varchar(255) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_datos_fiscales`),
  KEY `fk_ae_datos_fiscales_ae_clientes_1` (`id_cliente`) USING BTREE,
  KEY `fk_cl_datos_fiscales_it_asentamientos_1` (`id_asentamiento`) USING BTREE,
  CONSTRAINT `fk_ae_datos_fiscales_ae_clientes_1` FOREIGN KEY (`id_cliente`) REFERENCES `cl_clientes` (`id_cliente`),
  CONSTRAINT `fk_cl_datos_fiscales_it_asentamientos_1` FOREIGN KEY (`id_asentamiento`) REFERENCES `it_asentamientos` (`id_asentamiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cl_detalle_factura
-- ----------------------------
DROP TABLE IF EXISTS `cl_detalle_factura`;
CREATE TABLE `cl_detalle_factura` (
  `id_detalle_factura` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_factura` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_detalle_factura`),
  KEY `fk_ae_desglose_factura_ae_facturas_1` (`id_factura`) USING BTREE,
  CONSTRAINT `fk_ae_desglose_factura_ae_facturas_1` FOREIGN KEY (`id_factura`) REFERENCES `cl_facturas` (`id_factura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cl_factura_formapago
-- ----------------------------
DROP TABLE IF EXISTS `cl_factura_formapago`;
CREATE TABLE `cl_factura_formapago` (
  `id_factura_formapago` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_factura` int(32) unsigned DEFAULT NULL,
  `cat_formapago` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_factura_formapago`),
  KEY `fk_cl_factura_formapago_cl_facturas_1` (`id_factura`) USING BTREE,
  KEY `fk_cl_factura_formapago_cm_catalogo_1` (`cat_formapago`) USING BTREE,
  CONSTRAINT `fk_cl_factura_formapago_cl_facturas_1` FOREIGN KEY (`id_factura`) REFERENCES `cl_facturas` (`id_factura`),
  CONSTRAINT `fk_cl_factura_formapago_cm_catalogo_1` FOREIGN KEY (`cat_formapago`) REFERENCES `cm_catalogo` (`id_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cl_facturas
-- ----------------------------
DROP TABLE IF EXISTS `cl_facturas`;
CREATE TABLE `cl_facturas` (
  `id_factura` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int(32) unsigned DEFAULT NULL,
  `id_statusfactura` int(32) unsigned DEFAULT NULL,
  `id_datos_fiscales` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_factura`),
  KEY `fk_ae_facturas_ae_catalogo_1` (`id_statusfactura`) USING BTREE,
  KEY `fk_ae_facturas_ae_clientes_1` (`id_cliente`) USING BTREE,
  CONSTRAINT `fk_ae_facturas_ae_catalogo_1` FOREIGN KEY (`id_statusfactura`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_ae_facturas_ae_clientes_1` FOREIGN KEY (`id_cliente`) REFERENCES `cl_clientes` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cl_movimientos_financieros
-- ----------------------------
DROP TABLE IF EXISTS `cl_movimientos_financieros`;
CREATE TABLE `cl_movimientos_financieros` (
  `id_movimiento_financiero` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int(32) unsigned DEFAULT NULL,
  `id_factura` int(32) unsigned DEFAULT NULL,
  `id_pago` int(32) unsigned DEFAULT NULL,
  `id_cobros` int(32) unsigned DEFAULT NULL,
  `saldo` decimal(10,2) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_movimiento_financiero`),
  KEY `fk_ae_movimientos_financieros_egreso_1` (`id_cobros`) USING BTREE,
  KEY `fk_ae_estados_financieros_ae_clientes_1` (`id_cliente`) USING BTREE,
  KEY `fk_ae_movimientos_financieros_ingreso_1` (`id_pago`) USING BTREE,
  KEY `fk_cl_movimientos_financieros_cl_facturas_1` (`id_factura`) USING BTREE,
  CONSTRAINT `fk_ae_estados_financieros_ae_clientes_1` FOREIGN KEY (`id_cliente`) REFERENCES `cl_clientes` (`id_cliente`),
  CONSTRAINT `fk_ae_movimientos_financieros_egreso_1` FOREIGN KEY (`id_cobros`) REFERENCES `cl_cobros` (`id_cobro`),
  CONSTRAINT `fk_ae_movimientos_financieros_ingreso_1` FOREIGN KEY (`id_pago`) REFERENCES `cl_pagos` (`id_pago`),
  CONSTRAINT `fk_cl_movimientos_financieros_cl_facturas_1` FOREIGN KEY (`id_factura`) REFERENCES `cl_facturas` (`id_factura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cl_pagos
-- ----------------------------
DROP TABLE IF EXISTS `cl_pagos`;
CREATE TABLE `cl_pagos` (
  `id_pago` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_factura` int(32) unsigned DEFAULT NULL,
  `cat_tipo_pago` int(32) unsigned DEFAULT NULL,
  `concepto` varchar(255) DEFAULT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pago`),
  KEY `fk_cl_pagos_cl_facturas_1` (`id_factura`) USING BTREE,
  KEY `fk_cl_pagos_cm_catalogo_1` (`cat_tipo_pago`) USING BTREE,
  CONSTRAINT `fk_cl_pagos_cl_facturas_1` FOREIGN KEY (`id_factura`) REFERENCES `cl_facturas` (`id_factura`),
  CONSTRAINT `fk_cl_pagos_cm_catalogo_1` FOREIGN KEY (`cat_tipo_pago`) REFERENCES `cm_catalogo` (`id_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cl_tarifas_clientes
-- ----------------------------
DROP TABLE IF EXISTS `cl_tarifas_clientes`;
CREATE TABLE `cl_tarifas_clientes` (
  `id_tarifa_cliente` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int(32) unsigned DEFAULT NULL,
  `costo_base` decimal(8,2) DEFAULT NULL,
  `km_adicional` decimal(8,2) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `inicio_vigencia` datetime DEFAULT NULL,
  `fin_vigencia` datetime DEFAULT NULL,
  `cat_statustarifa` int(32) unsigned DEFAULT NULL,
  `cat_tipo_tarifa` int(32) unsigned DEFAULT NULL,
  `tabulado` int(1) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tarifa_cliente`),
  KEY `fk_tarifas_ae_clientes_1` (`id_cliente`) USING BTREE,
  KEY `fk_cl_tarifas_clientes_cm_catalogo_1` (`cat_statustarifa`),
  KEY `fk_cl_tarifas_clientes_cm_catalogo_2` (`cat_tipo_tarifa`),
  CONSTRAINT `fk_cl_tarifas_clientes_cm_catalogo_1` FOREIGN KEY (`cat_statustarifa`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_cl_tarifas_clientes_cm_catalogo_2` FOREIGN KEY (`cat_tipo_tarifa`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_tarifas_ae_clientes_1` FOREIGN KEY (`id_cliente`) REFERENCES `cl_clientes` (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cm_catalogo
-- ----------------------------
DROP TABLE IF EXISTS `cm_catalogo`;
CREATE TABLE `cm_catalogo` (
  `id_cat` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_padre` int(32) unsigned DEFAULT NULL,
  `catalogo` varchar(100) DEFAULT NULL,
  `etiqueta` varchar(100) DEFAULT NULL,
  `activo` varchar(5) DEFAULT NULL,
  `orden` int(32) DEFAULT NULL,
  `valor` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cat`),
  KEY `fk_cm_catalogo_cm_catalogo_1` (`id_padre`) USING BTREE,
  CONSTRAINT `fk_cm_catalogo_cm_catalogo_1` FOREIGN KEY (`id_padre`) REFERENCES `cm_catalogo` (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_apartados
-- ----------------------------
DROP TABLE IF EXISTS `cr_apartados`;
CREATE TABLE `cr_apartados` (
  `id_apartados` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador` int(32) unsigned DEFAULT NULL,
  `mensuales` int(8) DEFAULT NULL,
  `anuales` int(8) DEFAULT NULL,
  `totales` int(8) DEFAULT NULL,
  `hit_anual` int(8) DEFAULT NULL,
  `hit_total` int(8) DEFAULT NULL,
  `turnos_anuales` int(8) DEFAULT NULL,
  `turnos_totales` int(8) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_apartados`),
  KEY `fk_vi_apartados_cr_operador_1` (`id_operador`),
  CONSTRAINT `fk_vi_apartados_cr_operador_1` FOREIGN KEY (`id_operador`) REFERENCES `cr_operador` (`id_operador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_bases
-- ----------------------------
DROP TABLE IF EXISTS `cr_bases`;
CREATE TABLE `cr_bases` (
  `id_base` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `cat_tipobase` int(32) unsigned DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `clave` varchar(3) DEFAULT NULL,
  `latitud` float(10,6) DEFAULT NULL,
  `longitud` float(10,6) DEFAULT NULL,
  `geocerca` text,
  `token_status` varchar(64) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_base`),
  KEY `fk_ae_bases_ae_catalogo_1` (`cat_tipobase`) USING BTREE,
  CONSTRAINT `fk_ae_bases_ae_catalogo_1` FOREIGN KEY (`cat_tipobase`) REFERENCES `cm_catalogo` (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_bases_operador_unidad
-- ----------------------------
DROP TABLE IF EXISTS `cr_bases_operador_unidad`;
CREATE TABLE `cr_bases_operador_unidad` (
  `id_base_operador_unidad` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_base` int(32) unsigned DEFAULT NULL,
  `id_operador_unidad` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_base_operador_unidad`),
  KEY `fk_ae_bases_operador_unidad_ae_operador_unidad_1` (`id_operador_unidad`) USING BTREE,
  KEY `fk_ae_bases_operador_unidad_ae_bases_1` (`id_base`) USING BTREE,
  CONSTRAINT `fk_ae_bases_operador_unidad_ae_bases_1` FOREIGN KEY (`id_base`) REFERENCES `cr_bases` (`id_base`),
  CONSTRAINT `fk_cr_bases_operador_unidad_cr_operador_unidad_1` FOREIGN KEY (`id_operador_unidad`) REFERENCES `cr_operador_unidad` (`id_operador_unidad`)
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_celulares
-- ----------------------------
DROP TABLE IF EXISTS `cr_celulares`;
CREATE TABLE `cr_celulares` (
  `id_celular` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `serie` varchar(32) DEFAULT NULL,
  `imei` varchar(32) DEFAULT NULL,
  `numero` decimal(10,0) DEFAULT NULL,
  `marcacion_corta` decimal(5,0) DEFAULT NULL,
  `marca` varchar(64) DEFAULT NULL,
  `modelo` varchar(64) DEFAULT NULL,
  `so` varchar(32) DEFAULT NULL,
  `version` varchar(16) DEFAULT NULL,
  `cat_status_celular` int(32) unsigned DEFAULT NULL,
  `sim` varchar(255) DEFAULT NULL,
  `externo` int(1) DEFAULT '0',
  `valor` decimal(9,2) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_celular`),
  KEY `fk_cr_celulares_cm_catalogo_1` (`cat_status_celular`) USING BTREE,
  CONSTRAINT `fk_cr_celulares_cm_catalogo_1` FOREIGN KEY (`cat_status_celular`) REFERENCES `cm_catalogo` (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_cordon
-- ----------------------------
DROP TABLE IF EXISTS `cr_cordon`;
CREATE TABLE `cr_cordon` (
  `id_cordon` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador_unidad` int(32) unsigned DEFAULT NULL,
  `id_episodio` int(32) unsigned DEFAULT NULL,
  `id_base` int(32) unsigned DEFAULT NULL,
  `cat_statuscordon` int(32) unsigned DEFAULT NULL,
  `llegada` datetime DEFAULT NULL,
  `salida` datetime DEFAULT NULL,
  `token` varchar(80) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cordon`),
  UNIQUE KEY `token` (`token`),
  KEY `fk_ae_cordon_ae_episodios_1` (`id_episodio`) USING BTREE,
  KEY `fk_ae_cordon_ae_bases_operador_unidad_1` (`id_operador_unidad`) USING BTREE,
  KEY `fk_ae_cordon_ae_catalogo_1` (`cat_statuscordon`) USING BTREE,
  KEY `fk_cr_cordon_cr_bases_1` (`id_base`),
  CONSTRAINT `fk_ae_cordon_ae_catalogo_1` FOREIGN KEY (`cat_statuscordon`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_ae_cordon_ae_episodios_1` FOREIGN KEY (`id_episodio`) REFERENCES `cr_episodios` (`id_episodio`),
  CONSTRAINT `fk_cr_cordon_cr_bases_1` FOREIGN KEY (`id_base`) REFERENCES `cr_bases` (`id_base`),
  CONSTRAINT `fk_cr_cordon_cr_operador_unidad_1` FOREIGN KEY (`id_operador_unidad`) REFERENCES `cr_operador_unidad` (`id_operador_unidad`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_domicilios
-- ----------------------------
DROP TABLE IF EXISTS `cr_domicilios`;
CREATE TABLE `cr_domicilios` (
  `id_domicilio` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador` int(32) unsigned DEFAULT NULL,
  `domicilio` text,
  `cat_tipodomicilio` int(32) unsigned DEFAULT NULL,
  `cat_statusdomicilio` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_domicilio`),
  KEY `fk_cr_domicilios_cr_operador_1` (`id_operador`) USING BTREE,
  CONSTRAINT `fk_cr_domicilios_cr_operador_1` FOREIGN KEY (`id_operador`) REFERENCES `cr_operador` (`id_operador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_episodios
-- ----------------------------
DROP TABLE IF EXISTS `cr_episodios`;
CREATE TABLE `cr_episodios` (
  `id_episodio` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador` int(32) unsigned DEFAULT NULL,
  `inicio` datetime DEFAULT NULL,
  `fin` datetime DEFAULT NULL,
  `tiempo` time DEFAULT NULL,
  `token_session` varchar(64) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_episodio`),
  KEY `fk_ae_episodios_ae_operador_1` (`id_operador`) USING BTREE,
  CONSTRAINT `fk_ae_episodios_ae_operador_1` FOREIGN KEY (`id_operador`) REFERENCES `cr_operador` (`id_operador`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_horario_operador_unidad
-- ----------------------------
DROP TABLE IF EXISTS `cr_horario_operador_unidad`;
CREATE TABLE `cr_horario_operador_unidad` (
  `id_horario_operador_unidad` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador_unidad` int(32) unsigned DEFAULT NULL,
  `id_horario_operador` int(32) unsigned DEFAULT NULL,
  `cat_horario` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_horario_operador_unidad`),
  KEY `fk_ae_horario_operador_unidad_ae_operador_unidad_1` (`id_operador_unidad`) USING BTREE,
  KEY `fk_ae_horario_operador_unidad_ae_catalogo_1` (`cat_horario`) USING BTREE,
  CONSTRAINT `fk_ae_horario_operador_unidad_ae_catalogo_1` FOREIGN KEY (`cat_horario`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_ae_horario_operador_unidad_ae_operador_unidad_1` FOREIGN KEY (`id_operador_unidad`) REFERENCES `cr_operador_unidad` (`id_operador_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_marcas
-- ----------------------------
DROP TABLE IF EXISTS `cr_marcas`;
CREATE TABLE `cr_marcas` (
  `id_marca` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `marca` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_marca`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_mensajes
-- ----------------------------
DROP TABLE IF EXISTS `cr_mensajes`;
CREATE TABLE `cr_mensajes` (
  `id_mensaje` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador` int(32) unsigned DEFAULT NULL,
  `mensaje` text,
  `read` binary(1) DEFAULT NULL,
  `user_alta` int(32) unsigned DEFAULT NULL,
  `user_mod` int(32) unsigned DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mensaje`),
  KEY `fk_cr_mensajes_cr_operador_1` (`id_operador`),
  CONSTRAINT `fk_cr_mensajes_cr_operador_1` FOREIGN KEY (`id_operador`) REFERENCES `cr_operador` (`id_operador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for cr_modelos
-- ----------------------------
DROP TABLE IF EXISTS `cr_modelos`;
CREATE TABLE `cr_modelos` (
  `id_modelo` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_marca` varchar(255) DEFAULT NULL,
  `modelo` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_modelo`)
) ENGINE=InnoDB AUTO_INCREMENT=730 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_numeq
-- ----------------------------
DROP TABLE IF EXISTS `cr_numeq`;
CREATE TABLE `cr_numeq` (
  `id_numeq` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `num` varchar(16) DEFAULT NULL,
  `eq_status` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_numeq`),
  KEY `fk_cr_numeq_cm_catalogo_1` (`eq_status`) USING BTREE,
  CONSTRAINT `fk_cr_numeq_cm_catalogo_1` FOREIGN KEY (`eq_status`) REFERENCES `cm_catalogo` (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_operador
-- ----------------------------
DROP TABLE IF EXISTS `cr_operador`;
CREATE TABLE `cr_operador` (
  `id_operador` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(32) unsigned DEFAULT NULL,
  `cat_statusoperador` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_operador`),
  KEY `fk_cr_operador_fw_usuarios_1` (`id_usuario`) USING BTREE,
  KEY `fk_ae_operador_ae_catalogo_1` (`cat_statusoperador`) USING BTREE,
  CONSTRAINT `fk_ae_operador_ae_catalogo_1` FOREIGN KEY (`cat_statusoperador`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_cr_operador_fw_usuarios_1` FOREIGN KEY (`id_usuario`) REFERENCES `fw_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_operador_celular
-- ----------------------------
DROP TABLE IF EXISTS `cr_operador_celular`;
CREATE TABLE `cr_operador_celular` (
  `id_operador_celular` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador` int(32) unsigned DEFAULT NULL,
  `id_celular` int(32) unsigned DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `cat_status_operador_celular` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_operador_celular`),
  KEY `fk_cr_operador_celular_celulares_1` (`id_celular`) USING BTREE,
  KEY `fk_cr_operador_celular_cm_catalogo_1` (`cat_status_operador_celular`) USING BTREE,
  KEY `fk_cr_operador_celular_cr_operador_1` (`id_operador`) USING BTREE,
  CONSTRAINT `fk_cr_operador_celular_celulares_1` FOREIGN KEY (`id_celular`) REFERENCES `cr_celulares` (`id_celular`),
  CONSTRAINT `fk_cr_operador_celular_cm_catalogo_1` FOREIGN KEY (`cat_status_operador_celular`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_cr_operador_celular_cr_operador_1` FOREIGN KEY (`id_operador`) REFERENCES `cr_operador` (`id_operador`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_operador_numeq
-- ----------------------------
DROP TABLE IF EXISTS `cr_operador_numeq`;
CREATE TABLE `cr_operador_numeq` (
  `id_operador_numeq` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador` int(32) unsigned DEFAULT NULL,
  `id_numeq` int(32) unsigned DEFAULT NULL,
  `cat_status_oper_numeq` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_operador_numeq`),
  KEY `fk_cr_operador_numeq_cm_catalogo_1` (`cat_status_oper_numeq`) USING BTREE,
  KEY `fk_ae_operador_numeq_ae_numeq_1` (`id_numeq`) USING BTREE,
  KEY `fk_ae_operador_numeq_ae_operador_1` (`id_operador`) USING BTREE,
  CONSTRAINT `fk_ae_operador_numeq_ae_numeq_1` FOREIGN KEY (`id_numeq`) REFERENCES `cr_numeq` (`id_numeq`),
  CONSTRAINT `fk_ae_operador_numeq_ae_operador_1` FOREIGN KEY (`id_operador`) REFERENCES `cr_operador` (`id_operador`),
  CONSTRAINT `fk_cr_operador_numeq_cm_catalogo_1` FOREIGN KEY (`cat_status_oper_numeq`) REFERENCES `cm_catalogo` (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_operador_unidad
-- ----------------------------
DROP TABLE IF EXISTS `cr_operador_unidad`;
CREATE TABLE `cr_operador_unidad` (
  `id_operador_unidad` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador` int(32) unsigned DEFAULT NULL,
  `id_unidad` int(32) unsigned DEFAULT NULL,
  `id_sync` int(32) unsigned DEFAULT NULL,
  `sync_token` varchar(80) DEFAULT NULL,
  `status_operador_unidad` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_operador_unidad`),
  KEY `fk_ae_operador_unidad_ae_operador_1` (`id_operador`) USING BTREE,
  KEY `fk_ae_operador_unidad_ae_unidades_1` (`id_unidad`) USING BTREE,
  KEY `fk_ae_operador_unidad_ae_sync_1` (`id_sync`) USING BTREE,
  KEY `fk_cr_operador_unidad_cm_catalogo_1` (`status_operador_unidad`),
  CONSTRAINT `fk_ae_operador_unidad_ae_operador_1` FOREIGN KEY (`id_operador`) REFERENCES `cr_operador` (`id_operador`),
  CONSTRAINT `fk_ae_operador_unidad_ae_unidades_1` FOREIGN KEY (`id_unidad`) REFERENCES `cr_unidades` (`id_unidad`),
  CONSTRAINT `fk_cr_operador_unidad_cm_catalogo_1` FOREIGN KEY (`status_operador_unidad`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_cr_operador_unidad_cr_sync_1` FOREIGN KEY (`id_sync`) REFERENCES `cr_sync` (`id_sync`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_presence
-- ----------------------------
DROP TABLE IF EXISTS `cr_presence`;
CREATE TABLE `cr_presence` (
  `id_presence` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador` int(32) unsigned NOT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id_presence`),
  UNIQUE KEY `token` (`id_operador`) USING BTREE,
  CONSTRAINT `fk_cr_presence_cr_operador_1` FOREIGN KEY (`id_operador`) REFERENCES `cr_operador` (`id_operador`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for cr_sync
-- ----------------------------
DROP TABLE IF EXISTS `cr_sync`;
CREATE TABLE `cr_sync` (
  `id_sync` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(80) NOT NULL,
  `accurate` float(10,4) DEFAULT NULL,
  `clave` varchar(3) DEFAULT NULL,
  `origen` varchar(64) DEFAULT NULL,
  `estado1` varchar(5) DEFAULT NULL,
  `estado2` varchar(5) DEFAULT NULL,
  `estado3` varchar(5) DEFAULT NULL,
  `estado4` varchar(5) DEFAULT NULL,
  `id_indexeddb` int(32) DEFAULT NULL,
  `id_episodio` int(32) DEFAULT NULL,
  `id_operador` int(32) DEFAULT NULL,
  `id_operador_unidad` int(32) DEFAULT NULL,
  `id_viaje` int(32) DEFAULT NULL,
  `latitud` float(10,6) DEFAULT NULL,
  `longitud` float(10,6) DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `serie` varchar(255) DEFAULT NULL,
  `tiempo` datetime DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_sync`),
  UNIQUE KEY `token` (`token`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=629 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_sync_ride
-- ----------------------------
DROP TABLE IF EXISTS `cr_sync_ride`;
CREATE TABLE `cr_sync_ride` (
  `id_sync_ride` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(80) NOT NULL,
  `id_operador_unidad` int(32) unsigned DEFAULT NULL,
  `cat_cve_store` int(32) DEFAULT NULL,
  `valor` varchar(255) DEFAULT NULL,
  `procesado` int(1) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_sync_ride`),
  UNIQUE KEY `token` (`token`) USING BTREE,
  KEY `fk_sync_ride_cr_operador_unidad_1` (`id_operador_unidad`),
  CONSTRAINT `fk_sync_ride_cr_operador_unidad_1` FOREIGN KEY (`id_operador_unidad`) REFERENCES `cr_operador_unidad` (`id_operador_unidad`)
) ENGINE=InnoDB AUTO_INCREMENT=543 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for cr_telefonos
-- ----------------------------
DROP TABLE IF EXISTS `cr_telefonos`;
CREATE TABLE `cr_telefonos` (
  `id_telefono` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador` int(32) unsigned DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `cat_tipotelefono` int(32) unsigned DEFAULT NULL,
  `cat_statustelefono` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_telefono`),
  KEY `fk_cl_telefonos_cl_clientes_1` (`id_operador`) USING BTREE,
  CONSTRAINT `fk_cr_telefonos_cr_operador_1` FOREIGN KEY (`id_operador`) REFERENCES `cr_operador` (`id_operador`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for cr_tiempo_base
-- ----------------------------
DROP TABLE IF EXISTS `cr_tiempo_base`;
CREATE TABLE `cr_tiempo_base` (
  `id_tiempo base` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador` int(32) unsigned NOT NULL,
  `id_operador_unidad` int(32) unsigned DEFAULT NULL,
  `distancia` varchar(64) DEFAULT NULL,
  `min_min` varchar(64) DEFAULT NULL,
  `min_max` varchar(64) DEFAULT NULL,
  `latlng` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id_tiempo base`),
  KEY `fk_cr_tiempo_base_cr_operador_1` (`id_operador`),
  KEY `fk_cr_tiempo_base_cr_operador_unidad_1` (`id_operador_unidad`),
  CONSTRAINT `fk_cr_tiempo_base_cr_operador_1` FOREIGN KEY (`id_operador`) REFERENCES `cr_operador` (`id_operador`),
  CONSTRAINT `fk_cr_tiempo_base_cr_operador_unidad_1` FOREIGN KEY (`id_operador_unidad`) REFERENCES `cr_operador_unidad` (`id_operador_unidad`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for cr_unidades
-- ----------------------------
DROP TABLE IF EXISTS `cr_unidades`;
CREATE TABLE `cr_unidades` (
  `id_unidad` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_modelo` int(32) unsigned DEFAULT NULL,
  `id_marca` int(32) unsigned DEFAULT NULL,
  `year` varchar(5) DEFAULT NULL,
  `placas` varchar(10) DEFAULT NULL,
  `motor` varchar(64) DEFAULT NULL,
  `color` varchar(32) DEFAULT NULL,
  `cat_status_unidad` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_unidad`),
  KEY `fk_cr_unidades_cm_catalogo_1` (`cat_status_unidad`) USING BTREE,
  KEY `fk_cr_unidades_cr_marcas_1` (`id_marca`) USING BTREE,
  KEY `fk_cr_unidades_cr_modelos_1` (`id_modelo`) USING BTREE,
  CONSTRAINT `fk_cr_unidades_cm_catalogo_1` FOREIGN KEY (`cat_status_unidad`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_cr_unidades_cr_marcas_1` FOREIGN KEY (`id_marca`) REFERENCES `cr_marcas` (`id_marca`),
  CONSTRAINT `fk_cr_unidades_cr_modelos_1` FOREIGN KEY (`id_modelo`) REFERENCES `cr_modelos` (`id_modelo`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fo_cobro_ingresos
-- ----------------------------
DROP TABLE IF EXISTS `fo_cobro_ingresos`;
CREATE TABLE `fo_cobro_ingresos` (
  `id_cobro_ingreso` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_ingreso` int(32) unsigned DEFAULT NULL,
  `monto` decimal(8,2) DEFAULT NULL,
  `tipo_cobro` varchar(64) DEFAULT NULL,
  `fecha_cobro` datetime DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cobro_ingreso`),
  KEY `fk_cobro_ingresos_ingresos_1` (`id_ingreso`) USING BTREE,
  CONSTRAINT `fk_cobro_ingresos_ingresos_1` FOREIGN KEY (`id_ingreso`) REFERENCES `fo_ingresos` (`id_ingreso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fo_comisiones
-- ----------------------------
DROP TABLE IF EXISTS `fo_comisiones`;
CREATE TABLE `fo_comisiones` (
  `id_comision` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_regla_comision` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_comision`),
  KEY `fk_comisiones_regla_comision_1` (`id_regla_comision`) USING BTREE,
  CONSTRAINT `fk_comisiones_regla_comision_1` FOREIGN KEY (`id_regla_comision`) REFERENCES `fo_regla_comision` (`id_regla_comision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fo_concepto_adeudo
-- ----------------------------
DROP TABLE IF EXISTS `fo_concepto_adeudo`;
CREATE TABLE `fo_concepto_adeudo` (
  `id_concepto_adeudo` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador_conecepto` int(32) unsigned DEFAULT NULL,
  `monto` decimal(8,2) DEFAULT NULL,
  `fecha_emision` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_concepto_adeudo`),
  KEY `fk_concepto_adeudo_operador_conceptos_1` (`id_operador_conecepto`) USING BTREE,
  CONSTRAINT `fk_concepto_adeudo_operador_conceptos_1` FOREIGN KEY (`id_operador_conecepto`) REFERENCES `fo_operador_conceptos` (`id_operador_concepto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fo_conceptos
-- ----------------------------
DROP TABLE IF EXISTS `fo_conceptos`;
CREATE TABLE `fo_conceptos` (
  `id_concepto` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `concepto` varchar(64) DEFAULT NULL,
  `monto` decimal(8,2) DEFAULT NULL,
  `periodicidad` int(4) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_concepto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fo_conceptos_aplicaciones
-- ----------------------------
DROP TABLE IF EXISTS `fo_conceptos_aplicaciones`;
CREATE TABLE `fo_conceptos_aplicaciones` (
  `id_concepto_aplicacion` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_concepto` int(32) unsigned DEFAULT NULL,
  `fecha_aplicacion` datetime DEFAULT NULL,
  `adeudos_generados` int(4) DEFAULT NULL,
  `monto_generado` decimal(8,2) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_concepto_aplicacion`),
  KEY `fk_conceptos_aplicaciones_conceptos_1` (`id_concepto`) USING BTREE,
  CONSTRAINT `fk_conceptos_aplicaciones_conceptos_1` FOREIGN KEY (`id_concepto`) REFERENCES `fo_conceptos` (`id_concepto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fo_ingresos
-- ----------------------------
DROP TABLE IF EXISTS `fo_ingresos`;
CREATE TABLE `fo_ingresos` (
  `id_ingreso` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador` int(32) unsigned DEFAULT NULL,
  `id_viaje` int(32) unsigned DEFAULT NULL,
  `id_comision` int(32) unsigned DEFAULT NULL,
  `id_tarifa_operador` int(32) unsigned DEFAULT NULL,
  `monto` decimal(8,2) DEFAULT NULL,
  `comision` decimal(8,2) DEFAULT NULL,
  `neto` decimal(8,2) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ingreso`),
  KEY `fk_ingresos_tarifas_operadores_1` (`id_tarifa_operador`) USING BTREE,
  KEY `fk_ingresos_ae_operador_1` (`id_operador`) USING BTREE,
  KEY `fk_ingresos_ae_viaje_1` (`id_viaje`) USING BTREE,
  KEY `fk_ingresos_comisiones_1` (`id_comision`) USING BTREE,
  CONSTRAINT `fk_ingresos_ae_operador_1` FOREIGN KEY (`id_operador`) REFERENCES `cr_operador` (`id_operador`),
  CONSTRAINT `fk_ingresos_ae_viaje_1` FOREIGN KEY (`id_viaje`) REFERENCES `vi_viaje` (`id_viaje`),
  CONSTRAINT `fk_ingresos_comisiones_1` FOREIGN KEY (`id_comision`) REFERENCES `fo_comisiones` (`id_comision`),
  CONSTRAINT `fk_ingresos_tarifas_operadores_1` FOREIGN KEY (`id_tarifa_operador`) REFERENCES `fo_tarifas_operadores` (`id_tarifa_operador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fo_movimientos
-- ----------------------------
DROP TABLE IF EXISTS `fo_movimientos`;
CREATE TABLE `fo_movimientos` (
  `id_movimiento` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_cobro_ingreso` int(32) unsigned DEFAULT NULL,
  `ingreso` decimal(8,2) DEFAULT NULL,
  `id_pago_concepto` int(32) unsigned DEFAULT NULL,
  `egreso` decimal(8,2) DEFAULT NULL,
  `saldo` decimal(8,2) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_movimiento`),
  KEY `fk_movimientos_cobro_ingresos_1` (`id_cobro_ingreso`) USING BTREE,
  KEY `fk_movimientos_pagos_conceptos_1` (`id_pago_concepto`) USING BTREE,
  CONSTRAINT `fk_movimientos_cobro_ingresos_1` FOREIGN KEY (`id_cobro_ingreso`) REFERENCES `fo_cobro_ingresos` (`id_cobro_ingreso`),
  CONSTRAINT `fk_movimientos_pagos_conceptos_1` FOREIGN KEY (`id_pago_concepto`) REFERENCES `fo_pagos_conceptos` (`id_pago_concepto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fo_operador_conceptos
-- ----------------------------
DROP TABLE IF EXISTS `fo_operador_conceptos`;
CREATE TABLE `fo_operador_conceptos` (
  `id_operador_concepto` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador` int(32) unsigned DEFAULT NULL,
  `id_concepto` int(32) unsigned DEFAULT NULL,
  `inicio_cobranza` datetime DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_operador_concepto`),
  KEY `fk_operador_conceptos_conceptos_1` (`id_concepto`) USING BTREE,
  KEY `fk_operador_conceptos_ae_operador_1` (`id_operador`) USING BTREE,
  CONSTRAINT `fk_operador_conceptos_ae_operador_1` FOREIGN KEY (`id_operador`) REFERENCES `cr_operador` (`id_operador`),
  CONSTRAINT `fk_operador_conceptos_conceptos_1` FOREIGN KEY (`id_concepto`) REFERENCES `fo_conceptos` (`id_concepto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fo_pagos_conceptos
-- ----------------------------
DROP TABLE IF EXISTS `fo_pagos_conceptos`;
CREATE TABLE `fo_pagos_conceptos` (
  `id_pago_concepto` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_concepto_adeudo` int(32) unsigned DEFAULT NULL,
  `monto` decimal(8,2) DEFAULT NULL,
  `tipo_pago` varchar(64) DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pago_concepto`),
  KEY `fk_pagos_conceptos_concepto_adeudo_1` (`id_concepto_adeudo`) USING BTREE,
  CONSTRAINT `fk_pagos_conceptos_concepto_adeudo_1` FOREIGN KEY (`id_concepto_adeudo`) REFERENCES `fo_concepto_adeudo` (`id_concepto_adeudo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fo_regla_comision
-- ----------------------------
DROP TABLE IF EXISTS `fo_regla_comision`;
CREATE TABLE `fo_regla_comision` (
  `id_regla_comision` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_regla_comision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fo_tarifas_operadores
-- ----------------------------
DROP TABLE IF EXISTS `fo_tarifas_operadores`;
CREATE TABLE `fo_tarifas_operadores` (
  `id_tarifa_operador` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_operador` int(32) unsigned DEFAULT NULL,
  `costo_base` varchar(255) DEFAULT NULL,
  `km_adicional` varchar(255) DEFAULT NULL,
  `cat_formapago` int(32) unsigned DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `eliminado` int(1) DEFAULT '0',
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tarifa_operador`),
  KEY `fk_tarifas_operadores_ae_operador_1` (`id_operador`) USING BTREE,
  KEY `fk_fo_tarifas_operadores_cm_catalogo_1` (`cat_formapago`) USING BTREE,
  CONSTRAINT `fk_fo_tarifas_operadores_cm_catalogo_1` FOREIGN KEY (`cat_formapago`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_tarifas_operadores_ae_operador_1` FOREIGN KEY (`id_operador`) REFERENCES `cr_operador` (`id_operador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fw_area
-- ----------------------------
DROP TABLE IF EXISTS `fw_area`;
CREATE TABLE `fw_area` (
  `id_area` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_ubicacion` int(32) unsigned DEFAULT NULL,
  `cat_status` int(32) unsigned DEFAULT NULL,
  `area_area` varchar(100) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_area`),
  KEY `fk_area_ubicacion_1` (`id_ubicacion`) USING BTREE,
  KEY `fk_area_ae_catalogo_1` (`cat_status`) USING BTREE,
  CONSTRAINT `fk_area_ae_catalogo_1` FOREIGN KEY (`cat_status`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_area_ubicacion_1` FOREIGN KEY (`id_ubicacion`) REFERENCES `fw_ubicacion` (`id_ubicacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fw_config
-- ----------------------------
DROP TABLE IF EXISTS `fw_config`;
CREATE TABLE `fw_config` (
  `id_config` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_site` int(32) unsigned DEFAULT NULL,
  `descripcion` varchar(64) DEFAULT NULL,
  `valor` varchar(16) DEFAULT NULL,
  `tmp_val` varchar(16) DEFAULT NULL,
  `data` text,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_config`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fw_dac_acl
-- ----------------------------
DROP TABLE IF EXISTS `fw_dac_acl`;
CREATE TABLE `fw_dac_acl` (
  `id_dac_acl` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(32) unsigned DEFAULT NULL,
  `tercio` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dac_acl`),
  KEY `fk_dac_acl_usuarios_1` (`id_usuario`) USING BTREE,
  CONSTRAINT `fk_dac_acl_usuarios_1` FOREIGN KEY (`id_usuario`) REFERENCES `fw_usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fw_login
-- ----------------------------
DROP TABLE IF EXISTS `fw_login`;
CREATE TABLE `fw_login` (
  `id_login` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(32) unsigned DEFAULT NULL,
  `session_id` varchar(32) DEFAULT NULL,
  `open` int(1) DEFAULT NULL,
  `fecha_login` datetime DEFAULT NULL,
  `ultima_verificacion` datetime DEFAULT NULL,
  `fecha_logout` datetime DEFAULT NULL,
  `tiempo_session` varchar(19) DEFAULT NULL,
  `ipv4` varchar(15) DEFAULT NULL,
  `ipv6` varchar(42) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_login`),
  KEY `fk_fw_login_fw_usuarios_1` (`id_usuario`),
  CONSTRAINT `fk_fw_login_fw_usuarios_1` FOREIGN KEY (`id_usuario`) REFERENCES `fw_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for fw_login_log
-- ----------------------------
DROP TABLE IF EXISTS `fw_login_log`;
CREATE TABLE `fw_login_log` (
  `id_login_log` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(32) unsigned DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `intentos` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id_login_log`),
  KEY `fk_login_log_usuarios_1` (`id_usuario`) USING BTREE,
  CONSTRAINT `fk_fw_login_log_fw_usuarios_1` FOREIGN KEY (`id_usuario`) REFERENCES `fw_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for fw_lost_password
-- ----------------------------
DROP TABLE IF EXISTS `fw_lost_password`;
CREATE TABLE `fw_lost_password` (
  `id_lost` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(80) DEFAULT NULL,
  `id_usuario` int(32) unsigned DEFAULT NULL,
  `correo` varchar(64) DEFAULT NULL,
  `cat_status` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_lost`),
  KEY `fk_lost_password_usuarios_1` (`id_usuario`) USING BTREE,
  KEY `fk_lost_password_ae_catalogo_1` (`cat_status`) USING BTREE,
  CONSTRAINT `fk_lost_password_ae_catalogo_1` FOREIGN KEY (`cat_status`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_lost_password_usuarios_1` FOREIGN KEY (`id_usuario`) REFERENCES `fw_usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fw_metodos
-- ----------------------------
DROP TABLE IF EXISTS `fw_metodos`;
CREATE TABLE `fw_metodos` (
  `id_metodo` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `controlador` varchar(255) DEFAULT NULL,
  `metodo` varchar(255) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `descripcion` longtext,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_metodo`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fw_nivel
-- ----------------------------
DROP TABLE IF EXISTS `fw_nivel`;
CREATE TABLE `fw_nivel` (
  `id_nivel` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_origen` int(32) unsigned DEFAULT NULL,
  `origen` varchar(255) DEFAULT NULL,
  `nivel` int(2) DEFAULT NULL,
  `n0` int(32) DEFAULT '0',
  `n1` int(32) DEFAULT '0',
  `n2` int(32) DEFAULT '0',
  `n3` int(32) DEFAULT '0',
  `n4` int(32) DEFAULT '0',
  `n5` int(32) DEFAULT '0',
  `n6` int(32) DEFAULT '0',
  `n7` int(32) DEFAULT '0',
  `n8` int(32) DEFAULT '0',
  `n9` int(32) DEFAULT '0',
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_nivel`),
  KEY `fk_nivel_area_1` (`id_origen`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fw_permisos
-- ----------------------------
DROP TABLE IF EXISTS `fw_permisos`;
CREATE TABLE `fw_permisos` (
  `id_permiso` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_metodo` int(32) unsigned DEFAULT NULL,
  `id_rol` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_permiso`),
  KEY `fk_permisos_roles_1` (`id_rol`) USING BTREE,
  KEY `fk_permisos_metodos_1` (`id_metodo`) USING BTREE,
  CONSTRAINT `fk_permisos_metodos_1` FOREIGN KEY (`id_metodo`) REFERENCES `fw_metodos` (`id_metodo`),
  CONSTRAINT `fk_permisos_roles_1` FOREIGN KEY (`id_rol`) REFERENCES `fw_roles` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=713 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fw_roles
-- ----------------------------
DROP TABLE IF EXISTS `fw_roles`;
CREATE TABLE `fw_roles` (
  `id_rol` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `cat_tiporol` int(32) unsigned DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_rol`),
  KEY `fk_fw_roles_cm_catalogo_1` (`cat_tiporol`) USING BTREE,
  CONSTRAINT `fk_fw_roles_cm_catalogo_1` FOREIGN KEY (`cat_tiporol`) REFERENCES `cm_catalogo` (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fw_roles_alta
-- ----------------------------
DROP TABLE IF EXISTS `fw_roles_alta`;
CREATE TABLE `fw_roles_alta` (
  `id_rol_alta` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_rol` int(32) unsigned DEFAULT NULL,
  `access` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_rol_alta`),
  KEY `fk_fw_roles_alta_fw_roles_1` (`id_rol`),
  CONSTRAINT `fk_fw_roles_alta_fw_roles_1` FOREIGN KEY (`id_rol`) REFERENCES `fw_roles` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fw_ubicacion
-- ----------------------------
DROP TABLE IF EXISTS `fw_ubicacion`;
CREATE TABLE `fw_ubicacion` (
  `id_ubicacion` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion_ubicacion` varchar(100) DEFAULT NULL,
  `direccion` longtext,
  `cat_tipo_ubicacion` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ubicacion`),
  KEY `fk_ubicacion_ae_catalogo_1` (`cat_tipo_ubicacion`) USING BTREE,
  CONSTRAINT `fk_ubicacion_ae_catalogo_1` FOREIGN KEY (`cat_tipo_ubicacion`) REFERENCES `cm_catalogo` (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fw_usuarios
-- ----------------------------
DROP TABLE IF EXISTS `fw_usuarios`;
CREATE TABLE `fw_usuarios` (
  `id_usuario` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_area` int(32) unsigned DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `usuario` varchar(64) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `id_rol` int(32) unsigned DEFAULT NULL,
  `nombres` varchar(255) DEFAULT NULL,
  `apellido_paterno` varchar(255) DEFAULT NULL,
  `apellido_materno` varchar(255) DEFAULT NULL,
  `id_ubicacion` int(32) unsigned DEFAULT NULL,
  `cat_status` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `username` (`usuario`) USING BTREE,
  KEY `fk_fw_usuarios_cm_catalogo_1` (`cat_status`) USING BTREE,
  KEY `fk_usuarios_area_1` (`id_area`) USING BTREE,
  KEY `fk_usuarios_roles_1` (`id_rol`) USING BTREE,
  KEY `fk_usuarios_ubicacion_1` (`id_ubicacion`) USING BTREE,
  CONSTRAINT `fk_fw_usuarios_cm_catalogo_1` FOREIGN KEY (`cat_status`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_usuarios_area_1` FOREIGN KEY (`id_area`) REFERENCES `fw_area` (`id_area`),
  CONSTRAINT `fk_usuarios_roles_1` FOREIGN KEY (`id_rol`) REFERENCES `fw_roles` (`id_rol`),
  CONSTRAINT `fk_usuarios_ubicacion_1` FOREIGN KEY (`id_ubicacion`) REFERENCES `fw_ubicacion` (`id_ubicacion`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for fw_usuarios_config
-- ----------------------------
DROP TABLE IF EXISTS `fw_usuarios_config`;
CREATE TABLE `fw_usuarios_config` (
  `id_usuario_config` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(32) unsigned DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `paginacion` int(32) DEFAULT NULL,
  `activar_paginado` varchar(5) DEFAULT NULL,
  `aceptar_tyc` varchar(2) DEFAULT 'NO',
  `fecha_ingreso` date DEFAULT NULL,
  `poseido` int(1) DEFAULT '0',
  `password` varchar(64) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario_config`),
  KEY `fk_usuarios_config_usuarios_1` (`id_usuario`) USING BTREE,
  CONSTRAINT `fk_usuarios_config_usuarios_1` FOREIGN KEY (`id_usuario`) REFERENCES `fw_usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for gps
-- ----------------------------
DROP TABLE IF EXISTS `gps`;
CREATE TABLE `gps` (
  `id_gps` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `latitud` varchar(16) DEFAULT NULL,
  `longitud` varchar(16) DEFAULT NULL,
  `tiempo` varchar(32) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bateria` varchar(32) DEFAULT NULL,
  `id_android` varchar(32) DEFAULT NULL,
  `serie` varchar(32) DEFAULT NULL,
  `acurate` varchar(32) DEFAULT NULL,
  `version` varchar(5) DEFAULT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `id_operador` int(32) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_gps`),
  KEY `fk_gps_cr_operador_1` (`id_operador`) USING BTREE,
  CONSTRAINT `fk_gps_cr_operador_1` FOREIGN KEY (`id_operador`) REFERENCES `cr_operador` (`id_operador`)
) ENGINE=InnoDB AUTO_INCREMENT=338 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for it_asentamientos
-- ----------------------------
DROP TABLE IF EXISTS `it_asentamientos`;
CREATE TABLE `it_asentamientos` (
  `id_asentamiento` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `asentamiento` varchar(255) DEFAULT NULL,
  `id_codigo_postal` int(32) unsigned DEFAULT NULL,
  `id_tipo_asenta` int(32) unsigned DEFAULT NULL,
  `id_municipio` int(32) unsigned DEFAULT NULL,
  `id_estado` int(32) unsigned DEFAULT NULL,
  `id_ciudad` int(32) unsigned DEFAULT NULL,
  `id_zona` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_asentamiento`),
  KEY `fk_asentamientos_zonas_1` (`id_zona`) USING BTREE,
  KEY `fk_asentamientos_ciudades_1` (`id_ciudad`) USING BTREE,
  KEY `fk_asentamientos_codigos_postales_1` (`id_codigo_postal`) USING BTREE,
  KEY `fk_asentamientos_estados_1` (`id_estado`) USING BTREE,
  KEY `fk_asentamientos_municipios_1` (`id_municipio`) USING BTREE,
  KEY `fk_asentamientos_tipo_asentamientos_1` (`id_tipo_asenta`) USING BTREE,
  CONSTRAINT `fk_asentamientos_ciudades_1` FOREIGN KEY (`id_ciudad`) REFERENCES `it_ciudades` (`id_ciudad`),
  CONSTRAINT `fk_asentamientos_codigos_postales_1` FOREIGN KEY (`id_codigo_postal`) REFERENCES `it_codigos_postales` (`id_codigo_postal`),
  CONSTRAINT `fk_asentamientos_estados_1` FOREIGN KEY (`id_estado`) REFERENCES `it_estados` (`id_estado`),
  CONSTRAINT `fk_asentamientos_municipios_1` FOREIGN KEY (`id_municipio`) REFERENCES `it_municipios` (`id_municipio`),
  CONSTRAINT `fk_asentamientos_tipo_asentamientos_1` FOREIGN KEY (`id_tipo_asenta`) REFERENCES `it_tipo_asentamientos` (`id_tipo_asenta`),
  CONSTRAINT `fk_asentamientos_zonas_1` FOREIGN KEY (`id_zona`) REFERENCES `it_zonas` (`id_zona`)
) ENGINE=InnoDB AUTO_INCREMENT=145942 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for it_ciudades
-- ----------------------------
DROP TABLE IF EXISTS `it_ciudades`;
CREATE TABLE `it_ciudades` (
  `id_ciudad` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `ciudad` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ciudad`)
) ENGINE=InnoDB AUTO_INCREMENT=639 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for it_cliente_destino
-- ----------------------------
DROP TABLE IF EXISTS `it_cliente_destino`;
CREATE TABLE `it_cliente_destino` (
  `id_cliente_destino` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int(32) unsigned DEFAULT NULL,
  `id_destino` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cliente_destino`),
  KEY `fk_ae_cliente_destino_ae_clientes_1` (`id_cliente`) USING BTREE,
  KEY `fk_ae_cliente_destino_ae_destinos_1` (`id_destino`) USING BTREE,
  CONSTRAINT `fk_ae_cliente_destino_ae_clientes_1` FOREIGN KEY (`id_cliente`) REFERENCES `cl_clientes` (`id_cliente`),
  CONSTRAINT `fk_ae_cliente_destino_ae_destinos_1` FOREIGN KEY (`id_destino`) REFERENCES `it_destinos` (`id_destino`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for it_cliente_origen
-- ----------------------------
DROP TABLE IF EXISTS `it_cliente_origen`;
CREATE TABLE `it_cliente_origen` (
  `id_cliente_origen` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int(32) unsigned DEFAULT NULL,
  `id_origen` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cliente_origen`),
  KEY `fk_ae_cliente_origen_ae_clientes_1` (`id_cliente`) USING BTREE,
  KEY `fk_ae_cliente_origen_ae_origenes_1` (`id_origen`) USING BTREE,
  CONSTRAINT `fk_ae_cliente_origen_ae_clientes_1` FOREIGN KEY (`id_cliente`) REFERENCES `cl_clientes` (`id_cliente`),
  CONSTRAINT `fk_ae_cliente_origen_ae_origenes_1` FOREIGN KEY (`id_origen`) REFERENCES `it_origenes` (`id_origen`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for it_codigos_postales
-- ----------------------------
DROP TABLE IF EXISTS `it_codigos_postales`;
CREATE TABLE `it_codigos_postales` (
  `id_codigo_postal` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `codigo_postal` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_codigo_postal`)
) ENGINE=InnoDB AUTO_INCREMENT=32453 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for it_destinos
-- ----------------------------
DROP TABLE IF EXISTS `it_destinos`;
CREATE TABLE `it_destinos` (
  `id_destino` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_direccion` int(32) unsigned DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_destino`),
  KEY `fk_ae_destinos_ae_direcciones_1` (`id_direccion`) USING BTREE,
  CONSTRAINT `fk_ae_destinos_ae_direcciones_1` FOREIGN KEY (`id_direccion`) REFERENCES `it_direcciones` (`id_direccion`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for it_direcciones
-- ----------------------------
DROP TABLE IF EXISTS `it_direcciones`;
CREATE TABLE `it_direcciones` (
  `id_direccion` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_asentamiento` int(32) unsigned DEFAULT NULL,
  `calle` varchar(255) DEFAULT NULL,
  `num_ext` varchar(255) DEFAULT NULL,
  `num_int` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `celular` varchar(255) DEFAULT NULL,
  `referencia` text,
  `geocodificacion_inversa` text,
  `geocoordenadas` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_direccion`),
  KEY `fk_it_direcciones_asentamientos_1` (`id_asentamiento`) USING BTREE,
  CONSTRAINT `fk_it_direcciones_asentamientos_1` FOREIGN KEY (`id_asentamiento`) REFERENCES `it_asentamientos` (`id_asentamiento`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for it_estados
-- ----------------------------
DROP TABLE IF EXISTS `it_estados`;
CREATE TABLE `it_estados` (
  `id_estado` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `estado` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for it_municipios
-- ----------------------------
DROP TABLE IF EXISTS `it_municipios`;
CREATE TABLE `it_municipios` (
  `id_municipio` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `municipio` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_municipio`)
) ENGINE=InnoDB AUTO_INCREMENT=2319 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for it_origenes
-- ----------------------------
DROP TABLE IF EXISTS `it_origenes`;
CREATE TABLE `it_origenes` (
  `id_origen` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_direccion` int(32) unsigned DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_origen`),
  KEY `fk_ae_origenes_ae_direcciones_1` (`id_direccion`) USING BTREE,
  CONSTRAINT `fk_ae_origenes_ae_direcciones_1` FOREIGN KEY (`id_direccion`) REFERENCES `it_direcciones` (`id_direccion`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for it_tipo_asentamientos
-- ----------------------------
DROP TABLE IF EXISTS `it_tipo_asentamientos`;
CREATE TABLE `it_tipo_asentamientos` (
  `id_tipo_asenta` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `d_tipo_asenta` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tipo_asenta`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for it_viaje_destino
-- ----------------------------
DROP TABLE IF EXISTS `it_viaje_destino`;
CREATE TABLE `it_viaje_destino` (
  `id_viaje_destino` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_viaje` int(32) unsigned DEFAULT NULL,
  `id_cliente_destino` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_viaje_destino`),
  KEY `fk_ae_bitacora_destino_ae_cliente_destino_1` (`id_cliente_destino`) USING BTREE,
  KEY `fk_ae_bitacora_destino_ae_bitacora_1` (`id_viaje`) USING BTREE,
  CONSTRAINT `fk_ae_bitacora_destino_ae_bitacora_1` FOREIGN KEY (`id_viaje`) REFERENCES `vi_viaje` (`id_viaje`),
  CONSTRAINT `fk_ae_bitacora_destino_ae_cliente_destino_1` FOREIGN KEY (`id_cliente_destino`) REFERENCES `it_cliente_destino` (`id_cliente_destino`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for it_zonas
-- ----------------------------
DROP TABLE IF EXISTS `it_zonas`;
CREATE TABLE `it_zonas` (
  `id_zona` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `zona` varchar(255) DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_zona`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for timeoff
-- ----------------------------
DROP TABLE IF EXISTS `timeoff`;
CREATE TABLE `timeoff` (
  `id_timeoff` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `init` decimal(8,2) DEFAULT NULL,
  `finish` decimal(8,2) DEFAULT NULL,
  `empresa` decimal(6,2) DEFAULT NULL,
  `chofer` decimal(6,2) DEFAULT NULL,
  `stat_timeoff` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_timeoff`),
  KEY `fk_timeoff_cm_catalogo_1` (`stat_timeoff`),
  CONSTRAINT `fk_timeoff_cm_catalogo_1` FOREIGN KEY (`stat_timeoff`) REFERENCES `cm_catalogo` (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for vi_costos_adicionales
-- ----------------------------
DROP TABLE IF EXISTS `vi_costos_adicionales`;
CREATE TABLE `vi_costos_adicionales` (
  `id_costos_adicionales` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_viaje` int(32) unsigned DEFAULT NULL,
  `cat_concepto` int(32) unsigned DEFAULT NULL,
  `valor` int(6) unsigned DEFAULT NULL,
  `costo` float(6,3) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_costos_adicionales`),
  KEY `fk_ae_costos_adicionales_ae_catalogo_1` (`cat_concepto`) USING BTREE,
  KEY `fk_ae_conceptos_varios_ae_bitacora_1` (`id_viaje`) USING BTREE,
  CONSTRAINT `fk_ae_conceptos_varios_ae_bitacora_1` FOREIGN KEY (`id_viaje`) REFERENCES `vi_viaje` (`id_viaje`),
  CONSTRAINT `fk_ae_costos_adicionales_ae_catalogo_1` FOREIGN KEY (`cat_concepto`) REFERENCES `cm_catalogo` (`id_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for vi_viaje
-- ----------------------------
DROP TABLE IF EXISTS `vi_viaje`;
CREATE TABLE `vi_viaje` (
  `id_viaje` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_cordon` int(32) unsigned DEFAULT NULL,
  `id_cliente_origen` int(32) unsigned DEFAULT NULL,
  `id_detalle_factura` int(32) unsigned DEFAULT NULL,
  `id_episodio` int(32) unsigned DEFAULT NULL,
  `id_tarifa_cliente` int(32) unsigned DEFAULT NULL,
  `id_operador_unidad` int(32) unsigned DEFAULT NULL,
  `cat_status_viaje` int(32) unsigned DEFAULT NULL,
  `cat_cancelaciones` int(32) unsigned DEFAULT NULL,
  `cat_tiposervicio` int(32) unsigned DEFAULT NULL,
  `cat_tipo_salida` int(32) unsigned DEFAULT NULL,
  `cat_tipotemporicidad` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_viaje`),
  KEY `fk_ae_viaje_tarifas_clientes_1` (`id_tarifa_cliente`) USING BTREE,
  KEY `fk_ae_bitacora_ae_cliente_origen_1` (`id_cliente_origen`) USING BTREE,
  KEY `fk_ae_bitacora_ae_cordon_1` (`id_cordon`) USING BTREE,
  KEY `fk_ae_bitacora_ae_desglose_factura_1` (`id_detalle_factura`) USING BTREE,
  KEY `fk_ae_viaje_ae_episodios_1` (`id_episodio`) USING BTREE,
  KEY `fk_vi_viaje_cm_catalogo_1` (`cat_status_viaje`),
  KEY `fk_vi_viaje_cm_catalogo_2` (`cat_cancelaciones`),
  KEY `fk_vi_viaje_cm_catalogo_3` (`cat_tiposervicio`),
  KEY `fk_vi_viaje_cm_catalogo_4` (`cat_tipo_salida`),
  KEY `fk_vi_viaje_cr_operador_unidad_1` (`id_operador_unidad`),
  CONSTRAINT `fk_ae_bitacora_ae_cliente_origen_1` FOREIGN KEY (`id_cliente_origen`) REFERENCES `it_cliente_origen` (`id_cliente_origen`),
  CONSTRAINT `fk_ae_bitacora_ae_cordon_1` FOREIGN KEY (`id_cordon`) REFERENCES `cr_cordon` (`id_cordon`),
  CONSTRAINT `fk_ae_bitacora_ae_desglose_factura_1` FOREIGN KEY (`id_detalle_factura`) REFERENCES `cl_detalle_factura` (`id_detalle_factura`),
  CONSTRAINT `fk_ae_viaje_ae_episodios_1` FOREIGN KEY (`id_episodio`) REFERENCES `cr_episodios` (`id_episodio`),
  CONSTRAINT `fk_ae_viaje_tarifas_clientes_1` FOREIGN KEY (`id_tarifa_cliente`) REFERENCES `cl_tarifas_clientes` (`id_tarifa_cliente`),
  CONSTRAINT `fk_vi_viaje_cm_catalogo_1` FOREIGN KEY (`cat_status_viaje`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_vi_viaje_cm_catalogo_2` FOREIGN KEY (`cat_cancelaciones`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_vi_viaje_cm_catalogo_3` FOREIGN KEY (`cat_tiposervicio`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_vi_viaje_cm_catalogo_4` FOREIGN KEY (`cat_tipo_salida`) REFERENCES `cm_catalogo` (`id_cat`),
  CONSTRAINT `fk_vi_viaje_cr_operador_unidad_1` FOREIGN KEY (`id_operador_unidad`) REFERENCES `cr_operador_unidad` (`id_operador_unidad`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for vi_viaje_claves
-- ----------------------------
DROP TABLE IF EXISTS `vi_viaje_claves`;
CREATE TABLE `vi_viaje_claves` (
  `id_viaje_clave` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_viaje` int(32) unsigned DEFAULT NULL,
  `id_sync` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_viaje_clave`),
  KEY `fk_ae_bitacora_claves_ae_bitacora_1` (`id_viaje`) USING BTREE,
  KEY `fk_vi_viaje_claves_cr_sync_1` (`id_sync`),
  CONSTRAINT `fk_ae_bitacora_claves_ae_bitacora_1` FOREIGN KEY (`id_viaje`) REFERENCES `vi_viaje` (`id_viaje`),
  CONSTRAINT `fk_vi_viaje_claves_cr_sync_1` FOREIGN KEY (`id_sync`) REFERENCES `cr_sync` (`id_sync`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for vi_viaje_clientes
-- ----------------------------
DROP TABLE IF EXISTS `vi_viaje_clientes`;
CREATE TABLE `vi_viaje_clientes` (
  `id_viaje_clientes` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_viaje` int(32) unsigned DEFAULT NULL,
  `id_cliente` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_viaje_clientes`),
  KEY `fk_ae_bitacora_clientes_ae_clientes_1` (`id_cliente`) USING BTREE,
  KEY `fk_ae_bitacora_clientes_ae_bitacora_1` (`id_viaje`) USING BTREE,
  CONSTRAINT `fk_ae_bitacora_clientes_ae_bitacora_1` FOREIGN KEY (`id_viaje`) REFERENCES `vi_viaje` (`id_viaje`),
  CONSTRAINT `fk_ae_bitacora_clientes_ae_clientes_1` FOREIGN KEY (`id_cliente`) REFERENCES `cl_clientes` (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for vi_viaje_detalle
-- ----------------------------
DROP TABLE IF EXISTS `vi_viaje_detalle`;
CREATE TABLE `vi_viaje_detalle` (
  `id_viaje_detalle` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_viaje` int(32) unsigned DEFAULT NULL,
  `km` decimal(8,2) DEFAULT NULL,
  `fecha_solicitud` datetime DEFAULT NULL,
  `fecha_asignacion` datetime DEFAULT NULL,
  `fecha_requerimiento` datetime DEFAULT NULL,
  `fecha_arribo` datetime DEFAULT NULL,
  `fecha_finalizacion` datetime DEFAULT NULL,
  `redondo` int(1) DEFAULT NULL,
  `apartado` int(1) DEFAULT '0',
  `observaciones` text,
  `msgPaqArray` text,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_viaje_detalle`),
  KEY `fk_ae_bitacora_detalle_ae_bitacora_1` (`id_viaje`) USING BTREE,
  CONSTRAINT `fk_ae_bitacora_detalle_ae_bitacora_1` FOREIGN KEY (`id_viaje`) REFERENCES `vi_viaje` (`id_viaje`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for vi_viaje_formapago
-- ----------------------------
DROP TABLE IF EXISTS `vi_viaje_formapago`;
CREATE TABLE `vi_viaje_formapago` (
  `id_viaje_formapago` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_viaje` int(32) unsigned DEFAULT NULL,
  `cat_formapago` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_viaje_formapago`),
  KEY `fk_ae_bitacora_formapago_ae_catalogo_1` (`cat_formapago`) USING BTREE,
  KEY `fk_ae_bitacora_formapago_ae_bitacora_1` (`id_viaje`) USING BTREE,
  CONSTRAINT `fk_ae_bitacora_formapago_ae_bitacora_1` FOREIGN KEY (`id_viaje`) REFERENCES `vi_viaje` (`id_viaje`),
  CONSTRAINT `fk_ae_bitacora_formapago_ae_catalogo_1` FOREIGN KEY (`cat_formapago`) REFERENCES `cm_catalogo` (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for vi_viaje_incidencia
-- ----------------------------
DROP TABLE IF EXISTS `vi_viaje_incidencia`;
CREATE TABLE `vi_viaje_incidencia` (
  `id_viaje_incidencia` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `id_viaje` int(32) unsigned DEFAULT NULL,
  `cat_incidencias` int(32) unsigned DEFAULT NULL,
  `user_alta` int(32) DEFAULT NULL,
  `user_mod` int(32) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_viaje_incidencia`),
  KEY `fk_ae_bitacora_incidencia_ae_catalogo_1` (`cat_incidencias`) USING BTREE,
  KEY `fk_ae_bitacora_incidencia_ae_bitacora_1` (`id_viaje`) USING BTREE,
  CONSTRAINT `fk_ae_bitacora_incidencia_ae_bitacora_1` FOREIGN KEY (`id_viaje`) REFERENCES `vi_viaje` (`id_viaje`),
  CONSTRAINT `fk_ae_bitacora_incidencia_ae_catalogo_1` FOREIGN KEY (`cat_incidencias`) REFERENCES `cm_catalogo` (`id_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;
SET FOREIGN_KEY_CHECKS=1;
