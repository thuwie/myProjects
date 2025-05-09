-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 09, 2025 at 03:56 PM
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
-- Database: `library_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `publish_year` int(11) DEFAULT NULL,
  `status` enum('available','borrowed') DEFAULT 'available',
  `summary` text DEFAULT NULL,
  `images` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `category`, `publish_year`, `status`, `summary`, `images`) VALUES
(1, 'Nhà giả kim', 'Paulo Coelho', 'Truyền cảm hứng', 1988, 'available', 'Một chàng trai trẻ lên đường tìm kho báu và khám phá ra kho báu thật sự là hành trình khám phá bản thân.', NULL),
(2, 'Mỗi lần vấp ngã là một lần trưởng thành', 'Cherry Nguyễn', 'Kỹ năng sống', 2020, 'available', 'Cuốn sách truyền cảm hứng vượt qua thất bại để trưởng thành và mạnh mẽ hơn mỗi ngày.', NULL),
(3, 'Tôi thấy hoa vàng trên cỏ xanh', 'Nguyễn Nhật Ánh', 'Văn học', 2010, 'available', 'Câu chuyện nhẹ nhàng về tuổi thơ, tình anh em và vùng quê yên bình đầy xúc cảm.', NULL),
(4, 'Nhật ký chú bé nhút nhát', 'Jeff Kinney', 'Hài hước', 2007, 'available', 'Nhật ký hài hước của một cậu bé tuổi teen với những tình huống dở khóc dở cười ở trường học.', NULL),
(5, 'Totto-chan: Cô bé bên cửa sổ', 'Tetsuko Kuroyanagi', 'Văn học thiếu nhi', 1981, 'available', 'Câu chuyện cảm động về một cô bé khác biệt và ngôi trường đặc biệt dạy trẻ bằng tình yêu thương.', NULL),
(6, 'Trên đường băng', 'Tony Buổi Sáng', 'Phát triển bản thân', 2014, 'available', 'Những bài học đời sống, lời khuyên thiết thực dành cho các bạn trẻ chuẩn bị bước vào đời.', NULL),
(7, 'Tuổi trẻ đáng giá bao nhiêu', 'Rosie Nguyễn', 'Động lực sống', 2016, 'available', 'Gợi mở về việc sống có định hướng, đọc sách, học ngoại ngữ và trải nghiệm tuổi trẻ ý nghĩa.', NULL),
(8, 'Dám bị ghét', 'Ichiro Kishimi & Fumitake Koga', 'Tâm lý học', 2013, 'available', 'Một cuộc đối thoại giữa triết gia và thanh niên về tự do, hạnh phúc và dám sống theo cách riêng.', NULL),
(9, 'Chiến binh cầu vồng', 'Andrea Hirata', 'Truyền cảm hứng', 2005, 'available', 'Câu chuyện có thật về các học sinh nghèo ở Indonesia vượt khó học tập đầy cảm hứng.', NULL),
(10, 'Đắc nhân tâm', 'Dale Carnegie', 'Kỹ năng giao tiếp', 1936, 'available', 'Cuốn sách kinh điển giúp bạn xây dựng mối quan hệ tốt, gây ảnh hưởng tích cực đến người khác.', NULL),
(11, 'Quẳng gánh lo đi và vui sống', 'Dale Carnegie', 'Phát triển bản thân', 1948, 'available', 'Hướng dẫn thực tế để loại bỏ lo lắng và sống một cuộc đời an yên và trọn vẹn hơn.', NULL),
(12, 'Đi tìm lẽ sống', 'Viktor E. Frankl', 'Tâm lý học', 1946, 'available', 'Chia sẻ trải nghiệm sống sót tại trại tập trung và tìm thấy ý nghĩa cuộc đời trong đau khổ.', NULL),
(13, 'Bài học diệu kỳ từ chiếc xe rác', 'David J. Pollay', 'Tư duy tích cực', 2010, 'available', 'Câu chuyện nhỏ giúp bạn buông bỏ giận dữ, sống nhẹ nhàng và tập trung vào điều tích cực.', NULL),
(14, 'Sống như người Nhật', 'Mari Fujimoto', 'Lối sống', 2018, 'available', 'Khám phá văn hóa, lối sống tối giản, cách ứng xử tinh tế của người Nhật.', NULL),
(15, 'Thiên thần nhỏ của tôi', 'Guillaume Musso', 'Tình cảm', 2007, 'available', 'Một câu chuyện đầy bí ẩn, lãng mạn và cảm động về tình phụ tử và ký ức.', NULL),
(16, 'Không gia đình', 'Hector Malot', 'Văn học cổ điển', 1878, 'available', 'Câu chuyện phiêu lưu của cậu bé Remi đi tìm gia đình giữa cuộc sống gian nan.', NULL),
(17, 'Conan – Thám tử lừng danh (Tập 1)', 'Gosho Aoyama', 'Manga – Trinh thám', 1994, 'available', 'Thám tử học sinh bị biến thành đứa trẻ, tiếp tục phá án với danh tính mới.', NULL),
(18, 'One Piece – Tập 1', '', 'Manga – Phiêu lưu', 1997, 'available', 'Hành trình của Luffy và đồng đội đi tìm kho báu One Piece trên đại dương bao la.', NULL),
(20, 'Thư gửi thanh xuân', 'Nhiều tác giả', 'Tản văn', 2020, 'available', 'Những dòng tâm sự về tuổi trẻ, tình bạn, tình yêu và giấc mơ dang dở.', NULL),
(21, 'Ngày xưa có một chuyện tình', 'Nguyễn Nhật Ánh', 'Văn học', 2016, 'available', 'Một câu chuyện tình nhẹ nhàng, sâu lắng, đầy xúc động về những rung động đầu đời.', NULL);

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
-- Table structure for table `readers`
--

CREATE TABLE `readers` (
  `student_id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `readers`
--

INSERT INTO `readers` (`student_id`, `name`, `email`, `phone`) VALUES
('SV001', 'Nguyễn Văn A', 'nva@example.com', '0901234567'),
('SV002', 'Trần Thị B', 'ttb@example.com', '0902345678'),
('SV003', 'Lê Văn C', 'lvc@example.com', '0903456789'),
('SV004', 'Phạm Thị D', 'ptd@example.com', '0904567890'),
('SV005', 'Hoàng Văn E', 'hve@example.com', '0905678901');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `readers`
--
ALTER TABLE `readers`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `loans_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `readers` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
