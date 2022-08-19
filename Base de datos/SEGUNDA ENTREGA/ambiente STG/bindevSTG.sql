-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
-- -----------------------------------------------------
-- Schema bindevstg
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bindevstg` DEFAULT CHARACTER SET utf8 ;
USE `bindevstg` ;

-- -----------------------------------------------------
-- Table `bindevstg`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`category` (
  `id_category` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_category`),
  constraint `category_UNIQUE` UNIQUE  (`name`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `bindevstg`.`desing`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`desing` (
  `id_desing` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_desing`),
  constraint `desing_UNIQUE` UNIQUE (`name`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevstg`.`size`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`size` (
  `id_size` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_size`),
  constraint `size_UNIQUE` UNIQUE (`name` ))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevstg`.`disburse`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`disburse` (
  `id_disburse` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `state` TINYINT default 1,
  PRIMARY KEY (`id_disburse`),
  constraint `CH_state_disburse` CHECK (`state`<=1 and `state`>=0),
  constraint `disburse_UNIQUE` UNIQUE(`name`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `bindevstg`.`supplier`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`supplier` (
  `id_supplier` INT AUTO_INCREMENT NOT NULL,
  `rut` varchar(12) NOT NULL,
  `company_name` VARCHAR(150) NOT NULL,
  `address` VARCHAR(500) NOT NULL,
  `phone` VARCHAR(200) NOT NULL,
  `state` TINYINT default 1,
  PRIMARY KEY (`id_supplier`),
  constraint `CH_state_supplier` CHECK (`state`<=1 and `state`>=0),
  constraint `UN_rut_sucursal` UNIQUE  (`rut` , `company_name` ))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevstg`.`role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`role` (
  `name_role` VARCHAR(100) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`name_role`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevstg`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`user` (
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
-- Table `bindevstg`.`status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`status` (
  `id_status` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_status`),
  constraint `UN_status` UNIQUE (`name`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevstg`.`delivery_time`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`delivery_time` (
  `id_delivery` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `state` TINYINT default 1,
  PRIMARY KEY (`id_delivery`),
  constraint `CH_state_delivery` CHECK (`state`<=1 and `state`>=0))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevstg`.`payment_method`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`payment_method` (
  `id_pay_meth` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `state` TINYINT default 1,
  PRIMARY KEY (`id_pay_meth`),
  constraint `CH_state_PayMet` CHECK (`state`<=1 and `state`>=0),
  constraint `UN_pay_meth` UNIQUE (`name`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevstg`.`photos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`photos` (
  `id_photo` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `state` TINYINT default 1,
  PRIMARY KEY (`id_photo`),
constraint `CH_state` CHECK (`state`<=1 and `state`>=0))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevstg`.`discount`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`discount` (
  `value` DECIMAL(4, 2) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `state` TINYINT default 1,
  PRIMARY KEY (`value`),
  constraint `CH_state_Discount` CHECK (`state`<=1 and `state`>=0))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevstg`.`employee`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`employee` (
  `ci` INT NOT NULL,
  `employee_user` INT NOT NULL,
  `employee_role` VARCHAR(100) NOT NULL,
  `state` TINYINT default 1,
  PRIMARY KEY (`employee_user`),
  constraint `UN_ci` unique (`ci`),
  constraint `CH_state_employee` CHECK (`state`<=1 and `state`>=0),
  CONSTRAINT `FK_employee_role`
    FOREIGN KEY (`employee_role`)
    REFERENCES `bindevstg`.`role` (`name_role`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_employee_user`
    FOREIGN KEY (`employee_user`)
    REFERENCES `bindevstg`.`user` (`id_user`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------state
-- Table `bindevstg`.`customer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`customer` (
  `customer_user` INT NOT NULL,
  `company_name` VARCHAR(300) NULL,
  `rut_nr` varchar(12) NULL,
  PRIMARY KEY (`customer_user`),
  constraint `UN_rut_company` UNIQUE  (`rut_nr`, `company_name`),
  CONSTRAINT `FK_user_id`
    FOREIGN KEY (`customer_user`)
    REFERENCES `bindevstg`.`user` (`id_user`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevstg`.`supply`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`supply` (
  `id_supply` INT NOT NULL AUTO_INCREMENT,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  `supplier_id` INT NOT NULL,
  `employee_ci` INT NOT NULL,
  `disburse_method` INT NOT NULL,
  `comment` VARCHAR(500) NULL,
  PRIMARY KEY (`id_supply`),
  CONSTRAINT `FK_supply_supplier`
    FOREIGN KEY (`supplier_id`)
    REFERENCES `bindevstg`.`supplier` (`id_supplier`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_supply_employee`
    FOREIGN KEY (`employee_ci`)
    REFERENCES `bindevstg`.`employee` (`ci`)
    ON DELETE RESTRICT
    ON UPDATE cascade,
  CONSTRAINT `FK_supply_disburse`
    FOREIGN KEY (`disburse_method`)
    REFERENCES `bindevstg`.`disburse` (`id_disburse`)
    ON DELETE RESTRICT
    ON UPDATE cascade)
ENGINE = InnoDB;

ALTER TABLE supply
AUTO_INCREMENT = 300000;
-- -----------------------------------------------------
-- Table `bindevstg`.`product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`product` (
  `barcode` INT NOT NULL AUTO_INCREMENT,
  `id_product` INT NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `product_category` INT NOT NULL,
  `product_desing` INT NOT NULL,
  `product_size` INT NOT NULL,
  `stock` INT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `state` TINYINT default 1,
  PRIMARY KEY (`barcode`),
  constraint `CH_state_product` CHECK (`state`<=1 and `state`>=0),
  constraint `UN_product` UNIQUE (`id_product`, `product_category`, `product_desing`, `product_size`),
  CONSTRAINT `FK_category_product`
    FOREIGN KEY (`product_category`)
    REFERENCES `bindevstg`.`category` (`id_category`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_desing_product`
    FOREIGN KEY (`product_desing`)
    REFERENCES `bindevstg`.`desing` (`id_desing`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_size_product`
    FOREIGN KEY (`product_size`)
    REFERENCES `bindevstg`.`size` (`id_size`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;
ALTER TABLE product
AUTO_INCREMENT = 12312300;

-- -----------------------------------------------------
-- Table `bindevstg`.`galery`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`galery` (
  `product_galery` INT NOT NULL,
  `photo_galery` INT NOT NULL,
  PRIMARY KEY (`product_galery`, `photo_galery`),
	CONSTRAINT `FK_product_galery`
    FOREIGN KEY (`product_galery`)
    REFERENCES `bindevstg`.`product` (`barcode`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `FK_photo_product`
    FOREIGN KEY (`photo_galery`)
    REFERENCES `bindevstg`.`photos` (`id_photo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevstg`.`supply_detail`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`supply_detail` (
  `supply_id` INT NOT NULL,
  `barcode_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `cost_unit` DECIMAL(10,2) NOT NULL,
  `amount_total` DECIMAL(10,2) NULL COMMENT 'cost_unit por quantity',
  PRIMARY KEY (`supply_id`, `barcode_id`),
CONSTRAINT `FK_product_reference`
    FOREIGN KEY (`barcode_id`)
    REFERENCES `bindevstg`.`product` (`barcode`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_supply_reference`
    FOREIGN KEY (`supply_id`)
    REFERENCES `bindevstg`.`supply` (`id_supply`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevstg`.`sale`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`sale` (
  `id_sale` INT NOT NULL AUTO_INCREMENT,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL ,
  `address` VARCHAR(500) NOT NULL,
  `user_purchase` INT NOT NULL,
  `sale_delivery` INT NOT NULL,
  `pay_met` INT NOT NULL,
  PRIMARY KEY (`id_sale`),
  CONSTRAINT `FK_customer_user`
    FOREIGN KEY (`user_purchase`)
    REFERENCES `bindevstg`.`customer` (`customer_user`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_delivery_sale`
    FOREIGN KEY (`sale_delivery`)
    REFERENCES `bindevstg`.`delivery_time` (`id_delivery`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_pay_sale`
    FOREIGN KEY (`pay_met`)
    REFERENCES `bindevstg`.`payment_method` (`id_pay_meth`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;
ALTER TABLE sale
AUTO_INCREMENT=700000;

-- -----------------------------------------------------
-- Table `bindevstg`.`report`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`report` (
  `sale_report` INT NOT NULL,
  `status_report` INT NOT NULL,
  `employee_report` INT NOT NULL,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL ,
  `comment` VARCHAR(500) NULL,
  PRIMARY KEY (`sale_report`, `status_report`),
  CONSTRAINT `UN_report_sale` UNIQUE (`sale_report`, `status_report`, `date`),
  CONSTRAINT `FK_sale_report`
    FOREIGN KEY (`sale_report`)
    REFERENCES `bindevstg`.`sale` (`id_sale`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_status_report`
    FOREIGN KEY (`status_report`)
    REFERENCES `bindevstg`.`status` (`id_status`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_employye_report`
    FOREIGN KEY (`employee_report`)
    REFERENCES `bindevstg`.`employee` (`ci`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevstg`.`sale_detail`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`sale_detail` (
  `sale_id` INT NOT NULL,
  `product_sale` INT NOT NULL,
  `quantity` INT NOT NULL,
  `unit_price` DECIMAL(10,2) NOT NULL,
  `sale_discount` DECIMAL(4,2) NULL,
  `amount_total` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`sale_id`, `product_sale`),
  CONSTRAINT `FK_id_sale`
    FOREIGN KEY (`sale_id`)
    REFERENCES `bindevstg`.`sale` (`id_sale`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_prod_sale`
    FOREIGN KEY (`product_sale`)
    REFERENCES `bindevstg`.`product` (`barcode`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_value_discount`
    FOREIGN KEY (`sale_discount`)
    REFERENCES `bindevstg`.`discount` (`value`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevstg`.`promo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevstg`.`promo` (
  `is_product` INT NOT NULL,
  `have_product` INT NOT NULL,
  `quantity` INT NOT NULL,
  PRIMARY KEY (`is_product`, `have_product`),
  CONSTRAINT `FK_is_product`
    FOREIGN KEY (`is_product`)
    REFERENCES `bindevstg`.`product` (`barcode`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_have_product`
    FOREIGN KEY (`have_product`)
    REFERENCES `bindevstg`.`product` (`barcode`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
