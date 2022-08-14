-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
-- -----------------------------------------------------
-- Schema bindevSTG
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bindevSTG` DEFAULT CHARACTER SET utf8 ;
USE `bindevSTG` ;

-- -----------------------------------------------------
-- Table `bindevSTG`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`category` (
  `id_category` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_category`),
  constraint `category_UNIQUE` UNIQUE  (`name`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `bindevSTG`.`desing`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`desing` (
  `id_desing` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_desing`),
  constraint `desing_UNIQUE` UNIQUE (`name`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`size`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`size` (
  `id_size` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_size`),
  constraint `size_UNIQUE` UNIQUE (`name` ))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`disburse`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`disburse` (
  `id_disburse` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_disburse`),
  constraint `disburse_UNIQUE` UNIQUE(`name`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `bindevSTG`.`supplier`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`supplier` (
  `id_supplier` INT NOT NULL,
  `rut` varchar(12) NOT NULL,
  `company_name` VARCHAR(150) NOT NULL,
  `address` VARCHAR(500) NOT NULL,
  `phone` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id_supplier`),
  constraint `UN_rut_sucursal` UNIQUE  (`rut` , `company_name` ))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`role` (
  `name_role` VARCHAR(100) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`name_role`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`users` (
  `id_user` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(200) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `surname` VARCHAR(150) NOT NULL,
  `address` VARCHAR(500) NULL,
  `phone` VARCHAR(50) NULL,
  `password` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id_user`),
  constraint `email_UNIQUE` UNIQUE (`email`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `bindevSTG`.`status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`status` (
  `id_status` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_status`),
  constraint `UN_status` UNIQUE (`name`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`delivery_time`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`delivery_time` (
  `id_delivery` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_delivery`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`payment_method`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`payment_method` (
  `id_pay_meth` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_pay_meth`),
  constraint `UN_pay_meth` UNIQUE (`name`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`photos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`photos` (
  `id_photo` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_photo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`discount`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`discount` (
  `value` FLOAT NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`value`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`employee`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`employee` (
  `ci` INT NOT NULL,
  `employee_user` INT NOT NULL,
  `employee_role` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`employee_user`),
  constraint `UN_ci` unique (`ci`),
  CONSTRAINT `FK_employee_role`
    FOREIGN KEY (`employee_role`)
    REFERENCES `bindevSTG`.`role` (`name_role`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_employee_user`
    FOREIGN KEY (`employee_user`)
    REFERENCES `bindevSTG`.`users` (`id_user`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`client`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`client` (
  `client_user` INT NOT NULL,
  `company_name` VARCHAR(300) NULL,
  `rut_nr` varchar(12) NULL,
  PRIMARY KEY (`client_user`),
  constraint `UN_rut_company` UNIQUE  (`rut_nr`, `company_name`),
  CONSTRAINT `FK_user_id`
    FOREIGN KEY (`client_user`)
    REFERENCES `bindevSTG`.`users` (`id_user`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`supply`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`supply` (
  `id_supply` INT NOT NULL AUTO_INCREMENT ,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL ,
  `supplier_id` INT NOT NULL,
  `employee_ci` INT NOT NULL,
  `disburse_method` INT NOT NULL,
  `comment` VARCHAR(500) NULL,
  PRIMARY KEY (`id_supply`),
  CONSTRAINT `FK_supply_supplier`
    FOREIGN KEY (`supplier_id`)
    REFERENCES `bindevSTG`.`supplier` (`id_supplier`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_supply_employee`
    FOREIGN KEY (`employee_ci`)
    REFERENCES `bindevSTG`.`employee` (`ci`)
    ON DELETE RESTRICT
    ON UPDATE cascade,
  CONSTRAINT `FK_supply_disburse`
    FOREIGN KEY (`disburse_method`)
    REFERENCES `bindevSTG`.`disburse` (`id_disburse`)
    ON DELETE RESTRICT
    ON UPDATE cascade)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`product` (
  `barcode` INT NOT NULL AUTO_INCREMENT,
  `id_product` INT NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `product_category` INT NOT NULL,
  `product_desing` INT NOT NULL,
  `product_size` INT NOT NULL,
  `stock` INT NOT NULL,
  `price` FLOAT NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`barcode`),
  constraint `UN_product` UNIQUE (`id_product`, `product_category`, `product_desing`, `product_size`),
  CONSTRAINT `FK_category_product`
    FOREIGN KEY (`product_category`)
    REFERENCES `bindevSTG`.`category` (`id_category`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_desing_product`
    FOREIGN KEY (`product_desing`)
    REFERENCES `bindevSTG`.`desing` (`id_desing`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_size_product`
    FOREIGN KEY (`product_size`)
    REFERENCES `bindevSTG`.`size` (`id_size`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`galery`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`galery` (
  `product_galery` INT NOT NULL,
  `photo_galery` INT NOT NULL,
  PRIMARY KEY (`product_galery`, `photo_galery`),
	CONSTRAINT `FK_product_galery`
    FOREIGN KEY (`product_galery`)
    REFERENCES `bindevSTG`.`product` (`barcode`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `FK_photo_product`
    FOREIGN KEY (`photo_galery`)
    REFERENCES `bindevSTG`.`photos` (`id_photo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`supply_detail`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`supply_detail` (
  `supply_id` INT NOT NULL,
  `barcode_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `cost_unit` FLOAT NOT NULL,
  `amount_total` FLOAT NULL COMMENT 'cost_unit por quantity',
  PRIMARY KEY (`supply_id`, `barcode_id`),
CONSTRAINT `FK_product_reference`
    FOREIGN KEY (`barcode_id`)
    REFERENCES `bindevSTG`.`product` (`barcode`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_supply_reference`
    FOREIGN KEY (`supply_id`)
    REFERENCES `bindevSTG`.`supply` (`id_supply`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`sale`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`sale` (
  `id_sale` INT NOT NULL AUTO_INCREMENT,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL ,
  `address` VARCHAR(500) NOT NULL,
  `user_purchase` INT NOT NULL,
  `sale_delivery` INT NOT NULL,
  `pay_met` INT NOT NULL,
  PRIMARY KEY (`id_sale`),
  CONSTRAINT `FK_client_user`
    FOREIGN KEY (`user_purchase`)
    REFERENCES `bindevSTG`.`client` (`client_user`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_delivery_sale`
    FOREIGN KEY (`sale_delivery`)
    REFERENCES `bindevSTG`.`delivery_time` (`id_delivery`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_pay_sale`
    FOREIGN KEY (`pay_met`)
    REFERENCES `bindevSTG`.`payment_method` (`id_pay_meth`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`report`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`report` (
  `sale_report` INT NOT NULL,
  `status_report` INT NOT NULL,
  `employee_report` INT NOT NULL,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL ,
  `comment` VARCHAR(500) NULL,
  PRIMARY KEY (`sale_report`, `status_report`),
  CONSTRAINT `UN_report_sale` UNIQUE (`sale_report`, `status_report`, `date`),
  CONSTRAINT `FK_sale_report`
    FOREIGN KEY (`sale_report`)
    REFERENCES `bindevSTG`.`sale` (`id_sale`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_status_report`
    FOREIGN KEY (`status_report`)
    REFERENCES `bindevSTG`.`status` (`id_status`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_employye_report`
    FOREIGN KEY (`employee_report`)
    REFERENCES `bindevSTG`.`employee` (`ci`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`sale_detail`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`sale_detail` (
  `sale_id` INT NOT NULL,
  `product_sale` INT NOT NULL,
  `quantity` INT NOT NULL,
  `unit_price` FLOAT NOT NULL,
  `sale_discount` FLOAT NULL,
  `amount_total` FLOAT NOT NULL,
  PRIMARY KEY (`sale_id`, `product_sale`),
  CONSTRAINT `FK_id_sale`
    FOREIGN KEY (`sale_id`)
    REFERENCES `bindevSTG`.`sale` (`id_sale`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_prod_sale`
    FOREIGN KEY (`product_sale`)
    REFERENCES `bindevSTG`.`product` (`barcode`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_value_discount`
    FOREIGN KEY (`sale_discount`)
    REFERENCES `bindevSTG`.`discount` (`value`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`promo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`promo` (
  `is_product` INT NOT NULL,
  `have_product` INT NOT NULL,
  `quantity` INT NOT NULL,
  PRIMARY KEY (`is_product`, `have_product`),
  CONSTRAINT `FK_is_product`
    FOREIGN KEY (`is_product`)
    REFERENCES `bindevSTG`.`product` (`barcode`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_have_product`
    FOREIGN KEY (`have_product`)
    REFERENCES `bindevSTG`.`product` (`barcode`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
