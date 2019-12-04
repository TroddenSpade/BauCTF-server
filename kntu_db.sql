-- PHP Version: 7.3

SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+03:30";

--
-- Database: `kntu_db`
--
CREATE DATABASE IF NOT EXISTS `kntu_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `kntu_db`;

CREATE TABLE `users` (
  `uid` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `token` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `problems` (
  `pid` int NOT NULL PRIMARY KEY ,
  `code` varchar(100) NOT NULL,
  `link` varchar(200) NOT NULL,
  `author` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `score` int NOT NULL,
  `state` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `submission` (
  `sid` int NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `pid` int NOT NULL,
  `uid` int NOT NULL,
  `code` varchar(100) NOT NULL,
  `ts` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (pid) REFERENCES problems(pid),
  FOREIGN KEY (uid) REFERENCES users(uid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
