-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2026 at 12:55 AM
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
-- Database: `zoowonderland2`
--
CREATE DATABASE IF NOT EXISTS `zoowonderland` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `zoowonderland`;

-- --------------------------------------------------------

--
-- Table structure for table `administradores`
--

CREATE TABLE IF NOT EXISTS `administradores` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  KEY `idx_admin_usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `administradores`
--

INSERT INTO `administradores` (`id_admin`, `id_usuario`, `estado`, `fecha_registro`) VALUES
(1, 1, 1, '2026-03-06 23:51:38');

-- --------------------------------------------------------

--
-- Table structure for table `animales`
--

CREATE TABLE IF NOT EXISTS `animales` (
  `id_animal` int(11) NOT NULL AUTO_INCREMENT,
  `especie` varchar(100) NOT NULL,
  `nombre_comun` varchar(100) DEFAULT NULL,
  `habitat` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `foto` varchar(800) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `id_area` int(11) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_animal`),
  KEY `idx_animal_area` (`id_area`),
  KEY `idx_especie` (`especie`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `animales`
--

INSERT INTO `animales` (`id_animal`, `especie`, `nombre_comun`, `habitat`, `descripcion`, `foto`, `estado`, `id_area`, `fecha_registro`) VALUES
(1, 'Jaguar leo', 'Jaguar', 'F-01', 'Felino grande', 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxMTEhUTExMWFRUXGBcaFxYYFxgaFxcVGBcXFxUXGBUYHSggGB0lHhcXITEhJSkrLi4uFx8zODMsNygtLisBCgoKDg0OGxAQGi0lICUtLS0tLS0tLS0tLy0tLS0tLSstLS0tLS0tLS0tLS0tLS0uLS0tLS0tLS0tLS0tLS0tLf/AABEIALcBEwMBIgACEQEDEQH/xAAcAAACAgMBAQAAAAAAAAAAAAAEBQMGAAECBwj/xABBEAACAQIFAgQEBAUBBgUFAAABAgMAEQQFEiExQVEGEyJhMnGBkSNCobEHFFJiwfAzQ3Ki0eEWU4KS8RUkNGOy/8QAGQEAAwEBAQAAAAAAAAAAAAAAAgMEAQAF/8QAKxEAAgICAgECBQMFAAAAAAAAAAECEQMhEjFBBFETIjJhcYHR8DNCkaGx/9oADAMBAAIRAxEAPwDy+OG1NcqxIQ2PB/erxJ/D1AP9o36VWc58OPAe69/+tTNsnckwtccLUozXEaqmweWyP8IqDMcvkj+IUtyYuPYhl5rcIrbrvU0CUyX0lAZCNq5mqSPioZzUK+oEgLVDI1bc0Tk0QfERKeDIn7jp1qmETiDCnemCmrF438NvDiHkA/DbfVpNlHdrdB3pachxATzAgeO19aOjrbv6WJ+4p7jQzi0Qw4ZiLgG1R6bGnWUzBFOoE8cAk3OwFu9T51k7KUk0MdZW8aAllB2GprEISTxZqGSSrZkcc59Ir5raYV3IRFZmP', 'Activo', 3, '2026-03-06 23:52:10'),
(2, 'Juguar tigris', 'Jaguar', 'F-01', 'Felino rayado', 'https://savingtheamazon.org/cdn/shop/articles/jaguar.jpg?v=1615042123&width=1100', 'Activo', 3, '2026-03-06 23:52:10'),
(3, 'Jaguar onca', 'Jaguar', 'F-01', 'Felino americano', 'https://www.greenforestecolodge.com/archivos/blogs/Foto-1-1.jpg', 'Activo', 3, '2026-03-06 23:52:10'),
(4, 'Tremarctos ornatus', 'Oso Andino', 'Montaña', 'Oso sudamericano', 'https://animalesdelperu.com/wp-content/uploads/2018/11/oso-andino.jpg.webp', 'Activo', 4, '2026-03-06 23:52:10'),
(5, 'Ursus arctos', 'Oso Pardo', 'Bosque', 'Oso europeo', 'https://files.worldwildlife.org/wwfcmsprod/images/Brown_bear_and_three_cubs/story_full_width/27ymkd7you_brown_bear_threatsHI_202779.jpg', 'Activo', 4, '2026-03-06 23:52:10'),
(6, 'Ursus maritimus', 'Oso Andino', 'Motaña', 'Oso albino', 'https://fotografias-2.larazon.es/clipping/cmsimages01/2023/09/06/B3B2680F-CE0D-4A6E-83C7-1C2C51C7EAF4/unico-oso-panda-albino-mundo-china_97.jpg?crop=1120,630,x41,y0&width=1600&height=900&optimize=low&format=webply', 'Observación', 4, '2026-03-06 23:52:10'),
(7, 'Vultur gryphus', 'Cóndor', 'Montaña', 'Ave andina', 'https://lh3.googleusercontent.com/gps-cs-s/AHVAweqWZ2hQRPfxVkcIvIHWgyIUW_IaoAzAw65Ii3gFySumgGpkUU0nqH7tcIwnIkwaeadTNeNPf-U0R9YYbZ_rpwMAGwVksVs6puNGEOHnq_44tZBhR2ua0d7MDjqmkqMzKgotBoCpyg=w243-h244-n-k-no-nu', 'Activo', 5, '2026-03-06 23:52:10'),
(8, 'Ara macao', 'Guacamayo', 'Selva', 'Ave colorida', 'https://pastaza.travel/wp-content/uploads/2021/12/guacamayo_rojo_ubicacion.jpg', 'Activo', 5, '2026-03-06 23:52:10'),
(9, 'Pavo cristatus', 'Pavo Real', 'Bosque', 'Ave ornamental', 'https://www.hola.com/horizon/square/b76aaaa94a0a-curiosidades-pavo-real-t.jpg?im=Resize=(960),type=downsize', 'Activo', 5, '2026-03-06 23:52:10'),
(10, 'Carassius auratus', 'Pez Dorado', 'Agua dulce', 'Pez ornamental', 'https://static.bainet.es/clip/3fdf0eb3-8208-4fc8-924b-9582357b12a1_source-aspect-ratio_1600w_0.webp', 'Activo', 6, '2026-03-06 23:52:10'),
(11, 'Austrolebias', 'Pez', 'Marino', 'Pez coral', 'https://nas.er.usgs.gov/XIMAGESERVERX/2011/20111221120420.jpg', 'Activo', 6, '2026-03-06 23:52:10'),
(13, 'Crocodylus acutus', 'Cocodrilo', 'Pantano', 'Reptil grande', 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjmhPuWkun4iJUciLNvHzDuQre1pG7KYUSQIXJEnu3E_5QUknWPRunL_SCXdBU0zCan8MwPUx2GCZV33eGWdy_OfBuR5vL2mCWUl1aj-0jq9amN11Fms7iBezMABOhwL8u35SzbYoGBq1Xr/s320/DSCN6068.JPG', 'Activo', 7, '2026-03-06 23:52:10'),
(14, 'Boa constrictor', 'Boa', 'Selva', 'Serpiente', 'https://cdn.img.noticiaslatam.lat/img/07e6/0c/06/1133241577_0:113:1200:788_1920x0_80_0_0_f22339ed2e2a825d06ffaccbcc78f375.jpg.webp', 'Activo', 7, '2026-03-06 23:52:10'),
(15, 'Iguana iguana', 'Iguana Verde', 'Selva', 'Reptil arbóreo', 'https://delamazonas.com/wp-content/uploads/2019/11/Iguana_iguana_colombia2.jpg', 'Activo', 7, '2026-03-06 23:52:10'),
(16, 'Tapirus terrestris', 'Tapir', 'Bosque', 'Mamífero grande', 'https://server-bucket-2022.s3.amazonaws.com/travelapp/d219bb24c2b3eee49947b9d935288164_1706184387718.jpeg', 'Activo', 9, '2026-03-06 23:52:10'),
(17, 'Ateles belzebuth', 'Mono Araña', 'Selva', 'Primates', 'https://namubak.com/cdn/shop/articles/especies-de-monos-de-costa-rica-monkeys-of-costa-rica-namubak1_884abed1-f7b0-4831-8209-8931b7737d39.jpg?v=1749690022', 'Activo', 9, '2026-03-06 23:52:10'),
(18, 'Zorro concolor', 'Zorro', 'Montaña', 'Felino', 'https://www.actualidadambiental.pe/wp-content/uploads/2025/03/serforzorro.jpg', 'Activo', 9, '2026-03-06 23:52:10'),
(19, 'Equus ferus', 'Caballo', 'Pradera', 'Equino', 'https://thumbs.dreamstime.com/b/galope-de-caballo-en-la-pradera-verde-primavera-piel-gallina-correr-galopar-251976354.jpg?w=992', 'Activo', 1, '2026-03-06 23:52:10'),
(20, 'Capibara capibara', 'Capibara', 'Jungla', 'Resistente', 'https://images.rove.me/w_1920,q_85/p4wsut5olvxg8op3zfmb/bolivia-capybara.jpg', 'Activo', 1, '2026-03-06 23:52:10'),
(21, 'Lama glama', 'Llama', 'Altiplano', 'Camélido', 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Llama_de_Bolivia_%28pixinn.net%29.jpg/500px-Llama_de_Bolivia_%28pixinn.net%29.jpg', 'Activo', 2, '2026-03-06 23:52:10'),
(22, 'Capra aegagrus', 'Cabra', 'Montaña', 'Animal doméstico', 'https://www.wwwrutapatagonia.chileturistic.com/configurador/Parques/Fauna_2832020154847.jpg', 'Activo', 2, '2026-03-06 23:52:10'),
(23, 'Oveja Oveja', 'Ovejas', 'Pradera', 'Bovino', 'https://lh3.googleusercontent.com/gps-cs-s/AHVAweqmFkJjcSCiJVHwyr3Z4A63lBL6CyX0h3ZAyJiJrSAwiFujE5lfcelEcPA1-wff5zFQ5Ywa1P8z4cVbx2xRqp0vfQVLLZnMOlDjM-SLbE0RAzwhL1cf3PbYxuW_anQnR1ryG6nW5Q=w243-h406-n-k-no-nu', 'Activo', 2, '2026-03-06 23:52:10'),
(24, 'Sus scrofa', 'Cerdo', 'Granja', 'Mamífero', 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjeMYf9XblY5Gy20hx5G9LSLfhJccESSVl1L-JKsbtfLH6tubMN4aORxftL6cNBxd02ahInHjbRFhRSNa6e6pKoeOeWzDPWhhWMiom3YHKTLiOEE9hL7nsbGjmf5i42B0isCdtGJGTRUbI/s1600/Productores+de+carne+de+cerdo+abandonan+el+rubro.jpg', 'Activo', 2, '2026-03-06 23:52:10'),
(25, 'Lemur catta', 'Lémur', 'Bosque', 'Primate', 'http://mongabay.s3.amazonaws.com/madagascar/600/madagascar_0591.jpg', 'Activo', 8, '2026-03-06 23:52:10'),
(26, 'Papio anubis', 'Babuino', 'Sabana', 'Primate', 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgabk_XGCci2zfKkNzZ7F55FYwDbn8z83dPBERCW2J-tyv6BITfkdAotCd-l8vLBEDVyawtMWaTSAzwtjVBh5sSz1WTyHed_HgqI61WqYLCarxWPAfLW0vrk1f9jui06VVrwsEhgV_qO68/s640/baboon.jpg', 'Activo', 8, '2026-03-06 23:52:10'),
(27, 'Vicugna vicugna', 'Vicuña', 'Altiplano', 'Camélido', 'https://lh3.googleusercontent.com/gps-cs-s/AHVAwep93BTKq4vGmeVSCXg5JZj2mQ_yxGEU4HQDqySDjtSNbknqO1xGjISr5HbdzG6E-4Ipck0GHCwf-2S7BLFwEg0WCdSD5lpWx6t0ZjuxNNjVyLruU_gPGlScEtjSbUMnlaZ1XOVF=s680-w680-h510-rw', 'Activo', 10, '2026-03-06 23:52:10'),
(28, 'Odocoileus virginianus', 'Venado', 'Bosque', 'Cérvido', 'https://www.opinion.com.bo/asset/thumbnail,992,558,center,center/media/opinion/images/2018/07/13/2018N260409.jpg', 'Activo', 10, '2026-03-06 23:52:10'),
(29, 'Nasua nasua', 'Coatí', 'Bosque', 'Omnívoro', 'https://misanimales.com/wp-content/uploads/2017/12/coati-300x169.jpg?auto=webp&quality=7500&width=640&crop=16:9,smart,safe&format=webp&optimize=medium&dpr=2&fit=cover&fm=webp&q=75&w=640&h=360', 'Activo', 10, '2026-03-06 23:52:10');

-- --------------------------------------------------------

--
-- Table structure for table `api_token`
--

CREATE TABLE IF NOT EXISTS `api_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expire_at` datetime NOT NULL,
  `last_used` datetime DEFAULT NULL,
  `ip_origen` varchar(45) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `idx_token` (`token`),
  KEY `idx_usuario` (`id_usuario`),
  KEY `idx_expires` (`expire_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE IF NOT EXISTS `areas` (
  `id_area` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `restringida` tinyint(1) DEFAULT 0,
  `descripcion` text DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_area`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id_area`, `nombre`, `restringida`, `descripcion`, `estado`, `fecha_registro`) VALUES
(1, 'Zona General Norte', 0, 'Área principal del zoológico', 1, '2026-03-06 23:51:51'),
(2, 'Zona General Sur', 0, 'Área recreativa', 1, '2026-03-06 23:51:51'),
(3, 'Área de Felinos', 1, 'Zona de grandes felinos', 1, '2026-03-06 23:51:51'),
(4, 'Área de Osos', 1, 'Zona de osos andinos', 1, '2026-03-06 23:51:51'),
(5, 'Área de Aves', 0, 'Zona de aves exóticas', 1, '2026-03-06 23:51:51'),
(6, 'Acuario Central', 1, 'Peces y especies marinas', 1, '2026-03-06 23:51:51'),
(7, 'Área Reptiles', 0, 'Zona de reptiles', 1, '2026-03-06 23:51:51'),
(8, 'Zona Infantil', 0, 'Área educativa', 1, '2026-03-06 23:51:51'),
(9, 'Área Conservación', 1, 'Investigación animal', 1, '2026-03-06 23:51:51'),
(10, 'Zona Interactiva', 1, 'Contacto supervisado', 1, '2026-03-06 23:51:51');

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nit` varchar(30) DEFAULT NULL,
  `tipo_cuenta` varchar(50) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  KEY `idx_cliente_usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nit`, `tipo_cuenta`, `id_usuario`, `estado`, `fecha_registro`) VALUES
(1, '123456701', 'Normal', 7, 1, '2026-03-06 23:49:28'),
(2, '123456702', 'Normal', 8, 1, '2026-03-06 23:49:28'),
(3, '123456703', 'Normal', 9, 1, '2026-03-06 23:49:28'),
(4, '123456704', 'Normal', 10, 1, '2026-03-06 23:49:28'),
(5, '123456705', 'Normal', 11, 1, '2026-03-06 23:49:28');

-- --------------------------------------------------------

--
-- Table structure for table `compras`
--

CREATE TABLE IF NOT EXISTS `compras` (
  `id_compra` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `estado_pago` tinyint(1) DEFAULT 0,
  `id_cliente` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_compra`),
  KEY `idx_compra_cliente` (`id_cliente`),
  KEY `idx_compra_fecha` (`fecha`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `compras`
--

INSERT INTO `compras` (`id_compra`, `fecha`, `hora`, `monto`, `estado_pago`, `id_cliente`, `estado`, `fecha_registro`) VALUES
(1, '2026-01-05', '09:30:00', 65.00, 1, 1, 1, '2026-03-06 23:52:53'),
(2, '2026-01-06', '10:15:00', 15.00, 1, 2, 1, '2026-03-06 23:52:53'),
(3, '2026-01-07', '11:00:00', 110.00, 1, 3, 1, '2026-03-06 23:52:53'),
(4, '2026-01-08', '12:30:00', 15.00, 1, 4, 1, '2026-03-06 23:52:53'),
(5, '2026-01-09', '14:00:00', 150.00, 1, 5, 1, '2026-03-06 23:52:53'),
(6, '2026-01-10', '09:45:00', 30.00, 1, 1, 1, '2026-03-06 23:52:53'),
(7, '2026-01-11', '10:20:00', 50.00, 1, 2, 1, '2026-03-06 23:52:53'),
(8, '2026-01-12', '11:10:00', 15.00, 1, 3, 1, '2026-03-06 23:52:53'),
(9, '2026-01-13', '12:40:00', 80.00, 1, 4, 1, '2026-03-06 23:52:53'),
(10, '2026-01-14', '13:30:00', 20.00, 1, 5, 1, '2026-03-06 23:52:53'),
(11, '2026-01-15', '09:50:00', 60.00, 1, 1, 1, '2026-03-06 23:52:53'),
(12, '2026-01-16', '10:45:00', 45.00, 1, 2, 1, '2026-03-06 23:52:53'),
(13, '2026-01-17', '11:30:00', 20.00, 1, 3, 1, '2026-03-06 23:52:53'),
(14, '2026-01-18', '12:00:00', 55.00, 1, 4, 1, '2026-03-06 23:52:53'),
(15, '2026-01-19', '14:10:00', 90.00, 1, 5, 1, '2026-03-06 23:52:53'),
(16, '2026-01-20', '09:15:00', 15.00, 1, 1, 1, '2026-03-06 23:52:53'),
(17, '2026-01-21', '10:40:00', 50.00, 1, 2, 1, '2026-03-06 23:52:53'),
(18, '2026-01-22', '11:20:00', 45.00, 1, 3, 1, '2026-03-06 23:52:53'),
(19, '2026-01-23', '13:00:00', 30.00, 1, 4, 1, '2026-03-06 23:52:53'),
(20, '2026-01-24', '14:30:00', 60.00, 1, 5, 1, '2026-03-06 23:52:53');

-- --------------------------------------------------------

--
-- Table structure for table `detalle_compra`
--

CREATE TABLE IF NOT EXISTS `detalle_compra` (
  `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `id_compra` int(11) NOT NULL,
  `id_ticket` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_detalle`),
  KEY `idx_detalle_compra` (`id_compra`),
  KEY `idx_detalle_ticket` (`id_ticket`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detalle_compra`
--

INSERT INTO `detalle_compra` (`id_detalle`, `id_compra`, `id_ticket`, `precio_unitario`, `cantidad`, `estado`, `fecha_registro`) VALUES
(1, 1, 1, 15.00, 1, 1, '2026-03-06 23:53:21'),
(2, 1, 2, 50.00, 1, 1, '2026-03-06 23:53:21'),
(3, 2, 3, 15.00, 1, 1, '2026-03-06 23:53:21'),
(4, 3, 4, 50.00, 1, 1, '2026-03-06 23:53:21'),
(5, 3, 5, 45.00, 1, 1, '2026-03-06 23:53:21'),
(6, 4, 6, 15.00, 1, 1, '2026-03-06 23:53:21'),
(7, 5, 7, 50.00, 1, 1, '2026-03-06 23:53:21'),
(8, 5, 8, 50.00, 1, 1, '2026-03-06 23:53:21'),
(9, 5, 9, 50.00, 1, 1, '2026-03-06 23:53:21'),
(10, 6, 10, 30.00, 1, 1, '2026-03-06 23:53:21'),
(11, 7, 11, 50.00, 1, 1, '2026-03-06 23:53:21'),
(12, 8, 12, 15.00, 1, 1, '2026-03-06 23:53:21'),
(13, 9, 13, 40.00, 1, 1, '2026-03-06 23:53:21'),
(14, 9, 14, 20.00, 1, 1, '2026-03-06 23:53:21'),
(15, 10, 15, 20.00, 1, 1, '2026-03-06 23:53:21'),
(16, 11, 16, 30.00, 1, 1, '2026-03-06 23:53:21'),
(17, 11, 17, 30.00, 1, 1, '2026-03-06 23:53:21'),
(18, 12, 18, 45.00, 1, 1, '2026-03-06 23:53:21'),
(19, 13, 19, 20.00, 1, 1, '2026-03-06 23:53:21'),
(20, 14, 20, 15.00, 1, 1, '2026-03-06 23:53:21'),
(21, 14, 21, 45.00, 1, 1, '2026-03-06 23:53:21'),
(22, 15, 22, 50.00, 1, 1, '2026-03-06 23:53:21'),
(23, 15, 23, 30.00, 1, 1, '2026-03-06 23:53:21'),
(24, 16, 24, 15.00, 1, 1, '2026-03-06 23:53:21'),
(25, 17, 25, 50.00, 1, 1, '2026-03-06 23:53:21'),
(26, 18, 26, 45.00, 1, 1, '2026-03-06 23:53:21'),
(27, 19, 27, 30.00, 1, 1, '2026-03-06 23:53:21'),
(28, 20, 28, 50.00, 1, 1, '2026-03-06 23:53:21'),
(29, 20, 29, 15.00, 1, 1, '2026-03-06 23:53:21');

-- --------------------------------------------------------

--
-- Table structure for table `eventos`
--

CREATE TABLE IF NOT EXISTS `eventos` (
  `id_evento` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_evento` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `tiene_costo` tinyint(1) DEFAULT 0,
  `precio` decimal(8,2) DEFAULT 0.00,
  `encargado_id` int(11) DEFAULT NULL,
  `lugar` varchar(150) DEFAULT NULL,
  `limite_participantes` int(11) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_evento`),
  KEY `fk_evento_guia` (`encargado_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evento_actividades`
--

CREATE TABLE IF NOT EXISTS `evento_actividades` (
  `id_actividad` int(11) NOT NULL AUTO_INCREMENT,
  `evento_id` int(11) NOT NULL,
  `nombre_actividad` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_actividad`),
  KEY `fk_evento_actividad` (`evento_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guias`
--

CREATE TABLE IF NOT EXISTS `guias` (
  `id_guia` int(11) NOT NULL AUTO_INCREMENT,
  `horarios` text DEFAULT NULL,
  `dias_trabajo` text DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_guia`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  KEY `idx_guia_usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guias`
--

INSERT INTO `guias` (`id_guia`, `horarios`, `dias_trabajo`, `id_usuario`, `estado`, `fecha_registro`) VALUES
(1, '09:00 - 15:00', 'Martes a Domingo', 2, 1, '2026-03-06 23:49:28'),
(2, '09:00 - 15:00', 'Martes a Domingo', 3, 1, '2026-03-06 23:49:28'),
(3, '09:00 - 15:00', 'Martes a Domingo', 4, 1, '2026-03-06 23:49:28'),
(4, '09:00 - 15:00', 'Martes a Domingo', 5, 1, '2026-03-06 23:49:28'),
(5, '09:00 - 15:00', 'Martes a Domingo', 6, 1, '2026-03-06 23:49:28');

-- --------------------------------------------------------

--
-- Table structure for table `guia_recorrido`
--

CREATE TABLE IF NOT EXISTS `guia_recorrido` (
  `id_guia_recorrido` int(11) NOT NULL AUTO_INCREMENT,
  `id_guia` int(11) NOT NULL,
  `id_recorrido` int(11) NOT NULL,
  `fecha_asignacion` date NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_guia_recorrido`),
  UNIQUE KEY `id_guia` (`id_guia`,`id_recorrido`),
  KEY `idx_gr_guia` (`id_guia`),
  KEY `idx_gr_recorrido` (`id_recorrido`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guia_recorrido`
--

INSERT INTO `guia_recorrido` (`id_guia_recorrido`, `id_guia`, `id_recorrido`, `fecha_asignacion`, `estado`, `fecha_registro`) VALUES
(1, 1, 2, '2026-03-05', 1, '2026-03-06 23:52:42'),
(2, 1, 3, '2026-03-04', 1, '2026-03-06 23:52:42'),
(3, 2, 4, '2026-01-03', 1, '2026-03-06 23:52:42'),
(4, 2, 5, '2026-01-04', 1, '2026-03-06 23:52:42'),
(5, 3, 1, '2026-01-05', 1, '2026-03-06 23:52:42'),
(6, 3, 6, '2026-01-06', 1, '2026-03-06 23:52:42'),
(7, 4, 2, '2026-01-07', 1, '2026-03-06 23:52:42'),
(8, 4, 4, '2026-01-08', 1, '2026-03-06 23:52:42'),
(9, 5, 3, '2026-01-09', 1, '2026-03-06 23:52:42'),
(10, 5, 6, '2026-01-10', 1, '2026-03-06 23:52:42');

-- --------------------------------------------------------

--
-- Table structure for table `recorridos`
--

CREATE TABLE IF NOT EXISTS `recorridos` (
  `id_recorrido` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `duracion` int(11) DEFAULT NULL,
  `capacidad` int(11) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_recorrido`),
  KEY `idx_recorrido_nombre` (`nombre`),
  KEY `idx_recorrido_tipo` (`tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recorridos`
--

INSERT INTO `recorridos` (`id_recorrido`, `nombre`, `tipo`, `precio`, `duracion`, `capacidad`, `estado`, `fecha_registro`) VALUES
(1, 'Recorrido General', 'No Guiado', 15.00, 60, 50, 1, '2026-03-06 23:52:20'),
(2, 'Felinos VIP', 'Guiado', 50.00, 90, 50, 1, '2026-03-06 23:52:20'),
(3, 'Osos Andinos', 'Guiado', 45.00, 80, 50, 1, '2026-03-06 23:52:20'),
(4, 'Cóndores', 'Guiado', 40.00, 70, 50, 1, '2026-03-06 23:52:20'),
(5, 'Acuario', 'No Guiado', 20.00, 50, 50, 1, '2026-03-06 23:52:20'),
(6, 'Recorrido Interactivo', 'Guiado', 30.00, 75, 50, 1, '2026-03-06 23:52:20');

-- --------------------------------------------------------

--
-- Table structure for table `recorrido_area`
--

CREATE TABLE IF NOT EXISTS `recorrido_area` (
  `id_recorrido_area` int(11) NOT NULL AUTO_INCREMENT,
  `id_recorrido` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_recorrido_area`),
  UNIQUE KEY `id_recorrido` (`id_recorrido`,`id_area`),
  KEY `idx_ra_recorrido` (`id_recorrido`),
  KEY `idx_ra_area` (`id_area`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recorrido_area`
--

INSERT INTO `recorrido_area` (`id_recorrido_area`, `id_recorrido`, `id_area`, `estado`, `fecha_registro`) VALUES
(1, 1, 1, 1, '2026-03-06 23:52:30'),
(2, 1, 2, 1, '2026-03-06 23:52:30'),
(3, 1, 5, 1, '2026-03-06 23:52:30'),
(4, 1, 8, 1, '2026-03-06 23:52:30'),
(5, 1, 10, 1, '2026-03-06 23:52:30'),
(6, 2, 3, 1, '2026-03-06 23:52:30'),
(7, 2, 9, 1, '2026-03-06 23:52:30'),
(8, 3, 4, 1, '2026-03-06 23:52:30'),
(9, 3, 9, 1, '2026-03-06 23:52:30'),
(10, 4, 5, 1, '2026-03-06 23:52:30'),
(11, 5, 6, 1, '2026-03-06 23:52:30'),
(12, 6, 8, 1, '2026-03-06 23:52:30'),
(13, 6, 10, 1, '2026-03-06 23:52:30'),
(14, 6, 1, 1, '2026-03-06 23:52:30');

-- --------------------------------------------------------

--
-- Table structure for table `reportes`
--

CREATE TABLE IF NOT EXISTS `reportes` (
  `id_reporte` int(11) NOT NULL AUTO_INCREMENT,
  `id_guia_recorrido` int(11) NOT NULL,
  `observaciones` text NOT NULL,
  `fecha_reporte` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_reporte`),
  UNIQUE KEY `idx_gr_unico` (`id_guia_recorrido`),
  KEY `idx_reporte_gr` (`id_guia_recorrido`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reportes`
--

INSERT INTO `reportes` (`id_reporte`, `id_guia_recorrido`, `observaciones`, `fecha_reporte`, `estado`, `fecha_registro`) VALUES
(1, 1, 'No hubo casualidades', '2026-03-03 19:03:53', 1, '2026-03-06 23:53:21');

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE IF NOT EXISTS `reservas` (
  `id_reserva` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `cupos` int(11) NOT NULL,
  `institucion` varchar(100) DEFAULT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  `estado_pago` tinyint(1) DEFAULT 0,
  `estado` tinyint(1) DEFAULT 1,
  `id_cliente` int(11) NOT NULL,
  `id_recorrido` int(11) NOT NULL,
  PRIMARY KEY (`id_reserva`),
  KEY `idx_reserva_cliente` (`id_cliente`),
  KEY `idx_reserva_recorrido` (`id_recorrido`),
  KEY `idx_reserva_fecha` (`fecha`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `fecha`, `hora`, `cupos`, `institucion`, `comentario`, `estado_pago`, `estado`, `id_cliente`, `id_recorrido`) VALUES
(21, '2026-02-01', '09:00:00', 30, 'Colegio San Martín', 'Visita escolar', 1, 1, 1, 1),
(22, '2026-02-03', '10:00:00', 25, 'UE Bolívar', 'Tour educativo', 1, 1, 1, 2),
(23, '2026-02-05', '11:00:00', 40, 'Instituto Andes', 'Biología', 1, 1, 4, 3),
(24, '2026-02-07', '09:30:00', 20, 'Colegio San José', 'Excursión', 1, 1, 4, 4),
(25, '2026-02-09', '14:00:00', 35, 'UMSA', 'Investigación', 1, 1, 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_rol`),
  UNIQUE KEY `nombre_rol` (`nombre_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion`, `estado`, `fecha_registro`) VALUES
(1, 'administrador', 'Administrador del sistema', 1, '2026-03-06 23:50:05'),
(2, 'guia', 'Guía del zoológico', 1, '2026-03-06 23:50:05'),
(3, 'cliente', 'Cliente visitante', 1, '2026-03-06 23:50:05');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE IF NOT EXISTS `tickets` (
  `id_ticket` int(11) NOT NULL AUTO_INCREMENT,
  `hora` time NOT NULL,
  `fecha` date NOT NULL,
  `codigo_qr` varchar(255) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_recorrido` int(11) NOT NULL,
  PRIMARY KEY (`id_ticket`),
  UNIQUE KEY `codigo_qr` (`codigo_qr`),
  KEY `idx_ticket_compra` (`id_compra`),
  KEY `idx_ticket_recorrido` (`id_recorrido`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id_ticket`, `hora`, `fecha`, `codigo_qr`, `id_compra`, `id_recorrido`) VALUES
(1, '09:30:00', '2026-01-05', 'QR001', 1, 1),
(2, '09:30:00', '2026-01-05', 'QR002', 1, 2),
(3, '10:15:00', '2026-01-06', 'QR003', 2, 1),
(4, '11:00:00', '2026-01-07', 'QR004', 3, 2),
(5, '11:00:00', '2026-01-07', 'QR005', 3, 3),
(6, '12:30:00', '2026-01-08', 'QR006', 4, 1),
(7, '14:00:00', '2026-01-09', 'QR007', 5, 2),
(8, '14:00:00', '2026-01-09', 'QR008', 5, 2),
(9, '14:00:00', '2026-01-09', 'QR009', 5, 2),
(10, '09:45:00', '2026-01-10', 'QR010', 6, 6),
(11, '10:20:00', '2026-01-11', 'QR011', 7, 2),
(12, '11:10:00', '2026-01-12', 'QR012', 8, 1),
(13, '12:40:00', '2026-01-13', 'QR013', 9, 4),
(14, '12:40:00', '2026-01-13', 'QR014', 9, 5),
(15, '13:30:00', '2026-01-14', 'QR015', 10, 5),
(16, '09:50:00', '2026-01-15', 'QR016', 11, 6),
(17, '09:50:00', '2026-01-15', 'QR017', 11, 6),
(18, '10:45:00', '2026-01-16', 'QR018', 12, 3),
(19, '11:30:00', '2026-01-17', 'QR019', 13, 5),
(20, '12:00:00', '2026-01-18', 'QR020', 14, 1),
(21, '12:00:00', '2026-01-18', 'QR021', 14, 3),
(22, '14:10:00', '2026-01-19', 'QR022', 15, 2),
(23, '14:10:00', '2026-01-19', 'QR023', 15, 6),
(24, '09:15:00', '2026-01-20', 'QR024', 16, 1),
(25, '10:40:00', '2026-01-21', 'QR025', 17, 2),
(26, '11:20:00', '2026-01-22', 'QR026', 18, 3),
(27, '13:00:00', '2026-01-23', 'QR027', 19, 6),
(28, '14:30:00', '2026-01-24', 'QR028', 20, 2),
(29, '14:30:00', '2026-01-24', 'QR029', 20, 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre1` varchar(50) NOT NULL,
  `nombre2` varchar(50) DEFAULT NULL,
  `apellido1` varchar(50) NOT NULL,
  `apellido2` varchar(50) DEFAULT NULL,
  `ci` int(11) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_rol` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `ci` (`ci`),
  UNIQUE KEY `correo` (`correo`),
  UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  KEY `idx_email` (`correo`),
  KEY `idx_username` (`nombre_usuario`),
  KEY `fk_usuario_rol` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre1`, `nombre2`, `apellido1`, `apellido2`, `ci`, `correo`, `telefono`, `nombre_usuario`, `contrasena`, `estado`, `fecha_registro`, `id_rol`) VALUES
(1, 'Favio', 'Estefano', 'Palomares', 'Lima', 84537272, 'favio@gmail.com', '78943431', 'faviopzoo', '$2y$12$K62IvhlFPpdJB3dwQiu6bOBrFw1sIIOSQ2wjjlJat/UY0mhU6NEay', 1, '2026-03-06 23:48:50', NULL),
(2, 'Charlie', 'Fernando', 'Papas', 'Mamani', 8333302, 'charlie@gmail.com', '78900002', 'charliep', '$2y$12$VNl.SOXerOKJIqLjF8NIkenKa3ij9MDGaIpLTNw/GP81EalWBAZjy', 1, '2026-03-06 23:48:50', NULL),
(3, 'María', 'Elena', 'Flores', 'Rojas', 8333303, 'maria@gmail.com', '78900003', 'mariaf', '$2y$12$NvrpW12dldxSGynk0A.o2.h5VH39opz/L9wusMcpDOJ8iXkXkqpkS', 1, '2026-03-06 23:48:50', NULL),
(4, 'José', 'Antonio', 'Paredes', 'Lopez', 8333304, 'jose@gmail.com', '78900004', 'josepa', '$2y$12$TIoAXorc3Panq6NSM5l7T.qqTiOaa6jAnZC45W380hDfgrovsdeM', 1, '2026-03-06 23:48:50', NULL),
(5, 'Ana', 'Lucía', 'Garcia', 'Choque', 8333305, 'ana@gmail.com', '78900005', 'anav', '$2y$12$WFBCZSzSvEtHqH4iwOMhE.XK/iMrIp7jhSDPJ3.ZtT24H9rJhXw7a', 1, '2026-03-06 23:48:50', NULL),
(6, 'Pedro', 'Luis', 'Gutiérrez', 'Soto', 8333306, 'pedro@gmail.com', '78900006', 'pedros', '$2y$12$au0mOqSXxhOdC/Z..RLxxecPrOfKCXBM9/dMj7eLRXwhdY1.8y5l.', 1, '2026-03-06 23:48:50', NULL),
(7, 'Juan', 'Carlos', 'Mendoza', 'Ruiz', 800007, 'juan@gmail.com', '78900007', 'juancm', '$2y$12$PhpjbCEPFRQxkym0RjGx2udYTrsHZQ.HqmV6xJfwVPGVCQBbQ49U6', 1, '2026-03-06 23:48:50', NULL),
(8, 'Laura', 'Isabel', 'Pinto', 'Salas', 800008, 'laura@gmail.com', '78900008', 'lauraps', '$2y$12$PhpjbCEPFRQxkym0RjGx2udYTrsHZQ.HqmV6xJfwVPGVCQBbQ49U6', 1, '2026-03-06 23:48:50', NULL),
(9, 'Miguel', 'Ángel', 'Torrez', 'Ramos', 800009, 'miguel@gmail.com', '78900009', 'migueltr', '$2y$12$PhpjbCEPFRQxkym0RjGx2udYTrsHZQ.HqmV6xJfwVPGVCQBbQ49U6', 1, '2026-03-06 23:48:50', NULL),
(10, 'Sofía', 'Natalia', 'Cruz', 'Rivera', 800010, 'sofia@gmail.com', '78900010', 'soficr', '$2y$12$PhpjbCEPFRQxkym0RjGx2udYTrsHZQ.HqmV6xJfwVPGVCQBbQ49U6', 1, '2026-03-06 23:48:50', NULL),
(11, 'Diego', 'Andrés', 'Herrera', 'Paz', 800011, 'diego@gmail.com', '78900011', 'diegohp', '$2y$12$PhpjbCEPFRQxkym0RjGx2udYTrsHZQ.HqmV6xJfwVPGVCQBbQ49U6', 1, '2026-03-06 23:48:50', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `administradores`
--
ALTER TABLE `administradores`
  ADD CONSTRAINT `administradores_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `animales`
--
ALTER TABLE `animales`
  ADD CONSTRAINT `animales_ibfk_1` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id_area`) ON UPDATE CASCADE;

--
-- Constraints for table `api_token`
--
ALTER TABLE `api_token`
  ADD CONSTRAINT `fk_api_token_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON UPDATE CASCADE;

--
-- Constraints for table `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id_ticket`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `fk_evento_guia` FOREIGN KEY (`encargado_id`) REFERENCES `guias` (`id_guia`) ON DELETE SET NULL;

--
-- Constraints for table `evento_actividades`
--
ALTER TABLE `evento_actividades`
  ADD CONSTRAINT `fk_evento_actividad` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id_evento`) ON DELETE CASCADE;

--
-- Constraints for table `guias`
--
ALTER TABLE `guias`
  ADD CONSTRAINT `guias_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `guia_recorrido`
--
ALTER TABLE `guia_recorrido`
  ADD CONSTRAINT `guia_recorrido_ibfk_1` FOREIGN KEY (`id_guia`) REFERENCES `guias` (`id_guia`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `guia_recorrido_ibfk_2` FOREIGN KEY (`id_recorrido`) REFERENCES `recorridos` (`id_recorrido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recorrido_area`
--
ALTER TABLE `recorrido_area`
  ADD CONSTRAINT `recorrido_area_ibfk_1` FOREIGN KEY (`id_recorrido`) REFERENCES `recorridos` (`id_recorrido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `recorrido_area_ibfk_2` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id_area`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `fk_reporte_guia_recorrido` FOREIGN KEY (`id_guia_recorrido`) REFERENCES `guia_recorrido` (`id_guia_recorrido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON UPDATE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_recorrido`) REFERENCES `recorridos` (`id_recorrido`) ON UPDATE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`id_recorrido`) REFERENCES `recorridos` (`id_recorrido`) ON UPDATE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
