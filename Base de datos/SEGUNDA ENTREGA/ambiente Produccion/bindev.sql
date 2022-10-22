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
  `picture` varchar(500) NULL,
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
  `picture` VARCHAR(1000) NOT NULL,
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
    ON DELETE RESTRICT
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
  PRIMARY KEY (`sale_report`, `status_report`),
  CONSTRAINT `UN_report_sale` UNIQUE (`sale_report`, `status_report`, `date`),
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
-- Table `bindev`.`productHistory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`productHistory` (
  `lineNumber` INT NOT NULL AUTO_INCREMENT,
  `idOfProduct` INT NOT NULL,
  `nameOfProduct` VARCHAR(500) NOT NULL,
  `oldStock` INT NOT NULL,
  `newStock` INT NOT NULL,
  `oldPrice` DECIMAL(10,2) NOT NULL,
  `newPrice` DECIMAL(10,2) NOT NULL,
  `dateOfEdit` date,
  PRIMARY KEY (`lineNumber`));
ALTER TABLE productHistory
AUTO_INCREMENT = 1000;
-- -----------------------------------------------------
-- Table `bindev`.`employeeHistory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`employeeHistory` (
  `lineNumber` INT NOT NULL AUTO_INCREMENT,
  `idEmployee` INT NOT NULL,
  `ciEmployee` INT NOT NULL,
  `nameOfEmployee` VARCHAR(500) NOT NULL,
  `oldRole` VARCHAR(500) NOT NULL,
  `newRole` VARCHAR(500) NOT NULL,
  `oldstate` TINYINT NOT NULL,
  `newstate` TINYINT NOT NULL,
  `dateOfEdit` date,
  PRIMARY KEY (`lineNumber`));
ALTER TABLE productHistory
AUTO_INCREMENT = 2000;
-- -----------------------------------------------------
-- Table `bindev`.`saleHistory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindev`.`saleHistory` (
  `lineNumber` INT NOT NULL AUTO_INCREMENT,
  `idEmployee` INT NOT NULL,
  `ciEmployee` INT NOT NULL,
  `nameOfEmployee` VARCHAR(500) NOT NULL,
  `oldRole` VARCHAR(500) NOT NULL,
  `newRole` VARCHAR(500) NOT NULL,
  `oldstate` TINYINT NOT NULL,
  `newstate` TINYINT NOT NULL,
  `dateOfEdit` date,
  PRIMARY KEY (`lineNumber`));
ALTER TABLE productHistory
AUTO_INCREMENT = 2000;

DROP TRIGGER IF EXISTS `bindev`.`sale_detail_VALIDATION`;
DELIMITER $$
USE `bindev`$$
CREATE DEFINER = CURRENT_USER TRIGGER `bindev`.`sale_detail_VALIDATION` BEFORE INSERT ON `sale_detail` FOR EACH ROW
BEGIN
declare stockTemp int;
declare totalParc decimal(10,2);
set stockTemp = (select stock from product where new.product_sale = barcode)-new.quantity;
if (stockTemp >=0) then 
update product set stock = stockTemp where barcode = new.product_sale;
set totalParc = new.quantity * (select price from product where new.product_sale = barcode);
set new.total = totalParc;
update sale set total = ((total + new.total) * 1.22) where id_sale = new.sale_id;
else
SIGNAL SQLSTATE '45000' SET message_text = 'NO HAY STOCK SUFICIENTE PARA REALIZAR LA OPERACION';
END if;
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `bindev`.`supply_detail_AMOUNT_TOTAL_AUTO`;
DELIMITER $$
USE `bindev`$$
CREATE DEFINER = CURRENT_USER TRIGGER `bindev`.`supply_detail_AMOUNT_TOTAL_AUTO` BEFORE INSERT ON `supply_detail` FOR EACH ROW
BEGIN
set new.amount_total = new.cost_unit * new.quantity;
update supply set total = ((total + new.amount_total) * 1.22) where id_supply = new.supply_id;
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `bindev`.`supply_detail_Actualiza_stock`;
DELIMITER $$
USE `bindev`$$
CREATE DEFINER = CURRENT_USER TRIGGER `bindev`.`supply_detail_Actualiza_stock` AFTER INSERT ON `supply_detail` FOR EACH ROW
BEGIN
update product set stock = stock + new.quantity where barcode = new.barcode_id; 
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

