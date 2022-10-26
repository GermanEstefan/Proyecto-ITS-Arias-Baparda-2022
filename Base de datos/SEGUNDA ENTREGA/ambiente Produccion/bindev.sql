-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
-- -----------------------------------------------------
-- Schema bindev
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bindev` DEFAULT CHARACTER SET utf8 ;
USE `bindev` ;

-- -----------------------------------------------------
-- Table `bindev`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`category` (
  `id_category` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `picture` LONGBLOB NULL,
  PRIMARY KEY (`id_category`),
  constraint `category_UNIQUE` UNIQUE  (`name`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `bindev`.`design`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`design` (
  `id_design` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_design`),
  constraint `design_UNIQUE` UNIQUE (`name`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindev`.`size`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`size` (
  `id_size` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_size`),
  constraint `size_UNIQUE` UNIQUE (`name` ))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindev`.`supplier`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`supplier` (
  `id_supplier` INT AUTO_INCREMENT NOT NULL,
  `rut` varchar(12) NOT NULL,
  `company_name` VARCHAR(150) NOT NULL,
  `address` VARCHAR(500) NOT NULL,
  `phone` VARCHAR(200) NOT NULL,
  `state` TINYINT default 1,
  PRIMARY KEY (`id_supplier`),
  constraint `CH_state_supplier` CHECK (`state`<=1 and `state`>=0),
  constraint `UN_rut` UNIQUE  (`rut`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindev`.`role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`role` (
  `name_role` VARCHAR(100) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`name_role`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindev`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`user` (
  `id_user` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(200) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `surname` VARCHAR(150) NOT NULL,
  `address` VARCHAR(500) NULL,
  `phone` VARCHAR(50) NULL,
  `password` VARCHAR(200) NOT NULL,
  `state` TINYINT default 1,
  PRIMARY KEY (`id_user`),
  constraint `CH_state_user` CHECK (`state`<=1 and `state`>=0),
  constraint `email_UNIQUE` UNIQUE (`email`))
