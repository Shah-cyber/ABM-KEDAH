-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2024 at 07:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `abmkedah`
--

-- --------------------------------------------------------

--
-- Table structure for table `abmevent`
--

CREATE TABLE `abmevent` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `banner` varchar(200) NOT NULL,
  `event_description` text DEFAULT NULL,
  `honor` varchar(255) DEFAULT NULL,
  `total_participation` int(11) DEFAULT NULL,
  `event_category` varchar(50) DEFAULT NULL,
  `event_status` varchar(50) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_session` varchar(50) DEFAULT NULL,
  `event_start_time` time DEFAULT NULL,
  `event_end_time` time NOT NULL,
  `event_location` varchar(255) DEFAULT NULL,
  `event_price` double DEFAULT NULL,
  `slot_reserved` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `abmevent`
--

INSERT INTO `abmevent` (`event_id`, `event_name`, `banner`, `event_description`, `honor`, `total_participation`, `event_category`, `event_status`, `event_date`, `event_session`, `event_start_time`, `event_end_time`, `event_location`, `event_price`, `slot_reserved`) VALUES
(1, 'Tech Summit', '../img/banner/banner_2.jpg', 'Join industry leaders, innovators, and enthusiasts at the Tech Summit for insightful discussions on the latest technological advancements, trends, and future directions. Participate in workshops, panels, and networking sessions to gain valuable knowledge and connect with key players in the tech world.', '500', 190, 'public', 'running', '2024-07-01', 'Morning', '06:00:00', '08:00:00', 'Dewan Qaseh, Kolej Mawar', 2, 0),
(2, 'Health Expo', '../img/banner/banner_1.png', 'Discover the latest in health and wellness at the Health Expo. Explore a variety of exhibits showcasing cutting-edge medical technology, health products, and services. Attend informative seminars and workshops led by healthcare professionals and experts.', '300', 97, 'public', 'running', '2024-07-17', 'Afternoon', '00:00:00', '00:00:00', 'New York', 3, 0),
(3, 'Art Festival', '../img/banner/banner_1.png', 'Experience a vibrant celebration of creativity at the Art Festival. Enjoy a diverse range of artworks from local and international artists, including paintings, sculptures, installations, and performances. Engage in interactive art activities and meet the creators behind the masterpieces.', '250', 296, 'public', 'running', '2024-07-17', 'Evening', '12:00:00', '14:00:00', 'Los Angeles', NULL, 0),
(4, 'Business Conference', '../img/banner/banner_1.png', 'Network with business professionals and thought leaders at the Business Conference. Gain insights into the latest market trends, business strategies, and innovations through keynote speeches, panel discussions, and breakout sessions. Enhance your business acumen and expand your professional network.', '700', 500, 'public', 'running', '2024-07-04', 'Morning', '00:00:00', '00:00:00', 'Chicago', 3, 0),
(5, 'Music Concert', '../img/banner/banner_1.png', 'Immerse yourself in a night of unforgettable music at the Music Concert. Featuring performances by renowned artists and bands across various genres, this event promises to deliver an exhilarating experience for music lovers. Enjoy the live music, vibrant atmosphere, and great company.', '400', 0, 'private', 'running', '2024-07-05', 'Night', '00:00:00', '00:00:00', 'Miami', NULL, 0),
(6, 'Food Fair', '../img/banner/banner_1.png', 'Indulge in a culinary adventure at the Food Fair. Sample a wide array of delicious dishes and beverages from local and international vendors. Discover new flavors, attend cooking demonstrations, and learn from culinary experts. A paradise for food enthusiasts!', '150', 250, 'public', 'ended', '2024-07-06', 'Afternoon', '00:00:00', '00:00:00', 'Houston', 3, 0),
(7, 'Book Fair', '../img/banner/banner_1.png', 'Explore a world of literature at the Book Fair. Browse through a vast collection of books from different genres and authors. Meet your favorite writers, attend book signings, and participate in literary discussions and workshops. Perfect for book lovers and aspiring writers.', '100', 120, 'public', 'ended', '2024-07-07', 'Morning', '00:00:00', '00:00:00', 'Boston', 4, 0),
(8, 'Science Exhibition', '../img/banner/banner_1.png', 'Witness the wonders of science at the Science Exhibition. Featuring interactive exhibits, experiments, and demonstrations, this event offers a hands-on learning experience for all ages. Discover the latest scientific discoveries and innovations in various fields.', '600', 350, 'private', 'deleted', '2024-07-08', 'Morning', '00:00:00', '00:00:00', 'San Diego', 5, 0),
(9, 'Charity Run', '../img/banner/banner_1.png', 'Join the community for a good cause at the Charity Run. Participate in a fun and challenging race to raise funds and awareness for a selected charity. Whether you\'re a seasoned runner or a casual jogger, this event is a great way to stay active and support a worthy cause.', '0', 500, 'private', 'ended', '2024-07-09', 'Morning', '00:00:00', '00:00:00', 'Seattle', 3, 0),
(10, 'Film Festival', '../img/banner/banner_1.png', 'Celebrate the art of filmmaking at the Film Festival. Enjoy screenings of a diverse selection of films from independent and established filmmakers. Participate in Q&A sessions, panel discussions, and workshops. A must-attend event for film enthusiasts and industry professionals.', '300', 200, 'public', 'running', '2024-07-17', 'Evening', '00:00:00', '00:00:00', 'Austin', NULL, 0),
(11, 'Film Festival 2', '../img/banner/banner_1.png', 'Experience a second round of cinematic excellence at Film Festival 2. This event features additional screenings, exclusive premieres, and special guest appearances. Dive deeper into the world of cinema with more opportunities for engagement and exploration.', '200', 0, 'public', 'running', '2024-07-18', 'Evening', '00:00:00', '00:00:00', 'Austin', 5, 0),
(12, 'Film Horror', '../img/banner/banner_1.png', 'Get ready for thrills and chills at the Film Horror event. Watch a curated selection of horror films that will keep you on the edge of your seat. Meet directors and actors, and participate in discussions about the art of creating suspense and fear in cinema.', '235', 143, 'private', 'drafted', '2024-07-30', 'Evening', '00:00:00', '00:00:00', 'Austin', NULL, 0),
(13, 'Film Romance', '../img/banner/banner_1.png', 'Feel the love at the Film Romance event. Enjoy a lineup of romantic films that celebrate love in all its forms. From classic love stories to modern romances, this event offers a heartwarming experience for all. Engage in discussions with filmmakers and fellow romance film enthusiasts.', '237', 184, 'public', 'drafted', '2024-08-07', 'Evening', '00:00:00', '00:00:00', 'Austin', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `ic_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`user_id`, `username`, `password`, `status`, `role`, `ic_number`) VALUES
(1, 'Fadhli Bin Abdullah', '$argon2id$v=19$m=65536,t=4,p=1$NnJ6Tk93QnBQLklKNy5mSQ$4z0lDFjPCr+wlnunPivGWcMxqVnPVcF2EAdCNq0/lCM', 'Active', 'Admin', '800101014321'),
(2, 'Chan Kok Leong', '$argon2id$v=19$m=65536,t=4,p=1$NnJ6Tk93QnBQLklKNy5mSQ$4z0lDFjPCr+wlnunPivGWcMxqVnPVcF2EAdCNq0/lCM', 'Active', 'Admin', '810202025432'),
(3, 'Aisyah Binti Rosli', '$argon2id$v=19$m=65536,t=4,p=1$NnJ6Tk93QnBQLklKNy5mSQ$4z0lDFjPCr+wlnunPivGWcMxqVnPVcF2EAdCNq0/lCM', 'Active', 'Admin', '820303036543'),
(4, 'Mohan Raj', '$argon2id$v=19$m=65536,t=4,p=1$NnJ6Tk93QnBQLklKNy5mSQ$4z0lDFjPCr+wlnunPivGWcMxqVnPVcF2EAdCNq0/lCM', 'Active', 'Admin', '830404047654'),
(5, 'Lee Mei Yin', '$argon2id$v=19$m=65536,t=4,p=1$NnJ6Tk93QnBQLklKNy5mSQ$4z0lDFjPCr+wlnunPivGWcMxqVnPVcF2EAdCNq0/lCM', 'Active', 'Admin', '840505058765'),
(6, 'Roslan Bin Hassan', '$argon2id$v=19$m=65536,t=4,p=1$NnJ6Tk93QnBQLklKNy5mSQ$4z0lDFjPCr+wlnunPivGWcMxqVnPVcF2EAdCNq0/lCM', 'Active', 'Admin', '850606069876'),
(7, 'Angeline Tan', '$argon2id$v=19$m=65536,t=4,p=1$NnJ6Tk93QnBQLklKNy5mSQ$4z0lDFjPCr+wlnunPivGWcMxqVnPVcF2EAdCNq0/lCM', 'Active', 'Admin', '860707070987'),
(8, 'Thiruvarasan', '$argon2id$v=19$m=65536,t=4,p=1$NnJ6Tk93QnBQLklKNy5mSQ$4z0lDFjPCr+wlnunPivGWcMxqVnPVcF2EAdCNq0/lCM', 'Active', 'Admin', '870808081098'),
(9, 'Zainab Binti Hassan', '$argon2id$v=19$m=65536,t=4,p=1$NnJ6Tk93QnBQLklKNy5mSQ$4z0lDFjPCr+wlnunPivGWcMxqVnPVcF2EAdCNq0/lCM', 'Active', 'Admin', '880909092109'),
(10, 'Yap Chee Keong', '$argon2id$v=19$m=65536,t=4,p=1$NnJ6Tk93QnBQLklKNy5mSQ$4z0lDFjPCr+wlnunPivGWcMxqVnPVcF2EAdCNq0/lCM', 'Active', 'Admin', '890101103210');

