-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-11-2023 a las 21:29:12
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
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `idEncuesta` int(11) NOT NULL,
  `idPedido` varchar(6) NOT NULL,
  `mesa` int(11) NOT NULL,
  `restaurante` int(11) NOT NULL,
  `mozo` int(11) NOT NULL,
  `cocinero` int(11) NOT NULL,
  `encuesta` varchar(66) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `idFactura` int(11) NOT NULL,
  `idPedido` varchar(6) NOT NULL,
  `propina` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(10000, 6, 2, 0, 'con cliente pagando'),
(10002, 6, 2, 0, 'con cliente esperando pedido'),
(10003, 6, 2, 0, 'cerrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `idPedido` varchar(255) NOT NULL,
  `estado` varchar(255) NOT NULL,
  `idMesa` int(255) NOT NULL,
  `nombreCliente` varchar(255) NOT NULL,
  `horaHecho` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`idPedido`, `estado`, `idMesa`, `nombreCliente`, `horaHecho`) VALUES
('84pIm', 'servido', 10000, 'campeon', '16:46:06');

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
(1, 'nadie', 'nadie', '', -1, '-1', '0000-00-00', NULL),
(2, 'esteban', 'perex', '1234', 44628819, 'bartender-tragos', '2023-11-03', NULL),
(3, 'jorge', 'perex', '5678', 33228819, 'cocinero-postres', '2023-11-03', NULL),
(4, 'jon', 'juan', 'serCocineroEstabueno', 23628812, 'cocinero-comida', '2015-11-11', NULL),
(5, 'roberto', 'juarez', '987654321', 44627819, 'mozo', '2015-05-15', NULL),
(6, 'jonh', 'jonh', 'salchijonh', 43622819, 'socio', '2021-12-12', NULL),
(7, 'jonh', 'juan', 'wachinFacha', 34628819, 'bartender-bebidas', '2021-12-12', NULL),
(8, 'jorge', 'perex', 'perex12345667', 33288119, 'mozo', '2023-11-03', NULL),
(10, 'robert', 'calamar', 'qazwsxedcrfv', 23628819, 'socio', '0000-00-00', NULL),
(11, 'franco', 'fracovich', '1234', 23628811, 'mozo', '0000-00-00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `idProducto` int(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `tiempoPreparacion` time NOT NULL,
  `zona` varchar(255) NOT NULL,
  `baja` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idProducto`, `nombre`, `tiempoPreparacion`, `zona`, `baja`) VALUES
(4, 'coca', '00:40:00', 'cocina', 0),
(5, 'super pancho', '00:00:50', 'cocina', 0),
(20, 'milanesa', '00:00:00', 'cocina', 0),
(29, 'milanesa a caballo', '00:40:00', 'cocina', 0),
(37, 'hamburguesa de garbanzo', '00:00:00', 'cocina', 0),
(38, 'corona', '00:00:00', 'barra de choperas', 0),
(39, 'daikiri', '00:00:00', 'barra de tragos y vinos', 0),
(40, 'cheesecake', '00:00:00', 'Candy Bar', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productopedido`
--

CREATE TABLE `productopedido` (
  `idProductoPedido` int(255) NOT NULL,
  `idProducto` int(255) NOT NULL,
  `idPedido` varchar(255) NOT NULL,
  `cant` int(255) NOT NULL,
  `tiempoPreparacion` time NOT NULL,
  `estado` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`idEncuesta`),
  ADD KEY `idPedido` (`idPedido`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`idFactura`),
  ADD KEY `idPedido` (`idPedido`);

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
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `idEncuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `idFactura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `IdMesa` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10004;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `idPersonal` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `idProducto` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `productopedido`
--
ALTER TABLE `productopedido`
  MODIFY `idProductoPedido` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD CONSTRAINT `encuesta_ibfk_1` FOREIGN KEY (`idPedido`) REFERENCES `pedido` (`idPedido`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`idPedido`) REFERENCES `pedido` (`idPedido`) ON DELETE CASCADE ON UPDATE CASCADE;

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
