--------------------------INSERT EN BINDEVSTG
--INSERT USUARIOS
INSERT INTO `bindevstg`.`users` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo1@gmail.com', 'Nicolas', 'Alvarez', 'direccion 1', '098776554', 'password1');
INSERT INTO `bindevstg`.`users` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo2@gmail.com', 'Rodrigo', 'Gimenez', 'direccion2', '267482', 'password2');
INSERT INTO `bindevstg`.`users` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo3@gmail.com', 'Pablo ', 'Gimenez', 'direccion 2', '098098098', 'password3');
INSERT INTO `bindevstg`.`users` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo4@gmail.com', 'ana maria', 'libertad', 'direcc generica', '457', 'password4');
INSERT INTO `bindevstg`.`users` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo5@gmail.com', 'emilia ', 'de guzman', 'direccion salto al fondo', '25443', 'password5');
INSERT INTO `bindevstg`.`users` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo6@gmail.com', 'clara', 'hiliarse', 'Al fondo que hay lugar', '45988864', 'password6');
INSERT INTO `bindevstg`.`users` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo7@seguridadcorporal.com', 'patricia', 'gutierrez', 'direccion otra', '2334513', 'password7');
INSERT INTO `bindevstg`.`users` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo8@seguridadcorporal.com', 'lucas', 'rodriguez', '18 de julio 2377', '22334455', 'password8');
INSERT INTO `bindevstg`.`users` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo9@seguridadcorporal.com', 'esteban ', 'quito', 'no me acuerdo', '09828848', 'password9');
INSERT INTO `bindevstg`.`users` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('correo10@seguridadcorporal.com', 'maximilian', 'fija', 'rio negro entre j carlos rodriguez y marcelino gutierrez', '56778883', 'password10');

--INSERT ROLES
INSERT INTO `bindevstg`.`role` (`name_role`, `description`) VALUES ('VENDEDOR', 'EL VENDEDOR');
INSERT INTO `bindevstg`.`role` (`name_role`, `description`) VALUES ('COMPRADOR', 'EL COMPRADOR');
INSERT INTO `bindevstg`.`role` (`name_role`, `description`) VALUES ('JEFE', 'EL JEFE');

--INSERT EMPLOYEE
INSERT INTO `bindevstg`.`employee` (`ci`, `employee_user`, `employee_role`) VALUES ('12345678', '8', 'vendedor');
INSERT INTO `bindevstg`.`employee` (`ci`, `employee_user`, `employee_role`) VALUES ('12121212', '9', 'comprador');
INSERT INTO `bindevstg`.`employee` (`ci`, `employee_user`, `employee_role`) VALUES ('12312332', '10', 'jefe');

--INSERT CATEGORY
INSERT INTO `bindevstg`.`category` (`id_category`, `name`, `description`) VALUES ('1', 'REMERAS', 'LAS MEJORES REMERAS');
INSERT INTO `bindevstg`.`category` (`id_category`, `name`, `description`) VALUES ('2', 'BUZOS', 'LOS MEJORES BUZOS');
INSERT INTO `bindevstg`.`category` (`id_category`, `name`, `description`) VALUES ('3', 'CHALECOS', 'LOS MEJORES CHALECOS');
INSERT INTO `bindevstg`.`category` (`id_category`, `name`, `description`) VALUES ('4', 'PANTALONES', 'LOS MEJORES PANTALONES');
INSERT INTO `bindevstg`.`category` (`id_category`, `name`, `description`) VALUES ('5', 'CASCOS ', 'LOS MEJORES CASCOS ');
INSERT INTO `bindevstg`.`category` (`id_category`, `name`, `description`) VALUES ('6', 'GUANTES', 'LOS MEJORES GUANTES');
INSERT INTO `bindevstg`.`category` (`id_category`, `name`, `description`) VALUES ('7', 'ZAPATOS', 'LOS MEJORES ZAPATOS');
INSERT INTO `bindevstg`.`category` (`id_category`, `name`, `description`) VALUES ('8', 'LENTES', 'LOS MEJORES LENTES DE PROTECCION');
INSERT INTO `bindevstg`.`category` (`id_category`, `name`, `description`) VALUES ('9', 'GENERICA', 'CATEGORIA GENERICA');
INSERT INTO `bindevstg`.`category` (`id_category`, `name`, `description`) VALUES ('1000', 'PROMO BASICA', 'PROMO GENERICA');
INSERT INTO `bindevstg`.`category` (`id_category`, `name`, `description`) VALUES ('1001', 'PROMO PYME', 'PROMO PARA PEQUEÑAS EMPRESAS');
INSERT INTO `bindevstg`.`category` (`id_category`, `name`, `description`) VALUES ('1002', 'PROMO MEDIUM', 'PROMO PARA EMPRESAS RECONOCIDAS');
INSERT INTO `bindevstg`.`category` (`id_category`, `name`, `description`) VALUES ('1003', 'BIG PROMO', 'PROMO PARA EMPRESAS LIDERES');

