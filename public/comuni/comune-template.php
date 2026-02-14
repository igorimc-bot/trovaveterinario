<?php
/**
 * Comune Template
 * Dynamic page for municipality-specific content
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// $comune is set by router.php (but it lacks full details, so we might need to fetch them if needed, but getComuneWithDetails is better)
if (!isset($comune) || !$comune) {
    http_response_code(404);
    require PUBLIC_PATH . '/404.php';
    exit;
}

// Get full comune details with province and region
$comuneFull = getComuneWithDetails($comune['slug']);
if (!$comuneFull) {
    http_response_code(404);
    require PUBLIC_PATH . '/404.php';
    exit;
}

// Get related data
$servizi = getAllServizi();

// SEO Meta
$locationName = $comuneFull['nome'];
$provinceName = $comuneFull['provincia_nome'];
$metaTitle = "Veterinario a {$locationName} ({$provinceName}) - Studi e Visite | Trova Veterinario";
$metaDescription = "Cerchi un veterinario a {$locationName}? Trova specialisti per cani, gatti ed esotici vicino a te a {$locationName}. Visualizza orari, contatti e recensioni.";
$metaKeywords = "veterinario {$locationName}, clinica veterinaria {$locationName}, veterinario vicino a me {$locationName}, veterinario {$locationName} {$provinceName}";
$canonical = APP_URL . '/comuni/' . $comuneFull['slug'];

// Breadcrumb
$breadcrumbItems = [
    ['name' => 'Home', 'url' => APP_URL],
    ['name' => $comuneFull['regione_nome'], 'url' => APP_URL . '/regioni/' . $comuneFull['regione_slug']],
    ['name' => $comuneFull['provincia_nome'], 'url' => APP_URL . '/province/' . $comuneFull['provincia_slug']],
    ['name' => $comuneFull['nome'], 'url' => $canonical]
];

// Schema Markup
$serviceSchema = generateServiceSchema(
    ['nome' => 'Veterinari e Cliniche', 'descrizione_breve' => 'Assistenza veterinaria completa'],
    $comuneFull,
    'comune'
);
$localBusinessSchema = generateLocalBusinessSchema(
    $comuneFull,
    ['nome' => $comuneFull['provincia_nome']],
    ['nome' => $comuneFull['regione_nome']]
);
$breadcrumbSchema = generateBreadcrumbSchema($breadcrumbItems);

// Include header
include __DIR__ . '/../../includes/header.php';
?>

<!-- Breadcrumb -->
<section class="breadcrumb-section">
    <div class="container">
        <?= renderBreadcrumbs($breadcrumbItems) ?>
    </div>
</section>

<!-- Hero Section -->
<section class="hero hero-small">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">
                Veterinari a <?= htmlspecialchars($comuneFull['nome']) ?>
            </h1>
            <p class="hero-subtitle">
                Trova i migliori veterinari e cliniche a <?= htmlspecialchars($comuneFull['nome']) ?>
                (<?= htmlspecialchars($comuneFull['provincia_sigla']) ?>). Consulenza, pronto soccorso e specialisti.
            </p>
        </div>
    </div>
</section>

<!-- Custom Content (if available) -->
<?php if (!empty($comuneFull['contenuto_custom'])): ?>
    <section class="content-section">
        <div class="container">
            <div class="content-wrapper">
                <?= $comuneFull['contenuto_custom'] ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Services Section -->
<!-- Services Section - Animals -->
<section class="services-section">
    <div class="container">
        <div class="section-header">
            <h2>Cure e Assistenze Specializzate a <?= htmlspecialchars($comuneFull['nome']) ?></h2>
            <p>Scopri specialisti e cliniche nel tuo comune</p>
        </div>

        <div class="services-grid">
            <?php
            $animali = array_filter($servizi, function ($s) {
                return isset($s['categoria']) && $s['categoria'] === 'animali';
            });
            if (empty($animali) && !empty($servizi) && !isset($servizi[0]['categoria'])) {
                $animali = $servizi;
            }

            foreach ($animali as $s): ?>
                <?php if (isset($s['attivo']) && $s['attivo'] == 0)
                    continue; ?>
                <a href="/<?= $s['slug'] ?>/<?= $comuneFull['slug'] ?>" class="service-link"
                    style="text-decoration: none; color: inherit;">
                    <article class="service-card">
                        <div class="service-card-img">
                            <?php if (!empty($s['immagine'])): ?>
                                <img src="/<?= ltrim(htmlspecialchars($s['immagine']), '/') ?>"
                                    alt="<?= htmlspecialchars($s['nome']) ?>">
                            <?php else: ?>
                                <div
                                    style="display: flex; align-items: center; justify-content: center; height: 100%; background: linear-gradient(45deg, #e6e9f0 0%, #eef1f5 100%); color: #999; font-weight: 600;">
                                    Trova Veterinario
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="service-card-content">
                            <h3 class="service-title"><?= htmlspecialchars($s['nome']) ?> a
                                <?= htmlspecialchars($comuneFull['nome']) ?>
                            </h3>
                            <?php if (!empty($s['features'])): ?>
                                <div class="service-features">Features: <?= htmlspecialchars($s['features']) ?></div>
                            <?php endif; ?>
                            <div class="service-divider"></div>
                            <p class="service-description"><?= htmlspecialchars($s['descrizione_breve']) ?></p>
                            <?php if (!empty($s['prezzo'])): ?>
                                <div class="service-footer">
                                    <span class="service-price-label">Prezzo a persona:</span>
                                    <span class="service-price">€<?= number_format($s['prezzo'], 2, ',', '.') ?></span>
                                </div>
                            <?php endif; ?>
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
        <div class="section-header">
            <h2>Interventi Veterinari Professionali a <?= htmlspecialchars($comuneFull['nome']) ?></h2>
            <p>Scopri specialisti e cliniche nel tuo comune</p>
        </div>

        <div class="services-grid">
            <?php
            $interventi = array_filter($servizi, function ($s) {
                return isset($s['categoria']) && $s['categoria'] === 'interventi';
            });

            foreach ($interventi as $s): ?>
                <?php if (isset($s['attivo']) && $s['attivo'] == 0)
                    continue; ?>
                <a href="/<?= $s['slug'] ?>/<?= $comuneFull['slug'] ?>" class="service-link"
                    style="text-decoration: none; color: inherit;">
                    <article class="service-card">
                        <div class="service-card-img">
                            <?php if (!empty($s['immagine'])): ?>
                                <img src="/<?= ltrim(htmlspecialchars($s['immagine']), '/') ?>"
                                    alt="<?= htmlspecialchars($s['nome']) ?>">
                            <?php else: ?>
                                <div
                                    style="display: flex; align-items: center; justify-content: center; height: 100%; background: linear-gradient(45deg, #e6e9f0 0%, #eef1f5 100%); color: #999; font-weight: 600;">
                                    Trova Veterinario
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="service-card-content">
                            <h3 class="service-title"><?= htmlspecialchars($s['nome']) ?> a
                                <?= htmlspecialchars($comuneFull['nome']) ?>
                            </h3>
                            <?php if (!empty($s['features'])): ?>
                                <div class="service-features">Features: <?= htmlspecialchars($s['features']) ?></div>
                            <?php endif; ?>
                            <div class="service-divider"></div>
                            <p class="service-description"><?= htmlspecialchars($s['descrizione_breve']) ?></p>
                            <?php if (!empty($s['prezzo'])): ?>
                                <div class="service-footer">
                                    <span class="service-price-label">Prezzo a persona:</span>
                                    <span class="service-price">€<?= number_format($s['prezzo'], 2, ',', '.') ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section id="contatti" class="contact-section">
    <div class="container">
        <div class="section-header">
            <h2>Richiedi Consulenza Gratuita</h2>
            <p>Compila il form e ti ricontatteremo entro 24 ore</p>
        </div>

        <div class="contact-form-wrapper">
            <?php
            // Set context for wizard
            $locationType = 'comune';
            $location = $comuneFull;
            include __DIR__ . '/../components/lead-form-wizard.php';
            ?>
        </div>
    </div>
</section>

<!-- Schema Markup -->
<script type="application/ld+json">
<?= $serviceSchema ?>
</script>

<script type="application/ld+json">
<?= $localBusinessSchema ?>
</script>

<script type="application/ld+json">
<?= $breadcrumbSchema ?>
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>