# Trova Veterinario

Trova Veterinario è il portale italiano dedicato alla ricerca di professionisti per la salute animale.
Permette agli utenti di trovare veterinari, cliniche e pronto soccorso specializzati per cani, gatti, animali esotici e da fattoria.

## 🚀 Caratteristiche

- **Ricerca Geocalizzata**: Trova specialisti in tutta Italia (Regioni, Province, Comuni).
- **Filtri per Specializzazione**: Cani, gatti, rettili, uccelli, animali da fattoria, ecc.
- **Lead Generation**: Form wizard multi-step per richiedere appuntamenti o preventivi.
- **Area Partner**: Gestione per veterinari e cliniche che vogliono aderire al network.
- **Blog e Guide**: Informazioni utili per la cura degli animali.

## 🛠️ Requisiti Tecnici

- PHP 7.4+
- MySQL 5.7+ / MariaDB 10.3+
- Composer
- Web Server (Apache/Nginx) con `mod_rewrite` abilitato

## 📦 Installazione

1.  **Clona il repository**
    ```bash
    git clone https://github.com/igorimc-bot/trovaveterinario.git
    cd trovaveterinario
    ```

2.  **Installa le dipendenze**
    ```bash
    composer install
    ```

3.  **Configura l'ambiente**
    Copia il file di esempio e configura i dati del database:
    ```bash
    cp .env.example .env
    ```

4.  **Database**
    Importa il dump del database (struttura e dati geografici).

## 🔒 Sicurezza

- Protezione CSRF su tutti i form
- Sanitizzazione degli input
- Integrazione Google reCAPTCHA v3
- Hash delle password sicuri

## 📞 Contatti

Per informazioni o supporto: `info@trovaveterinario.com`