-- --------------------------------------------------------

--
-- Table structure for table `allocated_merits`
--

CREATE TABLE `allocated_merits` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `merit_id` int(11) NOT NULL,
  `merit_point` int(11) NOT NULL,
  `allocation_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allocated_merits`
--

INSERT INTO `allocated_merits` (`id`, `user_id`, `event_id`, `merit_id`, `merit_point`, `allocation_date`) VALUES
(49, 0, 1, 1, 20, '2024-07-17 15:40:02'),
(50, 0, 2, 2, 73, '2024-07-17 15:46:03'),
(51, 25, 2, 2, 73, '2024-07-17 15:52:38'),
(52, 2, 1, 1, 20, '2024-07-17 15:53:23'),
(53, 9, 5, 5, 80, '2024-07-17 16:28:25'),
(54, 9, 3, 3, 40, '2024-07-17 16:28:42'),
(55, 9, 1, 1, 20, '2024-07-17 16:29:26');

-- --------------------------------------------------------

--
-- Table structure for table `joinevent`
--

CREATE TABLE `joinevent` (
  `event_reg_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nonMember_event_reg_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `joinevent`
--

INSERT INTO `joinevent` (`event_reg_id`, `event_id`, `user_id`, `nonMember_event_reg_id`) VALUES
(7, 10, 2, NULL),
(8, 1, 2, NULL),
(9, 1, 3, NULL),
(10, 1, 9, NULL),
(11, 3, 9, NULL),
(12, 5, 9, NULL),
(13, 3, 5, NULL),
(14, 1, 5, NULL),
(18, 1, 22, NULL),
(19, 2, NULL, 4),
(20, 11, NULL, 5),
(21, 3, NULL, 7),
(22, 3, 22, NULL),
(23, 3, 25, NULL),
(24, 1, 25, NULL),
(25, 2, 25, NULL),
(26, 3, NULL, 8),
(27, 3, NULL, 9),
(28, 10, NULL, 10),
(29, 10, NULL, 11);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `ic_number` varchar(20) DEFAULT NULL,
  `total_merit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`user_id`, `username`, `password`, `status`, `role`, `ic_number`, `total_merit`) VALUES
