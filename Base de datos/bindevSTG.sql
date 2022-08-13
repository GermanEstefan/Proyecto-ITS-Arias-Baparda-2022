-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema bindevSTG
-- -----------------------------------------------------

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
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) VISIBLE)
ENGINE = InnoDB
COMMENT = '		';


-- -----------------------------------------------------
-- Table `bindevSTG`.`desing`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`desing` (
  `id_desing` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_desing`),
  UNIQUE INDEX `desingcol_UNIQUE` (`name` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`size`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`size` (
  `id_size` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_size`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`disburse`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`disburse` (
  `id_disburse` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_disburse`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`supplier`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`supplier` (
  `id_supplier` INT NOT NULL,
  `rut` INT(12) NOT NULL,
  `company_name` VARCHAR(150) NOT NULL,
  `addres` VARCHAR(500) NOT NULL,
  `phone` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id_supplier`),
  UNIQUE INDEX `UN_Rut_Company` (`rut` ASC, `company_name` ASC) COMMENT 'No se repite Rut y Sucursal' INVISIBLE)
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
-- Table `bindevSTG`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`user` (
  `id_users` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(200) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `surname` VARCHAR(150) NOT NULL,
  `address` VARCHAR(500) NULL,
  `phone` VARCHAR(50) NULL,
  `password` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id_users`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE)
ENGINE = InnoDB
COMMENT = '	';


-- -----------------------------------------------------
-- Table `bindevSTG`.`status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`status` (
  `id_status` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id_status`),
  UNIQUE INDEX `name` (`name` ASC) VISIBLE)
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
  PRIMARY KEY (`id_pay_meth`))
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
  PRIMARY KEY (`ci`),
  INDEX `FK_employee_role_idx` (`employee_role` ASC) VISIBLE,
  INDEX `FK_employye_user_idx` (`employee_user` ASC) VISIBLE,
  UNIQUE `UNIQUE_user` (`employee_user` ASC) VISIBLE,
  CONSTRAINT `FK_employee_role`
    FOREIGN KEY (`employee_role`)
    REFERENCES `bindevSTG`.`role` (`name_role`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_employye_user`
    FOREIGN KEY (`employee_user`)
    REFERENCES `bindevSTG`.`user` (`id_users`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bindevSTG`.`client`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`client` (
  `user_represent` INT NOT NULL,
  `company_name` VARCHAR(300) NULL,
  `rut_nr` INT(12) NULL,
  PRIMARY KEY (`user_represent`),
  UNIQUE INDEX `UN_rut_company` (`rut_nr` ASC, `company_name` ASC) VISIBLE,
  CONSTRAINT `FK_user_id`
    FOREIGN KEY (`user_represent`)
    REFERENCES `bindevSTG`.`user` (`id_users`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB
COMMENT = '	';


-- -----------------------------------------------------
-- Table `bindevSTG`.`supply`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`supply` (
  `id_supply` INT NOT NULL DEFAULT 10000,
  `date` DATETIME NOT NULL,
  `supplier_id` INT NOT NULL,
  `employee_ci` INT NOT NULL,
  `disburse_method` INT NOT NULL,
  `comment` VARCHAR(500) NULL,
  PRIMARY KEY (`id_supply`),
  INDEX `FK_supply_supplier_idx` (`supplier_id` ASC) VISIBLE,
  INDEX `FK_supply_employee_idx` (`employee_ci` ASC) VISIBLE,
  INDEX `FK_disburse_method_idx` (`disburse_method` ASC) VISIBLE,
  CONSTRAINT `FK_supply_supplier`
    FOREIGN KEY (`supplier_id`)
    REFERENCES `bindevSTG`.`supplier` (`id_supplier`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `FK_supply_employee`
    FOREIGN KEY (`employee_ci`)
    REFERENCES `bindevSTG`.`employee` (`ci`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `FK_supply_disburse`
    FOREIGN KEY (`disburse_method`)
    REFERENCES `bindevSTG`.`disburse` (`id_disburse`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
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
  INDEX `FK_category_product_idx` (`product_category` ASC) VISIBLE,
  INDEX `FK_desing_product_idx` (`product_desing` ASC) VISIBLE,
  INDEX `FK_size_product_idx` (`product_size` ASC) VISIBLE,
  UNIQUE INDEX `UN_product` (`id_product` ASC, `product_category` ASC, `product_desing` ASC, `product_size` ASC) COMMENT 'No permite que el prod se repita' VISIBLE,
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
ENGINE = InnoDB
COMMENT = '	';


-- -----------------------------------------------------
-- Table `bindevSTG`.`galery`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`galery` (
  `product_galery` INT NOT NULL,
  `photo_galery` INT NOT NULL,
  PRIMARY KEY (`product_galery`, `photo_galery`),
  INDEX `FK_photo_product_idx` (`photo_galery` ASC) INVISIBLE,
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
  `barcode` INT NOT NULL,
  `quantity` INT NOT NULL,
  `cost_unit` FLOAT NOT NULL,
  `amount_total` FLOAT NULL COMMENT 'cost_unit por quantity',
  PRIMARY KEY (`supply_id`, `barcode`),
  INDEX `FK_product_supply_idx` (`barcode` ASC) INVISIBLE,
  CONSTRAINT `FK_product_supply`
    FOREIGN KEY (`barcode`)
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
  `date` DATETIME NOT NULL,
  `address` VARCHAR(500) NOT NULL,
  `user_purchase` INT NOT NULL,
  `sale_delivery` INT NOT NULL,
  `pay_met` INT NOT NULL,
  PRIMARY KEY (`id_sale`),
  INDEX `FK_client_user_idx` (`user_purchase` ASC) VISIBLE,
  INDEX `FK_delivery_sale_idx` (`sale_delivery` ASC) VISIBLE,
  INDEX `FK_pay_sale_idx` (`pay_met` ASC) VISIBLE,
  CONSTRAINT `FK_client_user`
    FOREIGN KEY (`user_purchase`)
    REFERENCES `bindevSTG`.`client` (`user_represent`)
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
ENGINE = InnoDB
COMMENT = '	';


-- -----------------------------------------------------
-- Table `bindevSTG`.`report`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`report` (
  `sale_report` INT NOT NULL,
  `status_report` INT NOT NULL,
  `employee_report` INT NOT NULL,
  `date` DATETIME NOT NULL,
  `comment` VARCHAR(500) NULL,
  PRIMARY KEY (`sale_report`, `status_report`),
  INDEX `IDX_date` (`date` ASC) INVISIBLE,
  INDEX `IDX_status` (`status_report` ASC) INVISIBLE,
  INDEX `IDX_employeer_reported` (`employee_report` ASC) VISIBLE,
  UNIQUE INDEX `UN_report_sale` (`sale_report` ASC, `status_report` ASC, `date` ASC) VISIBLE,
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
  INDEX `FK_prod_sale_idx` (`product_sale` ASC) VISIBLE,
  INDEX `FK_value_discount_idx` (`sale_discount` ASC) VISIBLE,
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
ENGINE = InnoDB
COMMENT = '		';


-- -----------------------------------------------------
-- Table `bindevSTG`.`promo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bindevSTG`.`promo` (
  `is_product` INT NOT NULL,
  `have_product` INT NOT NULL,
  `quantity` INT NOT NULL,
  PRIMARY KEY (`is_product`, `have_product`),
  INDEX `FK_have_product_idx` (`have_product` ASC) INVISIBLE,
  INDEX `IDX_is_product` (`is_product` ASC) VISIBLE,
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
