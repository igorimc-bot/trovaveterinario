<?php
/**
 * Homepage
 * Main landing page for Aste Giudiziarie 24
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// SEO Meta
$metaTitle = getSetting('site_name', 'Aste Giudiziarie 24') . ' - ' . getSetting('site_tagline', 'Assistenza Completa per Aste in Italia');
$metaDescription = 'Assistenza completa per aste giudiziarie e fallimentari in Italia. Consulenza gratuita, supporto legale, perizie. Auto, case, barche, immobili all\'asta. Trova le migliori opportunità.';
$canonical = APP_URL;

// Schema Markup - Organization
$schemaMarkup = [
    '@context' => 'https://schema.org',
    '@type' => 'ProfessionalService',
    'name' => 'Aste Giudiziarie 24',
    'description' => 'Assistenza completa per aste giudiziarie e fallimentari in Italia',
    'url' => APP_URL,
    'areaServed' => [
        '@type' => 'Country',
        'name' => 'Italia'
    ],
    'serviceType' => [
        'Consulenza Aste Giudiziarie',
        'Assistenza Aste Fallimentari',
        'Perizie Immobiliari',
        'Assistenza Legale Aste'
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
    <!-- Slideshow Backgrounds -->
    <div class="hero-slideshow">
        <div class="hero-slide active" style="background-image: url('/assets/img/hero-slide-1.png');"></div>
        <div class="hero-slide" style="background-image: url('/assets/img/hero-slide-2.png');"></div>
        <div class="hero-slide" style="background-image: url('/assets/img/hero-slide-3.png');"></div>
    </div>

    <div class="hero-overlay"></div>
    <div class="container hero-container">
        <h1 class="hero-title">
            <span class="text-highlight">Aste Giudiziarie 24</span><br>
            Il Tuo Partner per le Aste in Italia
        </h1>
        <p class="hero-subtitle">
            <?= getContent('hero_subtitle', 'Consulenza professionale, supporto legale e perizie per acquisti all\'asta in tutta Italia. Trova le migliori opportunità nella tua zona.') ?>
        </p>

        <div class="hero-cta">
            <a href="#contatti" class="btn btn-primary btn-lg">Richiedi Consulenza Gratuita</a>
            <a href="#servizi" class="btn btn-secondary btn-lg">Scopri i Servizi</a>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="servizi" class="services-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>I Nostri Servizi</h2>
            <p>Assistenza completa per ogni tipo di asta giudiziaria e fallimentare</p>
        </div>

        <div class="services-grid">
            <!-- Styles moved to style.css -->
            <?php foreach ($servizi as $s): ?>
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
                                    style="display: flex; align-items: center; justify-content: center; height: 100%; background: linear-gradient(45deg, #e6e9f0 0%, #eef1f5 100%); color: #999; font-weight: 600; border-radius: 15px;">
                                    Aste Giudiziarie 24
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="service-card-content">
                            <h3 class="service-title">
                                <?= htmlspecialchars($s['nome']) ?>
                            </h3>

                            <?php if (!empty($s['features'])): ?>
                                <div class="service-features">
                                    Features: <?= htmlspecialchars($s['features']) ?>
                                </div>
                            <?php endif; ?>

                            <div class="service-divider"></div>

                            <p class="service-description">
                                <?= htmlspecialchars($s['descrizione_breve']) ?>
                            </p>

                            <?php if (!empty($s['prezzo'])): ?>
                                <div class="service-footer">
                                    <span class="service-price-label">Price Per Person:</span>
                                    <span class="service-price">
                                        $<?= number_format($s['prezzo'], 2, ',', '.') ?>
                                    </span>
                                </div>
                            <?php endif; ?>
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
            <h2>Perché Scegliere Aste Giudiziarie 24</h2>
            <p>Affidati a professionisti del settore per un acquisto sicuro e vantaggioso</p>
        </div>

        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">✓</div>
                <h3>Consulenza Gratuita</h3>
                <p>Valutazione preliminare senza impegno per ogni richiesta.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">👨‍⚖️</div>
                <h3>Esperti del Settore</h3>
                <p>Avvocati, periti e consulenti qualificati a tua disposizione.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">🇮🇹</div>
                <h3>Copertura Nazionale</h3>
                <p>Assistenza operativa in tutte le regioni italiane.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">🔒</div>
                <h3>Trasparenza Totale</h3>
                <p>Preventivi chiari e dettagliati, nessun costo nascosto.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">📋</div>
                <h3>Supporto Completo</h3>
                <p>Dall'analisi dell'asta alla gestione burocratica post-acquisto.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">⚡</div>
                <h3>Risposta Rapida</h3>
                <p>Contatto entro 24 ore dalla tua richiesta di informazioni.</p>
            </div>
        </div>
    </div>
</section>

<!-- Regions Section -->
<section id="regioni" class="regions-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Operiamo in Tutta Italia</h2>
            <p>Seleziona la tua regione per trovare le migliori opportunità</p>
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
            <h2>Richiedi Consulenza Gratuita</h2>
            <p>Compila il form sottostante e ti ricontatteremo entro 24 ore</p>
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
                <h3 class="faq-question">Cos'è un'asta giudiziaria?</h3>
                <div class="faq-answer">
                    <p>Un'asta giudiziaria è una vendita pubblica di beni (immobili, veicoli, mobili) disposta
                        dall'autorità giudiziaria nell'ambito di procedure esecutive o fallimentari.</p>
                </div>
            </div>

            <div class="faq-item">
                <h3 class="faq-question">Quali sono i vantaggi di acquistare all'asta?</h3>
                <div class="faq-answer">
                    <p>I principali vantaggi sono: prezzi inferiori al mercato, trasparenza della procedura, garanzie
                        legali, e possibilità di trovare opportunità uniche.</p>
                </div>
            </div>

            <div class="faq-item">
                <h3 class="faq-question">Che tipo di assistenza offrite?</h3>
                <div class="faq-answer">
                    <p>Offriamo consulenza completa: analisi dell'asta, perizie, assistenza legale, supporto per
                        finanziamenti, e gestione post-acquisto.</p>
                </div>
            </div>

            <div class="faq-item">
                <h3 class="faq-question">La consulenza è davvero gratuita?</h3>
                <div class="faq-answer">
                    <p>Sì, la valutazione preliminare è completamente gratuita e senza impegno. Ti forniremo un
                        preventivo dettagliato solo se decidi di procedere.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>