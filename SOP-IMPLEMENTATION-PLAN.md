# Aste Giudiziarie e Fallimentari - Implementation Plan (SOP)

## Obiettivo del Progetto

Creare un sito web di **lead generation** per aste giudiziarie e fallimentari in Italia, con focus su:
- SEO geo-localizzato (Regioni > Province > Comuni)
- Lead generation per servizi specifici (auto, case, barche, moto, immobili, mobili)
- CRM per gestione lead e partner
- Sezione pubblicità per professionisti del settore
- Ottimizzazione massima (SEO, Core Web Vitals, Schema Markup)

---

## User Review Required

> [!IMPORTANT]
> **Keywords Principali Confermate**
> - Aste Giudiziarie
> - Aste Fallimentari
> 
> Queste saranno le keyword pillar per tutta la strategia SEO.

> [!WARNING]
> **Scala del Progetto**
> Con 20 regioni × 107 province × ~8,000 comuni × 8 servizi = **~6.8 milioni di combinazioni possibili**.
> 
> Implementeremo un sistema di generazione dinamica con cache per evitare di creare fisicamente milioni di file.

---

## Proposed Changes

### 1. Architettura del Progetto

Struttura basata sul progetto elettricista ma adattata per scala nazionale:

```
/
├── .env
├── .env.production.example
├── composer.json
├── database.sql
├── router.php
├── includes/
│   ├── config.php
│   ├── db.php
│   └── functions.php
├── public/
│   ├── .htaccess
│   ├── index.php
│   ├── pubblicita.php
│   ├── sitemap.xml.php
│   ├── robots.txt
│   ├── privacy-policy.php
│   ├── cookie-policy.php
│   ├── termini-condizioni.php
│   ├── admin/
│   │   ├── index.php
│   │   ├── login.php
│   │   ├── dashboard.php
│   │   ├── leads/
│   │   ├── partners/
│   │   ├── advertising/
│   │   ├── cms/
│   │   └── stats/
│   ├── api/
│   │   ├── submit-lead.php
│   │   └── submit-advertising.php
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── img/
│   ├── servizi/
│   │   └── [servizio-slug].php
│   ├── regioni/
│   │   └── [regione-slug]/
│   │       └── index.php
│   ├── province/
│   │   └── [provincia-slug]/
│   │       └── index.php
│   └── comuni/
│       └── [comune-slug]/
│           └── index.php
└── scripts/
    ├── install.php
    ├── populate-regioni.php
    ├── populate-province.php
    ├── populate-comuni.php
    ├── populate-servizi.php
    └── create-admin.php
```

---

### 2. Database Schema

#### Tabella `regioni`

```sql
CREATE TABLE `regioni` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `codice_istat` VARCHAR(2) NOT NULL,
  `attiva` TINYINT(1) NOT NULL DEFAULT 1,
  `contenuto_custom` TEXT,
  `meta_title` VARCHAR(70),
  `meta_description` VARCHAR(200),
  PRIMARY KEY (`id`),
  INDEX `idx_slug` (`slug`),
  INDEX `idx_attiva` (`attiva`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Dati**: 20 regioni italiane

---

#### Tabella `province`

```sql
CREATE TABLE `province` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `sigla` VARCHAR(2) NOT NULL,
  `regione_id` INT NOT NULL,
  `attiva` TINYINT(1) NOT NULL DEFAULT 1,
  `contenuto_custom` TEXT,
  `meta_title` VARCHAR(70),
  `meta_description` VARCHAR(200),
  PRIMARY KEY (`id`),
  INDEX `idx_slug` (`slug`),
  INDEX `idx_regione` (`regione_id`),
  INDEX `idx_attiva` (`attiva`),
  FOREIGN KEY (`regione_id`) REFERENCES `regioni`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Dati**: 107 province italiane

---

#### Tabella `comuni`

