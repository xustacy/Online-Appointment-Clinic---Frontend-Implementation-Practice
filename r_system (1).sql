-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-04-17 06:24:41
-- 伺服器版本： 10.4.28-MariaDB
-- PHP 版本： 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `r_system`
--

-- --------------------------------------------------------

--
-- 資料表結構 `diagnose_time`
--

CREATE TABLE `diagnose_time` (
  `appointment_id` int(5) NOT NULL,
  `did` varchar(20) NOT NULL,
  `pno` int(5) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `time` varchar(10) NOT NULL,
  `category` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `diagnose_time`
--

INSERT INTO `diagnose_time` (`appointment_id`, `did`, `pno`, `date`, `time`, `category`) VALUES
(2, 'S10490023', 1, '2024-01-22', '上午', '內科'),
(3, 'S10490023', 1, '2024-01-09', '上午', '內科'),
(4, 'S10490023', 1, '2024-01-15', '下午', '內科'),
(5, 'S10490023', 1, '2024-01-17', '下午', '內科'),
(7, 's000000000', 5, '2024-01-17', '下午', '針炙'),
(8, 'S10490039', 3, '2024-01-17', '上午', '內科');

-- --------------------------------------------------------

--
-- 資料表結構 `doctor`
--

CREATE TABLE `doctor` (
  `name` varchar(10) DEFAULT NULL,
  `did` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `doctor`
--

INSERT INTO `doctor` (`name`, `did`) VALUES
('無指定', 's000000000'),
('許舒雅', 'S10490005'),
('陳玟茵', 'S10490023'),
('吳映潔', 'S10490039');

-- --------------------------------------------------------

--
-- 資料表結構 `patient`
--

CREATE TABLE `patient` (
  `pno` int(5) NOT NULL,
  `name` varchar(10) DEFAULT NULL,
  `rId` varchar(10) NOT NULL,
  `birthday` date NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(10) DEFAULT NULL,
  `address` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `patient`
--

INSERT INTO `patient` (`pno`, `name`, `rId`, `birthday`, `phone`, `address`) VALUES
(1, '趙品欣', 'R123456789', '1999-03-16', '0988041664', '台中市龍井區新興路3巷2號 '),
(3, '張依林', 'S201348598', '1997-02-20', '0905267884', '彰化縣永安鎮中正路20巷158號 '),
(4, '柯文哲', 'R224828678', '2018-01-15', '0905267884', '台中市龍井區'),
(5, '吳子誠', 'E125756339', '2003-12-21', '0937438598', '台中市台灣大道四段1727號'),
(6, '孔小明', 'Q123456852', '2001-09-30', '0987692127', '花蓮縣海安鎮市政路一段30號');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `diagnose_time`
--
ALTER TABLE `diagnose_time`
  ADD PRIMARY KEY (`appointment_id`),
  ADD UNIQUE KEY `appointment` (`appointment_id`),
  ADD KEY `diagnose_time_ibfk_2` (`did`),
  ADD KEY `diagnose_time_ibfk_1` (`pno`);

--
-- 資料表索引 `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`did`);

--
-- 資料表索引 `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`pno`),
  ADD UNIQUE KEY `rId` (`rId`);

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `diagnose_time`
--
ALTER TABLE `diagnose_time`
  ADD CONSTRAINT `diagnose_time_ibfk_1` FOREIGN KEY (`pno`) REFERENCES `patient` (`pno`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `diagnose_time_ibfk_2` FOREIGN KEY (`did`) REFERENCES `doctor` (`did`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