(2, 'Lim Siew Meii', 'password123', 'Associate Member', 'user', '910202025432', 40),
(3, 'Siti Nurhaliza', 'password123', 'Associate Member', 'user', '920303036543', 40),
(4, 'Rajesh Kumar', 'password123', 'Associate Member', 'user', '930404047654', 93),
(5, 'Chong Wei', 'password123', 'MFLS Alumni', 'user', '940505058765', 73),
(6, 'Nurul Izzah', 'password123', 'MFLS Alumni', 'user', '950606069876', 40),
(7, 'Samantha Tan', 'password123', 'MFLS Alumni', 'user', '960707070987', 115),
(8, 'Arun Pillai', 'password123', 'MFLS Alumni', 'user', '970808081098', 135),
(9, 'Aminah Binti Yusof', 'password123', 'Associate Member', 'user', '980909092109', 185),
(18, 'Abdul Karim', '$argon2id$v=19$m=65536,t=4,p=1$RlZuZmZRU2pjUXVzN3c0Mw$c7EF8CLaWaM46lCFzvpfLmsjFHh9fLIQSWYBEb/ncwU', 'MFLS Alumni', 'user', '031112130626', 80),
(20, 'sddsd', '$argon2id$v=19$m=65536,t=4,p=1$emRVU0pKaERjVy9HNDU4Rw$HYbdaHjakyGcfBhzhNNjCtoS+T2QpaYAJYXQP2sJT+g', 'male', 'user', '23232', 40),
(22, 'HelloWorld', '$argon2id$v=19$m=65536,t=4,p=1$TGZ3NTQ3WWtqVXY3VzlvMQ$G9DfIAdDfDp5OHp9z+7KkuNAjEiC90L9J95B1BkaYaU', 'Associate Member', 'user', '1111111', 20),
(24, 'Astra123', '$argon2id$v=19$m=65536,t=4,p=1$a2lnNTdLY1puQS9OcmF0Tw$99OKdMqOuqPIR2tLJZk6ut3rSU3XJhtiDr5gtZCKTVg', 'MFLS Alumni', 'user', '0311078902341', 70),
(25, 'Joshua24', '$argon2id$v=19$m=65536,t=4,p=1$bmJTNlMxZm9LTU1EUXZCNA$EQaSqKwtBY+XwS9YMGCBMorK0dacanEroYWfSkW6ZGQ', 'MFLS Alumni', 'user', '071103130892', 73),
(27, 'Izzah', '$argon2id$v=19$m=65536,t=4,p=1$dlpkU2VuYW9VTExYdS90OQ$b2tAHxOY/GU+nG878mOc01P82SmFoDPOjdlpjXe9V4E', 'MFLS Alumni', 'user', '019823823', 0);

