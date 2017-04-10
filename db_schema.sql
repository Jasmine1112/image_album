-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 10, 2017 at 04:20 PM
-- Server version: 5.6.32
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `info230_SP17_wh298sp17`
--
CREATE DATABASE IF NOT EXISTS `info230_SP17_wh298sp17` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `info230_SP17_wh298sp17`;

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `title` varchar(255) NOT NULL,
  `date_created` date NOT NULL,
  `date_modified` date NOT NULL,
  `style` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`title`, `date_created`, `date_modified`, `style`) VALUES
('Animals', '2017-03-18', '2017-03-27', 'adorable'),
('Dancers', '2017-03-27', '2017-03-28', 'Goal'),
('Fashion', '2017-03-18', '2017-03-28', 'dazzling'),
('Landscape', '2017-03-11', '2017-03-28', 'spectacular');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `imageID` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `credit` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`imageID`, `file_path`, `caption`, `credit`) VALUES
(1, 'images/img1.jpg', 'Torres Del Paine', 'http://www.onlyadayaway.com/torres-del-paine-o-circuit-trek-tips/'),
(2, 'images/img2.png', 'Uyuni Salt Flats', 'https://www.boliviahop.com/discounts/salt-flat-tours-and-discounts/'),
(3, 'images/img3.jpg', 'Machu Picchu', 'http://www.nationalgeographicexpeditions.com/expeditions/peru-machu-picchu-tour/detail'),
(4, 'images/img4.jpg', 'ham', 'https://www.pinterest.com/pin/111675265738228664/'),
(5, 'images/img5.jpg', 'wildlife at Olympic National Park', 'http://georgewashingtoninn.com/inn-daytrips-olympic-national-park/'),
(6, 'images/img6.jpg', 'Husky', 'https://www.pinterest.com/pin/22799541836579759/'),
(7, 'images/img7.jpeg', 'Grand Prismatic Spring', 'https://imaggeo.egu.eu/view/527/'),
(8, 'images/img8.jpeg', 'Antelope Canyon', 'http://laurenceourac.com/when-the-light-strikes-the-beauty-of-the-antelope-canyon/'),
(9, 'images/img9.jpeg', 'Mammoth Hot Springs', 'http://hdwallpaperdaily.com/mammoth-hot-springs-wallpaper/'),
(10, 'images/img10.jpeg', 'Sequoia National Park', 'http://www.gamewarden.org/conservation-challenge-sequoia'),
(11, 'images/img11.jpeg', 'Kitty', 'https://www.pinterest.com/pin/363595369887844280/'),
(12, 'images/img12.jpeg', 'White Bichon Puppy', 'http://www.petyourdog.com/dog_pictures/more/Bichon_Frise/post/all/most_recent/all/0'),
(13, 'images/img13.jpeg', 'Betta fish', 'https://www.theodysseyonline.com/betta-fish-are-amazing'),
(14, 'images/img14.jpeg', 'Bunny selfie', 'http://www.m-magazine.com/posts/check-out-this-bunny-s-adorable-selfie-17397'),
(15, 'images/img15.jpeg', 'Squirrel and nuts', 'http://www.pestworld.org/pest-guide/nuisance-wildlife/tree-squirrels/'),
(16, 'images/img16.jpeg', 'Ballet dancers', 'http://llcollection-groupll.blogspot.com/2011/06/interesting-facts-about-ballet-dance.html'),
(17, 'images/img17.jpeg', 'Flamenco dancer drawing', 'https://www.pinterest.com/pin/468796642433521759/'),
(18, 'images/img18.jpeg', 'So you think you can dance(dancers)', 'http://www.jeffandwill.com/2014/08/14/sytycd-11-top-8-elimination-is-so-wrong/'),
(19, 'images/img19.jpeg', 'Contemporary dancers', 'http://articles.baltimoresun.com/2014-07-03/entertainment/bal-so-you-think-you-can-dance-recap-top-20-dances-their-own-styles-20140703_1_ballroom-sonya-tayeh-bridget-whitman'),
(20, 'images/img20.jpeg', 'Dancer and sunset', 'http://www.hollyireland.com/dance-beach-photography/#sthash.DwLccQzC.dpbs'),
(21, 'images/img21.jpeg', 'Dancer and mountains', 'http://www.chinadaily.com.cn/world/2014-11/28/content_18992644_3.htm'),
(22, 'images/img22.jpeg', 'Break Dancer', 'http://www.fusionplate.com/1995/20-stunning-dance-photo-manipulation-tutorials-for-photoshop/'),
(23, 'images/img23.jpeg', 'Galen Hooks-River choreography', 'https://i.ytimg.com/vi/D97ezyej1mI/maxresdefault.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `saves`
--

CREATE TABLE `saves` (
  `title` varchar(255) NOT NULL,
  `imageID` int(11) NOT NULL,
  `date_saved` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `saves`
--

INSERT INTO `saves` (`title`, `imageID`, `date_saved`) VALUES
('Animals', 5, '2017-03-27'),
('Animals', 6, '2017-03-27'),
('Animals', 11, '2017-03-27'),
('Animals', 12, '2017-03-27'),
('Animals', 13, '2017-03-27'),
('Animals', 14, '2017-03-27'),
('Animals', 15, '2017-03-27'),
('Dancers', 16, '2017-03-27'),
('Dancers', 17, '2017-03-27'),
('Dancers', 18, '2017-03-27'),
('Dancers', 19, '2017-03-27'),
('Dancers', 20, '2017-03-27'),
('Dancers', 21, '2017-03-27'),
('Dancers', 22, '2017-03-27'),
('Dancers', 23, '2017-03-28'),
('Landscape', 1, '2017-03-28'),
('Landscape', 3, '2017-03-28'),
('Landscape', 5, '2017-03-28'),
('Landscape', 7, '2017-03-28'),
('Landscape', 8, '2017-03-28'),
('Landscape', 9, '2017-03-28'),
('Landscape', 10, '2017-03-28'),
('Landscape', 21, '2017-03-28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `hashpassword` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `hashpassword`, `username`) VALUES
(1, '$2y$10$Jc.0AFxg3YOCwxhBdSn77egJnaCDTIjc9/dg8iLidJxg2wKgtqPWm', 'Jasmine');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`title`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`imageID`);

--
-- Indexes for table `saves`
--
ALTER TABLE `saves`
  ADD PRIMARY KEY (`title`,`imageID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `idx_unique_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `imageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
