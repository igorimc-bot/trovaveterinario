<?php
$metaTitle = "Termini e Condizioni - Trovaveterinario";
$metaDescription = "Termini e condizioni di utilizzo del servizio Trovaveterinario.";
include __DIR__ . '/../includes/header.php';
?>

<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Termini e Condizioni</li>
            </ol>
        </nav>
    </div>
</div>

<section class="legal-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="mb-5">Termini e Condizioni di Utilizzo</h1>

                <div class="legal-content">
                    <p class="lead">L'accesso e l'uso di questo sito web (trovaveterinario.com) presuppongono
                        l'accettazione dei presenti Termini e Condizioni.</p>

                    <h3 class="mt-4">1. Informazioni Generali</h3>
                    <p>Il sito è gestito da <strong>NOVATECH INFORMATICA di Riboni Igor</strong> (di seguito
                        "Titolare").<br>
                        Per qualsiasi informazione è possibile scrivere a: <a
                            href="mailto:info@trovaveterinario.com">info@trovaveterinario.com</a>.</p>

                    <h3 class="mt-4">2. Oggetto del Servizio</h3>
                    <p>Trova Veterinario offre un servizio di intermediazione informativa nel settore
                        della salute animale. Il sito ha lo scopo di mettere in contatto l'Utente con
                        professionisti del settore (veterinari, cliniche, specialisti) per prenotare visite o richiedere
                        consulenze.</p>
                    <p>Il sito <strong>non</strong> è una testata giornalistica e <strong>non</strong> svolge attività
                        medico-veterinaria diretta, limitandosi a fornire un elenco di professionisti qualificati.</p>

                    <h3 class="mt-4">3. Limitazione di Responsabilità</h3>
                    <p>Sebbene il Titolare si impegni a fornire informazioni accurate e aggiornate, non può garantire
                        l'assoluta completezza o assenza di errori delle informazioni riportate relative ai
                        professionisti (che
                        fanno sempre fede ai dati forniti dagli ordini competenti).</p>
                    <p>Il Titolare non sarà responsabile per eventuali danni diretti o indiretti derivanti dall'utilizzo
                        delle informazioni presenti sul sito.</p>

                    <h3 class="mt-4">4. Proprietà Intellettuale</h3>
                    <p>Tutti i contenuti del sito (testi, loghi, grafica, immagini, software) sono di proprietà del
                        Titolare o dei rispettivi proprietari e sono protetti dalle leggi sul diritto d'autore. È
                        vietata la riproduzione, anche parziale, senza esplicito consenso scritto.</p>

                    <h3 class="mt-4">5. Modifiche ai Termini</h3>
                    <p>Il Titolare si riserva il diritto di modificare i presenti Termini e Condizioni in qualsiasi
                        momento. L'Utente è tenuto a verificare periodicamente questa pagina.</p>

                    <h3 class="mt-4">6. Legge Applicabile</h3>
                    <p>I presenti Termini sono regolati dalla legge italiana. Per qualsiasi controversia sarà competente
                        in via esclusiva il Foro di competenza del Titolare.</p>

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
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>