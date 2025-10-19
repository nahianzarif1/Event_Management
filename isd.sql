-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2025 at 09:18 AM
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
-- Database: `isd`
--

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `eventID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `eventName` varchar(100) NOT NULL,
  `eventType` varchar(100) NOT NULL,
  `eventDate` datetime NOT NULL,
  `location` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`eventID`, `userID`, `eventName`, `eventType`, `eventDate`, `location`) VALUES
(1, 1, 'Alice Wedding', 'Wedding', '2025-11-15 14:00:00', 'Central Park'),
(2, 2, 'Bob Birthday', 'Birthday', '2025-12-20 19:00:00', 'Bob’s House'),
(3, 3, 'Charlie Conference', 'Conference', '2026-01-10 09:00:00', 'Convention Center'),
(4, 4, 'Diana Gala', 'Gala', '2025-10-25 18:30:00', 'Grand Ballroom'),
(5, 5, 'Ethan Workshop', 'Workshop', '2026-02-05 13:00:00', 'Tech Hub'),
(628263, 1, 'Photography Booking', 'Media', '2025-10-10 00:16:00', 'New York, USA'),
(733464, 306064, 'DJ Booking', 'Entertainment', '2025-10-10 18:36:00', 'Dubai, UAE'),
(814770, 8244413, 'Photography Booking', 'Media', '2025-10-09 01:14:00', 'New York, USA');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `orderDate` datetime NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('Pending','Paid','Shipped','Cancelled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `userID`, `orderDate`, `total_amount`, `status`) VALUES
(0, 1, '2025-10-06 23:24:52', 345.69, 'Pending'),
(1, 1, '2025-09-01 10:30:00', 179.99, 'Paid'),
(2, 2, '2025-09-02 11:00:00', 15.00, 'Pending'),
(3, 3, '2025-09-03 09:45:00', 200.00, 'Shipped'),
(4, 4, '2025-09-04 14:20:00', 45.00, 'Cancelled'),
(5, 5, '2025-09-05 16:10:00', 75.00, 'Paid'),
(100378, 1, '2025-10-06 23:46:05', 5.00, ''),
(144514, 1, '2025-10-06 23:36:53', 15.00, 'Pending'),
(214293, 1, '2025-10-06 23:48:46', 150.00, 'Pending'),
(233548, 1, '2025-10-06 23:44:24', 150.00, ''),
(262600, 1, '2025-10-06 23:28:08', 149.95, 'Pending'),
(281016, 1, '2025-10-06 23:27:43', 600.00, 'Pending'),
(284757, 1, '2025-10-06 23:41:39', 29.99, 'Pending'),
(313451, 306064, '2025-10-12 00:28:47', 25.75, 'Pending'),
(318392, 306064, '2025-10-08 18:35:51', 350.04, 'Pending'),
(486400, 1, '2025-10-06 23:46:25', 29.99, 'Pending'),
(679640, 1, '2025-10-06 23:48:30', 29.99, 'Pending'),
(711298, 8244413, '2025-10-07 01:14:23', 29.99, 'Pending'),
(793908, 1, '2025-10-06 23:41:20', 15.00, 'Pending'),
(881327, 1, '2025-10-06 23:51:04', 15.00, 'Pending'),
(991509, 306064, '2025-10-12 00:28:26', 52.73, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `orderItemID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`orderItemID`, `orderID`, `itemID`, `quantity`, `price`) VALUES
(223820, 991509, 5, 1, 11.25),
(382155, 991509, 3, 1, 9.99),
(590645, 313451, 5, 1, 11.25),
(746425, 313451, 4, 1, 14.50),
(778087, 991509, 2, 1, 16.99),
(797799, 991509, 4, 1, 14.50);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `paymentDate` datetime NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) NOT NULL,
  `method` enum('Credit_card','Paypal','Bank_transfer','Cash') NOT NULL,
  `status` enum('Success','Failed','Pending','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`paymentID`, `orderID`, `paymentDate`, `amount`, `method`, `status`) VALUES
(196758, 313451, '2025-10-12 00:28:47', 25.75, 'Cash', 'Pending'),
(563796, 991509, '2025-10-12 00:28:26', 52.73, 'Paypal', 'Success');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `serviceID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` int(11) NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`serviceID`, `name`, `description`, `price`, `duration`, `category`) VALUES
(1, 'Photography', 'Professional event photography', 500.00, 180, 'Media'),
(2, 'Catering', 'Full-course catering service', 1500.00, 240, 'Food'),
(3, 'DJ', 'Music DJ service', 300.00, 120, 'Entertainment'),
(4, 'Decoration', 'Event decoration setup', 700.00, 360, 'Decor'),
(5, 'Security', 'Event security personnel', 400.00, 480, 'Safety');