DROP TRIGGER IF EXISTS `bindev`.`ProdDispoParaPromo`;

DELIMITER $$
USE `bindev`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `bindev`.`ProdDispoParaPromo` BEFORE INSERT ON `promo` FOR EACH ROW
BEGIN 
declare stockTempo int;
declare cantUnidPromo int;
set cantUnidPromo = (Select stock from product where new.is_product = barcode);
set stockTempo = (select stock from product where new.have_product = barcode)-(new.quantity * cantUnidPromo);
if (new.quantity <=0) then
SIGNAL SQLSTATE '45002' SET message_text = 'CANTIDAD INCORRECTA';
else
if (stockTempo >=0) then 
update product set stock = stockTempo where barcode = new.have_product;
else
SIGNAL SQLSTATE '45001' SET message_text = 'NO HAY STOCK DEL PRODUCTO PARA AGREGAR AL PRODUCTO';
END if;
END if;
end$$
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
DROP TRIGGER IF EXISTS `bindev`.`InsertDatetimeReport`;
DELIMITER $$
USE `bindev`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `bindev`.`AutomaticDateReport` BEFORE INSERT ON `report` FOR EACH ROW
BEGIN 
	SET NEW.date = NOW();
END$$
DELIMITER ;

-- -----------------------------------------------------
-- INSERT BASICOS PARA CONFIGURACION INICIAL DEL SISTEMA
-- -----------------------------------------------------
-- -----------------------------------------------------
-- USUARIOS PARA TENER INGRESAR AL SISTEMA 
-- ----------------------------------------------------- 
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('system@seguridadcorporal.com', 'System', 'System', 'System', '0','--,.r.ad');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('master@seguridadcorporal.com', 'master', 'master', 'master', '22334455', 'master');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('ventas@seguridadcorporal.com', 'vendedor', 'vendedor', 'vendedor', '22334455', 'vendedor');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('compras@seguridadcorporal.com', 'comprador ', 'comprador', 'comprador', '22334455', 'comprador');
INSERT INTO `bindev`.`role` (`name_role`, `description`) VALUES ('SYSTEM', 'RESPUESTA AUTOMATICA DEL SISTEMA');
INSERT INTO `bindev`.`role` (`name_role`, `description`) VALUES ('VENDEDOR', 'Personal de ventas');
INSERT INTO `bindev`.`role` (`name_role`, `description`) VALUES ('COMPRADOR', 'Personal de compras');
INSERT INTO `bindev`.`role` (`name_role`, `description`) VALUES ('JEFE', 'Cargo de FEJE');
INSERT INTO `bindev`.`EMPLOYEE` (`ci`, `employee_user`, `employee_role`) VALUES ('1', '5000', 'SYSTEM');
INSERT INTO `bindev`.`EMPLOYEE` (`ci`, `employee_user`, `employee_role`) VALUES ('123', '5001', 'JEFE');
INSERT INTO `bindev`.`EMPLOYEE` (`ci`, `employee_user`, `employee_role`) VALUES ('1234', '5002', 'VENDEDOR');
INSERT INTO `bindev`.`EMPLOYEE` (`ci`, `employee_user`, `employee_role`) VALUES ('12345', '5003', 'COMPRADOR');