--insert talles
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('1', 'XS', 'extra small');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('2', 'S', 'small');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('3', 'M', 'medium');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('4', 'L', 'large');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('5', 'XL', 'extra large');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('6', 'XXL', 'exXtra large');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('38', 'TALLE 38', 'CALZADO 38');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('39', 'TALLE 39', 'CALZADO 39');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('40', 'TALLE 40', 'CALZADO40');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('41', 'TALLE 41', 'CALZADO TALLE 41');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('42', 'TALLE 42', 'CALZADO TALLE 42');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('43', 'TALLE 43', 'CLAZADO TALLE 43');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('44', 'TALLE 44', 'CALZADO TALLE 44');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('45', 'TALLE 45', 'CALZADO 45');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('1000', 'PROMO BASIC ', 'PROMO BASICAS');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('1001', 'PROMO PYMES', 'PROMO PEQUEÑAS EMPRESAS');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('1002', 'PROMO MEDIUM', 'PROMO PARA EMPRESAS EN GRAL');
INSERT INTO `bindevstg`.`size` (`id_size`, `name`, `description`) VALUES ('1003', 'PROMO BIG ', 'PROMO PARA GRANDES EMPRESAS');

--INSERT DISEÑOS COLOR
INSERT INTO `bindevstg`.`usersng` (`id_desing`, `name`, `description`) VALUES ('0', 'BLANCO', 'COLOR BLANCO');
INSERT INTO `bindevstg`.`usersng` (`id_desing`, `name`, `description`) VALUES ('1', 'NEGRO', 'COLOR NEGRO');
INSERT INTO `bindevstg`.`usersng` (`id_desing`, `name`, `description`) VALUES ('2', 'GRIS', 'COLOR GRIS');
INSERT INTO `bindevstg`.`usersng` (`id_desing`, `name`, `description`) VALUES ('3', 'VERDE', 'COLOR VERDE');
INSERT INTO `bindevstg`.`usersng` (`id_desing`, `name`, `description`) VALUES ('4', 'AMARILLO', 'COLOR AMARILLO');
INSERT INTO `bindevstg`.`usersng` (`id_desing`, `name`, `description`) VALUES ('5', 'ANARANJADO', 'COLOR ANARANJADO');
INSERT INTO `bindevstg`.`usersng` (`id_desing`, `name`, `description`) VALUES ('6', 'ROJO', 'COLOR ROJO');
INSERT INTO `bindevstg`.`usersng` (`id_desing`, `name`, `description`) VALUES ('7', 'GENERICO', 'CATEGORIA GENERICA');
INSERT INTO `bindevstg`.`usersng` (`id_desing`, `name`, `description`) VALUES ('99', 'PROMO', 'PROMO');




--REVISAR LOS UNIQUE CONSTRAINT INDEX, NO ESTA APLICANDO EL CONSTRAINT  
-- SOLUCION EMPLOYEE 
    --ALTER TABLE EMPLOYEE
    --ADD constraint UN_user unique (employee_user);

--INSERT 