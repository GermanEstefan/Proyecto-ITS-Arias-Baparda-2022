
INSERT INTO `bindev`.`role` (`name_role`, `description`) VALUES ('VENDEDOR', 'SER UN VENDEDOR');
INSERT INTO `bindev`.`role` (`name_role`, `description`) VALUES ('COMPRADOR', 'SER UN COMPRADOR');
INSERT INTO `bindev`.`role` (`name_role`, `description`) VALUES ('JEFE', 'SER UN JEFE');

INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('test@test.com', 'Test', 'Testing','', '','123456');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('jefe@jefe.com', 'Jefesito', 'Jefesin','jefe 123123', '11222122','123456');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('vendedor@vendedor.com', 'Venedorrrr', 'Vendedoristo','', '','123456');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('comprador@comprador.com', 'Compradorr', 'Compradorsitoo','', '','123456');
INSERT INTO `bindev`.`user` (`email`, `name`, `surname`, `address`, `phone`, `password`) VALUES ('juancito@gmail.com', 'Juan', 'Perez','', '','123456');

INSERT INTO `bindev`.`employee` (`ci`, `employee_user`, `employee_role`) VALUES (50219376, 5001, 'JEFE');
INSERT INTO `bindev`.`employee` (`ci`, `employee_user`, `employee_role`) VALUES (50219375, 5002, 'VENDEDOR');
INSERT INTO `bindev`.`employee` (`ci`, `employee_user`, `employee_role`) VALUES (50219374, 5003, 'COMPRADOR');

UPDATE `bindev`.`customer` SET `company_name` = 'Empresa SA', `rut_nr` = '12312312' WHERE (`customer_user` = '5004');