-- --------------------------------------------------------

--
-- Table structure for table `merit`
--

CREATE TABLE `merit` (
  `merit_id` int(11) NOT NULL,
  `merit_point` int(11) DEFAULT NULL,
  `person_in_charge_name` varchar(255) DEFAULT NULL,
  `person_in_charge_phone_number` varchar(20) DEFAULT NULL,
  `allocation_date` date DEFAULT NULL,
  `event_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `merit`
--

INSERT INTO `merit` (`merit_id`, `merit_point`, `person_in_charge_name`, `person_in_charge_phone_number`, `allocation_date`, `event_id`) VALUES
(1, 20, 'John Doea', '012-3456122', '2023-01-19', 1),
(2, 73, 'Jane Smithes', '012-9876543', '2023-02-20', 2),
(3, 40, 'Alice Johnson', '012-2345678', '2023-03-05', 3),
(4, 60, 'Bob Brown', '012-8765432', '2023-04-10', 4),
(5, 80, 'Charlie Davis', '012-3451234', '2023-05-25', 5),
(6, 20, 'Diana Evans', '012-5432167', '2023-06-15', 6),
(7, 12, 'Edward Green', '012-6543210', '2023-07-20', 7);

-- --------------------------------------------------------

--
-- Table structure for table `nonmember`
--

CREATE TABLE `nonmember` (
  `nonMember_event_reg_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `ic_number` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nonmember`
--

INSERT INTO `nonmember` (`nonMember_event_reg_id`, `name`, `ic_number`, `email`, `phone_number`) VALUES
(4, 'Adam', '031106130984', 'Adam@yahoo.com', '232092332'),
(5, 'Joshua', '3293892893', 'Joshua@fakey.com', '93939384'),
(6, 'ddddsds', '3323223', 'waduh@fakey.com', '233232'),
(7, 'babu', '3234455454', 'babu@real.com', '23232323'),
(8, 'Adam', '23344322332', 'Adam@realdomain.com', '3234332'),
(9, 'Josh', '232323', 'Adam23@realdomain.com', '43343'),
(10, 'Babu', '3243433', 'babu@fakey.com', '4334334'),
(11, '2323232', '3232232', 'babu23@fakey.com', '23323223');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` varchar(200) NOT NULL,
  `payment_name` varchar(200) NOT NULL,
  `payment_fee` decimal(10,2) DEFAULT NULL,
  `payment_time` time DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nonMember_event_reg_id` int(11) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `event_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `payment_name`, `payment_fee`, `payment_time`, `payment_date`, `user_id`, `nonMember_event_reg_id`, `payment_status`, `transaction_id`, `event_id`) VALUES
