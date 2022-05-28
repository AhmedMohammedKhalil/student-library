-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2022 at 10:57 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `students_library`
--
CREATE DATABASE IF NOT EXISTS `students_library` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `students_library`;

-- --------------------------------------------------------

--
-- Table structure for table `aboutus`
--

DROP TABLE IF EXISTS `aboutus`;
CREATE TABLE IF NOT EXISTS `aboutus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `aboutus`
--

INSERT INTO `aboutus` (`id`, `text`) VALUES
(35, '<p>students can borrow book</p>'),
(36, '<p>Doctors can buy books</p>');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `available` varchar(20) NOT NULL,
  `conditions` varchar(50) NOT NULL,
  `photo` text DEFAULT NULL,
  `price` double DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `books_ibfk_2` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `description`, `available`, `conditions`, `photo`, `price`, `user_id`) VALUES
(6, 'arabic language', '&lt;p&gt;arabic book&lt;/p&gt;', 'available', 'old', NULL, 3, 7),
(7, 'Big Ideas Simply Explained', '&lt;p&gt;physics book&lt;/p&gt;', 'available', 'new', NULL, 4.5, 3),
(8, 'Mathmatics', '&lt;p&gt;Math book&lt;/p&gt;', 'available', 'used', NULL, 5, 6),
(9, 'arabic language for english speaking students', '&lt;p&gt;arabic book&lt;/p&gt;', 'available', 'new', NULL, 8, 3),
(10, 'Seven Brief Lessons on Physics', '&lt;p&gt;physics book&lt;/p&gt;', 'available', 'new', NULL, 7, 6),
(11, 'Mathmatics', '&lt;p&gt;Math book&lt;/p&gt;', 'available', 'new', NULL, 9, 7);

-- --------------------------------------------------------

--
-- Table structure for table `book_course`
--

DROP TABLE IF EXISTS `book_course`;
CREATE TABLE IF NOT EXISTS `book_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `book_id` (`book_id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `book_course`
--

INSERT INTO `book_course` (`id`, `book_id`, `course_id`) VALUES
(19, 6, 3),
(20, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `major_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `major_id` (`major_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `major_id`) VALUES
(1, 'Physics', 4),
(3, 'Arabic', 6),
(4, 'Math', 5);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_names`
--

DROP TABLE IF EXISTS `doctor_names`;
CREATE TABLE IF NOT EXISTS `doctor_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor_names`
--

INSERT INTO `doctor_names` (`id`, `name`, `course_id`) VALUES
(8, 'Aabed Abdalla', 1),
(9, 'Talal Amar', 1),
(10, '', 1),
(11, 'Reem Falah', 3),
(12, 'Nora Abdalla', 3),
(13, 'Batol', 3),
(14, 'Sama Omran', 4);

-- --------------------------------------------------------

--
-- Table structure for table `majors`
--

DROP TABLE IF EXISTS `majors`;
CREATE TABLE IF NOT EXISTS `majors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `uni_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uni_id` (`uni_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `majors`
--

INSERT INTO `majors` (`id`, `name`, `description`, `uni_id`) VALUES
(4, 'physics', '&lt;p&gt;physics&lt;/p&gt;', 13),
(5, 'math', '&lt;p&gt;math&lt;/p&gt;', 14),
(6, 'arabic', '&lt;p&gt;arabic&lt;/p&gt;', 13),
(7, 'english', '&lt;p&gt;english&lt;/p&gt;', 14);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `book_id` (`book_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `univerisities`
--

DROP TABLE IF EXISTS `univerisities`;
CREATE TABLE IF NOT EXISTS `univerisities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `photo` text DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `univerisities`
--

INSERT INTO `univerisities` (`id`, `name`, `photo`, `description`) VALUES
(13, 'Education University', './imgs/univeristies/univeristy.png', '&lt;p&gt;Education University&lt;/p&gt;'),
(14, 'Kuwait University', NULL, '&lt;p&gt;Kuwait University&lt;/p&gt;');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `email` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` text DEFAULT NULL,
  `phone` text NOT NULL,
  `major_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `major_id` (`major_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `type`, `email`, `password`, `photo`, `phone`, `major_id`) VALUES
(3, 'reem falah', 'student', 'rem@gmail.com', '$2y$10$AycU2tfhtaq.OZGvnzWAGu5fznJ5x.h4.uI1C3PeQWXLLrD627/LC', './imgs/profileImages/project-12.jpg', '69532581', 4),
(5, 'admin', 'admin', 'admin@gmail.com', '$2y$10$AycU2tfhtaq.OZGvnzWAGu5fznJ5x.h4.uI1C3PeQWXLLrD627/LC', './imgs/profileImages/user-image.jpg', '', NULL),
(6, 'sama talal', 'student', 'sama@gmail.com', '$2y$10$AycU2tfhtaq.OZGvnzWAGu5fznJ5x.h4.uI1C3PeQWXLLrD627/LC', NULL, '69532581', 6),
(7, 'tala abdalla', 'doctor', 'tala@gmail.com', '$2y$10$AycU2tfhtaq.OZGvnzWAGu5fznJ5x.h4.uI1C3PeQWXLLrD627/LC', NULL, '69532581', 5);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `book_course`
--
ALTER TABLE `book_course`
  ADD CONSTRAINT `book_course_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `book_course_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor_names`
--
ALTER TABLE `doctor_names`
  ADD CONSTRAINT `doctor_names_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `majors`
--
ALTER TABLE `majors`
  ADD CONSTRAINT `majors_ibfk_1` FOREIGN KEY (`uni_id`) REFERENCES `univerisities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
