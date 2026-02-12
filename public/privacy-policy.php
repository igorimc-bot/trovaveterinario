<?php
$metaTitle = "Privacy Policy - Trovaveterinario";
$metaDescription = "Informativa sulla Privacy di Trovaveterinario. Scopri come trattiamo i tuoi dati personali in conformità con il GDPR.";
include __DIR__ . '/../includes/header.php';
?>

<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Privacy Policy</li>
            </ol>
        </nav>
    </div>
</div>

<section class="legal-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="mb-5">Informativa sulla Privacy</h1>

                <div class="legal-content">
                    <p class="lead">Gentile Utente, in questa pagina si descrivono le modalità di gestione del sito in
                        riferimento al trattamento dei dati personali degli utenti che lo consultano.</p>

                    <p>Si tratta di un'informativa resa ai sensi degli artt. 13 e 14 del Regolamento (UE) 2016/679
                        (GDPR) a coloro che interagiscono con i servizi web di <strong>Aste Giudiziarie 24</strong>,
                        accessibili per via telematica a partire dall'indirizzo:
                        <strong>https://astegiudiziarie24.it</strong>.
                    </p>

                    <h3 class="mt-4">1. Titolare del Trattamento</h3>
                    <p>Il Titolare del trattamento dei dati è:</p>
                    <div class="alert alert-light border">
                        <strong>NOVATECH INFORMATICA di Riboni Igor</strong><br>
                        Email di contatto: <a href="mailto:info@trovaveterinario.com">info@trovaveterinario.com</a>
                    </div>

                    <h3 class="mt-4">2. Tipologia di Dati Raccimolati</h3>
                    <p>Fra i Dati Personali raccolti da questa Applicazione, in modo autonomo o tramite terze parti, ci
                        sono: Cookie; Dati di utilizzo; email; nome; cognome; numero di telefono; provincia; regione;
                        comune.</p>

                    <h4 class="mt-3">Dati forniti volontariamente dall'utente</h4>
                    <p>L'invio facoltativo, esplicito e volontario di posta elettronica agli indirizzi indicati su
                        questo sito, o la compilazione di form di contatto, comporta la successiva acquisizione
                        dell'indirizzo del mittente, necessario per rispondere alle richieste, nonché degli eventuali
                        altri dati personali inseriti nella missiva.</p>

                    <h3 class="mt-4">3. Finalità del Trattamento</h3>
                    <p>I Dati dell'Utente sono raccolti per consentire al Titolare di fornire i propri Servizi, così
                        come per le seguenti finalità:</p>
                    <ul>
                        <li>Contattare l'Utente per fornire informazioni sui servizi richiesti.</li>
                        <li>Gestione delle richieste di assistenza e supporto.</li>
                        <li>Analisi statistica (in forma anonima e aggregata).</li>
                        <li>Adempimento di obblighi di legge, contabili e fiscali.</li>
                    </ul>

                    <h3 class="mt-4">4. Modalità del Trattamento</h3>
                    <p>Il Titolare adotta le opportune misure di sicurezza volte ad impedire l'accesso, la divulgazione,
                        la modifica o la distruzione non autorizzate dei Dati Personali. Il trattamento viene effettuato
                        mediante strumenti informatici e/o telematici, con modalità organizzative e con logiche
                        strettamente correlate alle finalità indicate.</p>

                    <h3 class="mt-4">5. Base Giuridica del Trattamento</h3>
                    <p>Il Titolare tratta Dati Personali relativi all'Utente in caso sussista una delle seguenti
                        condizioni:</p>
                    <ul>
                        <li>L'Utente ha prestato il consenso per una o più finalità specifiche (es. invio form
                            contatti).</li>
                        <li>Il trattamento è necessario all'esecuzione di un contratto con l'Utente e/o all'esecuzione
                            di misure precontrattuali.</li>
                        <li>Il trattamento è necessario per adempiere un obbligo legale al quale è soggetto il Titolare.
                        </li>
                    </ul>

                    <h3 class="mt-4">6. Periodo di Conservazione</h3>
                    <p>I Dati sono trattati e conservati per il tempo richiesto dalle finalità per le quali sono stati
                        raccolti. Al termine del periodo di conservazione i Dati Personali saranno cancellati.</p>

                    <h3 class="mt-4">7. Diritti dell'Utente</h3>
                    <p>Gli Utenti possono esercitare determinati diritti con riferimento ai Dati trattati dal Titolare.
                        In particolare, l'Utente ha il diritto di:</p>
                    <ul>
                        <li>Revocare il consenso in ogni momento.</li>
                        <li>Opporsi al trattamento dei propri Dati.</li>
                        <li>Accedere ai propri Dati.</li>
                        <li>Verificare e chiedere la rettificazione.</li>
                        <li>Ottenere la limitazione del trattamento.</li>
                        <li>Ottenere la cancellazione o rimozione dei propri Dati Personali.</li>
                    </ul>
                    <p>Per esercitare i diritti dell'Utente, le richieste possono essere indirizzate al Titolare
                        all'indirizzo email indicato in questo documento.</p>

                    <h3 class="mt-4">8. Modifiche a questa Privacy Policy</h3>
                    <p>Il Titolare del Trattamento si riserva il diritto di apportare modifiche alla presente privacy
                        policy in qualunque momento dandone informazione agli Utenti su questa pagina. Si prega dunque
                        di consultare regolarmente questa pagina, facendo riferimento alla data di ultima modifica
                        indicata in fondo.</p>

                    <p class="mt-5 text-muted small">Ultimo aggiornamento:
                        <?= date('d/m/Y') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .legal-content p,
    .legal-content li {
        font-size: 1.1rem;
        color: var(--text-main);
        line-height: 1.7;
        margin-bottom: 1rem;
    }

    .legal-content h3 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .legal-content ul {
        list-style-type: disc;
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>