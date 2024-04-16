-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 02 فبراير 2024 الساعة 18:23
-- إصدار الخادم: 8.0.36
-- PHP Version: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web1200418_dbschema`
--

-- --------------------------------------------------------

--
-- بنية الجدول `cart`
--

CREATE TABLE `cart` (
  `id` bigint NOT NULL,
  `customerId` bigint NOT NULL,
  `productId` bigint NOT NULL,
  `price` float NOT NULL,
  `quantity` int NOT NULL,
  `name` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `emp`
--

CREATE TABLE `emp` (
  `id` int NOT NULL,
  `username` text COLLATE utf8mb4_general_ci NOT NULL,
  `password` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `emp`
--

INSERT INTO `emp` (`id`, `username`, `password`) VALUES
(3, 'mzlad12345', '$2y$10$rRSE2XcWGeo5g279N1xireF8rdmZEmQAzJM.9E3CxPKLVSbmMUMbq'),
(4, 'mohalad', '$2y$10$tOfdtSooq4ZcbmqckVMwc.buaTMixr72B1hkg2z1notXa7Qa9fR1a'),
(5, 'mohalad123', '$2y$10$SnwJ3PewiXu3MRi5OGdO/eqfUH2lgGvGVHNR5mgXek7R/5kCZmoNG');

-- --------------------------------------------------------

--
-- بنية الجدول `orderdetails`
--

CREATE TABLE `orderdetails` (
  `id` bigint NOT NULL,
  `orderId` bigint NOT NULL,
  `productId` bigint NOT NULL,
  `name` text COLLATE utf8mb4_general_ci NOT NULL,
  `price` float NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `orderdetails`
--

INSERT INTO `orderdetails` (`id`, `orderId`, `productId`, `name`, `price`, `quantity`) VALUES
(16, 3788198261, 6742548623, 'قدر فخار', 10, 10),
(17, 3788198261, 8639250968, 'زعتر الارض الفلسطيني', 25, 5),
(18, 6945987832, 6234073604, 'خزف خليلي', 40, 2),
(19, 8882913729, 8476105458, 'منحوتة صخرية', 15, 2),
(20, 5837077643, 6867622016, 'صابون نابلسي', 30, 3),
(21, 8656037127, 6234073604, 'خزف خليلي', 40, 2),
(22, 8656037127, 6867622016, 'صابون نابلسي', 30, 1),
(23, 9633032769, 8639250968, 'زعتر الارض الفلسطيني', 25, 2),
(24, 6966170402, 6234073604, 'خزف خليلي', 40, 21),
(25, 8965653962, 6867622016, 'صابون نابلسي', 30, 2);

-- --------------------------------------------------------

--
-- بنية الجدول `orders`
--

CREATE TABLE `orders` (
  `id` bigint NOT NULL,
  `customerId` bigint NOT NULL,
  `name` text COLLATE utf8mb4_general_ci NOT NULL,
  `address` text COLLATE utf8mb4_general_ci NOT NULL,
  `email` text COLLATE utf8mb4_general_ci NOT NULL,
  `tele` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` text COLLATE utf8mb4_general_ci NOT NULL,
  `dorder` text COLLATE utf8mb4_general_ci NOT NULL,
  `dshipping` text COLLATE utf8mb4_general_ci,
  `total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `orders`
--

INSERT INTO `orders` (`id`, `customerId`, `name`, `address`, `email`, `tele`, `status`, `dorder`, `dshipping`, `total`) VALUES
(1088302696, 3029842367, 'mohammad ladadweh', 'Rukab St', 'mohalada1@gmail.com', '+970594659371', 'shipped', '2024-02-02', '2024-02-26', 0),
(2445144428, 5584900461, 'mohammad AbuThaher', 'Rmallah', 'mohammadabuthaher06@gmail.com', '+970569482508', 'Waiting for processing', '2024-02-02', NULL, 110),
(3788198261, 3029842367, 'mohammad ladadweh', 'Rukab St', 'mohalada1@gmail.com', '+970594659371', 'shipped', '2024-02-02', '2024-02-23', 225),
(4279032456, 3029842367, 'mohammad ladadweh', 'Rukab St', 'mohalada1@gmail.com', '+970594659371', 'shipped', '2024-02-02', '2024-02-19', 50),
(4463632971, 5584900461, 'mohammad AbuThaher', 'Rmallah', 'mohammadabuthaher06@gmail.com', '+970569482508', 'shipped', '2024-02-02', '2024-02-07', 0),
(5837077643, 5539239503, 'ahmad ali', 'Ramallah', 'ali@gmail.com', '+970599456789', 'shipped', '2024-02-02', '2024-02-23', 90),
(6945987832, 3029842367, 'mohammad ladadweh', 'Rukab St', 'mohalada1@gmail.com', '+970594659371', 'shipped', '2024-02-02', '2024-02-20', 80),
(6966170402, 5584900461, 'mohammad AbuThaher', 'Rmallah', 'mohammadabuthaher06@gmail.com', '+970569482508', 'shipped', '2024-02-02', '2024-02-20', 840),
(8656037127, 5584900461, 'mohammad AbuThaher', 'Rmallah', 'mohammadabuthaher06@gmail.com', '+970569482508', 'shipped', '2024-02-02', '2024-02-27', 110),
(8882913729, 5539239503, 'ahmad ali', 'Ramallah', 'ali@gmail.com', '+970599456789', 'shipped', '2024-02-02', '2024-02-28', 30),
(8965653962, 3029842367, 'mohammad ladadweh', 'Rukab St', 'mohalada1@gmail.com', '+970594659371', 'shipped', '2024-02-02', '2024-02-28', 60),
(9633032769, 3029842367, 'mohammad ladadweh', 'Rukab St', 'mohalada1@gmail.com', '+970594659371', 'shipped', '2024-02-02', '2024-02-28', 50);

-- --------------------------------------------------------

--
-- بنية الجدول `products`
--

CREATE TABLE `products` (
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `price` float NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `products`
--

INSERT INTO `products` (`name`, `description`, `category`, `price`, `size`, `remarks`, `quantity`, `img`, `id`) VALUES
('خزف خليلي', 'خزف خليلي صنع في فلسطين، استمتع بإحدى تشكيلاتنا من القطع الفنية يدوية الصنع', 'highdemand', 40, 'M', 'Made in Palestine', 55, 'item6234073604img1.jpg,item6234073604img2.jpg', 6234073604),
('قدر فخار', ' قدر فخار للطهي مصنوع من الفخار صالح للفرن او النار يتحمل درجات الحرارة المختلفة', 'newarrival', 10, 'M', 'Made in Palestine', 0, 'item6742548623img1.jpg', 6742548623),
('test', 'test', 'newarrival', 22, 'm', 'test', 22, 'item6796290722img1.jpg', 6796290722),
('صابون نابلسي', 'صابون زيت الزيتون الطبيعي, 145 جرام من نابلسي', 'featured', 30, '145 جم', 'Made in Palestine', 74, 'item6867622016img1.jpg,item6867622016img2.jpg,item6867622016img3.webp', 6867622016),
('منحوتة صخرية', 'منحوتة صخرية معبرة', 'onsale', 15, 'S', 'Made in Palestine', 93, 'item8476105458img1.jpg', 8476105458),
('زعتر الارض الفلسطيني', 'زعتر الارض الفلسطيني 500 جم', 'normal', 25, ' 500 جم ', 'Made in Palestine', 13, 'item8639250968img1.jpg', 8639250968);

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `name` text COLLATE utf8mb4_general_ci NOT NULL,
  `address` text COLLATE utf8mb4_general_ci NOT NULL,
  `dob` date NOT NULL,
  `idnumber` int NOT NULL,
  `email` text COLLATE utf8mb4_general_ci NOT NULL,
  `phone` text COLLATE utf8mb4_general_ci NOT NULL,
  `ccnumber` int NOT NULL,
  `expdate` text COLLATE utf8mb4_general_ci NOT NULL,
  `ccname` text COLLATE utf8mb4_general_ci NOT NULL,
  `bank` text COLLATE utf8mb4_general_ci NOT NULL,
  `username` text COLLATE utf8mb4_general_ci NOT NULL,
  `password` text COLLATE utf8mb4_general_ci NOT NULL,
  `id` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`name`, `address`, `dob`, `idnumber`, `email`, `phone`, `ccnumber`, `expdate`, `ccname`, `bank`, `username`, `password`, `id`) VALUES
('mohammad ladadweh', 'Rukab St', '2024-01-25', 124151, 'mohalada1@gmail.com', '+970594659371', 1251251, '2024-11', '12513252', 'arab bank', 'mzlad1234', '$2y$10$TfYYnkPuXcsIVDOUzb69vOGzfZUUQLuwahoCc5roenYdOFQA6pg4S', 3029842367),
('ahmad ali', 'Ramallah', '2024-02-20', 44547458, 'ali@gmail.com', '+970599456789', 2147483647, '2024-12', 'ali ahmad', 'islamic', 'ahmadA', '$2y$10$ws/1hxAASWH/Nksf1tEzWePHuzgAoCBQRe7RV20n4H38MHLG7k7dS', 5539239503),
('mohammad AbuThaher', 'Rmallah', '2024-02-02', 1202833, 'mohammadabuthaher06@gmail.com', '+970569482508', 123456789, '2024-02', '14523645', 'arab bank', 'AbuThaher', '$2y$10$uC63SP2dQxjNQWbEhLXTQ.J0qngIuRSXHRF6qSZO5rQ9b86m1N7se', 5584900461);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp`
--
ALTER TABLE `emp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `emp`
--
ALTER TABLE `emp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
