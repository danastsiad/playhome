-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Июл 02 2023 г., 14:59
-- Версия сервера: 10.4.25-MariaDB
-- Версия PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `gaming_company`
--

-- --------------------------------------------------------

--
-- Структура таблицы `developers`
--

CREATE TABLE `developers` (
  `ID_Developer` int(11) NOT NULL,
  `Name` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `developers`
--

INSERT INTO `developers` (`ID_Developer`, `Name`) VALUES
(1, 'Максим Иванов'),
(2, 'Андрей Беляев'),
(3, 'Лев Марков');

-- --------------------------------------------------------

--
-- Структура таблицы `games`
--

CREATE TABLE `games` (
  `ID_Game` int(11) NOT NULL,
  `Picture` varchar(100) NOT NULL,
  `Name` varchar(32) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `ID_Genre` int(11) DEFAULT NULL,
  `Rating` decimal(10,1) DEFAULT NULL,
  `ID_Developer` int(11) DEFAULT NULL,
  `Release_date` date DEFAULT NULL,
  `ID_Platform` int(11) DEFAULT NULL,
  `Price` decimal(10,0) DEFAULT NULL,
  `Remainder` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `games`
--

INSERT INTO `games` (`ID_Game`, `Picture`, `Name`, `Description`, `ID_Genre`, `Rating`, `ID_Developer`, `Release_date`, `ID_Platform`, `Price`, `Remainder`) VALUES
(1, '/playhome/img/1.png', 'RIMWORLD', 'управляет тремя людьми, выжившими после крушения космического лайнера, в строительстве колонии в приграничном мире на краю изученного пространства.', 1, '7.1', 1, '2018-10-17', 1, '790', 20),
(2, '/playhome/img/2.png', 'AQUATICO', 'это градостроительный симулятор на выживание, действие которого разворачивается под водой. Постройте свой город на дне океана, приспосабливаясь к условиям подводной жизни.', 2, '6.7', 2, '2023-01-12', 2, '590', 3),
(3, '/playhome/img/3.png', 'STELLARIS', 'это стратегия, где все события будут происходить в режиме реального времени. Откройте для себя захватывающую и постоянно меняющуюся вселенную.', 2, '6.5', 3, '2016-05-09', 1, '550', 7);

-- --------------------------------------------------------

--
-- Структура таблицы `genres`
--

CREATE TABLE `genres` (
  `ID_Genre` int(11) NOT NULL,
  `Name` varchar(23) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `genres`
--

INSERT INTO `genres` (`ID_Genre`, `Name`) VALUES
(1, 'Инди'),
(2, 'Симуляторы');

-- --------------------------------------------------------

--
-- Структура таблицы `platforms`
--

CREATE TABLE `platforms` (
  `ID_Platform` int(11) NOT NULL,
  `Name` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `platforms`
--

INSERT INTO `platforms` (`ID_Platform`, `Name`) VALUES
(1, 'Windows'),
(2, 'PC'),
(12, 'IOS');

-- --------------------------------------------------------

--
-- Структура таблицы `players`
--

CREATE TABLE `players` (
  `ID_Player` int(11) NOT NULL,
  `ID_User` int(11) NOT NULL,
  `ID_Game` int(11) NOT NULL,
  `Promocode` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `players`
--

INSERT INTO `players` (`ID_Player`, `ID_User`, `ID_Game`, `Promocode`) VALUES
(1, 2, 1, '16JPL0P3O27F3EQH'),
(2, 2, 2, 'XZRA9HRTDVJ1A6AN'),
(3, 2, 3, 'UMGGQ2JEWBKFRKMU');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `ID_User` int(11) NOT NULL,
  `Login` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`ID_User`, `Login`, `Email`, `Password`, `Status`) VALUES
(1, 'php', 'php@mpt.ru', 'fcea920f7412b5da7be0cf42b8c93759', 1),
(2, '123', '123@mpt.ru', 'e10adc3949ba59abbe56e057f20f883e', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `developers`
--
ALTER TABLE `developers`
  ADD PRIMARY KEY (`ID_Developer`);

--
-- Индексы таблицы `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`ID_Game`),
  ADD KEY `ID_Genre` (`ID_Genre`,`ID_Developer`,`ID_Platform`),
  ADD KEY `ID_Developer` (`ID_Developer`),
  ADD KEY `ID_Platform` (`ID_Platform`);

--
-- Индексы таблицы `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`ID_Genre`);

--
-- Индексы таблицы `platforms`
--
ALTER TABLE `platforms`
  ADD PRIMARY KEY (`ID_Platform`);

--
-- Индексы таблицы `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`ID_Player`),
  ADD KEY `ID_Users` (`ID_User`,`ID_Game`),
  ADD KEY `ID_Game` (`ID_Game`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID_User`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `developers`
--
ALTER TABLE `developers`
  MODIFY `ID_Developer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `games`
--
ALTER TABLE `games`
  MODIFY `ID_Game` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `genres`
--
ALTER TABLE `genres`
  MODIFY `ID_Genre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `platforms`
--
ALTER TABLE `platforms`
  MODIFY `ID_Platform` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `players`
--
ALTER TABLE `players`
  MODIFY `ID_Player` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `ID_User` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`ID_Developer`) REFERENCES `developers` (`ID_Developer`),
  ADD CONSTRAINT `games_ibfk_2` FOREIGN KEY (`ID_Genre`) REFERENCES `genres` (`ID_Genre`),
  ADD CONSTRAINT `games_ibfk_3` FOREIGN KEY (`ID_Platform`) REFERENCES `platforms` (`ID_Platform`);

--
-- Ограничения внешнего ключа таблицы `players`
--
ALTER TABLE `players`
  ADD CONSTRAINT `players_ibfk_1` FOREIGN KEY (`ID_User`) REFERENCES `users` (`ID_User`),
  ADD CONSTRAINT `players_ibfk_2` FOREIGN KEY (`ID_Game`) REFERENCES `games` (`ID_Game`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
