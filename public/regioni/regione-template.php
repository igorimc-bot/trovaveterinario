<?php
/**
 * Regione Template
 * Dynamic page for region-specific content
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// $regione is set by router.php
if (!isset($regione) || !$regione) {
    http_response_code(404);
    require PUBLIC_PATH . '/404.php';
    exit;
}

// Get related data
$servizi = getAllServizi();
$province = getProvinceByRegione($regione['id']);

// SEO Meta
// SEO Meta
$locationName = $regione['nome'];
$metaTitle = "Veterinari in {$locationName} - Cerca Cliniche e Ambulatori | Trova Veterinario";
$metaDescription = "Cerchi un veterinario in {$locationName}? Trova i migliori specialisti, cliniche veterinarie e pronto soccorso H24 nella regione {$locationName}. Leggi recensioni e prenota.";
$metaKeywords = "veterinario {$locationName}, clinica veterinaria {$locationName}, pronto soccorso veterinario {$locationName}, veterinari {$locationName}";
$canonical = APP_URL . '/regioni/' . $regione['slug'];

// Breadcrumb
$breadcrumbItems = [
    ['name' => 'Home', 'url' => APP_URL],
    ['name' => $regione['nome'], 'url' => $canonical]
];

// Schema Markup
$serviceSchema = generateServiceSchema(
    ['nome' => 'Veterinari e Cliniche', 'descrizione_breve' => 'Assistenza veterinaria completa'],
    $regione,
    'regione'
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
                Veterinari in <?= htmlspecialchars($regione['nome']) ?>
            </h1>
            <p class="hero-subtitle">
                Trova i migliori veterinari e cliniche in <?= htmlspecialchars($regione['nome']) ?>. Consulenza,
                pronto soccorso e specialisti.
            </p>
        </div>
    </div>
</section>

<!-- Custom Content (if available) -->
<?php if (!empty($regione['contenuto_custom'])): ?>
    <section class="content-section">
        <div class="container">
            <div class="content-wrapper">
                <?= $regione['contenuto_custom'] ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Services Section -->
<!-- Services Section - Animals -->
<section class="services-section">
    <div class="container">
        <div class="section-header">
            <h2>Cure e Assistenze Specializzate in <?= htmlspecialchars($regione['nome']) ?></h2>
            <p>Scegli la specializzazione di cui hai bisogno</p>
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
                <a href="/<?= $s['slug'] ?>/<?= $regione['slug'] ?>" class="service-link"
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
                            <h3 class="service-title"><?= htmlspecialchars($s['nome']) ?> in
                                <?= htmlspecialchars($regione['nome']) ?>
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
            <h2>Interventi Veterinari Professionali in <?= htmlspecialchars($regione['nome']) ?></h2>
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
                <a href="/<?= $s['slug'] ?>/<?= $regione['slug'] ?>" class="service-link"
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
                            <h3 class="service-title"><?= htmlspecialchars($s['nome']) ?> in
                                <?= htmlspecialchars($regione['nome']) ?>
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

<!-- Province Section -->
<?php if (!empty($province)): ?>
    <section class="locations-section">
        <div class="container">
            <div class="section-header">
                <h2>Province in <?= htmlspecialchars($regione['nome']) ?></h2>
                <p>Seleziona la tua provincia per informazioni più specifiche</p>
            </div>

            <div class="locations-grid">
                <?php foreach ($province as $prov): ?>
                    <a href="/province/<?= $prov['slug'] ?>" class="location-card">
                        <h3><?= htmlspecialchars($prov['nome']) ?></h3>
                        <span class="location-arrow">→</span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

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
            $locationType = 'regione';
            $location = $regione;
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
<?= $breadcrumbSchema ?>
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>