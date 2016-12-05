-- phpMyAdmin SQL Dump
-- version 4.6.5.1deb3+deb.cihar.com~yakkety.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 05, 2016 at 09:37 PM
-- Server version: 5.7.16-0ubuntu0.16.10.1
-- PHP Version: 7.0.8-3ubuntu3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digikademik`
--

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1480693283),
('m140209_132017_init', 1480693289),
('m140403_174025_create_account_table', 1480693290),
('m140504_113157_update_tables', 1480693290),
('m140504_130429_create_token_table', 1480693290),
('m140830_171933_fix_ip_field', 1480693290),
('m140830_172703_change_account_table_name', 1480693291),
('m141222_110026_update_ip_field', 1480693291),
('m141222_135246_alter_username_length', 1480693291),
('m150614_103145_update_social_account_table', 1480693291),
('m150623_212711_fix_username_notnull', 1480693291),
('m151218_234654_add_timezone_to_profile', 1480693291);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
