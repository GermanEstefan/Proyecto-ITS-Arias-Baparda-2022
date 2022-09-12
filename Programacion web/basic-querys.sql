INSERT INTO `role` (`name_role`, `description`) VALUES
('COMPRADOR', 'SER UN COMPRADOR'),
('JEFE', 'SER UN JEFE'),
('VENDEDOR', 'SER UN VENDEDOR');

INSERT INTO `user` (`id_user`, `email`, `name`, `surname`, `address`, `phone`, `password`, `state`) VALUES
(5063, 'test@test.com', 'Test', 'Testing', '', '', '123456', 1),
(5064, 'jefe@jefe.com', 'Jefesito', 'Jefesin', 'jefe 123123', '11222122', '123456', 1),
(5066, 'vendedor@vendedor.com', 'Venedorrrr', 'Vendedoristo', NULL, NULL, '123456', 1),
(5067, 'comprador@comprador.com', 'Compradorr', 'Compradorsitoo', NULL, NULL, '123456', 1),
(5068, 'juancito@gmail.com', 'Juan', 'Perez', NULL, NULL, '123456', 1);

INSERT INTO `customer` (`customer_user`, `company_name`, `rut_nr`) VALUES
(5063, NULL, NULL),
(5064, NULL, NULL),
(5066, NULL, NULL),
(5067, NULL, NULL),
(5068, 'Empresa SA', '12312312');

INSERT INTO `employee` (`ci`, `employee_user`, `employee_role`, `state`) VALUES
(50219376, 5064, 'JEFE', 1),
(50219375, 5066, 'VENDEDOR', 1),
(50219374, 5067, 'COMPRADOR', 1);


