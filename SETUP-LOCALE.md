# 🚀 Setup Locale - Aste Giudiziarie 24

## ✅ Stato Attuale

### Database
- ✅ Database `astegiudiziarie24` creato e popolato
- ✅ 20 Regioni italiane
- ✅ 68 Province
- ✅ 37 Comuni
- ✅ 8 Servizi
- ✅ 1 Admin user

### Servizi Laragon
- ✅ PHP 8.3.30 installato e funzionante
- ✅ MySQL 8.4.3 in esecuzione
- ✅ Apache in esecuzione
- ✅ Composer 2.9.4 installato

### Configurazione
- ✅ Virtual host Apache creato: `C:\laragon\etc\apache2\sites-enabled\astegiudiziarie24.conf`
- ⚠️ File hosts da configurare (richiede permessi amministratore)

---

## 📋 Passi per Completare l'Installazione

### Passo 1: Configurare il File Hosts

**IMPORTANTE:** Questo passo richiede permessi di amministratore.

1. **Clicca con il tasto destro** su `setup-hosts.ps1`
2. Seleziona **"Esegui come amministratore"**
3. Lo script aggiungerà automaticamente l'entry: `127.0.0.1 astegiudiziarie24.test`

**Alternativa Manuale:**
1. Apri Notepad come amministratore
2. Apri il file: `C:\Windows\System32\drivers\etc\hosts`
3. Aggiungi alla fine: `127.0.0.1 astegiudiziarie24.test`
4. Salva il file

### Passo 2: Riavviare Apache

**Opzione A - Tramite Script:**
1. Esegui `restart-apache.ps1`

**Opzione B - Tramite Laragon:**
1. Apri Laragon
2. Clicca su **"Stop All"**
3. Clicca su **"Start All"**

### Passo 3: Accedere al Sito

Apri il browser e vai a:

**🌐 http://astegiudiziarie24.test**

---

## 🧪 Cosa Verificare

### Homepage (/)
- [ ] Hero section con titolo "Aste Giudiziarie e Fallimentari"
- [ ] Griglia servizi (8 card)
- [ ] Sezione benefici (6 card)
- [ ] Griglia regioni (20 regioni)
- [ ] Form di contatto funzionante
- [ ] FAQ accordion

### Pagina Pubblicità (/pubblicita)
- [ ] Hero section per partner
- [ ] Benefici partnership
- [ ] Form richiesta partnership

### Sitemap (/sitemap.xml)
- [ ] XML generato correttamente

### 404 Page
- [ ] Visita un URL inesistente (es: /test-404)
- [ ] Verifica che mostri la pagina 404 personalizzata

---

## 🔐 Credenziali Admin

**URL Admin:** http://astegiudiziarie24.test/admin (da implementare)

**Credenziali:**
- Email: `admin@astegiudiziarie24.it`
- Password: `Admin123!`

---

## 🛠️ Troubleshooting

### Problema: "Impossibile raggiungere il sito"

**Soluzione 1:** Verifica il file hosts
```powershell
Get-Content C:\Windows\System32\drivers\etc\hosts
```
Deve contenere: `127.0.0.1 astegiudiziarie24.test`

**Soluzione 2:** Verifica che Apache sia in esecuzione
```powershell
Get-Process httpd
```
Dovrebbe mostrare almeno 2 processi httpd.

**Soluzione 3:** Verifica la configurazione Apache
```powershell
Get-Content C:\laragon\etc\apache2\sites-enabled\astegiudiziarie24.conf
```

**Soluzione 4:** Riavvia Laragon completamente
- Stop All → Chiudi Laragon → Riapri Laragon → Start All

### Problema: "Database connection failed"

**Verifica connessione MySQL:**
```powershell
mysql -u root -e "SHOW DATABASES;"
```

**Verifica file .env:**
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=astegiudiziarie24
DB_USER=root
DB_PASS=
```

### Problema: CSS/JS non caricano

1. Verifica che i file esistano in `/public/assets/`
2. Controlla la console del browser (F12) per errori 404
3. Verifica che il DocumentRoot sia impostato su `/public`

### Problema: Errori 500

1. Controlla i log Apache: `C:\laragon\logs\astegiudiziarie24-error.log`
2. Verifica che `mod_rewrite` sia abilitato in Apache
3. Verifica che `.htaccess` esista in `/public`

---

## 📊 Comandi Utili

### Verificare Database
```powershell
# Connetti al database
mysql -u root astegiudiziarie24

# Query utili
SELECT COUNT(*) FROM regioni;    # Dovrebbe essere 20
SELECT COUNT(*) FROM servizi;    # Dovrebbe essere 8
SELECT COUNT(*) FROM province;   # Dovrebbe essere 68
SELECT COUNT(*) FROM comuni;     # Dovrebbe essere 37
SELECT * FROM users;             # Mostra admin user
```

### Verificare Servizi
```powershell
# PHP
php --version

# MySQL
mysql --version

# Composer
composer --version

# Processi in esecuzione
Get-Process httpd, mysqld
```

---

## 🎯 Prossimi Passi

Una volta verificato che il sito funziona:

1. **Sviluppare Admin Panel (CRM)**
   - Dashboard per gestione lead
   - Gestione partner
   - Statistiche e analytics

2. **Creare Template Geo-localizzati**
   - Pagine per regioni
   - Pagine per province
   - Pagine per comuni

3. **Aggiungere Pagine Legali**
   - Privacy Policy
   - Cookie Policy
   - Termini e Condizioni

4. **Configurare Email**
   - Setup SMTP in `.env`
   - Test invio email

5. **Configurare reCAPTCHA**
   - Ottenere chiavi da Google
   - Aggiungere in `.env`

---

## 📞 Supporto

Per problemi o domande, consulta:
- `README.md` - Documentazione generale
- `TESTING.md` - Istruzioni di testing
- Logs Apache: `C:\laragon\logs\astegiudiziarie24-error.log`
- Logs PHP: `D:\Progetti\astegiudiziarie24.it\logs\`

---

**✨ Buon lavoro!**
