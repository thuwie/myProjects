-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 19, 2025 at 07:19 PM
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
  `id` varchar(11) NOT NULL,
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

INSERT INTO `books` (`id`, `images`, `title`, `author`, `category`, `publish_year`, `summary`, `status`) VALUES
('GT_01', 'uploads/1747308867_the-lord-of-the-rings.png', 'The Lord of the Rings', 'J.R.R. Tolkien', 'GT', 1955, 'Frodo Baggins nhận nhiệm vụ tiêu hủy chiếc nhẫn quyền lực để cứu thế giới khỏi bóng tối.', 'borrowed'),
('GT_02', 'uploads/1747308897_the-hobbit.png', 'The Hobbit', 'J.R.R. Tolkien', 'GT', 1937, 'Bilbo Baggins, một hobbit bình thường, tham gia vào cuộc phiêu lưu tìm kho báu cùng nhóm người lùn.', 'borrowed'),
('KN_01', 'uploads/1747306239_bai-hoc-dieu-ky-tu-chiec-xe-rac.png', 'Bài Học Diệu Kỳ Từ Chiếc Xe Rác', 'David J. Pollay', 'KN', 2010, 'Khuyến khích con người bỏ qua những điều tiêu cực, học cách sống lạc quan và tích cực hơn.', 'available'),
('KN_02', 'uploads/1747306557_quang-ganh-lo-di-va-vui-song.png', 'Quẳng Gánh Lo Đi Và Vui Sống (How to Stop Worrying and Start Living)', 'Dale Carnegie', 'KN', 1948, 'Cung cấp phương pháp và lời khuyên giúp con người giảm lo âu, sống vui vẻ và hiệu quả hơn.', 'available'),
('KN_03', 'uploads/1747306776_tuoi-tre-dang-gia-bao-nhieu.png', 'Tuổi Trẻ Đáng Giá Bao Nhiêu?', 'Rosie Nguyễn', 'KN', 2016, 'Hướng dẫn người trẻ cách tận dụng thời gian, học hỏi, làm việc và sống trọn vẹn trong tuổi thanh xuân.', 'available'),
('KN_04', 'uploads/1747310805_dac-nhan-tam.png', 'Đắc Nhân Tâm', 'Dale Carnegie', 'KN', 1936, 'Cuốn sách kinh điển về nghệ thuật giao tiếp và thuyết phục, giúp người đọc xây dựng mối quan hệ tốt đẹp trong công việc và cuộc sống.', 'available'),
('KN_05', 'uploads/1747310843_dan-ong-sao-hoa-dan-ba-sao-kim.png', 'Đàn Ông Sao Hỏa, Đàn Bà Sao Kim', 'John Gray', 'KN', 1992, 'Giải thích sự khác biệt giữa nam và nữ trong tư duy và cảm xúc, từ đó cải thiện mối quan hệ tình cảm.', 'available'),
('KN_06', 'uploads/1747310875_nghe-thuat-tap-trung.png', 'Nghệ Thuật Tập Trung', 'Cal Newport', 'KN', 2016, 'Khuyến khích người đọc rèn luyện khả năng tập trung sâu để đạt hiệu suất cao trong công việc.', 'available'),
('KN_07', 'uploads/1747310975_doi-ngan-dung-ngu-dai.png', 'Đời Ngắn Đừng Ngủ Dài', 'Robin Sharma', 'KN', 2016, 'Những bài học và lời khuyên để sống một cuộc đời ý nghĩa và trọn vẹn.', 'available'),
('KN_08', 'uploads/1747311080_ren-luyen-tu-duy-phan-bien.png', 'Rèn Luyện Tư Duy Phản Biện', 'Albert Rutherford', 'KN', 2018, 'Phát triển khả năng tư duy phản biện để đưa ra quyết định chính xác và logic.', 'available'),
('KN_09', 'uploads/1747311116_tu-duy-nhanh-va-cham.png', 'Tư Duy Nhanh Và Chậm', 'Daniel Kahneman', 'KN', 2011, 'Phân tích hai hệ thống tư duy của con người và cách chúng ảnh hưởng đến quyết định.', 'available'),
('KN_10', 'uploads/1747311145_phi-ly-tri.png', 'Phi Lý Trí', 'Dan Ariely', 'KN', 2008, 'Khám phá những hành vi phi lý trí trong cuộc sống hàng ngày và cách chúng ảnh hưởng đến quyết định của chúng ta.', 'available'),
('KN_11', 'uploads/1747311177_im-lang-suc-manh-cua-nguoi-huong-noi.png', 'Im Lặng: Sức Mạnh Của Người Hướng Nội', 'Susan Cain', 'KN', 2012, 'Tôn vinh giá trị của người hướng nội trong một thế giới ưa chuộng sự hướng ngoại.', 'available'),
('KN_12', 'uploads/1747311226_dam-bi-ghet.png', 'Dám Bị Ghét', 'Ichiro Kishimi & Fumitake Koga', 'KN', 2013, 'Giới thiệu triết lý của Alfred Adler về tự do và hạnh phúc.', 'available'),
('KN_13', 'uploads/1747312127_suc-manh-cua-eq.png', 'Sức Mạnh Của EQ', 'Patrick King', 'KN', 2024, 'Cuốn sách nhấn mạnh rằng thành công trong cuộc sống phụ thuộc vào cách bạn đối xử với mọi người, và EQ là kỹ năng có thể rèn luyện được.', 'available'),
('TH_01', 'uploads/1747325484_ban-ve-tu-do.png', 'Bàn Về Tự Do (On Liberty)', 'John Stuart Mill', 'TH', 1859, 'Nền tảng của tư tưởng tự do hiện đại, bảo vệ quyền tự do cá nhân.', 'available'),
('TH_02', 'uploads/1747325522_homodeus-luoc-su-tuong-lai.png', 'Homo Deus – Lược Sử Tương Lai', 'Yuval Noah Harari', 'TH', 2015, 'Dự đoán tương lai con người từ góc nhìn triết học và công nghệ. |', 'available'),
('TH_03', 'uploads/1747325548_sapiens-luoc-su-loai-nguoi.png', 'Sapiens – Lược Sử Loài Người', 'Yuval Noah Harari', 'TH', 2011, 'Phân tích sự phát triển của nhân loại với góc nhìn lịch sử – triết học. |', 'available'),
('TH_04', 'uploads/1747325570_su-an-ui-cua-triet-hoc.png', 'Sự An Ủi Của Triết Học', 'Alain de Botton', 'TH', 2000, 'Dẫn dắt triết học ứng dụng giúp con người vượt qua đau khổ. |', 'available'),
('TH_05', 'uploads/1747325596_su-xa-la.png', 'Sự Xa Lạ (The Stranger)', 'Albert Camus', 'TH', 1942, 'Dưới dạng tiểu thuyết, phản ánh triết lý phi lý và sự vô nghĩa của đời sống. |', 'available'),
('TH_06', 'uploads/1747325627_than-thoai-sisyphe.png', 'Thần Thoại Sisyphe', 'Albert Camus', 'TH', 1942, 'So sánh cuộc đời với Sisyphe – người mãi đẩy đá lên núi, nói về ý nghĩa và sự nổi loạn chống lại phi lý.', 'available'),
('TK_01', 'uploads/1747306319_chien-binh-cau-vong.png', 'Chiến Binh Cầu Vồng (Laskar Pelangi)', 'Andrea Hirata', 'TK', 2005, 'Kể về 10 đứa trẻ nghèo vượt khó ở Indonesia và hành trình học tập, khát vọng vươn lên từ nghịch cảnh.', 'available'),
('TK_02', 'uploads/1747313898_kien-hanh-va-dinh-kien.png', 'Kiêu Hãnh Và Định Kiến', ' Jane Austen', 'TK', 1813, 'Chuyện tình của Elizabeth và Darcy vượt qua những định kiến xã hội và bản thân.', 'available'),
('TK_03', 'uploads/1747325653_the-gioi-la-y-chi-va-bieu-tuong.png', 'Thế Giới Là Ý Chí Và Biểu Tượng', 'Arthur Schopenhauer', 'TK', 1818, 'Nhìn nhận cuộc sống là sự biểu hiện của ý chí mù quáng, mang màu sắc bi quan. |', 'available'),
('TL_01', 'uploads/1747306378_di-tim-le-song.png', 'Đi Tìm Lẽ Sống (Man’s Search for Meaning)', 'Viktor E. Frankl', 'TL', 1946, 'Trải nghiệm của tác giả tại trại tập trung Đức Quốc xã và cách ông tìm ra ý nghĩa cuộc sống ngay cả trong đau khổ.', 'available'),
('TL_02', 'uploads/1747306529_nha-gia-kim.png', 'Nhà Giả Kim (The Alchemist)', 'Paulo Coelho', 'TL', 1988, 'Hành trình của chàng chăn cừu Santiago đi tìm kho báu, khám phá bản thân và ý nghĩa cuộc sống.', 'available'),
('TL_03', 'uploads/1747328254_cam-xuc-la-ke-thu-so-mot-cua-thanh-cong.png', 'Cảm Xúc Là Kẻ Thù Số Một Của Thành Công', 'Ryan Holiday', 'TL', 2014, 'Áp dụng chủ nghĩa khắc kỷ để kiểm soát cảm xúc tiêu cực và tăng năng suất. |', 'available'),
('TL_04', 'uploads/1747328288_chung-ta-la-ai.png', 'Chúng Ta Là Ai (Behave)', 'Robert Sapolsky', 'TL', 2017, 'Giải thích hành vi con người từ di truyền, môi trường đến thần kinh học. |', 'available'),
('TL_05', 'uploads/1747328565_cuoc-doi-soi-to.png', 'Cuộc Đời Soi Tỏ (The Examined Life)', 'Stephen Grosz', 'TL', 2013, 'Các câu chuyện trị liệu tâm lý giúp hiểu rõ hành vi và cảm xúc con người. |', 'available'),
('TL_06', 'uploads/1747328607_nhung-don-tam-ly-trong-thuyet-phuc.png', 'Những Đòn Tâm Lý Trong Thuyết Phục (Influence)', 'Morgan Housel', 'TL', 2020, 'Phân tích hành vi tài chính từ góc nhìn tâm lý học và cảm xúc cá nhân. |', 'available'),
('TL_07', 'uploads/1747328648_sao-ta-lam-dieu-ta-lam.png', 'Tại Sao Chúng Ta Làm Điều Mình Làm? (Why We Do What We Do)', 'Edward L. Deci', 'TL', 1995, 'Giải thích động lực nội tại và ảnh hưởng của môi trường đến hành vi.', 'available'),
('TL_08', 'uploads/1747328679_tam-ly-hoc-dam-dong.png', 'Tâm Lý Học Đám Đông (The Crowd)', 'Gustave Le Bon', 'TL', 1895, 'Nghiên cứu cách suy nghĩ và hành vi của cá nhân thay đổi khi ở trong đám đông.', 'available'),
('TL_09', 'uploads/1747328720_tam-ly-hoc-thanh-cong.png', 'Tâm Lý Học Thành Công', 'Carol S. Dweck', 'TL', 2006, 'Giới thiệu khái niệm “mindset”: tư duy cố định vs tư duy phát triển.', 'available'),
('TL_10', 'uploads/1747329328_tam-ly-hoc-ve-tien.png', 'Tâm Lý Học Về Tiền (The Psychology of Money)', 'Morgan Housel', 'TL', 2020, 'Phân tích hành vi tài chính từ góc nhìn tâm lý học và cảm xúc cá nhân. |', 'available'),
('TL_11', 'uploads/1747329356_tam-tri-vuot-thoi-gian.png', 'Tâm Trí Vượt Thời Gian (The Time Paradox)', 'Philip Zimbardo', 'TL', 2008, 'Cách nhận thức thời gian ảnh hưởng đến quyết định và hạnh phúc. |', 'available'),
('TL_12', 'uploads/1747329381_thoi-mien-bang-ngon-tu.png', 'Thôi Miên Bằng Ngôn Từ', 'Joe Vitale', 'TL', 2007, 'Tập trung vào cách sử dụng ngôn ngữ để thuyết phục và gây ảnh hưởng. |', 'available'),
('TL_13', 'uploads/1747329410_tri-tue-xuc-cam.png', 'Trí Tuệ Xúc Cảm (Emotional Intelligence)', 'Daniel Goleman', 'TL', 1995, 'Khẳng định vai trò của EQ quan trọng hơn IQ trong thành công và cuộc sống. |', 'available'),
('TN_01', 'uploads/1747309140_joni-mat-tit-va-dong-bon-tinh-nghich.png', 'Joni Mặt Tịt và Đồng Bọn Tinh Nghịch', 'Nguyễn Khắc Cường', 'TN', 2023, 'Câu chuyện hài hước và nhân văn về chú mèo Joni và những người bạn, phản ánh đời sống thị dân hiện đại thông qua góc nhìn của loài vật.', 'available'),
('VH_01', 'uploads/1747306408_khong-gia-dinh.png', 'Không Gia Đình (Sans Famille)', 'Hector Malot', 'VH', 1878, 'Cuộc hành trình của cậu bé Rémi đi khắp nước Pháp để tìm gia đình thật sự của mình.', 'available'),
('VH_02', 'uploads/1747306436_lao-hac.png', 'Lão Hạc', 'Nam Cao', 'VH', 1943, 'Một ông lão nghèo, yêu thương con, chấp nhận đói khổ để giữ gìn phẩm giá và dành phần tốt đẹp cho con trai.', 'available'),
('VH_03', 'uploads/1747306471_ngay-xua-co-mot-chyen-tinh.png', 'Ngày Xưa Có Một Chuyện Tình', 'Nguyễn Nhật Ánh', 'VH', 2016, 'Câu chuyện về mối tình tay ba trong sáng giữa những người bạn thân từ thời niên thiếu đến khi trưởng thành.', 'available'),
('VH_04', 'uploads/1747306586_thien-than-nho-cua-toi.png', 'Thiên Thần Nhỏ Của Tôi', 'Nguyễn Nhật Ánh', 'VH', 2008, 'Chuyện tình dễ thương giữa cậu bé và cô bé hàng xóm – một thiên thần nhỏ trong cuộc đời cậu.', 'available'),
('VH_05', 'uploads/1747306749_truyen-kieu.png', 'Truyện Kiều', 'Nguyễn Du', 'VH', 1820, 'Số phận thăng trầm của Thúy Kiều – một người con gái tài sắc nhưng gặp nhiều trắc trở trong tình yêu và cuộc đời.', 'available'),
('VH_06', 'uploads/1747309018_cho-toi-xin-mot-ve-di-tuoi-tho.png', 'Cho Tôi Xin Một Vé Đi Tuổi Thơ', 'Nguyễn Nhật Ánh', 'VH', 2008, 'Khắc họa thế giới tuổi thơ hồn nhiên, trong sáng, với những trò nghịch ngợm và ước mơ giản dị, gợi nhớ về một thời tuổi thơ đã qua.', 'available'),
('VH_07', 'uploads/1747309053_co-gai-den-tu-hom-qua.png', 'Cô Gái Đến Từ Hôm Qua', 'Nguyễn Nhật Ánh', 'VH', 1990, 'Kể về mối tình học trò ngây thơ giữa Thư và Việt An, đan xen giữa hiện tại và ký ức tuổi thơ, mang đến những cảm xúc nhẹ nhàng, sâu lắng.', 'available'),
('VH_08', 'uploads/1747309087_toi-la-beto.png', 'Tôi Là Bêtô', 'Nguyễn Nhật Ánh', 'VH', 2007, 'Chuyện kể qua góc nhìn của chú chó Bêtô, phản ánh thế giới loài người với những niềm vui, nỗi buồn, và tình yêu thương chân thành.', 'available'),
('VH_09', 'uploads/1747313675_ong-gia-va-bien-ca.png', 'Ông Già Và Biển Cả', 'Ernest Hemingway', 'VH', 1952, 'Câu chuyện về cuộc chiến kiên cường giữa lão chài Santiago và con cá kiếm khổng lồ.', 'available'),
('VH_10', 'uploads/1747313823_tieng-chim-hot-trong-bui-man-gai.png', 'Tiếng Chim Hót Trong Bụi Mận Gai', 'Colleen McCullough', 'VH', 1977, 'Bi kịch tình yêu giữa một linh mục và cô gái trẻ trong bối cảnh nước Úc.', 'available'),
('VH_11', 'uploads/1747313985_nhung-nguoi-khon-kho-chap1.png', 'Những Người Khốn Khổ', 'Victor Hugo', 'VH', 1862, 'Bi kịch và hy vọng trong cuộc đời Jean Valjean, phản ánh lòng trắc ẩn và công lý.', 'available'),
('VH_12', 'uploads/1747314083_thang-gu-nha-tho-duc-ba.png', 'Thằng Gù Nhà Thờ Đức Bà', 'Victor Hugo', 'VH', 1831, 'Câu chuyện cảm động về tình yêu và nỗi đau của chàng gù Quasimodo.', 'available'),
('VH_13', 'uploads/1747314125_toi-ac-va-hinh-phat.png', 'Tội Ác Và Hình Phạt', 'Fyodor Dostoyevsky', 'VH', 1866, 'Tâm lý phức tạp của một sinh viên giết người và hành trình chuộc lỗi.', 'available'),
('VH_14', 'uploads/1747314169_anh-em-nha-karamazov.png', 'Anh Em Nhà Karamazov', 'Fyodor Dostoyevsky', 'VH', 1880, 'Tác phẩm triết học – văn học bàn về đạo đức, tự do, và niềm tin.', 'available'),
('VH_15', 'uploads/1747314231_cuon-theo-chieu-gio.png', 'Cuốn Theo Chiều Gió', 'Margaret Mitchell', 'VH', 1936, 'Tình yêu và chiến tranh trong thời Nội chiến Mỹ, với nhân vật nữ mạnh mẽ Scarlett O’Hara.', 'available'),
('VH_16', 'uploads/1747314283_cach-dong-bat-tan.png', 'Cánh Đồng Bất Tận', 'Nguyễn Ngọc Tư', 'VH', 2005, 'Truyện ngắn đặc sắc của văn học Việt Nam hiện đại về thân phận con người nơi miền Tây.', 'available'),
('VH_17', 'uploads/1747314338_so-do.png', 'Số Đỏ', 'Vũ Trọng Phụng', 'VH', 1936, 'Tác phẩm trào phúng phản ánh xã hội thực dân nửa phong kiến.', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` int(11) NOT NULL,
  `book_id` varchar(11) DEFAULT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('pending','approved','returned') DEFAULT 'pending',
  `approved_at` datetime DEFAULT NULL,
  `approved_by` varchar(50) DEFAULT NULL,
  `returned_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`id`, `book_id`, `student_id`, `borrow_date`, `return_date`, `status`, `approved_at`, `approved_by`, `returned_at`) VALUES
(10, 'GT_01', '4651050264', '2025-05-19', '2025-05-21', 'approved', '2025-05-20 00:01:19', 'Nguyễn Thị Nở', NULL),
(11, 'GT_02', '4651050264', '2025-05-19', '2025-05-28', 'approved', '2025-05-20 00:03:41', 'Nguyễn Thị Nở', NULL),
(12, 'KN_01', '4651050264', '2025-05-19', '2025-05-31', 'returned', '2025-05-20 00:18:48', 'Nguyễn Thị Nở', NULL);

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
('4151050140', 'Nguyễn Thị Nở', '$2y$10$Oee3IUPwzScuKj5oxUa5H.S6wruVybGDsT7F3Ktn9q7AuURF7r/1e', 'newbierhp2000@gmail.com', 'admin', '2025-05-13 22:56:49'),
('4651050264', 'Nguyễn Thị Hồng Thư', '$2y$10$398YHPgkNIDDV2QlyonwiurIMlbnIw/gmzZz2PfvCLYbDU4lwadje', 'karanguyen6605@gmail.com', 'user', '2025-05-13 23:13:22');

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
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `fk_loans_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `fk_loans_user` FOREIGN KEY (`student_id`) REFERENCES `users` (`masv`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