```sql
CREATE TABLE `comuni` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `provincia_id` INT NOT NULL,
  `cap` VARCHAR(5),
  `codice_istat` VARCHAR(6),
  `attivo` TINYINT(1) NOT NULL DEFAULT 1,
  `contenuto_custom` TEXT,
  `meta_title` VARCHAR(70),
  `meta_description` VARCHAR(200),
  PRIMARY KEY (`id`),
  INDEX `idx_slug` (`slug`),
  INDEX `idx_provincia` (`provincia_id`),
  INDEX `idx_attivo` (`attivo`),
  UNIQUE KEY `unique_comune_provincia` (`slug`, `provincia_id`),
  FOREIGN KEY (`provincia_id`) REFERENCES `province`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Dati**: ~8,000 comuni italiani

---

#### Tabella `servizi`

```sql
CREATE TABLE `servizi` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `categoria` ENUM('veicoli', 'immobili', 'altro') NOT NULL,
  `descrizione_breve` VARCHAR(160),
  `contenuto` TEXT,
  `immagine` VARCHAR(255),
  `attivo` TINYINT(1) NOT NULL DEFAULT 1,
  `ordine` INT NOT NULL DEFAULT 0,
  `meta_title` VARCHAR(70),
  `meta_description` VARCHAR(200),
  PRIMARY KEY (`id`),
  INDEX `idx_slug` (`slug`),
  INDEX `idx_categoria` (`categoria`),
  INDEX `idx_attivo` (`attivo`),
  INDEX `idx_ordine` (`ordine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Servizi Iniziali**:
1. Auto all'Asta (veicoli)
2. Moto all'Asta (veicoli)
3. Barche all'Asta (veicoli)
4. Case all'Asta (immobili)
5. Aste Immobiliari (immobili)
6. Aste Mobiliari (altro)
7. Aste Giudiziarie (altro)
8. Aste Fallimentari (altro)

---

#### Tabella `leads`

```sql
CREATE TABLE `leads` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nome` VARCHAR(100) NOT NULL,
  `cognome` VARCHAR(100) NOT NULL,
  `telefono` VARCHAR(20) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `indirizzo` VARCHAR(255),
  `civico` VARCHAR(10),
  `comune_id` INT,
  `provincia_id` INT,
  `regione_id` INT,
  `cap` VARCHAR(5),
  `servizio_id` INT NOT NULL,
  `tipo_richiesta` JSON COMMENT '["informazioni", "consulenza", "valutazione", "assistenza"]',
  `descrizione` TEXT,
  `preferenza_contatto` ENUM('telefono', 'email', 'whatsapp') DEFAULT 'telefono',
  `orario_preferito` VARCHAR(50),
  `stato` ENUM('nuovo', 'assegnato', 'contattato', 'chiuso', 'perso') NOT NULL DEFAULT 'nuovo',
  `partner_id` INT,
  `note_interne` TEXT,
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `utm_source` VARCHAR(100),
  `utm_medium` VARCHAR(100),
  `utm_campaign` VARCHAR(100),
  `utm_term` VARCHAR(100),
  `utm_content` VARCHAR(100),
  PRIMARY KEY (`id`),
  INDEX `idx_stato` (`stato`),
  INDEX `idx_servizio` (`servizio_id`),
  INDEX `idx_comune` (`comune_id`),
  INDEX `idx_provincia` (`provincia_id`),
  INDEX `idx_regione` (`regione_id`),
  INDEX `idx_partner` (`partner_id`),
  INDEX `idx_created` (`created_at`),
  FOREIGN KEY (`servizio_id`) REFERENCES `servizi`(`id`),
  FOREIGN KEY (`comune_id`) REFERENCES `comuni`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`provincia_id`) REFERENCES `province`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`regione_id`) REFERENCES `regioni`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

#### Altre Tabelle

- `partners` - Gestione partner (avvocati, periti, consulenti)
- `advertising_leads` - Lead dalla sezione pubblicità
- `advertising_history` - Storico azioni advertising
- `lead_history` - Storico azioni lead
- `users` - Utenti admin
- `contenuti` - Contenuti dinamici CMS
- `impostazioni` - Configurazioni sito
- `faq` - Domande frequenti

---

### 3. Strategia SEO Geo-Localizzata

#### URL Structure

**Homepage**
```
https://astegiudiziarie24.it/
```

**Servizi**
```
https://astegiudiziarie24.it/servizi/auto-all-asta/
https://astegiudiziarie24.it/servizi/case-all-asta/
https://astegiudiziarie24.it/servizi/aste-giudiziarie/
https://astegiudiziarie24.it/servizi/aste-fallimentari/
```

**Geo-Localizzazione Regioni**
```
https://astegiudiziarie24.it/regioni/lombardia/
https://astegiudiziarie24.it/regioni/lazio/
```

**Geo-Localizzazione Province**
```
https://astegiudiziarie24.it/province/milano/
https://astegiudiziarie24.it/province/roma/
```

**Geo-Localizzazione Comuni**
```
https://astegiudiziarie24.it/comuni/milano/
https://astegiudiziarie24.it/comuni/roma/
```

**Combinazioni Servizio × Località**
```
https://astegiudiziarie24.it/auto-all-asta/lombardia/
https://astegiudiziarie24.it/auto-all-asta/provincia-milano/
https://astegiudiziarie24.it/auto-all-asta/milano/
https://astegiudiziarie24.it/case-all-asta/lazio/
https://astegiudiziarie24.it/aste-giudiziarie/roma/
```

#### Meta Title Templates

```php
// Regione + Servizio
"{servizio} in {regione} | Aste Giudiziarie 24"
// Esempio: "Auto all'Asta in Lombardia | Aste Giudiziarie 24"

// Provincia + Servizio
"{servizio} in Provincia di {provincia} | Aste Giudiziarie 24"
// Esempio: "Case all'Asta in Provincia di Milano | Aste Giudiziarie 24"

// Comune + Servizio
"{servizio} a {comune} | Aste Giudiziarie 24"
// Esempio: "Aste Fallimentari a Roma | Aste Giudiziarie 24"
```

#### Meta Description Templates

```php
// Regione + Servizio
"Cerchi {servizio_lower} in {regione}? Assistenza completa per aste giudiziarie e fallimentari. Consulenza gratuita, esperti del settore. Contattaci ora!"

// Provincia + Servizio
"Scopri le migliori {servizio_lower} in provincia di {provincia}. Supporto legale, perizie, finanziamenti. Richiedi consulenza gratuita!"

// Comune + Servizio
"{servizio} a {comune}: assistenza professionale per aste giudiziarie e fallimentari. Preventivo gratuito, esperti locali. Chiamaci!"
```

---

### 4. Schema Markup Strategy

#### LocalBusiness Schema (Homepage)

```json
{
  "@context": "https://schema.org",
  "@type": "ProfessionalService",
  "name": "Aste Giudiziarie 24",
  "description": "Assistenza completa per aste giudiziarie e fallimentari in Italia",
  "url": "https://astegiudiziarie24.it",
  "areaServed": {
    "@type": "Country",
    "name": "Italia"
  },
  "serviceType": [
    "Consulenza Aste Giudiziarie",
    "Assistenza Aste Fallimentari",
    "Perizie Immobiliari",
    "Assistenza Legale Aste"
  ]
}
```

#### Service Schema (Pagine Servizi)

```json
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "Auto all'Asta",
  "provider": {
    "@type": "Organization",
    "name": "Aste Giudiziarie 24"
  },
  "areaServed": {
    "@type": "State",
    "name": "Lombardia"
  },
  "description": "Assistenza completa per acquisto auto all'asta in Lombardia"
}
```

#### BreadcrumbList Schema

```json
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "https://astegiudiziarie24.it"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Lombardia",
      "item": "https://astegiudiziarie24.it/regioni/lombardia"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "Milano",
      "item": "https://astegiudiziarie24.it/province/milano"
    }
  ]
}
```

---

### 5. Lead Generation Forms

#### Form Principale (Multi-step)

**Step 1: Servizio**
- Selezione servizio (auto, case, barche, moto, etc.)
- Tipo asta (giudiziaria/fallimentare)

**Step 2: Località**
- Regione (select)
- Provincia (select dinamico)
- Comune (select dinamico)
- CAP

**Step 3: Dati Personali**
- Nome
- Cognome
- Email
- Telefono
- Preferenza contatto

**Step 4: Dettagli Richiesta**
- Tipo richiesta (informazioni, consulenza, valutazione, assistenza)
- Descrizione
- Orario preferito

**Step 5: Privacy**
- Consenso privacy
- Consenso marketing
- reCAPTCHA v3

---

### 6. CRM & Partner Management

#### Tipologie Partner

1. **Avvocati Specializzati**
   - Zone competenza
   - Servizi offerti
   - Tariffe

2. **Periti/Geometri**
   - Zone competenza
   - Tipologie perizie
   - Tariffe

3. **Consulenti Finanziari**
   - Zone competenza
   - Servizi finanziamento
   - Condizioni

4. **Imprese Ristrutturazioni**
   - Zone competenza
   - Servizi offerti
   - Portfolio lavori

#### Distribuzione Lead

- Assegnazione automatica per zona + servizio
- Rotazione tra partner
- Notifiche email/SMS
- Dashboard partner (opzionale fase 2)

---

### 7. Sezione Pubblicità

Identica al progetto elettricista:

- Form contatto per professionisti
- Gestione lead pubblicitari
- Storico contatti
- CRM dedicato

**Target**: Avvocati, periti, consulenti, agenzie immobiliari, finanziarie

---

### 8. Core Web Vitals Optimization

#### Performance

- **Lazy loading** immagini
- **Minificazione** CSS/JS
- **Compressione** Gzip/Brotli
- **Caching** browser e server
- **CDN** per assets statici (opzionale)

#### LCP (Largest Contentful Paint)

- Ottimizzazione hero image
- Preload font critici
- Inline critical CSS

#### FID (First Input Delay)

- Defer JavaScript non critico
- Code splitting

#### CLS (Cumulative Layout Shift)

- Dimensioni esplicite immagini
- Font display: swap
- Placeholder per contenuti dinamici

---

### 9. Sitemap Strategy

#### Sitemap Index

```xml
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap>
    <loc>https://astegiudiziarie24.it/sitemap-main.xml</loc>
  </sitemap>
  <sitemap>
    <loc>https://astegiudiziarie24.it/sitemap-servizi.xml</loc>
  </sitemap>
  <sitemap>
    <loc>https://astegiudiziarie24.it/sitemap-regioni.xml</loc>
  </sitemap>
  <sitemap>
    <loc>https://astegiudiziarie24.it/sitemap-province.xml</loc>
  </sitemap>
  <sitemap>
    <loc>https://astegiudiziarie24.it/sitemap-comuni.xml</loc>
  </sitemap>
