-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para rfidplay
CREATE DATABASE IF NOT EXISTS `rfidplay` /*!40100 DEFAULT CHARACTER SET utf8mb3 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `rfidplay`;

-- Volcando estructura para tabla rfidplay.camposdejuego
CREATE TABLE IF NOT EXISTS `camposdejuego` (
  `id_campo` int NOT NULL AUTO_INCREMENT,
  `nombre_campo` varchar(200) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `fotoblob` longblob,
  PRIMARY KEY (`id_campo`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.documentos
CREATE TABLE IF NOT EXISTS `documentos` (
  `id_documento` int NOT NULL AUTO_INCREMENT,
  `tipo_documento` int DEFAULT NULL,
  `numero_documento` varchar(45) DEFAULT NULL,
  `Nombre1` varchar(100) DEFAULT NULL,
  `Nombre2` varchar(45) DEFAULT NULL,
  `Ape1` varchar(45) DEFAULT NULL,
  `Ape2` varchar(45) DEFAULT NULL,
  `telefonos` varchar(45) DEFAULT NULL,
  `documentosfoto` longblob,
  `docfile` longblob,
  `rfidcpde` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_documento`),
  KEY `tiposdocfk_idx` (`tipo_documento`),
  CONSTRAINT `tiposdocfk` FOREIGN KEY (`tipo_documento`) REFERENCES `tiposdoc` (`idtiposdoc`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.entrenadores
CREATE TABLE IF NOT EXISTS `entrenadores` (
  `idEntrenadores` int NOT NULL AUTO_INCREMENT,
  `id_escuela` int DEFAULT NULL,
  `usuariofk` int DEFAULT NULL,
  `etiqueta_rfid` varchar(255) DEFAULT NULL,
  `id_documento` int DEFAULT NULL,
  PRIMARY KEY (`idEntrenadores`),
  KEY `iddocume_idx` (`id_documento`),
  KEY `idusuario_idx` (`usuariofk`),
  KEY `idequipofk_idx` (`id_escuela`),
  CONSTRAINT `iddocume` FOREIGN KEY (`id_documento`) REFERENCES `documentos` (`id_documento`),
  CONSTRAINT `idequipofk` FOREIGN KEY (`id_escuela`) REFERENCES `escuelasdefutbol` (`id_escuela`),
  CONSTRAINT `idusuario` FOREIGN KEY (`usuariofk`) REFERENCES `usuarios` (`idusuarios`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.equipos
CREATE TABLE IF NOT EXISTS `equipos` (
  `id_equipo` int NOT NULL AUTO_INCREMENT,
  `nombre_equipo` varchar(200) DEFAULT NULL,
  `id_escuela` int DEFAULT NULL,
  `fk_type_equipo` int DEFAULT NULL,
  PRIMARY KEY (`id_equipo`),
  KEY `id_escuelafk_idx` (`id_escuela`),
  KEY `fk_tip_es_idx` (`fk_type_equipo`),
  CONSTRAINT `fk_tip_es` FOREIGN KEY (`fk_type_equipo`) REFERENCES `tip_equi_age` (`idtip_equi_age`),
  CONSTRAINT `id_escuelafk` FOREIGN KEY (`id_escuela`) REFERENCES `escuelasdefutbol` (`id_escuela`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.escuelasdefutbol
CREATE TABLE IF NOT EXISTS `escuelasdefutbol` (
  `id_escuela` int NOT NULL AUTO_INCREMENT,
  `nombre_escuela` varchar(200) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `fotoes` longblob,
  PRIMARY KEY (`id_escuela`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.jugadores
CREATE TABLE IF NOT EXISTS `jugadores` (
  `id_jugador` int NOT NULL AUTO_INCREMENT,
  `id_documento` int DEFAULT NULL,
  `id_escuela` int DEFAULT NULL,
  PRIMARY KEY (`id_jugador`),
  KEY `id_documentofk_idx` (`id_documento`),
  KEY `fk_equipo_idx` (`id_escuela`),
  CONSTRAINT `fk_equipo` FOREIGN KEY (`id_escuela`) REFERENCES `escuelasdefutbol` (`id_escuela`),
  CONSTRAINT `id_documentofk` FOREIGN KEY (`id_documento`) REFERENCES `documentos` (`id_documento`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.jugadorpartido
CREATE TABLE IF NOT EXISTS `jugadorpartido` (
  `idjugadorpartido` int NOT NULL,
  `idjugador` int DEFAULT NULL,
  `idpartido` int DEFAULT NULL,
  `goles` int DEFAULT NULL,
  `faltas` int DEFAULT NULL,
  PRIMARY KEY (`idjugadorpartido`),
  KEY `jugadorfk_idx` (`idjugador`),
  KEY `partidoa_idx` (`idpartido`),
  CONSTRAINT `jugadorfk` FOREIGN KEY (`idjugador`) REFERENCES `jugadores` (`id_jugador`),
  CONSTRAINT `partidoa` FOREIGN KEY (`idpartido`) REFERENCES `partidos` (`id_partido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.jugador_equipo
CREATE TABLE IF NOT EXISTS `jugador_equipo` (
  `idjugador_equipo` int NOT NULL,
  `equipofk` int DEFAULT NULL,
  `idjugadorfk` int DEFAULT NULL,
  PRIMARY KEY (`idjugador_equipo`),
  KEY `idjugador_idx` (`idjugadorfk`),
  KEY `idequipos_idx` (`equipofk`),
  CONSTRAINT `idequipos` FOREIGN KEY (`equipofk`) REFERENCES `equipos` (`id_equipo`),
  CONSTRAINT `idjugador` FOREIGN KEY (`idjugadorfk`) REFERENCES `jugadores` (`id_jugador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.partidos
CREATE TABLE IF NOT EXISTS `partidos` (
  `id_partido` int NOT NULL AUTO_INCREMENT,
  `id_equipo_local` int DEFAULT NULL,
  `id_equipo_visitante` int DEFAULT NULL,
  `idcampojuego` int DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT NULL,
  `rcode` varchar(6) DEFAULT NULL,
  `estado` int DEFAULT NULL,
  `fecha_fi` date DEFAULT NULL,
  `hora_fi` time DEFAULT NULL,
  PRIMARY KEY (`id_partido`),
  KEY `id_equipo_localfk_idx` (`id_equipo_local`),
  KEY `id_equipo_visitantefk_idx` (`id_equipo_visitante`),
  KEY `id_camposfk_idx` (`idcampojuego`),
  CONSTRAINT `id_camposfk` FOREIGN KEY (`idcampojuego`) REFERENCES `camposdejuego` (`id_campo`),
  CONSTRAINT `id_equipo_localfk` FOREIGN KEY (`id_equipo_local`) REFERENCES `equipos` (`id_equipo`),
  CONSTRAINT `id_equipo_visitantefk` FOREIGN KEY (`id_equipo_visitante`) REFERENCES `equipos` (`id_equipo`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.rfidscodes
CREATE TABLE IF NOT EXISTS `rfidscodes` (
  `idrfidscodes` int NOT NULL,
  `rfidscodescol` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idrfidscodes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `idroles` int NOT NULL,
  `rolescol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idroles`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.sensor
CREATE TABLE IF NOT EXISTS `sensor` (
  `idsensor` int NOT NULL AUTO_INCREMENT,
  `sensoruid` varchar(50) DEFAULT NULL,
  `iduserfk` int DEFAULT NULL,
  `idcampoa` int DEFAULT NULL,
  PRIMARY KEY (`idsensor`),
  KEY `iduserfk_idx` (`iduserfk`),
  KEY `idcamporffk_idx` (`idcampoa`),
  CONSTRAINT `idcamporffk` FOREIGN KEY (`idcampoa`) REFERENCES `camposdejuego` (`id_campo`),
  CONSTRAINT `iduserfk` FOREIGN KEY (`iduserfk`) REFERENCES `usuarios` (`idusuarios`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.tiposdoc
CREATE TABLE IF NOT EXISTS `tiposdoc` (
  `idtiposdoc` int NOT NULL,
  `tiposdoccol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idtiposdoc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.tip_equi_age
CREATE TABLE IF NOT EXISTS `tip_equi_age` (
  `idtip_equi_age` int NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idtip_equi_age`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.torneos
CREATE TABLE IF NOT EXISTS `torneos` (
  `id_torneo` int NOT NULL AUTO_INCREMENT,
  `nombre_torneo` varchar(200) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` int DEFAULT NULL,
  `observaciones` text,
  PRIMARY KEY (`id_torneo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.trusted_sensors
CREATE TABLE IF NOT EXISTS `trusted_sensors` (
  `idtrusted_sensors` int NOT NULL,
  `trusted_sensorscol` varchar(100) DEFAULT NULL,
  `mac_address` varchar(17) DEFAULT NULL,
  `wifi_ssid` varchar(50) DEFAULT NULL,
  `wifi_key` varchar(50) DEFAULT NULL,
  `serial_number` varchar(15) DEFAULT NULL,
  `cpu` varchar(50) DEFAULT NULL,
  `flash_memory` varchar(20) DEFAULT NULL,
  `ram_memory` varchar(20) DEFAULT NULL,
  `wireless_connectivity` varchar(50) DEFAULT NULL,
  `communication_interfaces` varchar(100) DEFAULT NULL,
  `operating_voltage` varchar(20) DEFAULT NULL,
  `operating_current` varchar(20) DEFAULT NULL,
  `supported_os` varchar(50) DEFAULT NULL,
  `development_platform` varchar(50) DEFAULT NULL,
  `size` varchar(20) DEFAULT NULL,
  `additional_features` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idtrusted_sensors`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.updates
CREATE TABLE IF NOT EXISTS `updates` (
  `idupdates` int NOT NULL AUTO_INCREMENT,
  `sensorup` int DEFAULT NULL,
  `Descripción` varchar(800) DEFAULT NULL,
  `fechadepubliacion` date DEFAULT NULL,
  `estado` int DEFAULT NULL,
  `sensortit` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idupdates`),
  KEY `idsensor_idx` (`sensorup`),
  CONSTRAINT `idsensor` FOREIGN KEY (`sensorup`) REFERENCES `trusted_sensors` (`idtrusted_sensors`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla rfidplay.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `idusuarios` int NOT NULL AUTO_INCREMENT,
  `mail` varchar(100) DEFAULT NULL,
  `contraseña` varchar(100) DEFAULT NULL,
  `docidfk` int DEFAULT NULL,
  `roles` int DEFAULT NULL,
  PRIMARY KEY (`idusuarios`),
  KEY `docfk_idx` (`docidfk`),
  KEY `rolesfk_idx` (`roles`),
  CONSTRAINT `docfk` FOREIGN KEY (`docidfk`) REFERENCES `documentos` (`id_documento`),
  CONSTRAINT `rolesfk` FOREIGN KEY (`roles`) REFERENCES `roles` (`idroles`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;

-- La exportación de datos fue deseleccionada.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