-- --------------------------------------------------------

--
-- Table structure for table `service_booking`
--

CREATE TABLE `service_booking` (
  `bookingID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `bookingDate` datetime NOT NULL DEFAULT current_timestamp(),
  `scheduledDate` datetime NOT NULL,
  `serviceID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_booking`
--

INSERT INTO `service_booking` (`bookingID`, `userID`, `eventID`, `bookingDate`, `scheduledDate`, `serviceID`) VALUES
(1, 1, 1, '2025-09-10 12:00:00', '2025-11-15 14:00:00', 1),
(2, 2, 2, '2025-09-11 13:00:00', '2025-12-20 19:00:00', 2),
(3, 3, 3, '2025-09-12 14:00:00', '2026-01-10 09:00:00', 3),
(4, 4, 4, '2025-09-13 15:00:00', '2025-10-25 18:30:00', 4),
(5, 5, 5, '2025-09-14 16:00:00', '2026-02-05 13:00:00', 5),
(295373, 8244413, 814770, '2025-10-07 01:14:57', '2025-10-09 01:14:00', 1),
(668218, 306064, 733464, '2025-10-08 18:36:26', '2025-10-10 18:36:00', 3),
(759246, 1, 628263, '2025-10-07 00:16:37', '2025-10-10 00:16:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `shop_item`
--

CREATE TABLE `shop_item` (
  `itemID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `image_url` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop_item`
--

INSERT INTO `shop_item` (`itemID`, `name`, `description`, `price`, `category`, `stock`, `image_url`) VALUES
(1, 'White floating candles', 'Dripless white floating candles for centerpieces.', 12.99, 'candles', 60, 'https://generalwaxcandlemaking.com/cdn/shop/articles/pillar-candles-of-different-sizes.jpg?v=1720422668'),
(2, 'Ivory pillar candles', 'Set of 3 ivory pillar candles for classic decor.', 16.99, 'candles', 80, 'https://generalwaxcandlemaking.com/cdn/shop/articles/pillar-candles-of-different-sizes.jpg?v=1720422668'),
(3, 'Tea light candles', 'Pack of 50 unscented tea lights for events.', 9.99, 'candles', 120, 'https://generalwaxcandlemaking.com/cdn/shop/articles/pillar-candles-of-different-sizes.jpg?v=1720422668'),
(4, 'Lavender scented candles', 'Lavender-scented candles for relaxed ambiance.', 14.50, 'candles', 45, 'https://generalwaxcandlemaking.com/cdn/shop/articles/pillar-candles-of-different-sizes.jpg?v=1720422668'),
(5, 'Glass candle holders', 'Clear glass holders suitable for tea lights.', 11.25, 'candles', 90, 'https://generalwaxcandlemaking.com/cdn/shop/articles/pillar-candles-of-different-sizes.jpg?v=1720422668'),
(6, 'Red rose bouquet', 'Artificial red rose bouquet for weddings.', 24.99, 'flowers', 70, 'https://cdn.shopify.com/s/files/1/0522/0021/0585/files/Rose.jpg?v=1743064665'),
(7, 'White rose centerpiece', 'White rose arrangement for tables.', 29.50, 'flowers', 40, 'https://cdn.shopify.com/s/files/1/0522/0021/0585/files/Rose.jpg?v=1743064665'),
(8, 'Sunflower bunch', 'Bright sunflower bunch for summer events.', 19.99, 'flowers', 50, 'https://cdn.shopify.com/s/files/1/0522/0021/0585/files/Rose.jpg?v=1743064665'),
(9, 'Orchid arrangement', 'Elegant orchid arrangement in a vase.', 32.99, 'flowers', 35, 'https://cdn.shopify.com/s/files/1/0522/0021/0585/files/Rose.jpg?v=1743064665'),
(10, 'Pink peony floral set', 'Pink peony stems for romantic decor.', 27.99, 'flowers', 55, 'https://cdn.shopify.com/s/files/1/0522/0021/0585/files/Rose.jpg?v=1743064665'),
(11, 'Gold table centerpiece stand', 'Metal stand for floral centerpieces.', 34.99, 'centerpieces', 30, 'https://www.marthastewart.com/thmb/bYQXclbIv2Kah5fUURir1geRanM=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/classic-center-pieces-emily-reiter-1218-bec6405e84394f778396f26cbd7ec90b.jpg'),
(12, 'Rustic wooden centerpiece box', 'Wooden box for rustic table setups.', 26.75, 'centerpieces', 25, 'https://www.marthastewart.com/thmb/bYQXclbIv2Kah5fUURir1geRanM=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/classic-center-pieces-emily-reiter-1218-bec6405e84394f778396f26cbd7ec90b.jpg'),
(13, 'Crystal vase centerpiece', 'Tall crystal vase for florals.', 49.99, 'centerpieces', 20, 'https://www.marthastewart.com/thmb/bYQXclbIv2Kah5fUURir1geRanM=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/classic-center-pieces-emily-reiter-1218-bec6405e84394f778396f26cbd7ec90b.jpg'),
(14, 'Mirror plate centerpiece base', 'Round mirror plate under arrangements.', 12.50, 'centerpieces', 80, 'https://www.marthastewart.com/thmb/bYQXclbIv2Kah5fUURir1geRanM=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/classic-center-pieces-emily-reiter-1218-bec6405e84394f778396f26cbd7ec90b.jpg'),
(15, 'Mason jar centerpiece', 'Mason jars for candles or flowers.', 15.99, 'centerpieces', 60, 'https://www.marthastewart.com/thmb/bYQXclbIv2Kah5fUURir1geRanM=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/classic-center-pieces-emily-reiter-1218-bec6405e84394f778396f26cbd7ec90b.jpg'),
(16, 'Warm white fairy lights', 'LED fairy lights for indoor/outdoor decor.', 18.99, 'lights', 100, 'https://i5.walmartimages.com/seo/Cotton-Ball-Fairy-Lights-3M-20-LED-Light-String-Portable-LED-Fairy-Lights-For-Indoor_bcc64f05-c2e6-4ce5-ad8a-3beb4b5a1c10.3c3945293f051754b1d1a4d41019ac23.jpeg'),
(17, 'LED curtain lights', 'Curtain-style string lights for backdrops.', 22.99, 'lights', 65, 'https://i5.walmartimages.com/seo/Cotton-Ball-Fairy-Lights-3M-20-LED-Light-String-Portable-LED-Fairy-Lights-For-Indoor_bcc64f05-c2e6-4ce5-ad8a-3beb4b5a1c10.3c3945293f051754b1d1a4d41019ac23.jpeg'),
(18, 'Hanging lantern lights', 'Rustic lantern LED lights.', 39.99, 'lights', 40, 'https://i5.walmartimages.com/seo/Cotton-Ball-Fairy-Lights-3M-20-LED-Light-String-Portable-LED-Fairy-Lights-For-Indoor_bcc64f05-c2e6-4ce5-ad8a-3beb4b5a1c10.3c3945293f051754b1d1a4d41019ac23.jpeg'),
(19, 'Neon party lights', 'Colorful neon LED strips for parties.', 45.00, 'lights', 30, 'https://i5.walmartimages.com/seo/Cotton-Ball-Fairy-Lights-3M-20-LED-Light-String-Portable-LED-Fairy-Lights-For-Indoor_bcc64f05-c2e6-4ce5-ad8a-3beb4b5a1c10.3c3945293f051754b1d1a4d41019ac23.jpeg'),
(20, 'Edison bulb string lights', 'Vintage Edison bulb string lights.', 29.99, 'lights', 55, 'https://i5.walmartimages.com/seo/Cotton-Ball-Fairy-Lights-3M-20-LED-Light-String-Portable-LED-Fairy-Lights-For-Indoor_bcc64f05-c2e6-4ce5-ad8a-3beb4b5a1c10.3c3945293f051754b1d1a4d41019ac23.jpeg'),
(21, 'Ivory backdrop curtain', 'Wrinkle-free ivory drape 10x12 ft.', 59.99, 'backdrops', 20, 'https://img.freepik.com/premium-photo/elegant-wedding-room-photography-backdrop-studio-backdrops-photographers_734511-10135.jpg'),
(22, 'Velvet backdrop', 'Soft velvet backdrop for luxury events.', 89.99, 'backdrops', 12, 'https://img.freepik.com/premium-photo/elegant-wedding-room-photography-backdrop-studio-backdrops-photographers_734511-10135.jpg'),
(23, 'Sequin backdrop', 'Shimmer sequin backdrop panel.', 75.00, 'backdrops', 18, 'https://img.freepik.com/premium-photo/elegant-wedding-room-photography-backdrop-studio-backdrops-photographers_734511-10135.jpg'),
(24, 'Floral arch backdrop', 'Large floral arch for ceremonies.', 120.00, 'backdrops', 8, 'https://img.freepik.com/premium-photo/elegant-wedding-room-photography-backdrop-studio-backdrops-photographers_734511-10135.jpg'),
(25, 'Greenery wall backdrop', 'Artificial greenery wall panel.', 95.00, 'backdrops', 10, 'https://img.freepik.com/premium-photo/elegant-wedding-room-photography-backdrop-studio-backdrops-photographers_734511-10135.jpg'),
(26, 'Balloon garland kit', 'Mixed color balloon garland for parties.', 22.50, 'decor', 70, 'https://mollyinmaine.com/wp-content/uploads/2023/03/Creative-Wall-Decor-Part-2-Non-Art-Displays-6-scaled.jpg'),
(27, 'Confetti balloons', 'Clear balloons with gold confetti.', 14.99, 'decor', 90, 'https://mollyinmaine.com/wp-content/uploads/2023/03/Creative-Wall-Decor-Part-2-Non-Art-Displays-6-scaled.jpg'),
(28, 'Table centerpiece decor set', 'Assorted decorative accents for tables.', 19.99, 'decor', 60, 'https://mollyinmaine.com/wp-content/uploads/2023/03/Creative-Wall-Decor-Part-2-Non-Art-Displays-6-scaled.jpg'),
(29, 'Wedding aisle decor', 'Floral aisle decorations for ceremonies.', 34.99, 'decor', 40, 'https://mollyinmaine.com/wp-content/uploads/2023/03/Creative-Wall-Decor-Part-2-Non-Art-Displays-6-scaled.jpg'),
(30, 'Backdrop accent decor', 'Greenery and frames to accent backdrops.', 27.99, 'decor', 35, 'https://mollyinmaine.com/wp-content/uploads/2023/03/Creative-Wall-Decor-Part-2-Non-Art-Displays-6-scaled.jpg'),
(31, 'Satin gold table runner', 'Satin gold table runner 12x108 in.', 11.99, 'linens', 80, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQVYtaMRnv9tJB5RqsnqeKpcF3s3aP3PUW1-w&s'),
(32, 'White stretch chair covers', 'White stretch chair covers.', 4.50, 'linens', 200, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQVYtaMRnv9tJB5RqsnqeKpcF3s3aP3PUW1-w&s'),
(33, 'Burgundy cloth napkins', 'Set of 12 burgundy cloth napkins.', 15.99, 'linens', 120, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQVYtaMRnv9tJB5RqsnqeKpcF3s3aP3PUW1-w&s'),
(34, 'Sequined tablecloth', 'Full-length sequined tablecloth for head tables.', 39.99, 'linens', 30, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQVYtaMRnv9tJB5RqsnqeKpcF3s3aP3PUW1-w&s'),
(35, 'Velvet napkin set', 'Luxurious velvet napkins set of 8.', 22.99, 'linens', 75, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQVYtaMRnv9tJB5RqsnqeKpcF3s3aP3PUW1-w&s'),
(36, 'Gold charger plates', 'Set of 8 gold charger plates.', 24.99, 'tableware', 60, 'https://m.media-amazon.com/images/I/71CF7zDwjlL._AC_SL1500_.jpg'),
(37, 'Crystal wine glasses', 'Set of 6 crystal wine glasses.', 34.99, 'tableware', 50, 'https://m.media-amazon.com/images/I/71CF7zDwjlL._AC_SL1500_.jpg'),
(38, 'Silver cutlery set', '24-piece stainless steel cutlery.', 29.50, 'tableware', 75, 'https://m.media-amazon.com/images/I/71CF7zDwjlL._AC_SL1500_.jpg'),
(39, 'White porcelain plates', 'Set of 12 dinner plates.', 39.99, 'tableware', 40, 'https://m.media-amazon.com/images/I/71CF7zDwjlL._AC_SL1500_.jpg'),
(40, 'Glass water carafe', 'Elegant glass carafe for tables.', 18.99, 'tableware', 65, 'https://m.media-amazon.com/images/I/71CF7zDwjlL._AC_SL1500_.jpg'),
(41, 'White aisle runner', 'White aisle runner for ceremonies.', 21.99, 'ceremony', 30, 'https://images.squarespace-cdn.com/content/v1/578537f5cd0f68f8a7411561/1569002290085-NDGE7SVHVLV3XL9VJ4S6/Hyatt+Regency+Scottsdale.jpg'),
(42, 'Flower girl basket', 'Wicker basket for petals.', 12.99, 'ceremony', 40, 'https://images.squarespace-cdn.com/content/v1/578537f5cd0f68f8a7411561/1569002290085-NDGE7SVHVLV3XL9VJ4S6/Hyatt+Regency+Scottsdale.jpg'),
(43, 'Satin ring pillow', 'Satin ring pillow for vows.', 10.99, 'ceremony', 35, 'https://images.squarespace-cdn.com/content/v1/578537f5cd0f68f8a7411561/1569002290085-NDGE7SVHVLV3XL9VJ4S6/Hyatt+Regency+Scottsdale.jpg'),
(44, 'Unity candle set', 'Two tapers and one pillar candle.', 19.99, 'ceremony', 25, 'https://images.squarespace-cdn.com/content/v1/578537f5cd0f68f8a7411561/1569002290085-NDGE7SVHVLV3XL9VJ4S6/Hyatt+Regency+Scottsdale.jpg'),
(45, 'Wooden welcome sign', 'Wooden welcome sign for entry.', 29.99, 'ceremony', 20, 'https://images.squarespace-cdn.com/content/v1/578537f5cd0f68f8a7411561/1569002290085-NDGE7SVHVLV3XL9VJ4S6/Hyatt+Regency+Scottsdale.jpg'),
(46, 'Gold frame table numbers', 'Framed table numbers set 1–20.', 34.99, 'signage', 15, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ1vQY1mipHso2Rb0TN45LWN0RQgCQbE9-0VQ&s'),
(47, 'Seating chart board', 'Large seating chart display board.', 44.99, 'signage', 10, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ1vQY1mipHso2Rb0TN45LWN0RQgCQbE9-0VQ&s'),
(48, 'Photo booth props', 'Assorted props for photo booth.', 16.99, 'entertainment', 40, 'https://etimg.etb2bimg.com/photo/81478822.cms'),
(49, 'Disposable cameras', 'Set of 5 disposable cameras for guests.', 35.00, 'entertainment', 25, 'https://etimg.etb2bimg.com/photo/81478822.cms'),
(50, 'Buffet catering set', 'Buffet table with assorted snacks.', 39.99, 'catering', 25, 'https://img.freepik.com/free-photo/buffet-table-with-snacks-from-burgers-cheeses-etc_140725-9343.jpg?semt=ais_hybrid&w=740&q=80');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staffID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_assignment`
--

CREATE TABLE `staff_assignment` (
  `assignmentID` int(11) NOT NULL,
  `bookingID` int(11) NOT NULL,
  `staffID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `securityque` varchar(200) NOT NULL,
  `securityans` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `name`, `username`, `email`, `phone`, `password`, `securityque`, `securityans`) VALUES
(1, 'Alice Johnson', 'alicej', 'alice@example.com', '123-456-7890', 'passAlice1', 'What is your pet’s name?', 'Fluffy'),
(2, 'Bob Smith', 'bobsmith', 'bob@example.com', '234-567-8901', 'passBob2', 'What is your mother’s maiden name?', 'Williams'),
(3, 'Charlie Brown', 'charlieb', 'charlie@example.com', '345-678-9012', 'passCharlie3', 'What is your favorite color?', 'Blue'),
(4, 'Diana Prince', 'dianap', 'diana@example.com', '456-789-0123', 'passDiana4', 'What city were you born in?', 'Metropolis'),
(5, 'Ethan Hunt', 'ethanh', 'ethan@example.com', '567-890-1234', 'passEthan5', 'What is your favorite food?', 'Pizza'),
(306064, 'abdullah', 'shihab', 'shihab@gmail.com', '123456789', '1111', 'What is my Hall room no?', '511'),
(708945, 'Sarwad Hasan', 'sarwad', 'sarwad@gmail.com', '0123456789', '2107006', 'What is my college name?', 'NDC'),
(1712189, 'Maruf Shafiq', 'maruf', 'maruf@gmail.com', '0123456789', '2107010', 'What is my Hall room no?', '416E'),
(2522279, 'Maruf Shafiq', 'maruf', 'maruf@gmail.com', '0123456789', '2107010', 'What is my Hall room no?', '416E'),
(3561221, 'Al Naheean Zarif', 'zarif_cse', 'zarif@gmail.com', '0123456789', '2107098', 'What computer do I use?', 'MacBook'),
(4989657, 'Rysul Nirob', 'nirob', 'nirob@gmail.com', '0123456789', '1234', 'What is your pet name?', 'Fluffy'),
(8244413, 'Rubayet Nabil', 'rubayet', 'nabil@gmail.com', '0123456789', '2107073', 'What is my hobby?', 'Sleeping');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`eventID`),
  ADD KEY `event_user_fk` (`userID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `order_user_fk` (`userID`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`orderItemID`),
  ADD KEY `orderItem_order_fk` (`orderID`),
  ADD KEY `orderItem_item_fk` (`itemID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentID`),
  ADD KEY `payment_order_fk` (`orderID`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`serviceID`);

--
-- Indexes for table `service_booking`
--
ALTER TABLE `service_booking`
  ADD PRIMARY KEY (`bookingID`),
  ADD KEY `booking_user_fk` (`userID`),
  ADD KEY `booking_service_fk` (`serviceID`),
  ADD KEY `booking_event_fk` (`eventID`);

--
-- Indexes for table `shop_item`
--
ALTER TABLE `shop_item`
  ADD PRIMARY KEY (`itemID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staffID`);

--
-- Indexes for table `staff_assignment`
--
ALTER TABLE `staff_assignment`
  ADD PRIMARY KEY (`assignmentID`),
  ADD KEY `assignment_booking_fk` (`bookingID`),
  ADD KEY `assignment_staff_fk` (`staffID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_user_fk` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `order_user_fk` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `orderItem_item_fk` FOREIGN KEY (`itemID`) REFERENCES `shop_item` (`itemID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `orderItem_order_fk` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_order_fk` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `service_booking`
--
ALTER TABLE `service_booking`
  ADD CONSTRAINT `booking_event_fk` FOREIGN KEY (`eventID`) REFERENCES `event` (`eventID`),
  ADD CONSTRAINT `booking_service_fk` FOREIGN KEY (`serviceID`) REFERENCES `service` (`serviceID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_user_fk` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `staff_assignment`
--
ALTER TABLE `staff_assignment`
  ADD CONSTRAINT `assignment_booking_fk` FOREIGN KEY (`bookingID`) REFERENCES `service_booking` (`bookingID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `assignment_staff_fk` FOREIGN KEY (`staffID`) REFERENCES `staff` (`staffID`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