-- -----------------------------------------------------
-- ATRIBUTOS BASICOS DEL SISTEMA
-- -----------------------------------------------------
INSERT INTO `bindev`.`category` (`name`, `description`, `picture`) VALUES ('PROMOCIONES', 'CATEGORIA DESIGNADA PARA PROMOS','https://picsum.photos/200/300');
INSERT INTO `bindev`.`design` (`name`, `description`) VALUES ('PROMOCIONES', 'DISEÑO DESIGNADO PARA PROMOS');
INSERT INTO `bindev`.`size` (`name`, `description`) VALUES ('PROMOCIONES', 'TALLE DESIGNADO PARA LA PROMO');
INSERT INTO `bindev`.`status` (`name`, `description`) VALUES ('Respuesta Automatica', 'Respuesta automatica del sistema');
INSERT INTO `bindev`.`status` (`name`, `description`) VALUES ('Pendinte de cobro', 'Estado pendiente de cobro, la venta aun no fue confirmada pero su mercaderia se encuentra reservada');
INSERT INTO `bindev`.`status` (`name`, `description`) VALUES ('Venta confirmada', 'Venta confirmada, dinero capturado, la mercaderia tiene dueño');
INSERT INTO `bindev`.`status` (`name`, `description`) VALUES ('En transporte', 'Venta en calle, en viaje a la direccion ingresada en la venta');
INSERT INTO `bindev`.`status` (`name`, `description`) VALUES ('Entregada', 'Entrega de la venta confirmada');
INSERT INTO `bindev`.`status` (`name`, `description`) VALUES ('Pick UP', 'Levanta en el local');
INSERT INTO `bindev`.`status` (`name`, `description`) VALUES ('Cancelada', 'La venta fue cancelada');
-- -----------------------------------------------------
-- DATOS BASICOS PARA PRUEBAS
-- -----------------------------------------------------
INSERT INTO `bindev`.`category` (`name`, `description`, `picture`) VALUES ('Remeras', 'CATEGORIA DESIGNADA PARA REMERAS','https://i.ibb.co/Stt9FQZ/camiseta-de-manga-corta-1.png');
INSERT INTO `bindev`.`category` (`name`, `description`, `picture`) VALUES ('Chalecos', 'CATEGORIA DESIGNADA PARA CHALECOS','https://i.ibb.co/JRX30YY/casco-5.png');
INSERT INTO `bindev`.`category` (`name`, `description`, `picture`) VALUES ('Pantalones', 'CATEGORIA DESIGNADA PARA PANTALONES','https://i.ibb.co/TWgZS11/pantalones-2.png');
INSERT INTO `bindev`.`category` (`name`, `description`, `picture`) VALUES ('Guantes', 'CATEGORIA DESIGNADA PARA GUANTES','https://i.ibb.co/jJv3MV5/guantes.png');
INSERT INTO `bindev`.`category` (`name`, `description`, `picture`) VALUES ('Cascos', 'CATEGORIA DESIGNADA PARA CASCOS','https://i.ibb.co/6NTsTn5/sombrero.png');
INSERT INTO `bindev`.`category` (`name`, `description`, `picture`) VALUES ('Zapatos', 'CATEGORIA DESIGNADA PARA ZAPATOS','https://i.ibb.co/44Yv3F8/cadena-de-bloques.png');
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
INSERT INTO `bindev`.`PRODUCT` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`, `picture`) VALUES ('11', 'REMERA Clasica', '2', '2', '2', '30', '500', 'Descripcion del producto','https://i.ibb.co/mRpFg8R/8negro.jpg & https://i.ibb.co/9bKNc4d/remera2.jpg & https://i.ibb.co/9bKNc4d/remera2.jpg');
INSERT INTO `bindev`.`PRODUCT` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`, `picture`) VALUES ('11', 'REMERA Clasica', '2', '3', '2', '8', '500', 'Descripcion del producto','https://i.ibb.co/mRpFg8R/8negro.jpg & https://i.ibb.co/9bKNc4d/remera2.jpg & https://i.ibb.co/9bKNc4d/remera2.jpg');
INSERT INTO `bindev`.`PRODUCT` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`, `picture`) VALUES ('11', 'REMERA Clasica', '2', '3', '3', '11', '500', 'Descripcion del producto','https://i.ibb.co/mRpFg8R/8negro.jpg & https://i.ibb.co/9bKNc4d/remera2.jpg & https://i.ibb.co/9bKNc4d/remera2.jpg');
INSERT INTO `bindev`.`PRODUCT` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`, `picture`) VALUES ('11', 'REMERA Clasica', '2', '3', '4', '0', '500', 'Descripcion del producto','https://i.ibb.co/mRpFg8R/8negro.jpg & https://i.ibb.co/9bKNc4d/remera2.jpg & https://i.ibb.co/9bKNc4d/remera2.jpg');
INSERT INTO `bindev`.`PRODUCT` (`id_product`, `name`, `product_category`, `product_design`, `product_size`, `stock`, `price`, `description`, `picture`) VALUES ('11', 'REMERA Clasica', '2', '2', '4', '21', '500', 'Descripcion del producto','https://i.ibb.co/mRpFg8R/8negro.jpg & https://i.ibb.co/9bKNc4d/remera2.jpg & https://i.ibb.co/9bKNc4d/remera2.jpg');





SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
