-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-08-2022 a las 02:58:38
-- Versión del servidor: 10.4.20-MariaDB
-- Versión de PHP: 7.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bindev`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer`
--

CREATE TABLE `customer` (
  `id_customer` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `company` varchar(250) NOT NULL,
  `nrut` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `customer`
--

INSERT INTO `customer` (`id_customer`, `email`, `company`, `nrut`) VALUES
(17, 'pajerin@gmail.com', 'Pelotudos SA', 2147483647),
(18, 'tarado@gmail.com', 'Pelotudos SA', 2147483647),
(19, 'pruebita@gmail.com', 'Pelotudos SA', 2147483647),
(20, 'pruebita2xd@gmail.com', 'Pelotudos SA', 2147483647),
(21, 'pruebita2222xd@gmail.com', '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employee`
--

CREATE TABLE `employee` (
  `id_employe` int(11) NOT NULL,
  `name_rol` varchar(100) NOT NULL,
  `ci` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `employee`
--

INSERT INTO `employee` (`id_employe`, `name_rol`, `ci`) VALUES
(38, 'JEFE', 12321321),
(35, 'JEFE', 50219376),
(37, 'JEFE', 50787787),
(40, 'VENDEDOR', 123123123),
(39, 'VENDEDOR', 2147483647);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idr` int(11) NOT NULL,
  `name_rol` varchar(100) NOT NULL,
  `description` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idr`, `name_rol`, `description`) VALUES
(1, 'vendedor', 'Vender cosas'),
(2, 'comprador', 'comprar cosas'),
(3, 'jefe', 'ser jefe');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `phone` int(11) NOT NULL,
  `password` varchar(100) NOT NULL,
  `address` varchar(150) NOT NULL,
  `state` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id_user`, `email`, `name`, `surname`, `phone`, `password`, `address`, `state`) VALUES
(17, 'pajerin@gmail.com', 'German', 'Rstefan', 93422646, '1234567', 'Pepito pepito', 1),
(18, 'tarado@gmail.com', 'German', 'Rstefan', 93422646, '1234567', 'Pepito pepito', 1),
(19, 'pruebita@gmail.com', 'German', 'Rstefan', 93422646, '1234567', 'Pepito pepito', 1),
(20, 'pruebita2xd@gmail.com', 'German', 'Rstefan', 93422646, '1234567', 'Pepito pepito', 1),
(21, 'pruebita2222xd@gmail.com', 'German', '', 0, '1234567', '', 1),
(35, 'german.estefan81@gmail.com', 'German', 'Rstefan', 93422646, '1234567', 'Pepito pepito', 1),
(37, 'german.estefanoooo81@gmail.com', 'German', 'Rstefan', 93422646, '1234567', 'Pepito pepito', 1),
(38, 'gfffo81@gmail.com', 'German', 'Rstefan', 93422646, '1234567', 'Pepito pepito', 1),
(39, 'asasasa@gmail.com', 'German', 'Rstefan', 93422646, '1234567', 'Pepito pepito', 1),
(40, 'perrito@gmail.com', 'German', 'Rstefan', 93422646, '1234567', 'Pepito pepito', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `customer`
--
ALTER TABLE `customer`
  ADD KEY `FK_USER` (`id_customer`);

--
-- Indices de la tabla `employee`
--
ALTER TABLE `employee`
  ADD UNIQUE KEY `FK_Ci` (`ci`),
  ADD KEY `FK_ROL` (`name_rol`),
  ADD KEY `FK_USER` (`id_employe`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idr`),
  ADD UNIQUE KEY `name_rol` (`name_rol`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `FK_ROL` FOREIGN KEY (`name_rol`) REFERENCES `rol` (`name_rol`),
  ADD CONSTRAINT `FK_USER` FOREIGN KEY (`id_employe`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
