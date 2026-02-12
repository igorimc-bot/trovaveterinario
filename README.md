# Aste Giudiziarie 24 - Lead Generation Website

Sito web per la generazione di lead nel settore aste giudiziarie e fallimentari in Italia, con CRM integrato per la gestione e distribuzione dei lead ai partner.

## 🚀 Quick Start

### Requisiti
- PHP 7.4+
- MySQL 5.7+ / MariaDB 10.3+
- Composer
- Apache/Nginx con mod_rewrite

### Installazione

1. **Clone repository**
   ```bash
   git clone <repository-url>
   cd astegiudiziarie24
   ```

2. **Installa dipendenze**
   ```bash
   composer install
   ```

3. **Configura environment**
   ```bash
   cp .env.example .env
   ```
   Modifica `.env` con le tue credenziali database.

4. **Crea database**
   ```bash
   php scripts/install.php
   ```

5. **Popola dati iniziali**
   ```bash
   php scripts/populate-regioni.php
   php scripts/populate-province.php
   php scripts/populate-comuni.php
   php scripts/populate-servizi.php
   php scripts/create-admin.php
   ```

6. **Configura web server**
   - Document root: `/public`
   - Abilita mod_rewrite

## 📁 Struttura Progetto

```
/public/          # Document root
/admin/           # CRM admin panel
/includes/        # Core PHP files
/api/             # API endpoints
/scripts/         # Utility scripts
```

## 🔐 Accesso Admin

URL: `/admin/`
Credenziali di default create durante setup.

## 📖 Documentazione

Vedi [implementation_plan.md](../brain/implementation_plan.md) per la documentazione completa.

## 🛠️ Sviluppo

- **Environment**: Development mode in `.env`
- **Debug**: Abilitato in development
- **Logs**: Controlla `/logs/` per errori

## 📊 Database

Schema completo in `database.sql`:
- 20 Regioni italiane
- 107 Province italiane
- ~8,000 Comuni italiani
- 8 Servizi (auto, case, barche, moto, etc.)

## 🔒 Sicurezza

- Password hash con bcrypt
- Prepared statements (SQL injection prevention)
- CSRF protection
- XSS sanitization
- reCAPTCHA v3

## 📞 Supporto

Per supporto tecnico, consulta la documentazione SOP.