ENGINE = InnoDB;
ALTER Table user
AUTO_INCREMENT=5000;
-- -----------------------------------------------------
-- Table `bindev`.`status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`status` (
  `id_status` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_status`),
  constraint `UN_status` UNIQUE (`name`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindev`.`delivery_time`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`delivery_time` (
  `id_delivery` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_delivery`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `bindev`.`employee`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`employee` (
  `ci` INT NOT NULL,
  `employee_user` INT NOT NULL,
  `employee_role` VARCHAR(100) NOT NULL,
  `state` TINYINT default 1,
  PRIMARY KEY (`employee_user`),
  constraint `UN_ci` unique (`ci`),
  constraint `CH_state_employee` CHECK (`state`<=1 and `state`>=0),
  CONSTRAINT `FK_employee_role`
    FOREIGN KEY (`employee_role`)
    REFERENCES `bindev`.`role` (`name_role`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_employee_user`
    FOREIGN KEY (`employee_user`)
    REFERENCES `bindev`.`user` (`id_user`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindev`.`customer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`customer` (
  `customer_user` INT NOT NULL,
  `company_name` VARCHAR(300) NULL,
  `rut_nr` varchar(12) NULL,
  PRIMARY KEY (`customer_user`),
  constraint `UN_rut` UNIQUE  (`rut_nr`),
  CONSTRAINT `FK_user_id`
    FOREIGN KEY (`customer_user`)
    REFERENCES `bindev`.`user` (`id_user`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindev`.`supply`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`supply` (
  `id_supply` INT NOT NULL AUTO_INCREMENT,
  `date` DATETIME NOT NULL,
  `supplier_id` INT NOT NULL,
  `employee_ci` INT NOT NULL,
  `comment` VARCHAR(500) NULL,
  `total` DECIMAL(10,2) NOT NULL default 0,
  PRIMARY KEY (`id_supply`),
  CONSTRAINT `FK_supply_supplier`
    FOREIGN KEY (`supplier_id`)
    REFERENCES `bindev`.`supplier` (`id_supplier`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_supply_employee`
    FOREIGN KEY (`employee_ci`)
    REFERENCES `bindev`.`employee` (`ci`)
    ON DELETE RESTRICT
    ON UPDATE cascade)
ENGINE = InnoDB;

ALTER TABLE supply
AUTO_INCREMENT = 300000;
-- -----------------------------------------------------
-- Table `bindev`.`product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`product` (
  `barcode` INT NOT NULL AUTO_INCREMENT,
  `id_product` INT NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `product_category` INT NOT NULL,
  `product_design` INT NOT NULL,
  `product_size` INT NOT NULL,
  `stock` INT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `picture` LONGBLOB NULL,
  `state` TINYINT default 1,
  PRIMARY KEY (`barcode`),
  constraint `CH_state_product` CHECK (`state`<=1 and `state`>=0),
  constraint `UN_product` UNIQUE (`id_product`, `product_design`, `product_size`),
  CONSTRAINT `FK_category_product`
    FOREIGN KEY (`product_category`)
    REFERENCES `bindev`.`category` (`id_category`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `FK_design_product`
    FOREIGN KEY (`product_design`)
    REFERENCES `bindev`.`design` (`id_design`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `FK_size_product`
    FOREIGN KEY (`product_size`)
    REFERENCES `bindev`.`size` (`id_size`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;
ALTER TABLE product
AUTO_INCREMENT = 12312300;


-- -----------------------------------------------------
-- Table `bindev`.`supply_detail`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`supply_detail` (
  `supply_id` INT NOT NULL,
  `barcode_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `cost_unit` DECIMAL(10,2) NOT NULL,
  `amount_total` DECIMAL(10,2) NOT NULL, 
  PRIMARY KEY (`supply_id`, `barcode_id`),
CONSTRAINT `FK_product_reference`
    FOREIGN KEY (`barcode_id`)
    REFERENCES `bindev`.`product` (`barcode`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_supply_reference`
    FOREIGN KEY (`supply_id`)
    REFERENCES `bindev`.`supply` (`id_supply`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindev`.`sale`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`sale` (
  `id_sale` INT NOT NULL AUTO_INCREMENT,
  `date` DATETIME NOT NULL ,
  `address` VARCHAR(500) NOT NULL,
  `user_purchase` INT NOT NULL,
  `sale_delivery` INT NOT NULL,
  `payment` INT NOT NULL,
  `total` DECIMAL(10,2) NOT NULL default 0,
  PRIMARY KEY (`id_sale`),
  CONSTRAINT `FK_customer_user`
    FOREIGN KEY (`user_purchase`)
    REFERENCES `bindev`.`customer` (`customer_user`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_delivery_sale`
    FOREIGN KEY (`sale_delivery`)
    REFERENCES `bindev`.`delivery_time` (`id_delivery`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;
ALTER TABLE sale
AUTO_INCREMENT=700000;

-- -----------------------------------------------------
-- Table `bindev`.`report`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`report` (
  `sale_report` INT NOT NULL,
  `status_report` INT NOT NULL,
  `employee_report` INT NOT NULL,
  `date` DATETIME NOT NULL ,
  `comment` VARCHAR(500) NULL,
  PRIMARY KEY (`sale_report`),
  CONSTRAINT `FK_sale_report`
    FOREIGN KEY (`sale_report`)
    REFERENCES `bindev`.`sale` (`id_sale`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_status_report`
    FOREIGN KEY (`status_report`)
    REFERENCES `bindev`.`status` (`id_status`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_employye_report`
    FOREIGN KEY (`employee_report`)
    REFERENCES `bindev`.`employee` (`ci`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindev`.`sale_detail`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`sale_detail` (
  `sale_id` INT NOT NULL,
  `product_sale` INT NOT NULL,
  `quantity` INT NOT NULL,
  `total` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`sale_id`, `product_sale`),
  CONSTRAINT `FK_id_sale`
    FOREIGN KEY (`sale_id`)
    REFERENCES `bindev`.`sale` (`id_sale`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_prod_sale`
    FOREIGN KEY (`product_sale`)
    REFERENCES `bindev`.`product` (`barcode`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindev`.`promo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`promo` (
  `is_product` INT NOT NULL,
  `have_product` INT NOT NULL,
  `quantity` INT NOT NULL,
  PRIMARY KEY (`is_product`, `have_product`),
  CONSTRAINT `FK_is_product`
    FOREIGN KEY (`is_product`)
    REFERENCES `bindev`.`product` (`barcode`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_have_product`
    FOREIGN KEY (`have_product`)
    REFERENCES `bindev`.`product` (`barcode`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `bindev`.`reportHistory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`reportHistory` (
  `idReg` INT NOT NULL AUTO_INCREMENT,
  `Type` VARCHAR(200) NOT NULL,
  `sale_report` INT NOT NULL,
  `status_report` INT NOT NULL,
  `employee_report` INT NOT NULL,
  `date` DATETIME NOT NULL ,
  `comment` VARCHAR(500) NULL,
  PRIMARY KEY (`idReg`))
ENGINE = InnoDB;
ALTER TABLE reportHistory
AUTO_INCREMENT=10;

-- -----------------------------------------------------
-- Table `bindev`.`puntuation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`puntuation` (
  `idClient` INT NOT NULL,
  `barcode` INT NOT NULL,
  `date` INT NOT NULL,
  `stars` INT NOT NULL,
  `comment` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`idClient`,`barcode`),
CONSTRAINT `FK_client_user`
    FOREIGN KEY (`idClient`)
    REFERENCES `bindev`.`user` (`id_user`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
CONSTRAINT `FK_barcode_product`
    FOREIGN KEY (`barcode`)
    REFERENCES `bindev`.`product` (`barcode`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


DROP TRIGGER IF EXISTS `bindev`.`supply_detail_AMOUNT_TOTAL_AUTO`;
DELIMITER $$
USE `bindev`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `bindev`.`setTotalEnSale` BEFORE INSERT ON `sale_detail` FOR EACH ROW
BEGIN
declare totalParc decimal(10,2); 
set totalParc = new.quantity * (select price from product where new.product_sale = barcode);
set new.total = totalParc;
update sale set total = (total + new.total) where id_sale = new.sale_id;
END$$
DELIMITER ;


DROP TRIGGER IF EXISTS `bindev`.`supply_detail_AMOUNT_TOTAL_AUTO`;
DELIMITER $$
USE `bindev`$$
CREATE DEFINER = CURRENT_USER TRIGGER `bindev`.`supply_detail_AMOUNT_TOTAL_AUTO` BEFORE INSERT ON `supply_detail` FOR EACH ROW
BEGIN
set new.amount_total = new.cost_unit * new.quantity;
update supply set total = (total + new.amount_total) where id_supply = new.supply_id;
END$$
DELIMITER ;


DROP TRIGGER IF EXISTS `bindev`.`Auto_insertado`;
DELIMITER $$
USE `bindev`$$
CREATE DEFINER = CURRENT_USER TRIGGER `bindev`.`Auto_insertado` AFTER INSERT ON `user` FOR EACH ROW
BEGIN
insert customer set customer_user = new.id_user; 
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `bindev`.`InsertDatetimeSupply`;
DELIMITER $$
USE `bindev`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `bindev`.`AutomaticDate` BEFORE INSERT ON `supply` FOR EACH ROW
BEGIN 
	SET NEW.date = NOW();
END$$
DELIMITER ;
DROP TRIGGER IF EXISTS `bindev`.`InsertDatetimeSale`;
DELIMITER $$
USE `bindev`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `bindev`.`AutomaticDateSale` BEFORE INSERT ON `sale` FOR EACH ROW
BEGIN 
	SET NEW.date = NOW();
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `bindev`.`InsertRegHistoryByUpdate`;

DELIMITER $$
USE `bindev`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `bindev`.`InsertRegHistoryByUpdate` BEFORE UPDATE ON `report` FOR EACH ROW
BEGIN
SET NEW.date = NOW();
INSERT INTO reporthistory ( `Type`,`sale_report`,`status_report`,`employee_report`,`date`,`comment`)
VALUES ('UpdateLog',NEW.sale_report,NEW.status_report,NEW.employee_report,NEW.date,NEW.comment);
END$$
DELIMITER ;
DROP TRIGGER IF EXISTS `bindev`.`AutomaticDateReport`;

DELIMITER $$
USE `bindev`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `bindev`.`AutomaticDateReport` BEFORE INSERT ON `report` FOR EACH ROW
BEGIN 
	SET NEW.date = NOW();
INSERT INTO reporthistory ( `Type`,`sale_report`,`status_report`,`employee_report`,`date`,`comment`)
VALUES ('OpenReg',NEW.sale_report,NEW.status_report,NEW.employee_report,NEW.date,NEW.comment);
END$$
DELIMITER ;


-- -----------------------------------------------------
-- INSERT BASICOS PARA CONFIGURACION INICIAL DEL SISTEMA
-- -----------------------------------------------------
-- -----------------------------------------------------
-- USUARIOS PARA TENER INGRESAR AL SISTEMA 
-- ----------------------------------------------------- 
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('system@seguridadcorporal.com', 'System', 'Response', 'Null', '0','--,.r.ad');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('master@seguridadcorporal.com', 'master', 'master', 'Address master', '22334455', 'master');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('ventas@seguridadcorporal.com', 'vendedor', 'vendedor', 'Address vendedor', '22334455', 'vendedor');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('compras@seguridadcorporal.com', 'comprador ', 'comprador', 'Address comprador', '22334455', 'comprador');
INSERT INTO `bindev`.`role` (`name_role`, `description`) VALUES ('SYSTEM', 'RESPUESTA AUTOMATICA DEL SISTEMA');
INSERT INTO `bindev`.`role` (`name_role`, `description`) VALUES ('VENDEDOR', 'Personal de ventas');
INSERT INTO `bindev`.`role` (`name_role`, `description`) VALUES ('COMPRADOR', 'Personal de compras');
INSERT INTO `bindev`.`role` (`name_role`, `description`) VALUES ('JEFE', 'Cargo de JEFE');
INSERT INTO `bindev`.`EMPLOYEE` (`ci`, `employee_user`, `employee_role`) VALUES ('1', '5000', 'SYSTEM');
INSERT INTO `bindev`.`EMPLOYEE` (`ci`, `employee_user`, `employee_role`) VALUES ('123', '5001', 'JEFE');
INSERT INTO `bindev`.`EMPLOYEE` (`ci`, `employee_user`, `employee_role`) VALUES ('1234', '5002', 'VENDEDOR');
INSERT INTO `bindev`.`EMPLOYEE` (`ci`, `employee_user`, `employee_role`) VALUES ('12345', '5003', 'COMPRADOR');


-- -----------------------------------------------------
-- ATRIBUTOS BASICOS DEL SISTEMA
-- -----------------------------------------------------
INSERT INTO `bindev`.`category` (`name`, `description`) VALUES ('PROMOCIONES', 'CATEGORIA DESIGNADA PARA PROMOS');
INSERT INTO `bindev`.`design` (`name`, `description`) VALUES ('PROMOCIONES', 'DISEÑO DESIGNADO PARA PROMOS');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('PROMOCIONES', 'TALLE DESIGNADO PARA LA PROMO');
INSERT INTO `bindev`.`status` (`name`, `description`) VALUES ('RESPUESTA AUTOMATICA DEL SISTEMA', 'Respuesta automatica del sistema');
INSERT INTO `bindev`.`status` (`name`, `description`) VALUES ('PENDIENTE', 'Estado pendiente de cobro, la venta aun no fue confirmada pero su mercaderia se encuentra reservada');
INSERT INTO `bindev`.`status` (`name`, `description`) VALUES ('CONFIRMADO', 'Venta confirmada, dinero capturado, la mercaderia tiene dueño');
INSERT INTO `bindev`.`status` (`name`, `description`) VALUES ('EN VIAJE', 'Venta en calle, en viaje a la direccion ingresada en la venta');
INSERT INTO `bindev`.`status` (`name`, `description`) VALUES ('ENTREGADO', 'Entrega de la venta confirmada');
INSERT INTO `bindev`.`status` (`name`, `description`) VALUES ('PICK-UP', 'Levanta en el local');
INSERT INTO `bindev`.`status` (`name`, `description`) VALUES ('CANCELADA', 'La venta fue cancelada');
INSERT INTO `bindev`.`delivery_time` (`name`, `description`) VALUES ('Lun a Vie de 8:00 a 13:00', 'Horario 1 abarca desde la apertura hasta la hora de descanso');
INSERT INTO `bindev`.`delivery_time` (`name`, `description`) VALUES ('Lun a Vie de 14:00 a 19:00', 'Horario 2 abarca desde el descanso hasta la hora de cierre');
INSERT INTO `bindev`.`delivery_time` (`name`, `description`) VALUES ('Sabados de 8:00 a 15:00', 'Horario 3 abarca desde la apertura del dia Sabado');
INSERT INTO `bindev`.`delivery_time` (`name`, `description`) VALUES ('Domingos de 8:00 a 12:00', 'Horario 4 abarca desde la apertura del dia Domingo');

-- -----------------------------------------------------
-- DATOS BASICOS PARA PRUEBAS
-- -----------------------------------------------------
INSERT INTO `bindev`.`category` (`name`, `description`) VALUES ('Remeras', 'CATEGORIA DESIGNADA PARA REMERAS');
INSERT INTO `bindev`.`category` (`name`, `description`) VALUES ('Chalecos', 'CATEGORIA DESIGNADA PARA CHALECOS');
INSERT INTO `bindev`.`category` (`name`, `description`) VALUES ('Pantalones', 'CATEGORIA DESIGNADA PARA PANTALONES');
INSERT INTO `bindev`.`category` (`name`, `description`) VALUES ('Guantes', 'CATEGORIA DESIGNADA PARA GUANTES');
INSERT INTO `bindev`.`category` (`name`, `description`) VALUES ('Cascos', 'CATEGORIA DESIGNADA PARA CASCOS');
INSERT INTO `bindev`.`category` (`name`, `description`) VALUES ('Zapatos', 'CATEGORIA DESIGNADA PARA ZAPATOS');
INSERT INTO `bindev`.`design` (`name`, `description`) VALUES ('BLANCO', 'COLOR BLANCO');
INSERT INTO `bindev`.`design` (`name`, `description`) VALUES ('NEGRO', 'COLOR NEGRO');
INSERT INTO `bindev`.`design` (`name`, `description`) VALUES ('ROJO', 'COLOR ROJO');
INSERT INTO `bindev`.`design` (`name`, `description`) VALUES ('VERDE', 'COLOR VERDE');
INSERT INTO `bindev`.`design` (`name`, `description`) VALUES ('AZUL', 'COLOR AZUL');
INSERT INTO `bindev`.`design` (`name`, `description`) VALUES ('NARANJA', 'COLOR NARANJA');
INSERT INTO `bindev`.`design` (`name`, `description`) VALUES ('AMARILLO', 'COLOR AMARILLO');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('XS', 'DESCRIPCION DEL TALLE');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('S', 'DESCRIPCION DEL TALLE');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('M', 'DESCRIPCION DEL TALLE');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('L', 'DESCRIPCION DEL TALLE');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('XL', 'DESCRIPCION DEL TALLE');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('XXL', 'DESCRIPCION DEL TALLE');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('TALLE 36', 'DESCRIPCION DEL TALLE');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('TALLE 37', 'DESCRIPCION DEL TALLE');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('TALLE 38', 'DESCRIPCION DEL TALLE');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('TALLE 39', 'DESCRIPCION DEL TALLE');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('TALLE 40', 'DESCRIPCION DEL TALLE');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('TALLE 41', 'DESCRIPCION DEL TALLE');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('TALLE 42', 'DESCRIPCION DEL TALLE');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('TALLE 43', 'DESCRIPCION DEL TALLE');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('TALLE 44', 'DESCRIPCION DEL TALLE');
INSERT INTO `bindev`.`PRODUCT` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('11', 'REMERA Clasica', '2', '2', '2', '30', '500', 'Descripcion del producto');
INSERT INTO `bindev`.`PRODUCT` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('11', 'REMERA Clasica', '2', '3', '2', '8', '500', 'Descripcion del producto');
INSERT INTO `bindev`.`PRODUCT` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('11', 'REMERA Clasica', '2', '3', '3', '11', '500', 'Descripcion del producto');
INSERT INTO `bindev`.`PRODUCT` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('11', 'REMERA Clasica', '2', '3', '4', '0', '500', 'Descripcion del producto');
INSERT INTO `bindev`.`PRODUCT` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('11', 'REMERA Clasica', '2', '2', '4', '21', '500', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('12', 'REMERA CALIDAD', '2', '4', '4', '3', '800', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('12', 'REMERA CALIDAD', '2', '4', '5', '3', '800', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('12', 'REMERA CALIDAD', '2', '4', '6', '3', '800', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('12', 'REMERA CALIDAD', '2', '4', '7', '3', '800', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('12', 'REMERA CALIDAD', '2', '5', '4', '3', '800', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('12', 'REMERA CALIDAD', '2', '5', '5', '3', '800', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('12', 'REMERA CALIDAD', '2', '5', '7', '3', '800', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('12', 'REMERA CALIDAD', '2', '6', '3', '3', '800', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('12', 'REMERA CALIDAD', '2', '6', '4', '3', '800', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('13', 'PANTALON Clasico', '4', '7', '4', '19', '1400', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('13', 'PANTALON Clasico', '4', '7', '5', '22', '1400', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('13', 'PANTALON Clasico', '4', '7', '6', '40', '1400', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('13', 'PANTALON Clasico', '4', '7', '7', '18', '1400', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('13', 'PANTALON Clasico', '4', '3', '4', '21', '1400', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('13', 'PANTALON Clasico', '4', '3', '5', '12', '1400', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('13', 'PANTALON Clasico', '4', '3', '6', '1', '1400', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('14', 'PANTALON alta calidad', '4', '7', '4', '22', '2000', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('14', 'PANTALON alta calidad', '4', '7', '5', '81', '2000', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('14', 'PANTALON alta calidad', '4', '7', '6', '41', '2000', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('14', 'PANTALON alta calidad', '4', '7', '7', '9', '2000', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('14', 'PANTALON alta calidad', '4', '3', '3', '20', '2000', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('14', 'PANTALON alta calidad', '4', '3', '6', '0', '2000', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('14', 'PANTALON alta calidad', '4', '3', '7', '12', '2000', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('17', 'CASCO clasico', '6', '8', '3', '100', '1200', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('17', 'CASCO clasico', '6', '8', '4', '40', '1200', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('17', 'CASCO clasico', '6', '8', '5', '70', '1200', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('17', 'CASCO clasico', '6', '8', '6', '80', '1200', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('18', 'ZAPATOS clasicos', '7', '3', '8', '300', '2200', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('18', 'ZAPATOS clasicos', '7', '3', '9', '1300', '2200', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('18', 'ZAPATOS clasicos', '7', '3', '10', '890', '2200', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('18', 'ZAPATOS clasicos', '7', '3', '11', '464', '2200', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('18', 'ZAPATOS clasicos', '7', '3', '12', '921', '2200', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('18', 'ZAPATOS clasicos', '7', '3', '13', '376', '2200', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('18', 'ZAPATOS clasicos', '7', '3', '14', '20', '2200', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('18', 'ZAPATOS clasicos', '7', '3', '15', '80', '2200', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('18', 'ZAPATOS clasicos', '7', '3', '16', '3', '2200', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('19', 'GUANTES clasico', '5', '8', '2', '41', '450', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('19', 'GUANTES clasico', '5', '8', '3', '612', '450', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('19', 'GUANTES clasico', '5', '8', '4', '90', '450', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('19', 'GUANTES clasico', '5', '8', '5', '9', '450', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('19', 'GUANTES clasico', '5', '7', '2', '225', '450', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('19', 'GUANTES clasico', '5', '7', '3', '570', '450', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('19', 'GUANTES clasico', '5', '7', '4', '0', '450', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('19', 'GUANTES clasico', '5', '7', '5', '9', '450', 'Descripcion del producto');
INSERT INTO `bindev`.`product` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`) VALUES ('1000', 'PROMO 0', '1', '1', '1', '0', '0', 'promoHardcodeada');

INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('nicolas@gmail.com', 'Nicolas', 'Alvarez', 'Casa de nico', '998877','Nicolas');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('Matias4371@gmail.com', 'Nacho', 'Arman-Duon', 'Casa de Nacho', '998877','Nacho');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('fabricioRivera@gmail.com', 'Fabricio', 'Rivera', 'Casa de Fabri', '998877','Fabricio');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('german.estefan81@gmail.com', 'German', 'Estefan', 'Casa de German', '998877','German');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('lauraacuna@gmail.com', 'Laura', 'Acuña', 'Laboratorio 1', '','DisenoWeb');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('LeoCarambula@gmail.com', 'Leonardo', 'Carambula', 'Laboratorio 2', '998877','ProgramacionWeb');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('chrisBarrios1@gmail.com', 'Christian', 'Barrios', 'Laboratorio 3', '998877','SistemasOperativos');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('LuisSuarez@gmail.com', 'Luis', 'Suarez', 'Nacional', '9','LuisSuarez');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('TonyPacheco@gmail.com', 'Tony', 'Pacheco', 'Penarol', '7','TonyPacheco');

INSERT INTO `bindev`.`supplier` (`rut`, `company_name`, `address`, `phone`) VALUES ('211003420017', 'Montevideo Uniformes', 'Dr. Salvador Ferrer Serra 2172', '24015020 - 29000000');
INSERT INTO `bindev`.`supplier` (`rut`, `company_name`, `address`, `phone`) VALUES ('211003420016', 'fupi SRL', 'Av. Uruguay 1124', '29011024');
INSERT INTO `bindev`.`supplier` (`rut`, `company_name`, `address`, `phone`) VALUES ('211003420015', 'Vicas SRL', ' Río Negro 1566', '29023624');
INSERT INTO `bindev`.`supplier` (`rut`, `company_name`, `address`, `phone`) VALUES ('211003420014', 'Garimport', 'Vilardebó 2133', '22095620 - Cajas 098748234');
INSERT INTO `bindev`.`supplier` (`rut`, `company_name`, `address`, `phone`) VALUES ('211003420013', 'Mundo Trabajo', 'Justicia 2233', '22010377 hablar con Carlos');

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
