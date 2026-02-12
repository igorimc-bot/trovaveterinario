# Istruzioni per Testare il Sito in Locale su Laragon

## ✅ Installazione Completata

Il database e tutti i file sono stati installati con successo!

**Database popolato con:**
- ✅ 20 Regioni italiane
- ✅ 71 Province (sample - espandibile a 107)
- ✅ 44 Comuni (sample - espandibile a ~8,000)
- ✅ 8 Servizi (auto, moto, barche, case, immobili, mobiliari, giudiziarie, fallimentari)

**Admin User creato:**
- Email: `admin@astegiudiziarie24.it`
- Password: `Admin123!`

---

## 🌐 Come Accedere al Sito

Laragon crea automaticamente un virtual host basato sul nome della cartella.

### Opzione 1: URL Automatico Laragon
Prova questi URL nel browser:

1. **http://_ astegiudiziarie24.it.test** (nome cartella completo)
2. **http://astegiudiziarie24.test** (nome semplificato)
3. **http://localhost** (se hai solo questo progetto)

### Opzione 2: Configurare Virtual Host Manuale

Se gli URL sopra non funzionano, configura manualmente:

1. **Apri Laragon**
2. **Menu → Apache → sites-enabled → Aggiungi**
3. **Crea file:** `astegiudiziarie24.conf`
4. **Contenuto:**
   ```apache
   <VirtualHost *:80>
       DocumentRoot "D:/Progetti/_ astegiudiziarie24.it/public"
       ServerName astegiudiziarie24.test
       ServerAlias *.astegiudiziarie24.test
       <Directory "D:/Progetti/_ astegiudiziarie24.it/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
5. **Aggiungi al file hosts** (C:\Windows\System32\drivers\etc\hosts):
   ```
   127.0.0.1 astegiudiziarie24.test
   ```
6. **Riavvia Apache** in Laragon

---

## 🧪 Cosa Verificare

### 1. Homepage (/)
Dovresti vedere:
- ✅ Hero section con titolo "Aste Giudiziarie e Fallimentari: Assistenza Completa"
- ✅ Griglia servizi (8 card)
- ✅ Sezione benefici (6 card)
- ✅ Griglia regioni (20 regioni)
- ✅ Form di contatto
- ✅ FAQ accordion

### 2. Pagina Pubblicità (/pubblicita)
- ✅ Hero section per partner
- ✅ Benefici partnership
- ✅ Form richiesta partnership

### 3. Test Form Lead
1. Compila il form nella homepage
2. Verifica che arrivi email di conferma (se configurato)
3. Controlla database: `SELECT * FROM leads ORDER BY id DESC LIMIT 1;`

### 4. Sitemap (/sitemap.xml)
- ✅ Dovrebbe mostrare XML con tutte le pagine

### 5. 404 Page
- Visita URL inesistente (es: /pagina-non-esistente)
- ✅ Dovrebbe mostrare pagina 404 personalizzata

---

## 🔧 Troubleshooting

### Errore "Database connection failed"
Verifica `.env`:
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=astegiudiziarie24
DB_USER=root
DB_PASS=
```

### Errore 404 su tutte le pagine
1. Verifica che il DocumentRoot punti a `/public`
2. Verifica che `mod_rewrite` sia abilitato in Apache
3. Verifica che `.htaccess` esista in `/public`

### CSS/JS non caricano
1. Verifica che i file esistano in `/public/assets/css/` e `/public/assets/js/`
2. Controlla la console del browser per errori 404

### Email non funzionano
Le email richiedono configurazione SMTP in `.env`:
```env
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USER=your_email
MAIL_PASS=your_password
```

Per testing locale, puoi disabilitare le email commentando le chiamate `sendEmail()` negli API endpoints.

---

## 📊 Verifica Database

Apri phpMyAdmin o HeidiSQL e verifica:

```sql
-- Conta regioni
SELECT COUNT(*) FROM regioni; -- Dovrebbe essere 20

-- Conta servizi
SELECT COUNT(*) FROM servizi; -- Dovrebbe essere 8

-- Conta province
SELECT COUNT(*) FROM province; -- Dovrebbe essere 71

-- Conta comuni
SELECT COUNT(*) FROM comuni; -- Dovrebbe essere 44

-- Verifica admin user
SELECT * FROM users;
```

---

## 🚀 Prossimi Passi

Una volta verificato che il sito funziona:

1. **Testare Form Lead**
   - Invia un lead di test
   - Verifica che venga salvato nel database

2. **Testare Routing**
   - Visita `/servizi/auto-all-asta` (dovrebbe dare 404 perché il template non esiste ancora)
   - Visita `/regioni/lombardia` (dovrebbe dare 404 perché il template non esiste ancora)

3. **Prossimo Sviluppo**
   - Creare template geo-localizzati
   - Creare admin panel (CRM)
   - Aggiungere pagine legali (privacy, cookie, termini)

---

## 📝 Note Importanti

- **Document Root**: DEVE essere `/public` non la root del progetto
- **mod_rewrite**: DEVE essere abilitato per il routing
- **PHP Version**: 7.4+ o 8.0+ (attualmente 8.3)
- **MySQL Version**: 5.7+ o 8.0+ (attualmente 8.4)

---

## ✅ Checklist Verifica

- [ ] Sito carica su http://astegiudiziarie24.test
- [ ] Homepage mostra tutte le sezioni
- [ ] CSS è caricato correttamente
- [ ] JavaScript funziona (menu mobile, FAQ accordion)
- [ ] Form lead si invia correttamente
- [ ] Database contiene i dati popolati
- [ ] Admin user è stato creato
- [ ] Sitemap.xml è accessibile
- [ ] 404 page funziona

---

**Se tutto funziona, sei pronto per procedere con lo sviluppo dei template geo-localizzati e dell'admin panel!** 🎉
