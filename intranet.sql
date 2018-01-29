-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 15-01-2018 a las 16:18:29
-- Versión del servidor: 5.7.20
-- Versión de PHP: 7.0.27-1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `intranet`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `description` varchar(5000) COLLATE utf8_spanish_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `day_week` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `hour` varchar(5) COLLATE utf8_spanish_ci DEFAULT NULL,
  `max` int(11) NOT NULL DEFAULT '0',
  `min_team` int(11) NOT NULL DEFAULT '1',
  `max_team` int(11) NOT NULL DEFAULT '1',
  `date_end_reg` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `games`
--

INSERT INTO `games` (`id`, `image`, `title`, `description`, `date`, `day_week`, `hour`, `max`, `min_team`, `max_team`, `date_end_reg`) VALUES
(1, 'ardilla.jpg', 'Unreal Tournament', 'BLABLABLABLALBLALBLABLBALABLBALLBALALBBLALBBLBALLBALBLB', '2018-01-13 21:00:00', 'Sábado', '21:00', 32, 1, 1, NULL),
(2, 'ardilla.jpg', 'Rocket League', 'BLABLALBLALBALBLBALBLABLLBALBLALBALBLBALBLABLABLALBLALBA', '2018-01-13 18:00:00', 'Sábado', '18:00', 32, 2, 2, '2018-01-08 00:00:00'),
(3, 'ardilla.jpg', 'Hearthstone', 'BLALBALBLBLABLBALBLALABLLABLA', '2018-01-14 16:00:00', 'Domingo', '16:00', 16, 4, 4, '2018-01-16 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `game_points`
--

CREATE TABLE `game_points` (
  `id_game` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `game_users`
--

CREATE TABLE `game_users` (
  `id_game` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_team` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `game_users`
--

INSERT INTO `game_users` (`id_game`, `id_user`, `id_team`) VALUES
(1, 1, NULL),
(3, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'god'),
(2, 'admin'),
(3, 'user');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nick` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `pass` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `role` int(11) DEFAULT NULL,
  `cryp` varchar(64) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nick`, `pass`, `email`, `role`, `cryp`) VALUES
(1, 'rgeo', '0f12d74ba9f4ba52b2852f7f0521d845', 'hermidamourelle@gmail.com', 3, 'cf5d56834a71d3bc9fbf5c640fd559fd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `winners`
--

CREATE TABLE `winners` (
  `id_game` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `position` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `game_points`
--
ALTER TABLE `game_points`
  ADD PRIMARY KEY (`id_game`,`position`);

--
-- Indices de la tabla `game_users`
--
ALTER TABLE `game_users`
  ADD PRIMARY KEY (`id_game`,`id_user`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_team` (`id_team`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role` (`role`);

--
-- Indices de la tabla `winners`
--
ALTER TABLE `winners`
  ADD PRIMARY KEY (`id_game`,`id_user`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `game_points`
--
ALTER TABLE `game_points`
  ADD CONSTRAINT `game_points_ibfk_1` FOREIGN KEY (`id_game`) REFERENCES `games` (`id`);

--
-- Filtros para la tabla `game_users`
--
ALTER TABLE `game_users`
  ADD CONSTRAINT `game_users_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `game_users_ibfk_2` FOREIGN KEY (`id_game`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `game_users_ibfk_3` FOREIGN KEY (`id_team`) REFERENCES `teams` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `winners`
--
ALTER TABLE `winners`
  ADD CONSTRAINT `winners_ibfk_1` FOREIGN KEY (`id_game`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `winners_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
