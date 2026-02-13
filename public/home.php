<?php
/**
 * Homepage
 * Main landing page for Trova Veterinario
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// SEO Meta
$metaTitle = 'Trova Veterinario | Il Network N.1 per la Salute del tuo Animale';
$metaDescription = 'Cerchi un veterinario vicino a te? Su Trova Veterinario puoi trovare i migliori specialisti per cani, gatti, rettili, uccelli e animali da fattoria. Prenota visite, emergenze e pronto soccorso H24.';
$metaKeywords = 'veterinario, clinica veterinaria, pronto soccorso veterinario, veterinario cani, veterinario gatti, veterinario esotici, veterinario animali da fattoria, salute animale';
$canonical = APP_URL;

// Schema Markup - Organization
$schemaMarkup = [
    '@context' => 'https://schema.org',
    '@type' => 'MedicalOrganization',
    'name' => 'Trova Veterinario',
    'description' => 'Servizio di ricerca veterinari in Italia',
    'url' => APP_URL,
    'areaServed' => [
        '@type' => 'Country',
        'name' => 'Italia'
    ],
    'medicalSpecialty' => [
        'Veterinary Medicine',
        'Emergency Veterinary Medicine'
    ]
];

// Get services
$servizi = getAllServizi();
$regioni = getAllRegioni();

// Include header
include __DIR__ . '/../includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <!-- Hero Background Image -->
    <div class="hero-background" style="background-image: url('/assets/img/Hero-Homepage-Trovaveterinario.webp');">
    </div>

    <div class="hero-overlay"></div>
    <div class="container hero-container">
        <h1 class="hero-title">
            <span class="text-highlight">Trova Veterinario</span><br>
            Il Miglior Specialista per il Tuo Amico
        </h1>
        <p class="hero-subtitle">
            Il punto di riferimento per la salute del tuo animale. Cerca veterinari, cliniche e pronto soccorso H24 in
            tutta Italia.
        </p>

        <div class="hero-cta">
            <a href="#contatti" class="btn btn-primary btn-lg">Prenota una Visita</a>
            <a href="#servizi" class="btn btn-secondary btn-lg">I Nostri Servizi</a>
        </div>
    </div>
</section>

<!-- Services Section - Animals -->
<section id="servizi" class="services-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Cure e Assistenze Specializzate</h2>
            <p>Scegli la specializzazione di cui hai bisogno</p>
        </div>

        <div class="services-grid">
            <?php
            // Filter Categories
            $animali = array_filter($servizi, function ($s) {
                return isset($s['categoria']) && $s['categoria'] === 'animali';
            });

            if (empty($animali) && !empty($servizi) && !isset($servizi[0]['categoria'])) {
                $animali = $servizi; // Fallback
            }

            foreach ($animali as $s): ?>
                <?php if (isset($s['attivo']) && $s['attivo'] == 0)
                    continue; ?>
                <a href="/servizi/<?= $s['slug'] ?>" class="service-link" style="text-decoration: none; color: inherit;">
                    <article class="service-card">
                        <div class="service-card-img">
                            <?php if (!empty($s['immagine'])): ?>
                                <img src="<?= htmlspecialchars($s['immagine']) ?>?v=<?= time() ?>"
                                    alt="<?= htmlspecialchars($s['nome']) ?>">
                            <?php else: ?>
                                <div
                                    style="display: flex; align-items: center; justify-content: center; height: 100%; background: linear-gradient(45deg, #e6e9f0 0%, #eef1f5 100%); color: #666; font-weight: 600; text-align:center; padding: 10px;">
                                    <?= htmlspecialchars($s['nome']) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="service-card-content">
                            <h3 class="service-title">
                                <?= htmlspecialchars($s['nome']) ?>
                            </h3>

                            <?php if (!empty($s['features'])): ?>
                                <div class="service-features">
                                    Include: <?= htmlspecialchars($s['features']) ?>
                                </div>
                            <?php endif; ?>

                            <div class="service-divider"></div>

                            <p class="service-description">
                                <?= htmlspecialchars($s['descrizione_breve']) ?>
                            </p>
                        </div>
                    </article>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Services Section - Interventions -->
<section class="services-section" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
    <div class="container">
        <div class="section-header text-center">
            <h2>Interventi Veterinari Professionali</h2>
            <p>Scegli la specializzazione di cui hai bisogno</p>
        </div>

        <div class="services-grid">
            <?php
            $interventi = array_filter($servizi, function ($s) {
                return isset($s['categoria']) && $s['categoria'] === 'interventi';
            });

            foreach ($interventi as $s): ?>
                <?php if (isset($s['attivo']) && $s['attivo'] == 0)
                    continue; ?>
                <a href="/servizi/<?= $s['slug'] ?>" class="service-link" style="text-decoration: none; color: inherit;">
                    <article class="service-card">
                        <div class="service-card-img">
                            <?php if (!empty($s['immagine'])): ?>
                                <img src="<?= htmlspecialchars($s['immagine']) ?>?v=<?= time() ?>"
                                    alt="<?= htmlspecialchars($s['nome']) ?>">
                            <?php else: ?>
                                <div
                                    style="display: flex; align-items: center; justify-content: center; height: 100%; background: linear-gradient(45deg, #e6e9f0 0%, #eef1f5 100%); color: #666; font-weight: 600; text-align:center; padding: 10px;">
                                    <?= htmlspecialchars($s['nome']) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="service-card-content">
                            <h3 class="service-title">
                                <?= htmlspecialchars($s['nome']) ?>
                            </h3>

                            <?php if (!empty($s['features'])): ?>
                                <div class="service-features">
                                    Include: <?= htmlspecialchars($s['features']) ?>
                                </div>
                            <?php endif; ?>

                            <div class="service-divider"></div>

                            <p class="service-description">
                                <?= htmlspecialchars($s['descrizione_breve']) ?>
                            </p>
                        </div>
                    </article>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-us-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Perché Scegliere Trova Veterinario</h2>
            <p>La salute del tuo animale è la nostra priorità</p>
        </div>

        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">🐾</div>
                <h3>Specialisti Certificati</h3>
                <p>Collaboriamo solo con veterinari qualificati e strutture d'eccellenza.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">🚑</div>
                <h3>Pronto Soccorso</h3>
                <p>Reperibilità per urgenze e cliniche aperte 24 ore su 24.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">📍</div>
                <h3>Vicino a Te</h3>
                <p>Trova facilmente lo studio veterinario più comodo nella tua zona.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">🔬</div>
                <h3>Tecnologia Avanzata</h3>
                <p>Accesso a cliniche dotate delle più moderne strumentazioni diagnostiche.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">❤️</div>
                <h3>Amore per gli Animali</h3>
                <p>Passione e dedizione sono al centro di ogni visita e trattamento.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">📅</div>
                <h3>Facilità di Contatto</h3>
                <p>Richiedi appuntamenti o preventivi in pochi click.</p>
            </div>
        </div>
    </div>
</section>

<!-- Regions Section -->
<section id="regioni" class="regions-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Veterinari in Tutta Italia</h2>
            <p>Seleziona la tua regione per trovare gli specialisti più vicini</p>
        </div>

        <div class="regions-grid">
            <?php foreach ($regioni as $regione): ?>
                <a href="/regioni/<?= $regione['slug'] ?>" class="region-card">
                    <h3>
                        <?= htmlspecialchars($regione['nome']) ?>
                    </h3>
                    <span class="region-arrow">→</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section id="contatti" class="contact-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Richiedi Informazioni o Prenota</h2>
            <p>Compila il form per essere ricontattato da uno specialista</p>
        </div>

        <div class="contact-form-wrapper">
            <?php include __DIR__ . '/components/lead-form-wizard.php'; ?>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <div class="section-header">
            <h2>Domande Frequenti</h2>
        </div>

        <div class="faq-list">
            <div class="faq-item">
                <h3 class="faq-question">Come trovo un veterinario esperto in esotici?</h3>
                <div class="faq-answer">
                    <p>Utilizza la ricerca per servizio selezionando "Veterinario per Animali Esotici" o "Rettili" per
                        visualizzare gli specialisti nella tua zona.</p>
                </div>
            </div>

            <div class="faq-item">
                <h3 class="faq-question">Cosa fare in caso di emergenza notturna?</h3>
                <div class="faq-answer">
                    <p>Cerca il servizio "Pronto Soccorso Veterinario" per trovare cliniche con reperibilità 24/7 e
                        servizio notturno.</p>
                </div>
            </div>

            <div class="faq-item">
                <h3 class="faq-question">Effettuate visite a domicilio?</h3>
                <div class="faq-answer">
                    <p>Molti veterinari nel nostro network offrono visite a domicilio, specialmente per animali da
                        fattoria o difficili da trasportare.</p>
                </div>
            </div>

            <div class="faq-item">
                <h3 class="faq-question">È necessario l'appuntamento?</h3>
                <div class="faq-answer">
                    <p>Per le visite di routine è sempre consigliato prenotare. Per le urgenze, consigliamo di
                        contattare telefonicamente la struttura prima di recarsi sul posto.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>