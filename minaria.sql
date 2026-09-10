-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: davidmonwn942.mysql.db
-- Tiempo de generación: 10-09-2026 a las 22:21:46
-- Versión del servidor: 8.0.46-37
-- Versión de PHP: 8.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `davidmonwn942`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aa_adjetivos_region`
--

CREATE TABLE `aa_adjetivos_region` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `genero` varchar(10) NOT NULL COMMENT 'masculino, femenino o todos',
  `numero` varchar(10) NOT NULL COMMENT 'singular, plural o todos',
  `grupo` int DEFAULT NULL COMMENT 'Agrupa las diferentes formas del mismo adjetivo',
  `mult_piedra` decimal(3,2) NOT NULL DEFAULT '1.00',
  `mult_metal` decimal(3,2) NOT NULL DEFAULT '1.00',
  `mult_madera` decimal(3,2) NOT NULL DEFAULT '1.00',
  `mult_comida` decimal(3,2) NOT NULL DEFAULT '1.00',
  `mult_oro` decimal(3,2) NOT NULL DEFAULT '1.00',
  `mult_mana` decimal(3,2) NOT NULL DEFAULT '1.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `aa_adjetivos_region`
--

INSERT INTO `aa_adjetivos_region` (`id`, `nombre`, `genero`, `numero`, `grupo`, `mult_piedra`, `mult_metal`, `mult_madera`, `mult_comida`, `mult_oro`, `mult_mana`) VALUES
(1, 'Antiguo', 'masculino', 'singular', 1, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00),
(2, 'Antigua', 'femenino', 'singular', 1, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00),
(3, 'Antiguos', 'masculino', 'plural', 1, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00),
(4, 'Antiguas', 'femenino', 'plural', 1, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00),
(5, 'Maldito', 'masculino', 'singular', 2, 0.85, 0.85, 1.10, 1.00, 0.95, 1.15),
(6, 'Maldita', 'femenino', 'singular', 2, 0.85, 0.85, 1.10, 1.00, 0.95, 1.15),
(7, 'Malditos', 'masculino', 'plural', 2, 0.85, 0.85, 1.10, 1.00, 0.95, 1.15),
(8, 'Malditas', 'femenino', 'plural', 2, 0.85, 0.85, 1.10, 1.00, 0.95, 1.15),
(9, 'Perdido', 'masculino', 'singular', 3, 0.80, 0.80, 1.20, 1.00, 0.90, 1.05),
(10, 'Perdida', 'femenino', 'singular', 3, 0.80, 0.80, 1.20, 1.00, 0.90, 1.05),
(11, 'Perdidos', 'masculino', 'plural', 3, 0.80, 0.80, 1.20, 1.00, 0.90, 1.05),
(12, 'Perdidas', 'femenino', 'plural', 3, 0.80, 0.80, 1.20, 1.00, 0.90, 1.05),
(13, 'Olvidado', 'masculino', 'singular', 4, 1.10, 1.10, 0.80, 1.00, 1.20, 1.00),
(14, 'Olvidada', 'femenino', 'singular', 4, 1.10, 1.10, 0.80, 1.00, 1.20, 1.00),
(15, 'Olvidados', 'masculino', 'plural', 4, 1.10, 1.10, 0.80, 1.00, 1.20, 1.00),
(16, 'Olvidadas', 'femenino', 'plural', 4, 1.10, 1.10, 0.80, 1.00, 1.20, 1.00),
(17, 'Encantado', 'masculino', 'singular', 5, 1.00, 1.00, 1.00, 1.00, 1.00, 1.20),
(18, 'Encantada', 'femenino', 'singular', 5, 1.00, 1.00, 1.00, 1.00, 1.00, 1.20),
(19, 'Encantados', 'masculino', 'plural', 5, 1.00, 1.00, 1.00, 1.00, 1.00, 1.20),
(20, 'Encantadas', 'femenino', 'plural', 5, 1.00, 1.00, 1.00, 1.00, 1.00, 1.20),
(21, 'Sagrado', 'masculino', 'singular', 6, 1.15, 1.15, 1.00, 1.00, 1.10, 1.00),
(22, 'Sagrada', 'femenino', 'singular', 6, 1.15, 1.15, 1.00, 1.00, 1.10, 1.00),
(23, 'Sagrados', 'masculino', 'plural', 6, 1.15, 1.15, 1.00, 1.00, 1.10, 1.00),
(24, 'Sagradas', 'femenino', 'plural', 6, 1.15, 1.15, 1.00, 1.00, 1.10, 1.00),
(33, 'Prohibido', 'masculino', 'singular', 9, 0.80, 0.80, 0.80, 0.80, 0.80, 0.80),
(34, 'Prohibida', 'femenino', 'singular', 9, 0.80, 0.80, 0.80, 0.80, 0.80, 0.80),
(35, 'Prohibidos', 'masculino', 'plural', 9, 0.80, 0.80, 0.80, 0.80, 0.80, 0.80),
(36, 'Prohibidas', 'femenino', 'plural', 9, 0.80, 0.80, 0.80, 0.80, 0.80, 0.80),
(37, 'Remoto', 'masculino', 'singular', 10, 0.90, 0.90, 0.90, 0.90, 0.90, 1.20),
(38, 'Remota', 'femenino', 'singular', 10, 0.90, 0.90, 0.90, 0.90, 0.90, 1.20),
(39, 'Remotos', 'masculino', 'plural', 10, 0.90, 0.90, 0.90, 0.90, 0.90, 1.20),
(40, 'Remotas', 'femenino', 'plural', 10, 0.90, 0.90, 0.90, 0.90, 0.90, 1.20),
(41, 'Desolado', 'masculino', 'singular', 11, 0.80, 0.80, 0.00, 0.80, 1.30, 1.00),
(42, 'Desolada', 'femenino', 'singular', 11, 0.80, 0.80, 0.00, 0.80, 1.30, 1.00),
(43, 'Desolados', 'masculino', 'plural', 11, 0.80, 0.80, 0.00, 0.80, 1.30, 1.00),
(44, 'Desoladas', 'femenino', 'plural', 11, 0.80, 0.80, 0.00, 0.80, 1.30, 1.00),
(45, 'Salvaje', 'todos', 'singular', 12, 1.00, 1.00, 1.30, 1.30, 1.00, 1.00),
(46, 'Salvajes', 'todos', 'plural', 12, 1.00, 1.00, 1.30, 1.30, 1.00, 1.00),
(47, 'Refulgente', 'todos', 'singular', 7, 1.05, 1.05, 1.05, 1.05, 1.05, 1.05),
(48, 'Refulgentes', 'todos', 'plural', 7, 1.05, 1.05, 1.05, 1.05, 1.05, 1.05),
(49, 'Eterna', 'femenino', 'singular', 8, 1.20, 1.20, 1.00, 1.00, 1.00, 1.00),
(50, 'Eterno', 'masculino', 'singular', 8, 1.20, 1.20, 1.00, 1.00, 1.00, 1.00),
(51, 'Carmesí', 'todos', 'singular', 13, 1.20, 1.00, 1.00, 1.00, 1.20, 1.00),
(52, 'Carmesíes', 'todos', 'plural', 13, 1.20, 1.00, 1.00, 1.00, 1.20, 1.00),
(53, 'Sombrío', 'masculino', 'singular', 14, 0.80, 0.80, 1.00, 1.00, 0.80, 1.20),
(54, 'Sombría', 'femenino', 'singular', 14, 0.80, 0.80, 1.00, 1.00, 0.80, 1.20),
(55, 'Sombríos', 'masculino', 'plural', 14, 0.80, 0.80, 1.00, 1.00, 0.80, 1.20),
(56, 'Sombrías', 'femenino', 'plural', 14, 0.80, 0.80, 1.00, 1.00, 0.80, 1.20),
(57, 'de la Desesperación', 'todos', 'todos', NULL, 0.80, 0.80, 1.10, 0.85, 0.90, 1.10),
(58, 'de la Esperanza', 'todos', 'todos', NULL, 1.00, 1.10, 1.00, 1.15, 0.90, 1.05),
(59, 'del Llanto', 'todos', 'todos', NULL, 0.85, 0.85, 0.85, 1.05, 0.80, 1.10),
(60, 'de las Sombras', 'todos', 'todos', NULL, 0.80, 0.80, 0.80, 0.80, 0.80, 1.20),
(61, 'del Olvido', 'todos', 'todos', NULL, 1.00, 1.00, 0.00, 1.00, 1.20, 0.90),
(62, 'de la Muerte', 'todos', 'todos', NULL, 1.00, 1.00, 1.00, 0.75, 1.00, 1.20),
(63, 'del Destino', 'todos', 'todos', NULL, 1.05, 1.05, 1.05, 1.00, 1.00, 1.00),
(64, 'de la Locura', 'todos', 'todos', NULL, 0.80, 0.80, 0.80, 1.10, 1.20, 1.00),
(65, 'de la Perdición', 'todos', 'todos', NULL, 1.00, 1.00, 1.00, 0.80, 1.20, 1.00),
(66, 'de la Oscuridad', 'todos', 'todos', NULL, 1.00, 1.00, 0.95, 1.00, 1.00, 1.05),
(67, 'de la Eternidad', 'todos', 'todos', NULL, 1.00, 1.00, 1.00, 1.00, 1.00, 1.05),
(68, 'del Silencio', 'todos', 'todos', NULL, 1.20, 0.95, 0.95, 0.95, 1.00, 1.00),
(69, 'de los Antiguos', 'todos', 'todos', NULL, 1.00, 1.00, 1.00, 1.00, 1.10, 1.10),
(70, 'de los Dioses', 'todos', 'todos', NULL, 1.00, 1.00, 1.00, 1.10, 1.20, 1.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aa_edificio`
--