</sitemapindex>
```

#### Priorità

- Homepage: 1.0
- Servizi: 0.9
- Regioni: 0.8
- Province: 0.7
- Comuni principali: 0.6
- Comuni minori: 0.5

---

### 10. Tecnologie & Dependencies

#### Composer Dependencies

```json
{
  "require": {
    "php": "^7.4|^8.0",
    "vlucas/phpdotenv": "^5.4",
    "phpmailer/phpmailer": "^6.6"
  }
}
```

#### PHP Extensions Required

- PDO
- PDO_MySQL
- mbstring
- json
- curl

---

### 11. Routing System

#### .htaccess

```apache
RewriteEngine On
RewriteBase /

# Redirect to HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Route everything through router.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ router.php?url=$1 [QSA,L]
```

#### router.php Logic

```php
// Servizi: /servizi/{slug}
// Regioni: /regioni/{slug}
// Province: /province/{slug}
// Comuni: /comuni/{slug}
// Servizio + Regione: /{servizio-slug}/{regione-slug}
// Servizio + Provincia: /{servizio-slug}/provincia-{provincia-slug}
// Servizio + Comune: /{servizio-slug}/{comune-slug}
```

---

## Verification Plan

### Automated Tests

1. **Database Tests**
   ```bash
   php scripts/test-connection.php
   ```

2. **SEO Tests**
   - Verify meta tags on sample pages
   - Check Schema Markup validity (schema.org validator)
   - Test sitemap generation
   - Verify canonical URLs

3. **Form Tests**
   - Test lead submission
   - Verify email notifications
   - Check database insertion
   - Test reCAPTCHA integration

4. **Performance Tests**
   - Google PageSpeed Insights
   - GTmetrix
   - WebPageTest
   - Core Web Vitals (Chrome DevTools)

### Manual Verification

1. **Cross-browser Testing**
   - Chrome
   - Firefox
   - Safari
   - Edge

2. **Mobile Testing**
   - iOS Safari
   - Android Chrome
   - Responsive design breakpoints

3. **SEO Verification**
   - Google Search Console
   - Submit sitemap
   - Monitor indexing
   - Check mobile usability

4. **Lead Flow Testing**
   - Submit test leads
   - Verify partner notifications
   - Check CRM functionality
   - Test lead assignment logic

---

## Deployment Checklist

- [ ] Setup production database
- [ ] Import geo data (regioni, province, comuni)
- [ ] Import servizi data
- [ ] Configure .env production
- [ ] Deploy codebase to Plesk
- [ ] Configure SSL certificate
- [ ] Setup email server (SMTP)
- [ ] Test production environment
- [ ] Submit sitemap to Google
- [ ] Setup Google Analytics
- [ ] Setup Google Search Console
- [ ] Configure backup strategy
- [ ] Monitor error logs

---

## Next Steps

1. **Review & Approve** questo implementation plan
2. **Gather Data**: Elenco completo regioni, province, comuni (posso generarlo)
3. **Start Development**: Fase 1 - Database Setup
4. **Iterative Development**: Seguire task.md

**Sei pronto per procedere?** 🚀
