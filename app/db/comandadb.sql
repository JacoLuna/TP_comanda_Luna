-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-11-2023 a las 15:56:30
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `comandadb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `IdMesa` int(255) NOT NULL,
  `idPersonal` int(255) NOT NULL,
  `cantComensales` int(255) NOT NULL,
  `rota` tinyint(1) NOT NULL,
  `estado` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`IdMesa`, `idPersonal`, `cantComensales`, `rota`, `estado`) VALUES
(10000, 3, 5, 0, 'con cliente comiendo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `idPedido` varchar(255) NOT NULL,
  `estado` varchar(255) NOT NULL,
  `idMesa` int(255) NOT NULL,
  `nombreCliente` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`idPedido`, `estado`, `idMesa`, `nombreCliente`) VALUES
('04vbP', 'en preparación', 10000, 'roberto'),
('06fiC', 'en preparación', 10000, 'roberto'),
('06Mqy', 'en preparación', 10000, 'roberto'),
('11KcB', 'en preparación', 10000, 'roberto'),
('21lGO', 'en preparación', 10000, 'roberto'),
('29NMz', 'en preparación', 10000, 'roberto'),
('29pVu', 'en preparación', 10000, 'roberto'),
('31Ltf', 'en preparación', 10000, 'roberto'),
('31pNx', 'en preparación', 10000, 'roberto'),
('36yPm', 'en preparación', 10000, 'roberto'),
('37Sgx', 'en preparación', 10000, 'roberto'),
('39gtK', 'en preparación', 10000, 'roberto'),
('44ssu', 'en preparación', 10000, 'roberto'),
('45mCK', 'en preparación', 10000, 'roberto'),
('50yhL', 'en preparación', 10000, 'roberto'),
('51oju', 'en preparación', 10000, 'roberto'),
('51TQE', 'en preparación', 10000, 'roberto'),
('58pSz', 'en preparación', 10000, 'roberto'),
('62khP', 'en preparación', 10000, 'roberto'),
('71Nxd', 'en preparación', 10000, 'roberto'),
('72qWl', 'en preparación', 10000, 'roberto'),
('72UQF', 'en preparación', 10000, 'roberto'),
('73JRs', 'en preparación', 10000, 'roberto'),
('75Njr', 'en preparación', 10000, 'roberto'),
('76Psp', 'en preparación', 10000, 'roberto'),
('87ExI', 'en preparación', 10000, 'roberto'),
('87SVX', 'en preparación', 10000, 'roberto'),
('95ROJ', 'en preparación', 10000, 'roberto'),
('98MHN', 'en preparación', 10000, 'roberto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `idPersonal` int(3) NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL,
  `contrasenia` varchar(255) NOT NULL,
  `DNI` int(8) NOT NULL,
  `rol` varchar(255) NOT NULL,
  `fechaIngreso` date NOT NULL DEFAULT current_timestamp(),
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`idPersonal`, `nombre`, `apellido`, `contrasenia`, `DNI`, `rol`, `fechaIngreso`, `fechaBaja`) VALUES
(1, 'nadie', 'nadie', '', -1, 'mozo', '0000-00-00', NULL),
(2, 'esteban', 'perex', '', 44628819, '', '2023-11-03', NULL),
(3, 'jorge', 'perex', '', 33228819, '', '2023-11-03', NULL),
(4, 'jon', 'juan', '', 23628812, '', '0000-00-00', NULL),
(5, 'roberto', 'juarez', '', 44627819, '', '2015-05-15', NULL),
(6, 'jonh', 'salchijonh', '', 43622819, '', '2021-12-12', NULL),
(7, 'jonh', 'juan', '', 34628819, '', '2021-12-12', NULL),
(8, 'jorge', 'perex', '', 33288119, '', '2023-11-03', NULL),
(10, 'robert', 'calamar', '', 23628819, 'socio', '0000-00-00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `idProducto` int(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `tiempoPreparacion` int(50) NOT NULL,
  `zona` varchar(255) NOT NULL,
  `baja` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

-- INSERT INTO `producto` (`idProducto`, `nombre`, `tiempoPreparacion`, `zona`, `baja`) VALUES
-- (4, 'coca', 1, 'cocina', 0),
-- (5, 'super pancho', 5, 'cocina', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productopedido`
--

CREATE TABLE `productopedido` (
  `idProductoPedido` int(255) NOT NULL,
  `idProducto` int(255) NOT NULL,
  `idPedido` varchar(255) NOT NULL,
  `cant` int(255) NOT NULL,
  `tiempoPreparacion` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productopedido`
--

INSERT INTO `productopedido` (`idProductoPedido`, `idProducto`, `idPedido`, `cant`, `tiempoPreparacion`) VALUES
(38, 5, '98MHN', 3, 5),
(39, 4, '98MHN', 3, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`IdMesa`),
  ADD KEY `idPersonal` (`idPersonal`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`idPedido`),
  ADD KEY `idMesa` (`idMesa`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`idPersonal`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idProducto`);

--
-- Indices de la tabla `productopedido`
--
ALTER TABLE `productopedido`
  ADD PRIMARY KEY (`idProductoPedido`),
  ADD KEY `idProducto` (`idProducto`),
  ADD KEY `idPedido` (`idPedido`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `IdMesa` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10002;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `idPersonal` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `idProducto` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `productopedido`
--
ALTER TABLE `productopedido`
  MODIFY `idProductoPedido` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD CONSTRAINT `mesa_ibfk_1` FOREIGN KEY (`idPersonal`) REFERENCES `personal` (`idPersonal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`idMesa`) REFERENCES `mesa` (`IdMesa`);

--
-- Filtros para la tabla `productopedido`
--
ALTER TABLE `productopedido`
  ADD CONSTRAINT `productopedido_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`idProducto`),
  ADD CONSTRAINT `productopedido_ibfk_3` FOREIGN KEY (`idPedido`) REFERENCES `pedido` (`idPedido`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
