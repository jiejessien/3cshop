-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2020 at 11:22 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `3cshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryid` int(11) UNSIGNED NOT NULL,
  `categoryname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `categorysort` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryid`, `categoryname`, `categorysort`) VALUES
(1, 'ASUS', 1),
(2, 'ACER', 2),
(3, 'HP', 3),
(4, 'SONY', 4);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `commentid` int(11) NOT NULL,
  `c_username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `c_orderdetailid` int(11) NOT NULL,
  `c_productid` int(11) NOT NULL,
  `c_description` text COLLATE utf8_unicode_ci NOT NULL,
  `c_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`commentid`, `c_username`, `c_orderdetailid`, `c_productid`, `c_description`, `c_datetime`) VALUES
(8, 'test001', 8, 11, '品質很好\r\nNice!																		', '2020-05-15 22:30:58'),
(9, 'test001', 7, 18, '使用起來還行OK					', '2020-05-03 13:42:11'),
(10, 'test002', 11, 13, '很棒												', '2020-05-03 13:43:20'),
(11, 'test003', 14, 17, '												品質良好!寄貨迅速!服務優良!', '2020-05-17 20:37:36'),
(12, 'test003', 13, 18, '						服務優良!', '2020-05-17 20:41:02'),
(13, 'test001', 1, 18, '						寄貨迅速!寄貨迅速!', '2020-05-18 00:27:41'),
(14, 'test003', 17, 22, '收到貨了謝謝						寄貨迅速!', '2020-05-18 10:34:01'),
(15, 'test004', 18, 25, '						服務優良!服務優良!', '2020-05-21 17:19:31');

-- --------------------------------------------------------

--
-- Table structure for table `memberdata`
--

