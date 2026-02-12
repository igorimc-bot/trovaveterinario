-- Script to update services for Trovaveterinario
-- Questo script deve essere importato DOPO aver importato il dump originale (full_dump_v1.sql o v2)

SET NAMES utf8mb4;

-- Svuota la tabella servizi esistente
TRUNCATE TABLE `servizi`;

-- Inserisci i nuovi servizi veterinari
-- Assumo la struttura basata su migrate_services.php: id, nome, slug, descrizione_brevi, prezzo, features, ...

INSERT INTO `servizi` (`nome`, `slug`, `descrizione_breve`, `prezzo`, `features`) VALUES
('Veterinario per Cani e Gatti', 'veterinario-cani-gatti', 'Specialisti nella cura di animali domestici comuni', NULL, NULL),
('Veterinario per Rettili', 'veterinario-rettili', 'Esperti in rettili e anfibi', NULL, NULL),
('Veterinario per Uccelli', 'veterinario-uccelli', 'Specialisti in avicoltura e uccelli esotici', NULL, NULL),
('Veterinario per Animali da Fattoria', 'veterinario-animali-fattoria', 'Cura per mucche, cavalli, maiali e ovini', NULL, NULL),
('Veterinario per Animali Esotici', 'veterinario-animali-esotici', 'Cura per furetti, conigli e altri animali non convenzionali', NULL, NULL),
('Pronto Soccorso Veterinario', 'pronto-soccorso-veterinario', 'Interventi urgenti 24/7', NULL, NULL),
('Vaccinazioni e Microchip', 'vaccinazioni-microchip', 'Servizi di base per la salute del tuo animale', NULL, NULL),
('Chirurgia Veterinaria', 'chirurgia-veterinaria', 'Interventi chirurgici specialistici', NULL, NULL);

-- Aggiorna eventuali riferimenti se necessario (qui non ce ne sono ancora)