CREATE TABLE `aa_edificio` (
  `idedificio` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `coste_piedra` decimal(12,2) NOT NULL DEFAULT '0.00',
  `coste_metal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `coste_madera` decimal(12,2) NOT NULL DEFAULT '0.00',
  `coste_comida` decimal(12,2) NOT NULL DEFAULT '0.00',
  `coste_oro` decimal(12,2) NOT NULL DEFAULT '0.00',
  `coste_magia` decimal(12,2) NOT NULL DEFAULT '0.00',
  `multiplicador_coste` decimal(5,2) NOT NULL DEFAULT '1.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aa_edificiorequiere`
--

CREATE TABLE `aa_edificiorequiere` (
  `idedificio` int NOT NULL,
  `idedificio_requerido` int NOT NULL,
  `nivel_edificio_requerido` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aa_exploracion`
--

CREATE TABLE `aa_exploracion` (
  `idexploracion` int NOT NULL,
  `idusuario` int NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `estado` enum('activa','finalizada') NOT NULL DEFAULT 'activa',
  `idregion_nueva` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `aa_exploracion`
--

INSERT INTO `aa_exploracion` (`idexploracion`, `idusuario`, `fecha_inicio`, `fecha_fin`, `estado`, `idregion_nueva`) VALUES
(1, 3, '2026-09-10 13:25:56', '2026-09-10 13:33:26', 'finalizada', NULL),
(2, 3, '2026-09-10 20:08:50', '2026-09-10 21:23:50', 'finalizada', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aa_idioma`
--

CREATE TABLE `aa_idioma` (
  `id` smallint UNSIGNED NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `orden` smallint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `aa_idioma`
--

INSERT INTO `aa_idioma` (`id`, `codigo`, `nombre`, `activo`, `orden`) VALUES
(1, 'es', 'Español', 1, 1),
(2, 'en', 'English', 1, 2),
(3, 'fr', 'Français', 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aa_region`
--

CREATE TABLE `aa_region` (
  `idregion` int NOT NULL,
  `idtipo_region` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `adjetivo_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `aa_region`
--

INSERT INTO `aa_region` (`idregion`, `idtipo_region`, `nombre`, `adjetivo_id`) VALUES
(2, 20, 'Cavernas del Olvido', 61),
(3, 5, 'Lago Olvidado', NULL),
(4, 7, 'Desierto Eterno', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aa_region_edificio`
--

CREATE TABLE `aa_region_edificio` (
  `idregion` int NOT NULL,
  `idedificio` int NOT NULL,
  `nivel` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aa_tipo_region`
--

CREATE TABLE `aa_tipo_region` (
  `idtipo_region` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `genero` varchar(10) NOT NULL COMMENT 'masculino, femenino o neutro',
  `numero` varchar(10) NOT NULL COMMENT 'singular o plural',
  `produccion_piedra_hora` decimal(12,2) NOT NULL DEFAULT '0.00',
  `produccion_metal_hora` decimal(12,2) NOT NULL DEFAULT '0.00',
  `produccion_madera_hora` decimal(12,2) NOT NULL DEFAULT '0.00',
  `produccion_comida_hora` decimal(12,2) NOT NULL DEFAULT '0.00',
  `produccion_oro_hora` decimal(12,2) NOT NULL DEFAULT '0.00',
  `produccion_mana_hora` decimal(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `aa_tipo_region`
--

INSERT INTO `aa_tipo_region` (`idtipo_region`, `nombre`, `genero`, `numero`, `produccion_piedra_hora`, `produccion_metal_hora`, `produccion_madera_hora`, `produccion_comida_hora`, `produccion_oro_hora`, `produccion_mana_hora`) VALUES
(1, 'Pantano', 'masculino', 'singular', 100.00, 0.00, 350.00, 450.00, 0.00, 0.00),
(2, 'Bosque', 'masculino', 'singular', 150.00, 0.00, 650.00, 150.00, 0.00, 0.00),
(3, 'Montaña', 'femenino', 'singular', 500.00, 200.00, 100.00, 50.00, 0.00, 0.00),
(4, 'Volcán', 'masculino', 'singular', 350.00, 150.00, 0.00, 0.00, 50.00, 60.00),
(5, 'Lago', 'masculino', 'singular', 100.00, 0.00, 200.00, 650.00, 0.00, 0.00),
(6, 'Ruinas', 'femenino', 'plural', 250.00, 50.00, 100.00, 0.00, 100.00, 60.00),
(7, 'Desierto', 'masculino', 'singular', 250.00, 50.00, 0.00, 50.00, 200.00, 0.00),
(8, 'Tundra', 'femenino', 'singular', 300.00, 200.00, 150.00, 200.00, 0.00, 0.00),
(9, 'Glaciar', 'masculino', 'singular', 250.00, 150.00, 0.00, 50.00, 50.00, 70.00),
(10, 'Río', 'masculino', 'singular', 150.00, 0.00, 250.00, 450.00, 40.00, 0.00),
(11, 'Playa', 'femenino', 'singular', 150.00, 0.00, 200.00, 450.00, 50.00, 0.00),
(12, 'Pradera', 'femenino', 'singular', 150.00, 0.00, 150.00, 650.00, 0.00, 0.00),
(13, 'Colinas', 'femenino', 'plural', 350.00, 150.00, 200.00, 150.00, 0.00, 0.00),
(14, 'Cañón', 'masculino', 'singular', 450.00, 250.00, 100.00, 50.00, 0.00, 0.00),
(15, 'Valle', 'masculino', 'singular', 200.00, 50.00, 350.00, 350.00, 0.00, 0.00),
(16, 'Isla', 'femenino', 'singular', 150.00, 50.00, 250.00, 350.00, 40.00, 0.00),
(17, 'Jungla', 'femenino', 'singular', 100.00, 0.00, 600.00, 250.00, 0.00, 0.00),
(18, 'Estepa', 'femenino', 'singular', 250.00, 100.00, 150.00, 400.00, 0.00, 0.00),
(19, 'Acantilados', 'masculino', 'plural', 450.00, 200.00, 100.00, 100.00, 0.00, 0.00),
(20, 'Cavernas', 'femenino', 'plural', 350.00, 200.00, 0.00, 50.00, 0.00, 60.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aa_usuario`
--

CREATE TABLE `aa_usuario` (
  `idusuario` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `confirmado` tinyint(1) NOT NULL DEFAULT '1',
  `piedra` decimal(12,2) NOT NULL DEFAULT '0.00',
  `produccion_piedra_hora` decimal(12,2) NOT NULL DEFAULT '0.00',
  `metal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `produccion_metal_hora` decimal(12,2) NOT NULL DEFAULT '0.00',
  `madera` decimal(12,2) NOT NULL DEFAULT '0.00',
  `produccion_madera_hora` decimal(12,2) NOT NULL DEFAULT '0.00',
  `comida` decimal(12,2) NOT NULL DEFAULT '0.00',
  `produccion_comida_hora` decimal(12,2) NOT NULL DEFAULT '0.00',
  `oro` decimal(12,2) NOT NULL DEFAULT '0.00',
  `produccion_oro_hora` decimal(12,2) NOT NULL DEFAULT '0.00',
  `mana` decimal(12,2) NOT NULL DEFAULT '0.00',
  `produccion_mana_hora` decimal(12,2) NOT NULL DEFAULT '0.00',
  `fecha_actualizado` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `aa_usuario`
--

INSERT INTO `aa_usuario` (`idusuario`, `nombre`, `email`, `password`, `confirmado`, `piedra`, `produccion_piedra_hora`, `metal`, `produccion_metal_hora`, `madera`, `produccion_madera_hora`, `comida`, `produccion_comida_hora`, `oro`, `produccion_oro_hora`, `mana`, `produccion_mana_hora`, `fecha_actualizado`) VALUES
(3, 'david', 'david', '07d046d5fac12b3f82daf5035b9aae86db5adc8275ebfbf05ec83005a4a8ba3e', 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1789068602);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aa_usuario_region`
--

CREATE TABLE `aa_usuario_region` (
  `idusuario` int NOT NULL,
  `idregion` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `aa_usuario_region`
--

INSERT INTO `aa_usuario_region` (`idusuario`, `idregion`) VALUES
(3, 2),
(3, 3),
(3, 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aa_adjetivos_region`
--
ALTER TABLE `aa_adjetivos_region`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `aa_edificio`
--
ALTER TABLE `aa_edificio`
  ADD PRIMARY KEY (`idedificio`);

--
-- Indices de la tabla `aa_edificiorequiere`
--
ALTER TABLE `aa_edificiorequiere`
  ADD PRIMARY KEY (`idedificio`,`idedificio_requerido`),
  ADD KEY `idedificio_requerido` (`idedificio_requerido`);

--
-- Indices de la tabla `aa_exploracion`
--
ALTER TABLE `aa_exploracion`
  ADD PRIMARY KEY (`idexploracion`),
  ADD KEY `idusuario` (`idusuario`),
  ADD KEY `idregion_nueva` (`idregion_nueva`);

--
-- Indices de la tabla `aa_idioma`
--
ALTER TABLE `aa_idioma`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_idioma_codigo` (`codigo`);

--
-- Indices de la tabla `aa_region`
--
ALTER TABLE `aa_region`
  ADD PRIMARY KEY (`idregion`),
  ADD KEY `idtipo_region` (`idtipo_region`);

--
-- Indices de la tabla `aa_region_edificio`
--
ALTER TABLE `aa_region_edificio`
  ADD PRIMARY KEY (`idregion`,`idedificio`),
  ADD KEY `idedificio` (`idedificio`);

--
-- Indices de la tabla `aa_tipo_region`
--
ALTER TABLE `aa_tipo_region`
  ADD PRIMARY KEY (`idtipo_region`);

--
-- Indices de la tabla `aa_usuario`
--
ALTER TABLE `aa_usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `aa_usuario_region`
--
ALTER TABLE `aa_usuario_region`
  ADD PRIMARY KEY (`idusuario`,`idregion`),
  ADD KEY `idregion` (`idregion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `aa_adjetivos_region`
--
ALTER TABLE `aa_adjetivos_region`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de la tabla `aa_edificio`
--
ALTER TABLE `aa_edificio`
  MODIFY `idedificio` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `aa_exploracion`
--
ALTER TABLE `aa_exploracion`
  MODIFY `idexploracion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `aa_idioma`
--
ALTER TABLE `aa_idioma`
  MODIFY `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `aa_region`
--
ALTER TABLE `aa_region`
  MODIFY `idregion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `aa_tipo_region`
--
ALTER TABLE `aa_tipo_region`
  MODIFY `idtipo_region` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `aa_usuario`
--
ALTER TABLE `aa_usuario`
  MODIFY `idusuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `aa_exploracion`
--
ALTER TABLE `aa_exploracion`
  ADD CONSTRAINT `aa_exploracion_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `aa_usuario` (`idusuario`),
  ADD CONSTRAINT `aa_exploracion_ibfk_2` FOREIGN KEY (`idregion_nueva`) REFERENCES `aa_region` (`idregion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
