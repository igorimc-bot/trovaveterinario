-- MySQL Database Schema for Aste Giudiziarie 24
-- Lead Generation Website for Judicial and Bankruptcy Auctions in Italy

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;

-- Disable foreign key checks for table drops
SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- Table structure for table `users` (MOVED TO TOP - referenced by other tables)
-- --------------------------------------------------------

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruolo` enum('admin','operatore') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'operatore',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `regioni`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `regioni`;
CREATE TABLE `regioni` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codice_istat` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attiva` tinyint(1) NOT NULL DEFAULT '1',
  `contenuto_custom` text COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug` (`slug`),
  KEY `idx_attiva` (`attiva`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `province`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `province`;
CREATE TABLE `province` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sigla` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `regione_id` int NOT NULL,
  `attiva` tinyint(1) NOT NULL DEFAULT '1',
  `contenuto_custom` text COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug` (`slug`),
  KEY `idx_regione` (`regione_id`),
  KEY `idx_attiva` (`attiva`),
  CONSTRAINT `fk_provincia_regione` FOREIGN KEY (`regione_id`) REFERENCES `regioni` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `comuni`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `comuni`;
CREATE TABLE `comuni` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provincia_id` int NOT NULL,
  `cap` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codice_istat` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attivo` tinyint(1) NOT NULL DEFAULT '1',
  `contenuto_custom` text COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_slug` (`slug`),
  KEY `idx_provincia` (`provincia_id`),
  KEY `idx_attivo` (`attivo`),
  UNIQUE KEY `unique_comune_provincia` (`slug`, `provincia_id`),
  CONSTRAINT `fk_comune_provincia` FOREIGN KEY (`provincia_id`) REFERENCES `province` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `servizi`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `servizi`;
CREATE TABLE `servizi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` enum('veicoli','immobili','altro') COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione_breve` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contenuto` text COLLATE utf8mb4_unicode_ci,
  `immagine` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attivo` tinyint(1) NOT NULL DEFAULT '1',
  `ordine` int NOT NULL DEFAULT '0',
  `meta_title` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug` (`slug`),
  KEY `idx_categoria` (`categoria`),
  KEY `idx_attivo` (`attivo`),
  KEY `idx_ordine` (`ordine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `leads`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `leads`;
CREATE TABLE `leads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cognome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indirizzo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `civico` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comune_id` int DEFAULT NULL,
  `provincia_id` int DEFAULT NULL,
  `regione_id` int DEFAULT NULL,
  `cap` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `servizio_id` int NOT NULL,
  `tipo_richiesta` json DEFAULT NULL COMMENT '["informazioni", "consulenza", "valutazione", "assistenza"]',
  `descrizione` text COLLATE utf8mb4_unicode_ci,
  `preferenza_contatto` enum('telefono','email','whatsapp') COLLATE utf8mb4_unicode_ci DEFAULT 'telefono',
  `orario_preferito` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stato` enum('nuovo','assegnato','contattato','chiuso','perso') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nuovo',
  `partner_id` int DEFAULT NULL,
  `note_interne` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `utm_source` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_medium` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_campaign` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_term` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_content` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_stato` (`stato`),
  KEY `idx_servizio` (`servizio_id`),
  KEY `idx_comune` (`comune_id`),
  KEY `idx_provincia` (`provincia_id`),
  KEY `idx_regione` (`regione_id`),
  KEY `idx_partner` (`partner_id`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `fk_lead_servizio` FOREIGN KEY (`servizio_id`) REFERENCES `servizi` (`id`),
  CONSTRAINT `fk_lead_comune` FOREIGN KEY (`comune_id`) REFERENCES `comuni` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_lead_provincia` FOREIGN KEY (`provincia_id`) REFERENCES `province` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_lead_regione` FOREIGN KEY (`regione_id`) REFERENCES `regioni` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `lead_history`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `lead_history`;
CREATE TABLE `lead_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lead_id` int NOT NULL,
  `azione` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dettagli` text COLLATE utf8mb4_unicode_ci,
  `user_id` int DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lead` (`lead_id`),
  KEY `idx_user` (`user_id`),
  CONSTRAINT `fk_lead_history_lead` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_lead_history_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `partners`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `partners`;
CREATE TABLE `partners` (
  `id` int NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nome_azienda` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipologia` enum('avvocato','perito','consulente_finanziario','impresa_ristrutturazioni','altro') COLLATE utf8mb4_unicode_ci NOT NULL,
  `referente` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regioni_competenza` json DEFAULT NULL COMMENT 'Array di ID regioni',
  `province_competenza` json DEFAULT NULL COMMENT 'Array di ID province',
  `servizi_offerti` json DEFAULT NULL COMMENT 'Array di ID servizi',
  `stato` enum('attivo','inattivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'attivo',
  `note` text COLLATE utf8mb4_unicode_ci,
  `lead_assegnati` int NOT NULL DEFAULT '0',
  `lead_chiusi` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_stato` (`stato`),
  KEY `idx_tipologia` (`tipologia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `advertising_leads`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `advertising_leads`;
CREATE TABLE `advertising_leads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `azienda` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipologia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tipo di professionista',
  `messaggio` text COLLATE utf8mb4_unicode_ci,
  `stato` enum('nuovo','contattato','concluso') COLLATE utf8mb4_unicode_ci DEFAULT 'nuovo',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_stato` (`stato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `advertising_history`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `advertising_history`;
CREATE TABLE `advertising_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lead_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `azione` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dettagli` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`),
  CONSTRAINT `fk_advertising_history_lead` FOREIGN KEY (`lead_id`) REFERENCES `advertising_leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `contenuti`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `contenuti`;
CREATE TABLE `contenuti` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chiave` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valore` text COLLATE utf8mb4_unicode_ci,
  `tipo` enum('testo','html','immagine') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'testo',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chiave` (`chiave`),
  KEY `fk_updated_by` (`updated_by`),
  CONSTRAINT `fk_contenuti_user` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `impostazioni`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `impostazioni`;
CREATE TABLE `impostazioni` (
  `chiave` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valore` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`chiave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `faq`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `faq`;
CREATE TABLE `faq` (
  `id` int NOT NULL AUTO_INCREMENT,
  `domanda` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `risposta` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'generale',
  `ordine` int NOT NULL DEFAULT '0',
  `attiva` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_attiva` (`attiva`),
  KEY `idx_categoria` (`categoria`),
  KEY `idx_ordine` (`ordine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Insert default settings
-- --------------------------------------------------------

INSERT INTO `impostazioni` (`chiave`, `valore`) VALUES
('email_admin_notification', '1'),
('email_customer_confirmation', '1'),
('form_success_message', 'Grazie per averci contattato! Riceverai una risposta entro 24 ore.'),
('site_name', 'Aste Giudiziarie 24'),
('site_tagline', 'Assistenza completa per aste giudiziarie e fallimentari in Italia');

-- --------------------------------------------------------
-- Insert default content
-- --------------------------------------------------------

INSERT INTO `contenuti` (`chiave`, `valore`, `tipo`) VALUES
('hero_title', 'Aste Giudiziarie e Fallimentari: Assistenza Completa', 'testo'),
('hero_subtitle', 'Consulenza professionale, supporto legale e perizie per acquisti all\'asta in tutta Italia. Trova le migliori opportunità nella tua zona.', 'testo'),
('global_why_us_text', 'Siamo specializzati nell\'assistenza per {servizio} in {zona}. Il nostro team di professionisti garantisce:', 'testo'),
('global_benefits_html', '<li><strong>Consulenza gratuita</strong>: valutazione preliminare senza impegno</li><li><strong>Esperti del settore</strong>: avvocati, periti e consulenti qualificati</li><li><strong>Supporto completo</strong>: dall\'analisi dell\'asta alla gestione post-acquisto</li><li><strong>Copertura nazionale</strong>: assistenza in tutta Italia</li><li><strong>Trasparenza totale</strong>: preventivi chiari e dettagliati</li>', 'html');

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