('11qseu86', 'Film Festival', 4.00, '22:29:43', '2024-07-13', 3, NULL, 'fail', 'TP2407133090683767', 10),
('343nksr3', 'Health Expo', 3.00, '15:01:39', '2024-07-16', NULL, 6, 'fail', 'TP2407164871817444', 2),
('5mut6ilj', 'Tech Summit', 2.00, '22:27:14', '2024-07-13', 3, NULL, 'success', 'TP2407134076078992', 1),
('93wbczha', 'Health Expo', 3.00, '22:42:25', '2024-07-16', 25, NULL, 'success', 'TP2407160764261437', 2),
('9mqz1nn1', 'Tech Summit', 2.00, '14:34:24', '2024-07-16', 22, NULL, 'success', 'TP2407161392818934', 1),
('a1b2c3d4', 'Membership Fee', 50.00, '12:34:56', '2024-07-10', NULL, NULL, 'pending', 'TX1234567890', 40),
('chxqsk94', 'Tech Summit', 2.00, '22:01:48', '2024-07-16', 25, NULL, 'success', 'TP2407161703375602', 1),
('cyg820os', 'Business Conference', 3.00, '22:42:54', '2024-07-16', 25, NULL, 'fail', 'TP2407163759077186', 4),
('e5f6g7h8', 'Annual Fee', 100.00, '09:28:14', '2024-06-15', 2, NULL, 'pending', 'TX0987654321', 41),
('emoy2c4f', 'Film Festival', 4.00, '18:27:33', '2024-07-13', 2, NULL, 'success', 'TP2407130285353274', 10),
('ev09q749', 'Health Expo', NULL, '14:55:26', '2024-07-16', NULL, 4, 'success', 'TP2407160731996249', 2),
('imufrtjv', 'Film Festival 2', 5.00, '15:00:05', '2024-07-16', NULL, 5, 'success', 'TP2407164452212897', 11),
('kxp3h2cq', 'Tech Summit', 2.00, '16:57:09', '2024-07-14', 5, NULL, 'success', 'TP2407140572981347', 1),
('lmvfkc07', 'Film Festival 2', 5.00, '20:18:03', '2024-07-13', 2, NULL, 'fail', 'TP2407133459581611', 11),
('psiqq9n5', 'Tech Summit', 2.00, '00:19:51', '2024-07-14', 9, NULL, 'success', 'TP2407143743151314', 1),
('vlnhc4s6', 'Film Festival 2', 5.00, '00:21:29', '2024-07-14', 9, NULL, 'fail', 'TP2407143124084418', 11),
('zgfyxfp9', 'Tech Summit', 2.00, '20:17:07', '2024-07-13', 2, NULL, 'success', 'TP2407132963470241', 1);

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `ic_number` varchar(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `race` varchar(50) DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `birthplace` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `prove_letter` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`ic_number`, `name`, `gender`, `race`, `religion`, `birthdate`, `birthplace`, `address`, `email`, `phone_number`, `prove_letter`) VALUES
('019823823', 'Izzah', 'Male', 'Melayu', 'Islam', '2003-07-17', 'Bangi', 'Selangor', 'Izzah@realdomain.com', '01028374745', ''),
('0311078902341', 'Astra', 'male', 'Melayu', 'Islam', '2024-07-16', 'Selangor', 'Sepang Streets', 'Astra@fakey.com', '9029230328', 'pagesassignment.pdf'),
('031112130626', 'Abdul Karim', 'Male', 'American', 'Atheist', '2024-06-19', 'Cambridge Hospital', 'California Streets', 'user20@nonexistentdomain.com', '0102045678', '031112130626_proof.pdf'),
('071103130892', 'Joshua', 'male', 'Malay', 'Islam', '2024-07-16', 'Hospital Faraway', 'Selangor', 'Joshua@realdomain.com', '0109348531', 'pagesassignment.pdf'),
('1111111', 'HelloWorld', 'male', 'Hello', 'World', '2024-07-15', 'Sunshine', 'Rainbows', 'HelloWorld@real.com', '012823932', 'pagesassignment.pdf'),
('23232', 'dssdsd', 'male', 'dssd', 'sdds', '2024-07-15', 'sdds', 'dssd', 'dsdsds@gmail.com', '23223', 'renewable-energy.png'),
('800101014321', 'Fadhli Bin Ahmad', 'Male', 'Malay', 'Islam', '1980-01-01', 'Hospital Sungai Petani', '123, Jalan Sungai Petani, Kedah', 'user19@nonexistentdomain.com', '0123456780', 'prove_letter.pdf'),
('810202025432', 'Chan Kok Leong', 'Male', 'Chinese', 'Christian', '1981-02-02', 'Hospital Tawau', '456, Jalan Tawau, Sabah', 'user18@nonexistentdomain.com', '0123456781', 'prove_letter.pdf'),
('820303036543', 'Aisyah Binti Rosli', 'Female', 'Malay', 'Islam', '1982-03-03', 'Hospital Klang', '789, Jalan Klang, Selangor', 'user17@nonexistentdomain.com', '0123456782', 'prove_letter.pdf'),
('830404047654', 'Mohan Raj', 'Male', 'Indian', 'Hindu', '1983-04-04', 'Hospital Labuan', '101, Jalan Labuan, Labuan', 'user16@nonexistentdomain.com', '0123456783', 'prove_letter.pdf'),
('840505058765', 'Lee Mei Yin', 'Female', 'Chinese', 'Buddhist', '1984-05-05', 'Hospital Kangar', '202, Jalan Kangar, Perlis', 'user15@nonexistentdomain.com', '0123456784', 'prove_letter.pdf'),
('850606069876', 'Roslan Bin Hassan', 'Male', 'Malay', 'Islam', '1985-06-06', 'Hospital Sandakan', '303, Jalan Sandakan, Sabah', 'user14@nonexistentdomain.com', '0123456785', 'prove_letter.pdf'),
('860707070987', 'Angeline Tan', 'Female', 'Chinese', 'Christian', '1986-07-07', 'Hospital Keningau', '404, Jalan Keningau, Sabah', 'user13@nonexistentdomain.com', '0123456786', 'prove_letter.pdf'),
('870808081098', 'Thiruvarasan', 'Male', 'Indian', 'Hindu', '1987-08-08', 'Hospital Muar', '505, Jalan Muar, Johor', 'user12@nonexistentdomain.com', '0123456787', 'prove_letter.pdf'),
('880909092109', 'Zainab Binti Hassan', 'Female', 'Malay', 'Islam', '1988-09-09', 'Hospital Kuching', '606, Jalan Kuching, Sarawak', 'user11@nonexistentdomain.com', '0123456788', 'prove_letter.pdf'),
('890101103210', 'Yap Chee Keong', 'Male', 'Chinese', 'Buddhist', '1989-10-10', 'Hospital Miri', '707, Jalan Miri, Sarawak', 'user10@nonexistentdomain.com', '0123456789', 'prove_letter.pdf'),
('910202025432', 'Lim Siew Meii', 'Female', 'Chinese', 'Buddhist', '1991-02-02', 'Hospital Ipoh', '456, Jalan Raja Permaisuri, Ipoh', 'user9@nonexistentdomain.com', '0123456790', ''),
('920303036543', 'Siti Nurhaliza', 'Female', 'Malay', 'Islam', '1992-03-03', 'Hospital Seremban', '789, Jalan Dato Bandar, Seremban', 'user8@nonexistentdomain.com', '0123456791', 'prove_letter.pdf'),
('930404047654', 'Rajesh Kumar', 'Male', 'Indian', 'Hindu', '1993-04-04', 'Hospital Georgetown', '101, Jalan Penang, Georgetown', 'user6@nonexistentdomain.com', '0123456792', 'prove_letter.pdf'),
('940505058765', 'Chong Wei', 'Male', 'Chinese', 'Christian', '1994-05-05', 'Hospital Johor Bahru', '202, Jalan Wong Ah Fook, Johor Bahru', 'user5@nonexistentdomain.com', '0123456793', 'prove_letter.pdf'),
('950606069876', 'Nurul Izzah', 'Female', 'Malay', 'Islam', '1995-06-06', 'Hospital Melaka', '303, Jalan Hang Tuah, Melaka', 'user4@nonexistentdomain.com', '0123456794', 'prove_letter.pdf'),
('960707070987', 'Samantha Tan', 'Female', 'Chinese', 'Christian', '1996-07-07', 'Hospital Kuantan', '404, Jalan Bukit Ubi, Kuantan', 'user3@nonexistentdomain.com', '0123456795', 'prove_letter.pdf'),
('970808081098', 'Arun Pillai', 'Male', 'Indian', 'Hindu', '1997-08-08', 'Hospital Alor Setar', '505, Jalan Putra, Alor Setar', 'user2@nonexistentdomain.com', '0123456796', 'prove_letter.pdf'),
('980909092109', 'Aminah Binti Yusof', 'Female', 'Malay', 'Islam', '1998-09-09', 'Hospital Kota Bharu', '606, Jalan Sultan Yahya Petra, Kota Bharu', 'user1@nonexistentdomain.com', '0123456797', 'prove_letter.pdf'),
('a', 'a', 'male', 'a', 'a', '2024-06-18', 'aa', 'aa', 'user7@nonexistentdomain.com', '22', 'pagesassignment.pdf');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abmevent`
--
ALTER TABLE `abmevent`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `ic_number` (`ic_number`);

--
-- Indexes for table `allocated_merits`
--
ALTER TABLE `allocated_merits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `joinevent`
--
ALTER TABLE `joinevent`
  ADD PRIMARY KEY (`event_reg_id`),
  ADD KEY `fk_event_id` (`event_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `ic_number` (`ic_number`);

--
-- Indexes for table `merit`
--
ALTER TABLE `merit`
  ADD PRIMARY KEY (`merit_id`);

--
-- Indexes for table `nonmember`
--
ALTER TABLE `nonmember`
  ADD PRIMARY KEY (`nonMember_event_reg_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `nonMember_event_reg_id` (`nonMember_event_reg_id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`ic_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abmevent`
--
ALTER TABLE `abmevent`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `allocated_merits`
--
ALTER TABLE `allocated_merits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `joinevent`
--
ALTER TABLE `joinevent`
  MODIFY `event_reg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `nonmember`
--
ALTER TABLE `nonmember`
  MODIFY `nonMember_event_reg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`ic_number`) REFERENCES `registration` (`ic_number`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `joinevent`
--
ALTER TABLE `joinevent`
  ADD CONSTRAINT `fk_event_id` FOREIGN KEY (`event_id`) REFERENCES `abmevent` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `member_ibfk_1` FOREIGN KEY (`ic_number`) REFERENCES `registration` (`ic_number`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `member` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`nonMember_event_reg_id`) REFERENCES `nonmember` (`nonMember_event_reg_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