CREATE TABLE `memberdata` (
  `m_id` int(11) NOT NULL,
  `m_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m_username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m_passwd` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `m_sex` enum('男','女') COLLATE utf8_unicode_ci NOT NULL,
  `m_birthday` date DEFAULT NULL,
  `m_level` enum('admin','member') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'member',
  `m_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `m_phone` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `m_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `m_login` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `m_logintime` datetime DEFAULT NULL,
  `m_jointime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `memberdata`
--

INSERT INTO `memberdata` (`m_id`, `m_name`, `m_username`, `m_passwd`, `m_sex`, `m_birthday`, `m_level`, `m_email`, `m_phone`, `m_address`, `m_login`, `m_logintime`, `m_jointime`) VALUES
(1, '系統管理員', 'admin', '$2y$10$JxDDmJA4NkQgLE0qLX7gb.w9s2P8.JWv2k.5KnU9ufl0XguY82R3e', '男', '1996-08-17', 'admin', 'jessienjss@gmail.com', '', '', 50, '2020-05-21 10:45:18', '2008-10-20 16:36:15'),
(2, '張惠玲', 'elven', '$2y$10$YdUhOvUTvwK5oWp/i3LafOd2ImwsE/85YmmoY2konsxdmMSsvczFO', '女', '1987-04-05', 'member', 'elven@superstar.com', '0966765556', '台北市濟洲北路12號2樓', 12, '2016-08-29 11:44:33', '2008-10-21 12:03:12'),
(3, '彭建志', 'jinglun', '$2y$10$WqB2bnMSO/wgBiHSOBV2iuLbrUCsp8VmNJdK2AyIW6IANUL9jeFjC', '男', '1987-07-01', 'member', 'jinglun@superstar.com', '0918181111', '台北市敦化南路93號5樓', 0, NULL, '2008-10-21 12:06:08'),
(4, '謝耿鴻', 'sugie', '$2y$10$6uWtdYATI3b/wMRk.AaqIei852PLf.WjeKm8X.Asl0VTmpxCkqbW6', '男', '1987-08-11', 'member', 'edreamer@gmail.com', '0914530768', '台北市中央路201號7樓', 2, '2016-08-29 14:03:53', '2008-10-21 12:06:08'),
(5, '蔣志明', 'shane', '$2y$10$pWefN9xkeXOKCx59GF6ZJuSGNnIFBY4q/DCmCvAwOFtnoTCujb3Te', '男', '1984-06-20', 'member', 'shane@superstar.com', '0946820035', '台北市建國路177號6樓', 0, NULL, '2008-10-21 12:06:08'),
(6, '王佩珊', 'ivy', '$2y$10$RPrt3YfaSs0d82inYIK6he.JaPqOrisWMqASuxN5g62EyRio.lyEa', '女', '1988-02-15', 'member', 'ivy@superstar.com', '0920981230', '台北市忠孝東路520號6樓', 0, NULL, '2008-10-21 12:06:08'),
(7, '林志宇', 'zhong', '$2y$10$pee.jvO6f4sSKahlc4cLLO9RUMyx8aphyqkSUdwHTNSy4Ax7YPdpq', '男', '1987-05-05', 'member', 'zhong@superstar.com', '0951983366', '台北市三民路1巷10號', 0, NULL, '2008-10-21 12:06:08'),
(8, '李曉薇', 'lala', '$2y$10$oiC9CaGiOdWu.6w5b3.b/Ora6fSuh8Lrbj8Kg5BUPT15O3QptksQS', '女', '1985-08-30', 'member', 'lala@superstar.com', '0918123456', '台北市仁愛路100號', 0, NULL, '2008-10-21 12:06:08'),
(9, '賴秀英', 'crystal', '$2y$10$8Q0.JEGILRM91qAlMmWnB.wpcY.rJEbgNgV5ntIlqZmdGaHPwikji', '女', '1986-12-10', 'member', 'crystal@superstar.com', '0907408965', '台北市民族路204號', 0, NULL, '2008-10-21 12:06:08'),
(10, '張雅琪', 'peggy', '$2y$10$RNqnXDNHkcTI2Zh2bkTKnOesz0FLXhihhT8ZL8OHoMeYSq7jsILMi', '女', '1988-12-01', 'member', 'peggy@superstar.com', '0916456723', '台北市建國北路10號', 0, NULL, '2008-10-21 12:06:08'),
(13, '黃信溢', 'dkdreamer', '$2y$10$Fx0rLJtV5mVtJzAJ52B/hup1AmviTe7Ciu0mtWBCZAkYC0qmg6OJy', '女', '1987-04-05', 'member', 'edreamer@gmail.com', '955648889', '愛蘭里梅村路213巷8號', 1, '2016-08-29 17:42:24', '2016-08-29 17:41:46'),
(14, '測試一', 'test001', '$2y$10$9VaYZc29I5NawuThSEZTDOL1fmS6R/SUkFg4EP13ClBgiNbEUofk2', '女', '1993-01-02', 'member', 'jessienjss@gmail.com', '0912345678', '高雄市', 15, '2020-05-18 00:26:20', '2020-04-26 14:39:28'),
(15, '測試二', 'test002', '$2y$10$eTQsaI88Vi9XlankX.V9s.qbN29SAUkLcp6gKlRfVCpejvneFbnUq', '女', '1993-09-07', 'member', 'jessienjss@gmail.com', '', '', 2, '2020-05-03 13:42:56', '2020-05-02 22:29:14'),
(16, '測試三', 'test003', '$2y$10$4Tvtd6r0oliBKAgdqF1ZKuWmNZYAmMs.wfjogiH/CcSsqT/ahlL92', '男', '1992-04-05', 'member', 'jiejissien@gmail.com', '09876543', '高雄市前金區', 5, '2020-05-18 10:19:47', '2020-05-15 22:47:54'),
(17, '測試四', 'test004', '$2y$10$/wQq19uQyAJKDQYOAT9IF.DKIOywoLbFb9xPs.CXXJs4UQ2BlOB/G', '女', '1988-08-08', 'member', 'jiejessien@gmail.com', '0912678543', '彰化縣彰化市', 2, '2020-05-21 17:11:20', '2020-05-21 17:02:33');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetail`
--

CREATE TABLE `orderdetail` (
  `orderdetailid` int(11) UNSIGNED NOT NULL,
  `orderid` int(11) UNSIGNED DEFAULT NULL,
  `productid` int(11) UNSIGNED DEFAULT NULL,
  `productname` varchar(254) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unitprice` int(11) UNSIGNED DEFAULT NULL,
  `quantity` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orderdetail`
--

INSERT INTO `orderdetail` (`orderdetailid`, `orderid`, `productid`, `productname`, `unitprice`, `quantity`) VALUES
(1, 1, 18, 'SONY VGN-FE25TP', 49800, 1),
(2, 1, 8, 'ACER 5562', 48900, 1),
(3, 2, 11, 'HP TC4200', 57900, 3),
(4, 3, 11, 'HP TC4200', 57900, 2),
(5, 3, 18, 'SONY VGN-FE25TP', 49800, 1),
(6, 4, 11, 'HP TC4200', 57900, 2),
(7, 4, 18, 'SONY VGN-FE25TP', 49800, 1),
(8, 5, 11, 'HP TC4200', 57900, 2),
(9, 5, 18, 'SONY VGN-FE25TP', 49800, 1),
(10, 6, 16, 'SONY VGN-AR18TP', 149800, 1),
(11, 6, 13, 'HP V2632', 39900, 1),
(12, 7, 21, 'ASUS ZenFone 6 ZS630KL (6G/128G)-迷霧黑', 15888, 2),
(13, 8, 18, 'SONY VGN-FE25TP', 49800, 1),
(14, 9, 17, 'SONY VAIO VGN-FJ79TP', 39800, 1),
(15, 10, 12, 'HP NC2400', 55900, 1),
(16, 10, 22, 'Acer Swift3 SF314-56G 14吋八代輕薄獨顯(i5-8265U/4G/256G SSD/MX250-2G/W10)', 20900, 1),
(17, 11, 22, 'Acer Swift3 SF314-56G 14吋八代輕薄獨顯(i5-8265U/4G/256G SSD/MX250-2G/W10)', 20900, 1),
(18, 12, 25, 'SONY Xperia 10 II (4G/128G) 6吋三鏡頭智慧手機', 11490, 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderid` int(11) UNSIGNED NOT NULL,
  `o_username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `total` int(11) UNSIGNED DEFAULT NULL,
  `deliverfee` int(11) UNSIGNED DEFAULT NULL,
  `grandtotal` int(11) UNSIGNED DEFAULT NULL,
  `customername` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customeraddress` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customerphone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paytype` enum('ATM匯款','線上刷卡','貨到付款') COLLATE utf8_unicode_ci DEFAULT 'ATM匯款'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderid`, `o_username`, `datetime`, `total`, `deliverfee`, `grandtotal`, `customername`, `customeraddress`, `customerphone`, `paytype`) VALUES
(1, 'test001', '2020-04-26 09:37:06', 98700, 0, 98700, '姓名', '住址', '電話', 'ATM匯款'),
(2, 'test001', '2020-04-28 18:24:00', 173700, 0, 173700, '姓名', '住址', '電話', '線上刷卡'),
(3, 'test001', '2020-04-28 18:26:53', 165600, 0, 165600, '111', '333', '222', 'ATM匯款'),
(4, 'test001', '2020-04-30 06:33:34', 165600, 0, 165600, '姓名', '住址', '電話', 'ATM匯款'),
(5, 'test001', '2020-04-30 06:34:04', 165600, 0, 165600, '姓名', '住址', '電話', 'ATM匯款'),
(6, 'test002', '2020-05-02 16:33:54', 189700, 0, 189700, '姓名', '住址', '電話', 'ATM匯款'),
(7, 'test003', '2020-05-15 17:13:27', 31776, 500, 32276, '姓名', '住址', '電話', 'ATM匯款'),
(8, 'test003', '2020-05-15 17:14:20', 49800, 500, 50300, '姓名', '住址', '電話', 'ATM匯款'),
(9, 'test003', '2020-05-15 17:15:40', 39800, 500, 40300, '姓名', '住址', '電話', 'ATM匯款'),
(10, 'test003', '2020-05-17 16:59:46', 76800, 0, 76800, 'test003', '高雄市前金區', '09876543', '貨到付款'),
(11, 'test003', '2020-05-18 04:32:44', 20900, 500, 21400, '測試三', '高雄市前金區', '09876543', '線上刷卡'),
(12, 'test004', '2020-05-21 11:14:43', 22980, 500, 23480, '測試四', '彰化縣彰化市', '0912678543', '線上刷卡');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productid` int(11) UNSIGNED NOT NULL,
  `categoryid` int(11) UNSIGNED NOT NULL,
  `productname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `productprice` int(11) UNSIGNED DEFAULT NULL,
  `productimages` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `producttime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productid`, `categoryid`, `productname`, `productprice`, `productimages`, `description`, `producttime`) VALUES
(1, 1, 'ASUS W5GT24DD', 54800, 'W5GT24DD.jpg', '◆ 1024MBDDRII雙通道記憶體\r\n◆ 100GB超大硬碟容量\r\n◆ 內建130萬畫素網路攝影機\r\n◆ 12吋鏡面寬螢幕', '2019-09-02 11:30:32'),
(2, 1, 'ASUS F3APT24DD', 51800, 'F3APT24DD.jpg', '◆ Intel雙核T2400\r\n◆ ATi獨立顯示TC256\r\n◆ 15.4吋鏡面寬螢幕\r\n◆ 100G-SATA\r\n◆ DVDSuperMulti(DL)', '2019-10-05 13:38:02'),
(3, 1, 'ASUS W7J', 58800, 'W7J.jpg', '◆ 雙核IntelDualCoreT2400處理器1.83G\r\n◆ Intel945PM高效晶片\r\n◆ nV獨立顯示256MB\r\n◆ 13吋鏡面寬螢', '2019-11-22 10:19:36'),
(4, 1, 'ASUS S6F', 68800, 'S6F.jpg', '◆ 11.1吋鏡面寬螢幕\r\n◆ 內建1GBDDRII記憶體\r\n◆ 雙核心低電壓超省電CPU\r\n◆ 大容量120GB\r\n◆ 附贈真皮保證書與真皮光學滑鼠', '2019-12-07 16:42:55'),
(5, 1, 'ASUS VX1', 108800, 'VX1.jpg', '◆ 師法藍寶堅尼跑車俐落線條美學\r\n◆ 15吋高解析鏡面螢幕1400x1050\r\n◆ 頂級獨立顯示NV7400VX-512MB\r\n◆ Intel雙核心T2500', '2019-09-02 11:30:32'),
(7, 2, 'ACER TM2403', 22900, 'TM2403.jpg', '◆ 14.1吋寬螢幕\r\n◆ Intel新一代910GML晶片,DDR2記憶體\r\n◆ CeleronM最新超值奈米機\r\n◆ 創新FOLIO造型', '2019-11-22 10:19:36'),
(8, 2, 'ACER 5562', 48900, '5562.jpg', '◆ IntelCoreDuo雙核心\r\n◆ ATIX1400512MB3D顯示\r\n◆ 旋轉式130萬視訊\r\n◆ 抽取式藍芽網路話機', '2019-12-07 16:42:55'),
(9, 2, 'ACER Ferrari 4002WLMi', 48800, '4002WLMi.jpg', '◆ 採用AMDTurion64全新64位元處理器\r\n◆ 上蓋碳纖維材質\r\n◆ ATIX700獨立顯示\r\n◆ 內建藍芽', '2019-09-02 11:30:32'),
(10, 2, 'ACER 3022WTMi', 52900, '3022WTMi.jpg', '◆ 1.5kg輕巧靈動A4大小\r\n◆ 超效IntelCoreDuo雙核心\r\n◆ 130萬畫素225度網路視訊\r\n◆ 藍芽無線傳輸', '2019-10-05 13:38:02'),
(11, 3, 'HP TC4200', 57900, 'TC4200.jpg', '◆ 可拆式旋轉鍵盤設計\r\n◆ 採用強化玻璃\r\n◆ 效能卓越、攜帶方便\r\n◆ 三年保固', '2019-11-22 10:19:36'),
(12, 3, 'HP NC2400', 55900, 'NC2400.jpg', '◆ 超輕12吋WXGA\r\n◆ 鎂合金機身\r\n◆ 內建指紋辨識器\r\n◆ 內建光碟1.5KG', '2019-12-07 16:42:55'),
(13, 3, 'HP V2632', 39900, 'V2632.jpg', '◆ 14吋鏡面寬螢幕\r\n◆ Intel雙核心T2400\r\n◆ 80GB-SATA超大硬碟\r\n◆ 藍芽技術', '2019-09-02 11:30:32'),
(14, 3, 'HP Presario B2821', 36900, 'B2821.jpg', '◆ ATIX600獨立128MB顯示晶片\r\n◆ 白色鋼琴烤漆外觀\r\n◆ BrightView超亮顯示屏\r\n◆ 輕薄便攜僅重2.0kg', '2019-10-05 13:38:02'),
(16, 4, 'SONY VGN-AR18TP', 149800, 'VGN-AR18TP.jpg', '◆ Intel雙核心T2600\r\n◆ NVIGF7600GT256MB\r\n◆ 160超大SATA硬碟\r\n◆ 17吋1920X1200高畫質\r\n◆ 藍光燒錄', '2019-12-07 16:42:55'),
(17, 4, 'SONY VAIO VGN-FJ79TP', 39800, 'VGN-FJ79TP.jpg', '◆ IntelPM2G處理器\r\n◆ 14吋寬螢\r\n◆ 80GBSATA大硬碟\r\n◆ DVD雙層燒錄\r\n◆ 專業版XPP', '2019-09-02 11:30:32'),
(18, 4, 'SONY VGN-FE25TP', 49800, 'VGN-FE25TP.jpg', '◆ Intel雙核心T2400\r\n◆ 80GBSATA大硬碟\r\n◆ NVIGF7400獨立顯示256MB\r\n◆ 15.4吋2.85KG', '2019-10-05 13:38:02'),
(21, 1, 'ASUS ZenFone 6 ZS630KL (6G/128G)-迷霧黑', 15888, 'ASUSooo.jpg', '●6.4吋極窄邊框全屏視野\r\n●S855高通旗艦處理器\r\n●6G RAM / 128G ROM\r\n●前後4800畫素180度翻轉鏡頭\r\n●5000mAh大電量QC4.0快充', '2020-05-09 01:33:00'),
(22, 2, 'Acer Swift3 SF314-56G 14吋八代輕薄獨顯(i5-8265U/4G/256G SSD/MX250-2G/W10)', 20900, '7690560_R.jpg', '輕薄窄邊框設計\r\n256GB PCIe SSD\r\n搭載指紋辨識超安全', '2020-05-16 00:06:35'),
(23, 2, '最新10代 Swift3 SF314-57G 14吋i5輕薄獨顯筆電(i5-1035G1/8G/512G SSD/MX250)', 26900, '7388550_R.jpg', '十代CPU+MX250最佳配置\r\n1.19kg極輕窄框IPS螢幕\r\nWifi6提升三倍速度不延遲', '2020-05-16 00:06:35'),
(24, 4, 'SONY Xperia XZ3 (6G/64G) 6吋雙卡防水手機', 14790, '8DA31A08F2.jpg', '．輕薄的無邊框設計\r\n．HDR OLED 顯示幕\r\n．立體聲擴音器\r\n．4K HDR 影片錄製\r\n．ISO12800感光夜拍', '2020-05-21 15:15:12'),
(25, 4, 'SONY Xperia 10 II (4G/128G) 6吋三鏡頭智慧手機', 11490, '3125890E2C.jpg', '．21:9寬6吋OLED 顯示幕\r\n．三鏡頭搭配超廣角廣角和望遠鏡頭\r\n．主鏡頭畫素12MP8MP8MP\r\n．IP65/68 抗水防塵等級\r\n．多視窗功能和側面感應', '2020-05-21 15:15:12'),
(26, 3, '【HP 惠普】13吋i7頂規SSD輕薄筆電-冰曜銀(10代i7/8G/512G PCIe SSD/Win10)', 28900, '7427648_R.jpg', '超輕薄機身, 超高行動性\r\n僅5.38mm薄的極窄邊框\r\n標配指紋辨識器 & 背光鍵盤', '2020-05-21 15:15:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryid`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`commentid`);

--
-- Indexes for table `memberdata`
--
ALTER TABLE `memberdata`
  ADD PRIMARY KEY (`m_id`),
  ADD UNIQUE KEY `m_username` (`m_username`);

--
-- Indexes for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD PRIMARY KEY (`orderdetailid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderid`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `commentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `memberdata`
--
ALTER TABLE `memberdata`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orderdetail`
--
ALTER TABLE `orderdetail`
  MODIFY `orderdetailid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
