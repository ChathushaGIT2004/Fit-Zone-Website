-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 27, 2025 at 07:47 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gym`
--
CREATE DATABASE IF NOT EXISTS `gym` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `gym`;

-- --------------------------------------------------------

--
-- Table structure for table `additionalpayments`
--

DROP TABLE IF EXISTS `additionalpayments`;
CREATE TABLE IF NOT EXISTS `additionalpayments` (
  `PaymentID` int NOT NULL AUTO_INCREMENT,
  `UserID` int NOT NULL,
  `Amount` int NOT NULL,
  `Description` varchar(1000) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Date` timestamp NOT NULL,
  PRIMARY KEY (`PaymentID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `additionalpayments`
--

INSERT INTO `additionalpayments` (`PaymentID`, `UserID`, `Amount`, `Description`, `Status`, `Date`) VALUES
(2, 1, 5000, 'HIIT Bootcamp', 'Unpaid', '2025-04-09 11:01:32');

-- --------------------------------------------------------

--
-- Table structure for table `chat-space`
--

DROP TABLE IF EXISTS `chat-space`;
CREATE TABLE IF NOT EXISTS `chat-space` (
  `Sender` int NOT NULL,
  `Reciever` int NOT NULL,
  `Message` varchar(1000) NOT NULL,
  `Date-time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `Date-time` (`Date-time`),
  UNIQUE KEY `Date-time_2` (`Date-time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chat-space`
--

INSERT INTO `chat-space` (`Sender`, `Reciever`, `Message`, `Date-time`) VALUES
(1, 3, 'hi', '2025-03-30 22:31:40'),
(3, 1, 'hello', '2025-03-30 22:31:54'),
(1, 2, 'iho', '2025-03-30 22:32:46'),
(1, 2, 'hi', '2025-03-30 23:25:06'),
(1, 2, 'hi', '2025-03-30 23:25:44'),
(1, 2, 'hellodncfncncncncn', '2025-03-30 23:28:19'),
(1, 2, 'hrhfhddndg nhrjfjfjfjfghmghmghmgmgmgmgmgmgmgmggkgm', '2025-03-30 23:33:04'),
(1, 2, 'hi', '2025-03-31 04:55:27'),
(1, 3, 'hii', '2025-03-31 08:32:34'),
(1, 3, 'hello', '2025-03-31 08:34:39'),
(1, 3, 'hi mage raththaran yaluwe', '2025-03-31 08:35:40'),
(3, 1, 'hello', '2025-03-31 08:39:10'),
(1, 2, 'hellow', '2025-03-31 08:58:53'),
(3, 1, 'eiii uba MOdaya', '2025-03-31 09:04:15'),
(1, 3, 'Gona', '2025-03-31 09:04:26'),
(1, 2, 'Hellow mr Sahan   sbnbjsbbnuijbsbsnsjisbsbndbdsklbmdsbdbdbdbdfbdfomkbldhbdbdkfmbldhdbnjdb;iuonckjlcdbcjkmbcjbn icjk ncmjbdjnbvcbv;igbkndbvdgbidbklcgbdibcgb', '2025-03-31 10:22:16'),
(1, 3, 'hello  my dead', '2025-03-31 12:42:36'),
(1, 3, 'hee', '2025-03-31 12:49:02'),
(1, 3, 'hi', '2025-03-31 13:50:42'),
(1, 3, 'hii', '2025-03-31 15:04:33'),
(2, 3, 'hii', '2025-04-05 10:21:25'),
(2, 3, 'hiih', '2025-04-05 10:23:45'),
(3, 2, 'hiii', '2025-04-05 10:23:55'),
(1, 3, 'hiii  there', '2025-04-23 01:28:00'),
(1, 3, 'Hello', '2025-04-22 21:52:11'),
(1, 2, 'Hellow There', '2025-04-23 00:42:07'),
(1, 2, 'hellow', '2025-04-23 01:27:44');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
CREATE TABLE IF NOT EXISTS `class` (
  `ClassID` int NOT NULL AUTO_INCREMENT,
  `Class-Code` varchar(100) NOT NULL,
  `ClassName` varchar(100) NOT NULL,
  `ClassDescription` varchar(1000) NOT NULL,
  `AssignedTrainers` int NOT NULL,
  PRIMARY KEY (`ClassID`),
  KEY `Class-Code` (`Class-Code`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`ClassID`, `Class-Code`, `ClassName`, `ClassDescription`, `AssignedTrainers`) VALUES
(1, 'C1', 'Yoga Class', 'this is a yoga class', 3),
(2, 'C2', 'Zumba class', 'this is zumba class', 3);

-- --------------------------------------------------------

--
-- Table structure for table `class-shedule`
--

DROP TABLE IF EXISTS `class-shedule`;
CREATE TABLE IF NOT EXISTS `class-shedule` (
  `ClassID` int NOT NULL,
  `Date` varchar(100) NOT NULL,
  `Start` time NOT NULL,
  `End` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `class-shedule`
--

INSERT INTO `class-shedule` (`ClassID`, `Date`, `Start`, `End`) VALUES
(1, '2025-04-01', '00:00:08', '00:00:09'),
(1, '2025-04-01', '00:00:08', '00:00:09'),
(2, '2025-05-14', '00:00:09', '00:00:10'),
(1, '2025-04-01', '00:00:08', '00:00:09'),
(2, '2025-05-14', '00:00:09', '00:00:10'),
(2, '2025-04-01', '00:00:09', '00:00:10'),
(1, '2025-04-01', '08:00:00', '00:00:09');

-- --------------------------------------------------------

--
-- Table structure for table `member-trainer`
--

DROP TABLE IF EXISTS `member-trainer`;
CREATE TABLE IF NOT EXISTS `member-trainer` (
  `MemberID` int NOT NULL,
  `TrainerID` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `member-trainer`
--

INSERT INTO `member-trainer` (`MemberID`, `TrainerID`) VALUES
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `membership-plans`
--

DROP TABLE IF EXISTS `membership-plans`;
CREATE TABLE IF NOT EXISTS `membership-plans` (
  `PlanID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Description` varchar(1000) NOT NULL,
  `Price` int NOT NULL,
  PRIMARY KEY (`PlanID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `membership-plans`
--

INSERT INTO `membership-plans` (`PlanID`, `Name`, `Description`, `Price`) VALUES
(1, 'Premieum', 'this is premieum package', 5),
(2, 'Basic', 'this is basic plan  ', 2000);

-- --------------------------------------------------------

--
-- Table structure for table `nutrition_supplements`
--

DROP TABLE IF EXISTS `nutrition_supplements`;
CREATE TABLE IF NOT EXISTS `nutrition_supplements` (
  `ItemID` int NOT NULL AUTO_INCREMENT,
  `Item_Name` varchar(100) NOT NULL,
  `Type` enum('Supplement','Meal Plan','Vitamin','Snack','Drink') NOT NULL,
  `Target_Goal` varchar(100) DEFAULT NULL,
  `Nutrition_Info` text,
  `Usage_Timing` varchar(100) DEFAULT NULL,
  `Dosage` varchar(50) DEFAULT NULL,
  `Allergens` varchar(255) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`ItemID`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nutrition_supplements`
--

INSERT INTO `nutrition_supplements` (`ItemID`, `Item_Name`, `Type`, `Target_Goal`, `Nutrition_Info`, `Usage_Timing`, `Dosage`, `Allergens`, `Price`) VALUES
(1, 'Whey Protein Isolate', 'Supplement', 'Muscle Gain', '120 kcal, 25g protein, 1g carb, 1g fat', 'Post-workout', '1 scoop', 'Dairy', 4500.00),
(2, 'Creatine Monohydrate', 'Supplement', 'Strength', '0 kcal, 0g protein, 0g carb, 0g fat', 'Pre-workout', '5g', 'None', 2500.00),
(3, 'BCAA Powder', 'Supplement', 'Endurance', '10 kcal, 0g protein, 2g carb, 0g fat', 'Pre/Post-workout', '10g', 'None', 3500.00),
(4, 'Multivitamin Capsules', 'Vitamin', 'Overall Health', 'Contains Vitamins A, B, C, D, E', 'Daily', '1 capsule', 'None', 1500.00),
(5, 'Fish Oil', 'Supplement', 'Heart Health', '10 kcal, 1g protein, 0g carb, 1g fat', 'Daily', '1 softgel', 'Fish', 1200.00),
(6, 'Glutamine Powder', 'Supplement', 'Recovery', '0 kcal, 0g protein, 0g carb, 0g fat', 'Post-workout', '5g', 'None', 2200.00),
(7, 'Vitamin C 1000mg', 'Vitamin', 'Immune Support', '1000mg Vitamin C', 'Daily', '1 tablet', 'None', 800.00),
(8, 'Casein Protein', 'Supplement', 'Muscle Repair', '120 kcal, 24g protein, 3g carb, 1g fat', 'Night', '1 scoop', 'Dairy', 4800.00),
(9, 'Whey Protein Concentrate', 'Supplement', 'Muscle Gain', '130 kcal, 25g protein, 4g carb, 2g fat', 'Post-workout', '1 scoop', 'Dairy', 4000.00),
(10, 'L-Carnitine Liquid', 'Supplement', 'Fat Loss', '10 kcal, 0g protein, 2g carb, 0g fat', 'Pre-workout', '10ml', 'None', 1800.00),
(11, 'ZMA (Zinc & Magnesium)', 'Supplement', 'Sleep & Recovery', 'Zinc 30mg, Magnesium 450mg', 'Night', '1 capsule', 'None', 2500.00),
(12, 'Beta-Alanine', 'Supplement', 'Endurance', '0 kcal, 0g protein, 0g carb, 0g fat', 'Pre-workout', '3g', 'None', 1500.00),
(13, 'CLA (Conjugated Linoleic Acid)', 'Supplement', 'Fat Loss', '10 kcal, 0g protein, 1g carb, 1g fat', 'Daily', '2 softgels', 'None', 2000.00),
(14, 'Hemp Protein', 'Supplement', 'Muscle Gain', '130 kcal, 20g protein, 10g carb, 4g fat', 'Post-workout', '1 scoop', 'None', 3500.00),
(15, 'MCT Oil', 'Supplement', 'Energy Boost', '100 kcal, 0g protein, 0g carb, 10g fat', 'Pre-workout', '1 tbsp', 'Coconut', 1700.00),
(16, 'Iron Tablets', 'Supplement', 'Iron Deficiency', '20mg Iron', 'Daily', '1 tablet', 'None', 1000.00),
(17, 'Probiotic Capsules', 'Supplement', 'Gut Health', 'Contains Lactobacillus and Bifidobacterium strains', 'Daily', '1 capsule', 'None', 1200.00),
(18, 'Green Tea Extract', 'Supplement', 'Fat Loss', '50mg caffeine, 2g EGCG', 'Pre-workout', '1 capsule', 'None', 1200.00),
(19, 'Pre-Workout Formula', 'Supplement', 'Energy & Focus', '100mg caffeine, 5g beta-alanine, 2g creatine', 'Pre-workout', '1 scoop', 'None', 2500.00),
(20, 'Collagen Peptides', 'Supplement', 'Joint Health', '10g collagen, 0g carb, 0g fat', 'Daily', '1 scoop', 'None', 3000.00);

-- --------------------------------------------------------

--
-- Table structure for table `personal-sessions`
--

DROP TABLE IF EXISTS `personal-sessions`;
CREATE TABLE IF NOT EXISTS `personal-sessions` (
  `SessionID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `TrainerID` int NOT NULL,
  `Start` time NOT NULL,
  `End` time NOT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY (`SessionID`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `personal-sessions`
--

INSERT INTO `personal-sessions` (`SessionID`, `Name`, `TrainerID`, `Start`, `End`, `Date`) VALUES
(1, 'Chathusha day 1', 0, '17:00:00', '18:00:00', '2025-04-14'),
(2, 'Day 2', 0, '14:01:03', '15:01:03', '2025-04-07'),
(3, 'Chathusha day 3', 3, '12:01:00', '15:58:00', '2025-04-06'),
(16, 'Day 4', 3, '16:30:00', '17:30:00', '2025-04-26');

-- --------------------------------------------------------

--
-- Table structure for table `stories`
--

DROP TABLE IF EXISTS `stories`;
CREATE TABLE IF NOT EXISTS `stories` (
  `UserID` int NOT NULL,
  `Title` varchar(200) NOT NULL,
  `Message` varchar(1000) NOT NULL,
  `Display` varchar(100) NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stories`
--

INSERT INTO `stories` (`UserID`, `Title`, `Message`, `Display`) VALUES
(1, 'this changes my life ', 'Joining FitZone changed my life. I lost 15kg in 4 months and feel more confident than ever before!', 'yes'),
(4, 'My Weight Loss Journey', 'Joining FitZone changed my life. I lost 15kg in 4 months and feel more confident than ever before!', 'yes'),
(5, 'Stronger Every Day', 'With the guidance of amazing trainers and consistent workouts, I\'ve become physically and mentally stronger. Thank you, FitZone!', 'yes'),
(2, 'My Weight Loss Journey', 'Joining FitZone changed my life. I lost 15kg in 4 months and feel more confident than ever before!', 'yes'),
(3, 'Stronger Every Day', 'With the guidance of amazing trainers and consistent workouts, I\'ve become physically and mentally stronger. Thank you, FitZone!', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `suggested_nutritions`
--

DROP TABLE IF EXISTS `suggested_nutritions`;
CREATE TABLE IF NOT EXISTS `suggested_nutritions` (
  `SuggestionID` int NOT NULL AUTO_INCREMENT,
  `UserID` int NOT NULL,
  `SuggestedBy` int NOT NULL,
  `ItemID` int NOT NULL,
  `Suggested_Date` datetime DEFAULT CURRENT_TIMESTAMP,
  `Comments` text,
  PRIMARY KEY (`SuggestionID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `suggested_nutritions`
--

INSERT INTO `suggested_nutritions` (`SuggestionID`, `UserID`, `SuggestedBy`, `ItemID`, `Suggested_Date`, `Comments`) VALUES
(1, 1, 3, 1, '2025-04-06 22:47:45', 'ssdbbpnuidvbu'),
(2, 1, 3, 1, '2025-04-06 22:47:55', 'dsbsdbxc '),
(3, 3, 3, 2, '2025-04-06 22:48:03', 'gnfgnmgn ,'),
(4, 2, 4, 15, '2025-04-23 07:35:39', 'pnui'),
(5, 1, 4, 7, '2025-05-05 11:40:19', 'suggestoins');

-- --------------------------------------------------------

--
-- Table structure for table `trainer`
--

DROP TABLE IF EXISTS `trainer`;
CREATE TABLE IF NOT EXISTS `trainer` (
  `TrainerID` int NOT NULL,
  `JobTitle` varchar(200) NOT NULL,
  `Description` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `trainer`
--

INSERT INTO `trainer` (`TrainerID`, `JobTitle`, `Description`) VALUES
(3, '-Yoga Expert', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user-height`
--

DROP TABLE IF EXISTS `user-height`;
CREATE TABLE IF NOT EXISTS `user-height` (
  `UserID` int NOT NULL,
  `Date` varchar(10) NOT NULL,
  `Hight` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user-height`
--

INSERT INTO `user-height` (`UserID`, `Date`, `Hight`) VALUES
(1, '2024-11-26', 156);

-- --------------------------------------------------------

--
-- Table structure for table `user-shedule`
--

DROP TABLE IF EXISTS `user-shedule`;
CREATE TABLE IF NOT EXISTS `user-shedule` (
  `USerID` int NOT NULL,
  `SheduleID` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user-shedule`
--

INSERT INTO `user-shedule` (`USerID`, `SheduleID`) VALUES
(1, 'C1'),
(1, 'W3'),
(1, 'C4'),
(1, 'C4'),
(1, '2'),
(1, '1'),
(1, 'C1'),
(1, '0'),
(1, 'W2'),
(1, 'W2');

-- --------------------------------------------------------

--
-- Table structure for table `user-weight`
--

DROP TABLE IF EXISTS `user-weight`;
CREATE TABLE IF NOT EXISTS `user-weight` (
  `UserID` int NOT NULL,
  `Date` varchar(100) NOT NULL,
  `Weight` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user-weight`
--

INSERT INTO `user-weight` (`UserID`, `Date`, `Weight`) VALUES
(1, '2025-05-01', 92);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `User_ID` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(100) NOT NULL,
  `First_Name` varchar(100) NOT NULL,
  `Last_Name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Email` varchar(200) NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `DOB` date NOT NULL,
  `Type` varchar(10) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Plan` varchar(20) NOT NULL,
  `Joined_Date` timestamp NOT NULL,
  `profilepic` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`User_ID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_ID`, `Username`, `First_Name`, `Last_Name`, `Email`, `Gender`, `DOB`, `Type`, `Password`, `Plan`, `Joined_Date`, `profilepic`) VALUES
(1, 'CJDew', 'Chathusha ', 'Dewmith', 'Chathushadewmin@gmail.com', 'male', '2004-08-05', 'member', 'dewmin123', '1', '2025-02-03 18:30:00', '1.jpg.jpeg'),
(2, 'Admin', 'Admin', 'Admin', 'Fitness@kurunagala.com', 'male', '2004-08-05', 'Admin', 'Admin123', '', '0000-00-00 00:00:00', ''),
(3, 'Oshanbro', 'Oshan ', 'Nethsara', 'Oshan@gmail.com', 'Male', '0000-00-00', 'Trainer', 'oshan123', '', '0000-00-00 00:00:00', '3.jpg'),
(4, 'Chira', 'Chiraj ', 'Deepesh', 'Chirajdeepesh@gmail.com', 'Male', '2004-05-15', 'Trainer', '123', '1', '0000-00-00 00:00:00', '4,j[g'),
(5, 'Samagalagoda', 'Samandee', 'Galagoda', 'Samagalagoda203@gmail.com', 'Female', '2001-05-05', 'Member', 'Sam123', '1', '0000-00-00 00:00:00', '5.jpeg'),
(6, 'SumaliWanigasekara', 'Sumali', 'Wanigasekara', 'Sumaliwani@gmail.com', 'Female', '1991-05-16', 'Member', 'Admin', '2', '2025-04-05 18:30:00', '6.jpg'),
(7, 'DGuruge', 'Denuwan', 'Guruge', 'DenuwanGuruge@gmail.com', 'Male', '2003-05-01', 'Member', 'denuwan', '2', '2025-04-05 18:30:00', '7'),
(9, 'Pubba', 'Pubudya ', 'Thathsarani', 'pubba@gmail.com', 'Female', '2005-10-30', 'Member', ' Pubba@2005 ', '1', '2025-04-05 18:30:00', ''),
(13, 'Dew', 'Nithika', 'Dilen', 'dewminpulasinghe@gmail.com', 'Male', '2004-05-08', 'Member', 'Dewmin@2004', '1', '2025-05-04 18:30:00', '13.png');

-- --------------------------------------------------------

--
-- Table structure for table `user_payments`
--

DROP TABLE IF EXISTS `user_payments`;
CREATE TABLE IF NOT EXISTS `user_payments` (
  `PaymentID` int NOT NULL AUTO_INCREMENT,
  `UserID` int DEFAULT NULL,
  `Month` varchar(10) DEFAULT NULL,
  `Year` int DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `Status` varchar(20) DEFAULT 'Unpaid',
  PRIMARY KEY (`PaymentID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_payments`
--

INSERT INTO `user_payments` (`PaymentID`, `UserID`, `Month`, `Year`, `Amount`, `Status`) VALUES
(1, 1, 'February', 2025, 10000.00, 'Paid'),
(2, 1, 'March', 2025, 10000.00, 'Paid'),
(3, 1, 'April', 2025, 10000.00, 'Unpaid');

-- --------------------------------------------------------

--
-- Table structure for table `workshops`
--

DROP TABLE IF EXISTS `workshops`;
CREATE TABLE IF NOT EXISTS `workshops` (
  `WorkshopID` int NOT NULL AUTO_INCREMENT,
  `Wordshop-Code` varchar(100) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Description` varchar(1000) NOT NULL,
  `Date` date NOT NULL,
  `Start` time NOT NULL,
  `End` time NOT NULL,
  `Price` int NOT NULL,
  PRIMARY KEY (`WorkshopID`),
  KEY `Wordshop-Code` (`Wordshop-Code`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `workshops`
--

INSERT INTO `workshops` (`WorkshopID`, `Wordshop-Code`, `Name`, `Description`, `Date`, `Start`, `End`, `Price`) VALUES
(1, 'W1', 'Yoga for Beginners', 'A beginner-friendly yoga workshop to improve flexibility and mindfulness.', '2025-04-05', '08:00:00', '09:30:00', 20),
(2, 'W2', 'HIIT Bootcamp', 'An intense high-intensity interval training session designed for fat burning and strength building.', '2025-04-07', '10:00:00', '11:00:00', 25),
(3, 'W3', 'Zumba Dance', 'A fun and energetic dance-based fitness class combining Latin and international music.', '2025-04-09', '18:00:00', '19:00:00', 15),
(4, 'W4', 'Strength Training', 'Focus on building muscle strength through free weights and bodyweight exercises.', '2025-04-12', '07:00:00', '08:30:00', 30),
(5, 'W5', 'Pilates for Core', 'A Pilates class aimed at strengthening the core muscles and improving posture.', '2025-04-15', '17:00:00', '18:00:00', 18),
(6, 'W6', 'Chathusha', 'nfbn', '2025-04-10', '12:22:00', '13:24:00', 60),
(7, 'W7', 'Chathusha', 'nfbn', '2025-04-10', '12:22:00', '13:24:00', 60);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
