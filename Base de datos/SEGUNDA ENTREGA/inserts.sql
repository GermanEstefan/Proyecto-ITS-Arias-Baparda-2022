--insert talles
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('1', 'XS', 'extra small');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('2', 'S', 'small');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('3', 'M', 'medium');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('4', 'L', 'large');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('5', 'XL', 'extra large');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('6', 'XXL', 'exXtra large');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('38', 'TALLE 38', 'CALZADO 38');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('39', 'TALLE 39', 'CALZADO 39');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('40', 'TALLE 40', 'CALZADO40');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('41', 'TALLE 41', 'CALZADO TALLE 41');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('42', 'TALLE 42', 'CALZADO TALLE 42');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('43', 'TALLE 43', 'CLAZADO TALLE 43');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('44', 'TALLE 44', 'CALZADO TALLE 44');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('45', 'TALLE 45', 'CALZADO 45');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('99', 'PROMO BASIC ', 'PROMO BASICAS');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('999', 'PROMO PYMES', 'PROMO PEQUEÑAS EMPRESAS');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('9999', 'PROMO MEDIUM', 'PROMO PARA EMPRESAS EN GRAL');
INSERT INTO `proybindev`.`size` (`id_size`, `name`, `description`) VALUES ('99999', 'PROMO BIG ', 'PROMO PARA GRANDES EMPRESAS');

--INSERT DISEÑOS COLOR
INSERT INTO `proybindev`.`desing` (`id_desing`, `name`, `description`) VALUES ('0', 'BLANCO', 'COLOR BLANCO');
INSERT INTO `proybindev`.`desing` (`id_desing`, `name`, `description`) VALUES ('1', 'NEGRO', 'COLOR NEGRO');
INSERT INTO `proybindev`.`desing` (`id_desing`, `name`, `description`) VALUES ('2', 'GRIS', 'COLOR GRIS');
INSERT INTO `proybindev`.`desing` (`id_desing`, `name`, `description`) VALUES ('3', 'VERDE', 'COLOR VERDE');
INSERT INTO `proybindev`.`desing` (`id_desing`, `name`, `description`) VALUES ('4', 'AMARILLO', 'COLOR AMARILLO');
INSERT INTO `proybindev`.`desing` (`id_desing`, `name`, `description`) VALUES ('5', 'ANARANJADO', 'COLOR ANARANJADO');
INSERT INTO `proybindev`.`desing` (`id_desing`, `name`, `description`) VALUES ('6', 'ROJO', 'COLOR ROJO');
INSERT INTO `proybindev`.`desing` (`id_desing`, `name`, `description`) VALUES ('7', 'GENERICO', 'CATEGORIA GENERICA');
INSERT INTO `proybindev`.`desing` (`id_desing`, `name`, `description`) VALUES ('99', 'PROMO', 'PROMO');

--INSERT ROLES
INSERT INTO `proybindev`.`role` (`name_role`, `description`) VALUES ('VENDEDOR', 'EL VENDEDOR');
INSERT INTO `proybindev`.`role` (`name_role`, `description`) VALUES ('COMPRADOR', 'EL COMPRADOR');
INSERT INTO `proybindev`.`role` (`name_role`, `description`) VALUES ('JEFE', 'EL JEFE');

--INSERT USUARIOS
INSERT INTO `proybindev`.`USER` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo1@gmail.com', 'Nicolas', 'Alvarez', 'direccion 1', '098776554', 'password1');
INSERT INTO `proybindev`.`USER` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo2@gmail.com', 'Rodrigo', 'Gimenez', 'direccion2', '267482', 'password2');
INSERT INTO `proybindev`.`USER` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo3@gmail.com', 'Pablo ', 'Gimenez', 'direccion 2', '098098098', 'password3');
INSERT INTO `proybindev`.`USER` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo4@gmail.com', 'ana maria', 'libertad', 'direcc generica', '457', 'password4');
INSERT INTO `proybindev`.`USER` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo5@gmail.com', 'emilia ', 'de guzman', 'direccion salto al fondo', '25443', 'password5');
INSERT INTO `proybindev`.`USER` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo6@gmail.com', 'clara', 'hiliarse', 'Al fondo que hay lugar', '45988864', 'password6');
INSERT INTO `proybindev`.`USER` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo7@seguridadcorporal.com', 'patricia', 'gutierrez', 'direccion otra', '2334513', 'password7');
INSERT INTO `proybindev`.`USER` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo8@seguridadcorporal.com', 'lucas', 'rodriguez', '18 de julio 2377', '22334455', 'password8');
INSERT INTO `proybindev`.`USER` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo9@seguridadcorporal.com', 'esteban ', 'quito', 'no me acuerdo', '09828848', 'password9');
INSERT INTO `proybindev`.`USER` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo10@seguridadcorporal.com', 'maximilian', 'fija', 'rio negro entre j carlos rodriguez y marcelino gutierrez', '56778883', 'password10');

--INSERT EMPLOYEE
INSERT INTO `proybindev`.`EMPLOYEE` (`ci`, `employee_user`, `employee_role`) VALUES ('46858631', '6', 'VENDEDOR');
INSERT INTO `proybindev`.`EMPLOYEE` (`ci`, `employee_user`, `employee_role`) VALUES ('36757762', '7', 'VENDEDOR');
INSERT INTO `proybindev`.`EMPLOYEE` (`ci`, `employee_user`, `employee_role`) VALUES ('34521134', '8', 'COMPRADOR');
INSERT INTO `proybindev`.`EMPLOYEE` (`ci`, `employee_user`, `employee_role`) VALUES ('23467162', '9', 'COMPRADOR');
INSERT INTO `proybindev`.`EMPLOYEE` (`ci`, `employee_user`, `employee_role`) VALUES ('12773725', '10', 'JEFE');

--REVISAR LOS UNIQUE CONSTRAINT INDEX, NO ESTA APLICANDO EL CONSTRAINT  
-- SOLUCION EMPLOYEE 
    --ALTER TABLE EMPLOYEE
    --ADD constraint UN_user unique (employee_user);

--INSERT 