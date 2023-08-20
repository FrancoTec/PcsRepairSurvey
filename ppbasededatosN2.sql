-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-05-2022 a las 00:44:07
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ppbasededatos`
DROP DATABASE IF EXISTS ppbasededatos;
CREATE DATABASE ppbasededatos;
use ppbasededatos;
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `Apellido` varchar(20) NOT NULL,
  `Nombre` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dni` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `Apellido`, `Nombre`, `email`, `dni`) VALUES
(104, 'Gonzales', 'Hernan', 'Gergoz@gmail.com', 25456789),
(105, 'Ragna', 'Laslow', 'Lasragna@gmail.com', 25456745),
(107, 'Lopez', 'Juan', 'ElJuanLo@gmail.com', 31245678);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `ID_E` int(11) NOT NULL,
  `Detalle` varchar(100) NOT NULL,
  `Cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`ID_E`, `Detalle`, `Cliente`) VALUES
(1, 'Samsung WER', 104);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `ID` int(11) NOT NULL,
  `Nombre` varchar(35) NOT NULL,
  `Punto_Pedido` int(2) NOT NULL,
  `Precio` float NOT NULL,
  `Stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`ID`, `Nombre`, `Punto_Pedido`, `Precio`, `Stock`) VALUES
(1, 'Luces Led', 8, 1500, 0),
(2, 'Luces Led', 8, 1500, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reparacion`
--

CREATE TABLE `reparacion` (
  `ID_R` int(11) NOT NULL,
  `Estado` varchar(20) NOT NULL,
  `Total` int(11) NOT NULL,
  `Diagnsotico` varchar(250) NOT NULL,
  `Fecha` date NOT NULL,
  `Equipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rep_comp`
--

CREATE TABLE `rep_comp` (
  `ID_Rep` int(11) NOT NULL,
  `ID_Compo` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID_User` int(11) NOT NULL,
  `user` varchar(10) NOT NULL,
  `pass` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID_User`, `user`, `pass`) VALUES
(1, 'JoeDoe4444', 'Leva4040'),
(2, 'Alvaro1', 'Amigabl3'),
(3, 'Alvaro1', 'Amigabl3'),
(4, 'Fabrizio22', 'Alfa1111'),
(5, 'Fabri1452', 'Eleva111'),
(6, 'Hector10', 'Lobo1010');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD KEY `Apellido` (`Apellido`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`ID_E`),
  ADD KEY `equipos_ibfk_1` (`Cliente`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `reparacion`
--
ALTER TABLE `reparacion`
  ADD PRIMARY KEY (`ID_R`),
  ADD KEY `reparacion_ibfk_1` (`Equipo`);

--
-- Indices de la tabla `rep_comp`
--
ALTER TABLE `rep_comp`
  ADD PRIMARY KEY (`ID_Rep`,`ID_Compo`),
  ADD KEY `ID_Compo` (`ID_Compo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID_User`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `ID_E` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `reparacion`
--
ALTER TABLE `reparacion`
  MODIFY `ID_R` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID_User` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `equipos_ibfk_1` FOREIGN KEY (`Cliente`) REFERENCES `clientes` (`id_cliente`);

--
-- Filtros para la tabla `reparacion`
--
ALTER TABLE `reparacion`
  ADD CONSTRAINT `reparacion_ibfk_1` FOREIGN KEY (`Equipo`) REFERENCES `equipos` (`ID_E`);

--
-- Filtros para la tabla `rep_comp`
--
ALTER TABLE `rep_comp`
  ADD CONSTRAINT `rep_comp_ibfk_1` FOREIGN KEY (`ID_Compo`) REFERENCES `productos` (`ID`),
  ADD CONSTRAINT `rep_comp_ibfk_2` FOREIGN KEY (`ID_Rep`) REFERENCES `reparacion` (`ID_R`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
