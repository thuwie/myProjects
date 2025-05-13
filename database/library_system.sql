-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2025 at 06:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `stt` int(11) NOT NULL,
  `images` varchar(500) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `publish_year` int(11) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `status` enum('available','borrowed') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`stt`, `images`, `title`, `author`, `category`, `publish_year`, `summary`, `status`) VALUES
(1, 'images/Nha-Gia-Kim-Paulo-Coelho.jpg', 'Nhà giả kim', 'Paulo Coelho', 'Truyền cảm hứng', 1988, 'Một chàng trai trẻ lên đường tìm kho báu và khám phá ra kho báu thật sự là hành trình khám phá bản thân.', 'available'),
(2, NULL, 'Mỗi lần vấp ngã là một lần trưởng thành', 'Cherry Nguyễn', 'Kỹ năng sống', 2020, 'Cuốn sách truyền cảm hứng vượt qua thất bại để trưởng thành và mạnh mẽ hơn mỗi ngày.', 'available'),
(3, NULL, 'Tôi thấy hoa vàng trên cỏ xanh', 'Nguyễn Nhật Ánh', 'Văn học', 2010, 'Câu chuyện nhẹ nhàng về tuổi thơ, tình anh em và vùng quê yên bình đầy xúc cảm.', 'available'),
(4, NULL, 'Nhật ký chú bé nhút nhát', 'Jeff Kinney', 'Hài hước', 2007, 'Nhật ký hài hước của một cậu bé tuổi teen với những tình huống dở khóc dở cười ở trường học.', 'available'),
(5, NULL, 'Totto-chan: Cô bé bên cửa sổ', 'Tetsuko Kuroyanagi', 'Văn học thiếu nhi', 1981, 'Câu chuyện cảm động về một cô bé khác biệt và ngôi trường đặc biệt dạy trẻ bằng tình yêu thương.', 'available'),
(6, NULL, 'Trên đường băng', 'Tony Buổi Sáng', 'Phát triển bản thân', 2014, 'Những bài học đời sống, lời khuyên thiết thực dành cho các bạn trẻ chuẩn bị bước vào đời.', 'available'),
(7, NULL, 'Tuổi trẻ đáng giá bao nhiêu', 'Rosie Nguyễn', 'Động lực sống', 2016, 'Gợi mở về việc sống có định hướng, đọc sách, học ngoại ngữ và trải nghiệm tuổi trẻ ý nghĩa.', 'available'),
(8, NULL, 'Dám bị ghét', 'Ichiro Kishimi & Fumitake Koga', 'Tâm lý học', 2013, 'Một cuộc đối thoại giữa triết gia và thanh niên về tự do, hạnh phúc và dám sống theo cách riêng.', 'available'),
(9, NULL, 'Chiến binh cầu vồng', 'Andrea Hirata', 'Truyền cảm hứng', 2005, 'Câu chuyện có thật về các học sinh nghèo ở Indonesia vượt khó học tập đầy cảm hứng.', 'available'),
(10, NULL, 'Đắc nhân tâm', 'Dale Carnegie', 'Kỹ năng giao tiếp', 1936, 'Cuốn sách kinh điển giúp bạn xây dựng mối quan hệ tốt, gây ảnh hưởng tích cực đến người khác.', 'available'),
(11, NULL, 'Quẳng gánh lo đi và vui sống', 'Dale Carnegie', 'Phát triển bản thân', 1948, 'Hướng dẫn thực tế để loại bỏ lo lắng và sống một cuộc đời an yên và trọn vẹn hơn.', 'available'),
(12, NULL, 'Đi tìm lẽ sống', 'Viktor E. Frankl', 'Tâm lý học', 1946, 'Chia sẻ trải nghiệm sống sót tại trại tập trung và tìm thấy ý nghĩa cuộc đời trong đau khổ.', 'available'),
(13, NULL, 'Bài học diệu kỳ từ chiếc xe rác', 'David J. Pollay', 'Tư duy tích cực', 2010, 'Câu chuyện nhỏ giúp bạn buông bỏ giận dữ, sống nhẹ nhàng và tập trung vào điều tích cực.', 'available'),
(14, NULL, 'Sống như người Nhật', 'Mari Fujimoto', 'Lối sống', 2018, 'Khám phá văn hóa, lối sống tối giản, cách ứng xử tinh tế của người Nhật.', 'available'),
(15, NULL, 'Thiên thần nhỏ của tôi', 'Guillaume Musso', 'Tình cảm', 2007, 'Một câu chuyện đầy bí ẩn, lãng mạn và cảm động về tình phụ tử và ký ức.', 'available'),
(16, NULL, 'Không gia đình', 'Hector Malot', 'Văn học cổ điển', 1878, 'Câu chuyện phiêu lưu của cậu bé Remi đi tìm gia đình giữa cuộc sống gian nan.', 'available'),
(17, NULL, 'Thư gửi thanh xuân', 'Nhiều tác giả', 'Tản văn', 2020, 'Những dòng tâm sự về tuổi trẻ, tình bạn, tình yêu và giấc mơ dang dở.', 'available'),
(18, NULL, 'Ngày xưa có một chuyện tình', 'Nguyễn Nhật Ánh', 'Văn học', 2016, 'Một câu chuyện tình nhẹ nhàng, sâu lắng, đầy xúc động về những rung động đầu đời.', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `actual_return` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `masv` varchar(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`masv`, `username`, `password`, `email`, `role`, `created_at`) VALUES
('4151050139', 'Nguyễn Hồng Phong', '$2y$10$5UbClo0Gl46D2iS22w3XIeHFXjqne55KH/1jEwW3dj1XyYSDsZMMO', 'nguyenhongphong4151050139@gmail.com', 'user', '2025-05-13 22:47:49'),
('4151050140', 'Nguyễn Thị Nở', '$2y$10$Oee3IUPwzScuKj5oxUa5H.S6wruVybGDsT7F3Ktn9q7AuURF7r/1e', 'newbierhp2000@gmail.com', 'admin', '2025-05-13 22:56:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`stt`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`masv`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `stt` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
