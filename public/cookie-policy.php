<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Policy - Trova Veterinario</title>
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .legal-content {
            padding: 120px 0 80px;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
            color: var(--text-main);
        }

        .legal-content h1 {
            margin-bottom: 30px;
            color: var(--primary-accent);
        }

        .legal-content h2 {
            margin: 30px 0 15px;
            font-size: 1.3rem;
        }

        .legal-content p {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <div class="container">
        <article class="legal-content">
            <h1>Cookie Policy</h1>
            <p>I cookie sono piccoli file di testo che vengono memorizzati sul tuo dispositivo quando visiti un sito
                web. Questo sito utilizza cookie tecnici e analitici per garantire il corretto funzionamento e
                migliorare l'esperienza utente.</p>

            <h2>1. Quali cookie utilizziamo</h2>
            <p>Utilizziamo le seguenti tipologie di cookie:</p>
            <ul>
                <li><strong>Cookie Tecnici:</strong> Necessari per la navigazione, la gestione delle sessioni e la
                    sicurezza del sito. Senza questi cookie, il sito potrebbe non funzionare correttamente.</li>
                <li><strong>Cookie Analitici:</strong> Utilizzati per raccogliere informazioni in forma aggregata sul
                    numero di visitatori e su come navigano il sito.</li>
            </ul>

            <h2>2. Cookie di terze parti</h2>
            <p>Il sito potrebbe includere componenti forniti da terze parti (come icone FontAwesome o font di Google)
                che potrebbero raccogliere dati tecnici anonimi per il loro corretto funzionamento.</p>

            <h2>3. Come gestire i cookie</h2>
            <p>Puoi scegliere di disabilitare i cookie modificando le impostazioni del tuo browser. Ti ricordiamo però
                che la disabilitazione dei cookie tecnici potrebbe compromettere alcune funzionalità del sito.</p>
            <p>Per maggiori informazioni su come gestire i cookie nei browser più diffusi:</p>
            <ul>
                <li><a href="https://support.google.com/chrome/answer/95647?hl=it" target="_blank">Google Chrome</a>
                </li>
                <li><a href="https://support.mozilla.org/it/kb/Gestione%20dei%20cookie" target="_blank">Mozilla
                        Firefox</a></li>
                <li><a href="https://support.apple.com/it-it/guide/safari/sfri11471/mac" target="_blank">Apple
                        Safari</a></li>
                <li><a href="https://support.microsoft.com/it-it/windows/eliminare-e-gestire-i-cookie-168dab11-0753-043d-7c16-ede5947fc64d"
                        target="_blank">Microsoft Edge</a></li>
            </ul>

            <h2>4. Aggiornamenti</h2>
            <p>Ci riserviamo il diritto di modificare questa Cookie Policy in qualsiasi momento. Gli aggiornamenti
                verranno pubblicati su questa pagina con la relativa data di revisione.</p>
        </article>
    </div>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>