-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2015 at 06:32 AM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `skripsispk_v1`
--

-- --------------------------------------------------------

--
-- Table structure for table `com_menu`
--

CREATE TABLE IF NOT EXISTS `com_menu` (
  `nav_id` int(11) unsigned NOT NULL,
  `portal_id` int(11) unsigned DEFAULT NULL,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `nav_title` varchar(50) DEFAULT NULL,
  `nav_desc` varchar(100) DEFAULT NULL,
  `nav_url` varchar(100) DEFAULT NULL,
  `nav_no` int(11) unsigned DEFAULT NULL,
  `active_st` enum('1','0') DEFAULT '1',
  `display_st` enum('1','0') DEFAULT '1',
  `nav_icon` varchar(50) DEFAULT NULL,
  `mdb` int(11) unsigned DEFAULT NULL,
  `mdd` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=627 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `com_menu`
--

INSERT INTO `com_menu` (`nav_id`, `portal_id`, `parent_id`, `nav_title`, `nav_desc`, `nav_url`, `nav_no`, `active_st`, `display_st`, `nav_icon`, `mdb`, `mdd`) VALUES
(1, 1, 0, 'Home', 'Selamat Datang', 'home/adminwelcome', 1, '1', '1', NULL, 1, '2011-11-28 11:39:00'),
(2, 1, 0, 'Settings', 'Pengaturan', 'settings/adminportal', 2, '1', '1', NULL, 1, '2011-11-28 11:45:06'),
(3, 1, 2, 'Application', 'Pengaturan aplikasi', '-', 21, '1', '1', NULL, 1, '2011-11-28 13:16:54'),
(4, 1, 3, 'Web Portal', 'Pengelolaan web portal', 'settings/adminportal', 211, '1', '1', NULL, 1, '2011-11-28 13:17:34'),
(5, 1, 3, 'Users', 'Pengelolaan pengguna', 'settings/adminuser', 212, '1', '1', NULL, 1, '2011-11-28 13:21:10'),
(6, 1, 3, 'Roles', 'Pengelolaan hak akses', 'settings/adminrole', 213, '1', '1', NULL, 1, '2011-11-28 13:21:36'),
(7, 1, 3, 'Navigation', 'Pengelolaan menu', 'settings/adminmenu', 214, '1', '1', NULL, 1, '2011-11-28 13:22:03'),
(8, 1, 3, 'Permissions', 'Pengelolaan hak akses pengguna', 'settings/adminpermissions', 215, '1', '1', NULL, 1, '2011-11-28 13:22:30'),
(9, 1, 3, 'Preferences', 'Pengelolaan preferences', 'settings/adminpreferences', 216, '1', '0', NULL, 1, '2011-11-28 13:23:39'),
(199, 1, 1, 'Admin Profile', '-', 'settings/adminprofile', 1, '1', '0', NULL, 1, '2014-08-06 10:01:31'),
(574, 2, 0, 'Dashboard', 'Dashboard', 'operator/welcome', 1, '1', '1', 'fa-dashboard', 1, '2015-08-03 09:00:30'),
(576, 2, 575, 'Data PSB', 'Data PSB', 'psb/data', 1, '1', '1', NULL, 1, '2015-08-03 09:04:33'),
(577, 2, 575, 'Pembayaran Pendaftaran', 'Pembayaran Pendaftaran', 'psb/pembayaran', 2, '1', '1', NULL, 1, '2015-08-03 09:05:50'),
(578, 2, 575, 'Pendaftaran PSB', 'Pendaftaran PSB', 'psb/pendaftaran', 3, '1', '1', NULL, 1, '2015-08-03 09:07:03'),
(579, 2, 575, 'Seleksi Administrasi', 'Seleksi Administrasi', 'psb/seleksi', 4, '1', '1', NULL, 1, '2015-08-03 09:07:33'),
(580, 2, 575, 'Pembayaran Her-Registrasi', 'Pembayaran Her-Registrasi', 'psb/herregistrasi', 5, '1', '1', NULL, 1, '2015-08-03 09:08:03'),
(581, 2, 575, 'Penempatan Siswa Baru', 'Penempatan Siswa Baru', 'psb/penempatan', 6, '1', '1', NULL, 1, '2015-08-03 09:08:30'),
(582, 2, 0, 'Pengaturan', 'Pengaturan', '#', 10, '1', '1', 'fa-gears', 1, '2015-08-03 20:20:02'),
(583, 2, 582, 'Preference', 'Preference', 'pengaturan/preference', 1, '1', '1', NULL, 1, '2015-08-03 20:22:21'),
(584, 2, 582, 'Operator', 'Operator', 'pengaturan/operator', 2, '1', '1', NULL, 1, '2015-08-03 20:22:57'),
(618, 2, 0, 'Keluar', 'Keluar', '-', 100, '1', '1', 'fa-power-off', 1, '2015-08-09 17:40:24'),
(619, 2, 0, 'Indikator 1', 'Indikator 1', 'master/indikator1', 1, '1', '1', 'fa-th', 1, '2015-08-12 19:27:54'),
(620, 2, 0, 'Indikator 2', 'Indikator 2', 'master/indikator2', 2, '1', '1', 'fa-th', 1, '2015-08-12 19:29:00'),
(621, 2, 0, 'Stadium', 'Stadium', 'master/stadium', 3, '1', '1', 'fa-laptop', 1, '2015-08-12 19:29:41'),
(622, 2, 0, 'Solusi 1', 'Solusi 1', 'master/solusi1', 3, '1', '1', 'fa-edit', 1, '2015-08-12 19:30:20'),
(623, 2, 0, 'Solusi 2', 'Solusi 2', 'master/solusi2', 5, '1', '1', 'fa-edit', 1, '2015-08-12 19:31:05'),
(624, 2, 0, 'Pencegahan Primer', 'Pencegahan Primer', 'master/pencegahan_primer', 7, '1', '1', 'fa-pie-chart', 1, '2015-08-12 19:36:23'),
(625, 2, 0, 'Pencegahan Khusus', 'Pencegahan Khusus', 'master/pencegahan_khusus', 8, '1', '1', 'fa-pie-chart', 1, '2015-08-12 19:37:15'),
(626, 2, 0, 'Diagnosa', 'Diagnosa', 'master/diagnosa', 9, '0', '0', 'fa-table', 1, '2015-08-12 19:39:12');

-- --------------------------------------------------------

--
-- Table structure for table `com_portal`
--

CREATE TABLE IF NOT EXISTS `com_portal` (
  `portal_id` int(11) unsigned NOT NULL,
  `portal_nm` varchar(50) DEFAULT NULL,
  `site_title` varchar(100) DEFAULT NULL,
  `site_desc` varchar(100) DEFAULT NULL,
  `meta_desc` varchar(255) DEFAULT NULL,
  `meta_keyword` varchar(255) DEFAULT NULL,
  `mdb` int(11) unsigned DEFAULT NULL,
  `mdd` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `com_portal`
--

INSERT INTO `com_portal` (`portal_id`, `portal_nm`, `site_title`, `site_desc`, `meta_desc`, `meta_keyword`, `mdb`, `mdd`) VALUES
(1, 'Developer Area', 'CiSmart 3.0 Developer Site', '-', '-', '-', 1, '2014-12-27 09:35:59'),
(2, 'Portal Pengurus Sekolah', 'Portal Pengurus Sekolah', 'Portal Pengurus Akademik Sekolah', '-', '-', 1, '2015-06-16 09:03:51'),
(6, 'Portal Orang Tua & Siswa', 'Portal Orang Tua & Siswa', 'Portal Orang Tua & Siswa', 'Portal Orang Tua & Siswa Sistem Informasi Sekolah', 'Portal Orang Tua & Siswa', 1, '2015-06-16 09:03:01');

-- --------------------------------------------------------

--
-- Table structure for table `com_preferences`
--

CREATE TABLE IF NOT EXISTS `com_preferences` (
  `pref_id` int(11) unsigned NOT NULL,
  `pref_group` varchar(50) DEFAULT NULL,
  `pref_nm` varchar(50) DEFAULT NULL,
  `pref_value` varchar(255) DEFAULT NULL,
  `mdb` int(11) unsigned DEFAULT NULL,
  `mdd` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `com_preferences`
--

INSERT INTO `com_preferences` (`pref_id`, `pref_group`, `pref_nm`, `pref_value`, `mdb`, `mdd`) VALUES
(11, 'ujian', 'waktu', '60', 2, '2015-03-06 02:10:06'),
(12, 'ujian', 'soal', '20', 2, '2015-03-06 02:10:17'),
(13, 'ujian', 'score', '70', 2, '2015-03-17 16:01:03'),
(14, 'ujian', 'her', '2', 18, '2015-04-24 08:25:33'),
(15, 'ujian', 'her_waktu', '15', 2, '2015-03-17 16:02:05'),
(24, 'sekolah', 'alamat', 'Kali Bata Jaksel', 23, '2015-06-19 10:37:35'),
(25, 'sekolah', 'tingkat', 'SMA', 23, '2015-06-19 10:37:35'),
(26, 'tingkat', 'sekolah', 'SD', NULL, NULL),
(27, 'tingkat', 'sekolah', 'SLTP', NULL, NULL),
(28, 'tingkat', 'sekolah', 'SMA', NULL, NULL),
(29, 'tingkat', 'sekolah', 'SMK', NULL, NULL),
(30, 'kelas', 'SD', '1', NULL, NULL),
(31, 'kelas', 'SD', '2', NULL, NULL),
(32, 'kelas', 'SD', '3', NULL, NULL),
(33, 'kelas', 'SD', '4', NULL, NULL),
(34, 'kelas', 'SD', '5', NULL, NULL),
(35, 'kelas', 'SD', '6', NULL, NULL),
(36, 'kelas', 'SLTP', '7', NULL, NULL),
(37, 'kelas', 'SLTP', '8', NULL, NULL),
(38, 'kelas', 'SLTP', '9', NULL, NULL),
(39, 'kelas', 'SMA', '10', NULL, NULL),
(40, 'kelas', 'SMA', '11', NULL, NULL),
(41, 'kelas', 'SMA', '12', NULL, NULL),
(44, 'agama', 'nama', 'ISLAM', NULL, NULL),
(45, 'agama', 'nama', 'PROTESTAN', NULL, NULL),
(46, 'agama', 'nama', 'BUDHA', NULL, NULL),
(47, 'agama', 'nama', 'KATHOLIK', NULL, NULL),
(48, 'agama', 'nama', 'HINDU', NULL, NULL),
(49, 'nikah', 'status', 'LAJANG', NULL, NULL),
(50, 'nikah', 'status', 'DUDA', NULL, NULL),
(51, 'nikah', 'status', 'JANDA', NULL, NULL),
(52, 'tingkat', 'pendidikan', 'SD', NULL, NULL),
(53, 'tingkat', 'pendidikan', 'SLTP', NULL, NULL),
(54, 'tingkat', 'pendidikan', 'SMA', NULL, NULL),
(55, 'tingkat', 'pendidikan', 'SMK', NULL, NULL),
(56, 'tingkat', 'pendidikan', 'D1', NULL, NULL),
(57, 'tingkat', 'pendidikan', 'D2', NULL, NULL),
(58, 'tingkat', 'pendidikan', 'D3', NULL, NULL),
(59, 'sekolah', 'nama', 'SMA Kalibata 21', 23, '2015-06-19 10:37:35'),
(60, 'tingkat', 'pendidikan', 'D4', 23, '2015-06-20 10:19:10'),
(61, 'tingkat', 'pendidikan', 'S1', NULL, NULL),
(62, 'tingkat', 'pendidikan', 'S2', NULL, NULL),
(63, 'tingkat', 'pendidikan', 'S3', NULL, NULL),
(77, 'agama', 'nama', 'Lainnya', NULL, NULL),
(78, 'cuti', 'batas', '12', NULL, NULL),
(79, 'biaya', 'pendaftaran', '100000', 23, '2015-07-11 10:42:35');

-- --------------------------------------------------------

--
-- Table structure for table `com_role`
--

CREATE TABLE IF NOT EXISTS `com_role` (
  `role_id` int(11) unsigned NOT NULL,
  `portal_id` int(11) unsigned DEFAULT NULL,
  `role_nm` varchar(50) DEFAULT NULL,
  `role_desc` varchar(100) DEFAULT NULL,
  `default_page` varchar(50) DEFAULT NULL,
  `mdb` int(11) unsigned DEFAULT NULL,
  `mdd` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `com_role`
--

INSERT INTO `com_role` (`role_id`, `portal_id`, `role_nm`, `role_desc`, `default_page`, `mdb`, `mdd`) VALUES
(1, 1, 'Developer', 'Hak akses developer', 'home/adminwelcome', 1, '2013-07-11 10:58:57'),
(41, 2, 'Administrator Database', '-', 'operator/welcome', 1, '2014-11-07 09:20:25');

-- --------------------------------------------------------

--
-- Table structure for table `com_role_menu`
--

CREATE TABLE IF NOT EXISTS `com_role_menu` (
  `role_id` int(11) unsigned NOT NULL,
  `nav_id` int(11) unsigned NOT NULL,
  `role_tp` varchar(4) NOT NULL DEFAULT '1111'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `com_role_menu`
--

INSERT INTO `com_role_menu` (`role_id`, `nav_id`, `role_tp`) VALUES
(1, 1, '1111'),
(1, 2, '1111'),
(1, 3, '1111'),
(1, 4, '1111'),
(1, 5, '1111'),
(1, 6, '1111'),
(1, 7, '1111'),
(1, 8, '1111'),
(1, 9, '1111'),
(1, 199, '1111'),
(41, 574, '1111'),
(41, 582, '1111'),
(41, 583, '1111'),
(41, 584, '1111'),
(41, 618, '1111'),
(41, 619, '1111'),
(41, 620, '1111'),
(41, 621, '1111'),
(41, 622, '1111'),
(41, 623, '1111'),
(41, 624, '1111'),
(41, 625, '1111'),
(41, 626, '1111');

-- --------------------------------------------------------

--
-- Table structure for table `com_role_user`
--

CREATE TABLE IF NOT EXISTS `com_role_user` (
  `role_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `com_role_user`
--

INSERT INTO `com_role_user` (`role_id`, `user_id`) VALUES
(41, 1),
(41, 24);

-- --------------------------------------------------------

--
-- Table structure for table `com_user`
--

CREATE TABLE IF NOT EXISTS `com_user` (
  `user_id` int(11) unsigned NOT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `user_pass` varchar(255) DEFAULT NULL,
  `user_key` varchar(50) DEFAULT NULL,
  `user_mail` varchar(50) DEFAULT NULL,
  `user_st` enum('admin','otoritas') DEFAULT 'otoritas',
  `lock_st` enum('1','0') DEFAULT '0',
  `operator_name` varchar(50) DEFAULT NULL,
  `operator_gender` varchar(50) DEFAULT NULL,
  `operator_birth_place` varchar(50) DEFAULT NULL,
  `operator_birth_day` date DEFAULT NULL,
  `operator_address` varchar(100) DEFAULT NULL,
  `operator_phone` varchar(50) DEFAULT NULL,
  `operator_photo` varchar(50) DEFAULT NULL,
  `operator_identitas` varchar(50) DEFAULT NULL,
  `operator_jabatan` varchar(50) DEFAULT NULL,
  `operator_nip` varchar(50) DEFAULT NULL,
  `guru_id` int(11) unsigned DEFAULT NULL,
  `mdb` int(11) unsigned DEFAULT NULL,
  `mdd` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `com_user`
--

INSERT INTO `com_user` (`user_id`, `user_name`, `user_pass`, `user_key`, `user_mail`, `user_st`, `lock_st`, `operator_name`, `operator_gender`, `operator_birth_place`, `operator_birth_day`, `operator_address`, `operator_phone`, `operator_photo`, `operator_identitas`, `operator_jabatan`, `operator_nip`, `guru_id`, `mdb`, `mdd`) VALUES
(1, 'lyan', 'TF0GFPEJrj+z57iM2GrgqYp/o0p/AkFXCg07uCCHQlcv3Kk+AnKCXZ7H+4wLLJpYCNM4CO6ZFyJ0yDz2V2PjbA==', '-1435417105', 'gloriancrack@gmail.com', 'admin', '0', 'Lyan Dwi Pangestu', 'L', 'Madiun', '1984-06-06', 'Yogyakarta', '08970420806', NULL, NULL, 'Developer', NULL, NULL, 1, '2015-08-12 20:19:50'),
(21, 'fanyss', '4ScwP7BUa7aodzT/4DtE4yy+EuMetUNktW0Rw3cGqR3EKL4JojqtV7iRlhbyJx2dveA8lyUIRmylHVJVvc4Vkw==', '-1531225755', 'fanyfairuz@gmail.com', 'otoritas', '0', 'Fairuz Saniyya Puspitasari', '0', '', NULL, '', '08973638229', '24.jpg', NULL, 'IT Support', NULL, NULL, 1, '2015-08-09 08:56:37'),
(22, 'fanys', '4ScwP7BUa7aodzT/4DtE4yy+EuMetUNktW0Rw3cGqR3EKL4JojqtV7iRlhbyJx2dveA8lyUIRmylHVJVvc4Vkw==', '-1531225755', 'fanyfairuz@gmail.com', 'otoritas', '0', 'Fairuz Saniyya Puspitasari', '0', '', NULL, '', '08973638229', '24.jpg', NULL, 'IT Support', NULL, NULL, 1, '2015-08-09 08:56:37'),
(24, 'fany', '4ScwP7BUa7aodzT/4DtE4yy+EuMetUNktW0Rw3cGqR3EKL4JojqtV7iRlhbyJx2dveA8lyUIRmylHVJVvc4Vkw==', '-1531225755', 'fanyfairuz@gmail.com', 'otoritas', '0', 'Fairuz Saniyya Puspitasari', '0', '', NULL, '', '08973638229', '24.jpg', NULL, 'IT Support', NULL, NULL, 1, '2015-08-09 08:56:37');

-- --------------------------------------------------------

--
-- Table structure for table `com_user_login`
--

CREATE TABLE IF NOT EXISTS `com_user_login` (
  `user_id` int(11) unsigned NOT NULL,
  `login_date` datetime NOT NULL,
  `logout_date` datetime DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `com_user_login`
--

INSERT INTO `com_user_login` (`user_id`, `login_date`, `logout_date`, `ip_address`) VALUES
(1, '2015-03-05 16:36:41', NULL, '::1'),
(1, '2015-03-07 10:40:45', NULL, '127.0.0.1'),
(1, '2015-03-09 11:55:31', NULL, '127.0.0.1'),
(1, '2015-03-13 16:16:36', NULL, '127.0.0.1'),
(1, '2015-03-14 08:13:18', '2015-03-14 08:43:42', '::1'),
(1, '2015-03-16 11:04:45', NULL, '127.0.0.1'),
(1, '2015-03-17 08:09:49', NULL, '127.0.0.1'),
(1, '2015-03-18 08:21:22', NULL, '127.0.0.1'),
(1, '2015-03-19 09:11:15', NULL, '127.0.0.1'),
(1, '2015-03-20 18:40:48', '2015-03-20 18:58:11', '::1'),
(1, '2015-03-21 10:27:51', NULL, '::1'),
(1, '2015-03-23 14:11:47', NULL, '::1'),
(1, '2015-03-30 18:38:25', NULL, '127.0.0.1'),
(1, '2015-03-31 12:28:38', NULL, '127.0.0.1'),
(1, '2015-04-03 09:21:22', NULL, '127.0.0.1'),
(1, '2015-04-05 12:00:25', NULL, '127.0.0.1'),
(1, '2015-04-06 13:06:56', NULL, '::1'),
(1, '2015-04-07 18:54:27', NULL, '::1'),
(1, '2015-04-08 19:23:46', NULL, '::1'),
(1, '2015-04-10 14:57:12', NULL, '127.0.0.1'),
(1, '2015-04-11 08:37:32', NULL, '127.0.0.1'),
(1, '2015-04-12 09:59:59', NULL, '127.0.0.1'),
(1, '2015-04-13 11:26:52', NULL, '127.0.0.1'),
(1, '2015-04-15 14:10:46', NULL, '::1'),
(1, '2015-04-16 14:49:42', NULL, '::1'),
(1, '2015-04-18 16:09:17', NULL, '::1'),
(1, '2015-04-19 11:31:56', NULL, '::1'),
(1, '2015-04-20 12:34:04', NULL, '192.168.1.253'),
(1, '2015-05-21 13:41:31', NULL, '127.0.0.1'),
(1, '2015-05-30 09:51:28', NULL, '127.0.0.1'),
(1, '2015-05-31 09:35:16', NULL, '127.0.0.1'),
(1, '2015-06-03 08:25:20', NULL, '127.0.0.1'),
(1, '2015-06-06 07:54:26', NULL, '127.0.0.1'),
(1, '2015-06-15 15:40:47', '2015-06-15 15:51:31', '::1'),
(1, '2015-06-17 13:56:18', NULL, '::1'),
(1, '2015-06-23 09:05:39', NULL, '::1'),
(1, '2015-06-24 11:42:37', NULL, '::1'),
(1, '2015-06-25 09:26:43', NULL, '::1'),
(1, '2015-07-08 09:52:55', NULL, '::1'),
(1, '2015-07-24 13:15:13', NULL, '::1'),
(1, '2015-07-31 20:40:42', NULL, '::1'),
(1, '2015-08-09 08:55:20', NULL, '::1'),
(1, '2015-08-11 18:33:51', NULL, '::1'),
(1, '2015-08-12 19:24:44', NULL, '::1'),
(24, '2015-08-09 17:06:30', '2015-08-09 17:40:37', '::1'),
(24, '2015-08-11 18:34:05', NULL, '::1'),
(24, '2015-08-12 15:55:26', '2015-08-12 21:20:03', '::1'),
(24, '2015-08-14 20:15:31', NULL, '::1');

-- --------------------------------------------------------

--
-- Table structure for table `com_user_ortu`
--

CREATE TABLE IF NOT EXISTS `com_user_ortu` (
  `user_id` int(11) unsigned NOT NULL,
  `siswa_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `com_user_sekolah`
--

CREATE TABLE IF NOT EXISTS `com_user_sekolah` (
  `user_id` int(11) unsigned NOT NULL,
  `guru_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `com_user_siswa`
--

CREATE TABLE IF NOT EXISTS `com_user_siswa` (
  `user_id` int(11) unsigned NOT NULL,
  `siswa_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `com_user_super`
--

CREATE TABLE IF NOT EXISTS `com_user_super` (
  `user_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `com_user_super`
--

INSERT INTO `com_user_super` (`user_id`) VALUES
(1);

-- --------------------------------------------------------

--
-- Table structure for table `peminatan`
--

CREATE TABLE IF NOT EXISTS `peminatan` (
  `kode_peminatan` int(10) NOT NULL,
  `nama_peminatan` varchar(50) NOT NULL,
  `Kuota` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE IF NOT EXISTS `siswa` (
  `nis` int(5) NOT NULL DEFAULT '0',
  `nama` varchar(45) NOT NULL,
  `jenis_kelamin` varchar(14) NOT NULL,
  `tempat_lahir` varchar(20) NOT NULL,
  `tgl_lahir` datetime(6) NOT NULL,
  `minat1` varchar(10) NOT NULL,
  `minat2` varchar(10) NOT NULL,
  `asal_sekolah` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`nis`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tgl_lahir`, `minat1`, `minat2`, `asal_sekolah`) VALUES
(2, 'lyan', 'laki-laki', 'yogya', '2015-07-02 00:00:00.000000', 'ipa', 'ips', 'SMP ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `com_menu`
--
ALTER TABLE `com_menu`
  ADD PRIMARY KEY (`nav_id`), ADD KEY `FK_com_menu_p` (`portal_id`);

--
-- Indexes for table `com_portal`
--
ALTER TABLE `com_portal`
  ADD PRIMARY KEY (`portal_id`);

--
-- Indexes for table `com_preferences`
--
ALTER TABLE `com_preferences`
  ADD PRIMARY KEY (`pref_id`);

--
-- Indexes for table `com_role`
--
ALTER TABLE `com_role`
  ADD PRIMARY KEY (`role_id`), ADD KEY `FK_com_role_p` (`portal_id`);

--
-- Indexes for table `com_role_menu`
--
ALTER TABLE `com_role_menu`
  ADD PRIMARY KEY (`nav_id`,`role_id`), ADD KEY `FK_com_role_menu_r` (`role_id`);

--
-- Indexes for table `com_role_user`
--
ALTER TABLE `com_role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`), ADD KEY `FK_com_role_user_r` (`role_id`);

--
-- Indexes for table `com_user`
--
ALTER TABLE `com_user`
  ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `user_name` (`user_name`), ADD KEY `FK_com_user` (`guru_id`);

--
-- Indexes for table `com_user_login`
--
ALTER TABLE `com_user_login`
  ADD PRIMARY KEY (`user_id`,`login_date`);

--
-- Indexes for table `com_user_ortu`
--
ALTER TABLE `com_user_ortu`
  ADD PRIMARY KEY (`user_id`,`siswa_id`);

--
-- Indexes for table `com_user_sekolah`
--
ALTER TABLE `com_user_sekolah`
  ADD PRIMARY KEY (`user_id`,`guru_id`);

--
-- Indexes for table `com_user_siswa`
--
ALTER TABLE `com_user_siswa`
  ADD PRIMARY KEY (`user_id`,`siswa_id`);

--
-- Indexes for table `com_user_super`
--
ALTER TABLE `com_user_super`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `peminatan`
--
ALTER TABLE `peminatan`
  ADD PRIMARY KEY (`kode_peminatan`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`nis`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `com_menu`
--
ALTER TABLE `com_menu`
  MODIFY `nav_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=627;
--
-- AUTO_INCREMENT for table `com_portal`
--
ALTER TABLE `com_portal`
  MODIFY `portal_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `com_preferences`
--
ALTER TABLE `com_preferences`
  MODIFY `pref_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT for table `com_role`
--
ALTER TABLE `com_role`
  MODIFY `role_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `com_user`
--
ALTER TABLE `com_user`
  MODIFY `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `com_menu`
--
ALTER TABLE `com_menu`
ADD CONSTRAINT `FK_com_menu_p` FOREIGN KEY (`portal_id`) REFERENCES `com_portal` (`portal_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `com_role`
--
ALTER TABLE `com_role`
ADD CONSTRAINT `FK_com_role_p` FOREIGN KEY (`portal_id`) REFERENCES `com_portal` (`portal_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `com_role_menu`
--
ALTER TABLE `com_role_menu`
ADD CONSTRAINT `FK_com_role_menu_m` FOREIGN KEY (`nav_id`) REFERENCES `com_menu` (`nav_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `FK_com_role_menu_r` FOREIGN KEY (`role_id`) REFERENCES `com_role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `com_role_user`
--
ALTER TABLE `com_role_user`
ADD CONSTRAINT `FK_com_role_user_r` FOREIGN KEY (`role_id`) REFERENCES `com_role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `FK_com_role_user_u` FOREIGN KEY (`user_id`) REFERENCES `com_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `com_user_login`
--
ALTER TABLE `com_user_login`
ADD CONSTRAINT `FK_com_user_login` FOREIGN KEY (`user_id`) REFERENCES `com_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `com_user_super`
--
ALTER TABLE `com_user_super`
ADD CONSTRAINT `FK_com_user_super` FOREIGN KEY (`user_id`) REFERENCES `com_user` (`user_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
