<?php
/**
 * Servizio + Location Template
 * Dynamic page for service + location combinations
 * Examples: /auto-all-asta/lombardia, /case-all-asta/milano
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// $servizio, $location, $locationType are set by router.php
if (!isset($servizio) || !$servizio || !isset($location) || !$location || !isset($locationType)) {
    http_response_code(404);
    require PUBLIC_PATH . '/404.php';
    exit;
}

// Get all servizi for form
$allServizi = getAllServizi();

// Build breadcrumb based on location type
$breadcrumbItems = [['name' => 'Home', 'url' => APP_URL]];

if ($locationType === 'regione') {
    $breadcrumbItems[] = ['name' => $location['nome'], 'url' => APP_URL . '/regioni/' . $location['slug']];
} elseif ($locationType === 'provincia') {
    // Get provincia with regione
    $provinciaFull = getProvinciaWithRegione($location['slug']);
    if ($provinciaFull) {
        $breadcrumbItems[] = ['name' => $provinciaFull['regione_nome'], 'url' => APP_URL . '/regioni/' . $provinciaFull['regione_slug']];
        $breadcrumbItems[] = ['name' => 'Provincia di ' . $provinciaFull['nome'], 'url' => APP_URL . '/province/' . $provinciaFull['slug']];
    }
} elseif ($locationType === 'comune') {
    // Get comune with full details
    $comuneFull = getComuneWithDetails($location['slug']);
    if ($comuneFull) {
        $breadcrumbItems[] = ['name' => $comuneFull['regione_nome'], 'url' => APP_URL . '/regioni/' . $comuneFull['regione_slug']];
        $breadcrumbItems[] = ['name' => 'Provincia di ' . $comuneFull['provincia_nome'], 'url' => APP_URL . '/province/' . $comuneFull['provincia_slug']];
        $breadcrumbItems[] = ['name' => $comuneFull['nome'], 'url' => APP_URL . '/comuni/' . $comuneFull['slug']];
    }
}

$breadcrumbItems[] = ['name' => $servizio['nome'], 'url' => '#'];

// SEO Meta
$locationName = $location['nome'];
$serviceName = $servizio['nome'];
$locationPreposition = ($locationType === 'regione') ? 'in' : 'a';

$metaTitle = "{$serviceName} {$locationPreposition} {$locationName} - I Migliori Specialisti | Trova Veterinario";
$metaDescription = "Cerchi {$serviceName} {$locationPreposition} {$locationName}? Trova i migliori veterinari, cliniche e pronto soccorso H24 nella tua zona. Leggi le recensioni e prenota.";
$metaKeywords = "{$serviceName} {$locationName}, veterinario {$locationName}, clinica veterinaria {$locationName}, pronto soccorso veterinario {$locationName}, {$serviceName} vicino a me";
// Canonical URL
$locationSlugPrefix = $locationType === 'provincia' ? 'provincia-' : '';
$canonical = APP_URL . '/' . $servizio['slug'] . '/' . $locationSlugPrefix . $location['slug'];

// Schema Markup
$serviceSchema = generateServiceSchema($servizio, $location, $locationType);
$breadcrumbSchema = generateBreadcrumbSchema($breadcrumbItems);

// Location preposition (in/a)
$locationPrep = $locationType === 'comune' ? 'a' : 'in';
$locationName = $locationType === 'provincia' ? 'Provincia di ' . $location['nome'] : $location['nome'];

// Include header
include __DIR__ . '/../includes/header.php';

// Get sub-locations based on current location type
$subLocations = [];
$subLocationType = '';
$subLocationTitle = '';

if ($locationType === 'regione') {
    $subLocations = getProvinceByRegione($location['id']);
    $subLocationType = 'provincia';
    $subLocationTitle = 'Province in ' . $location['nome'];
} elseif ($locationType === 'provincia') {
    // For provinces, we want to show comuni
    // Note: getComuniByProvincia might return a lot of results, we might want to paginate or limit?
    // For now, let's show all as per "menu regione" behavior
    $subLocations = getComuniByProvincia($location['id']);
    $subLocationType = 'comune';
    $subLocationTitle = 'Comuni in Provincia di ' . $location['nome'];
}
?>

<!-- Breadcrumb -->
<section class="breadcrumb-section">
    <div class="container">
        <?= renderBreadcrumbs($breadcrumbItems) ?>
    </div>
</section>

<!-- Hero Section -->
<section class="hero hero-small" <?php if (!empty($servizio['immagine'])): ?>style="background-image: url('/<?= ltrim(htmlspecialchars($servizio['immagine']), '/') ?>'); background-size: cover; background-position: center;"
    <?php endif; ?>>
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">
                <?= htmlspecialchars($servizio['nome']) ?>
                <?= $locationPrep ?>
                <?= htmlspecialchars($locationName) ?>
            </h1>
            <p class="hero-subtitle">
                Assistenza completa per
                <?= strtolower(htmlspecialchars($servizio['nome'])) ?>
                <?= $locationPrep ?>
                <?= htmlspecialchars($locationName) ?>.
                Consulenza gratuita, esperti locali, supporto legale.
            </p>
        </div>
    </div>
</section>

<!-- Service Description -->
<section class="content-section">
    <div class="container">
        <div class="content-wrapper">
            <h2>Assistenza per
                <?= htmlspecialchars($servizio['nome']) ?>
                <?= $locationPrep ?>
                <?= htmlspecialchars($locationName) ?>
            </h2>

            <?php
            // Always use the generator to wrap content with location specifics
            require_once __DIR__ . '/../includes/text_generator.php';
            echo generateServiceLocationContent($servizio, $location, $locationType);
            ?>

            <?php if (empty($servizio['contenuto'])): // Only show button for fully generated content ?>
                <div class="text-center mt-4" style="margin-top: 2rem;">
                    <a href="#contatti" class="btn btn-primary">Richiedi Consulenza Gratuita</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Benefits Section -->
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

<!-- Sub-locations Section (Provinces/Comuni) -->
<?php if (!empty($subLocations)): ?>
    <section class="locations-section">
        <div class="container">
            <div class="section-header">
                <h2><?= htmlspecialchars($subLocationTitle) ?></h2>
                <p>Seleziona un'area per vederne i dettagli</p>
            </div>

            <div class="locations-grid">
                <?php foreach ($subLocations as $subLoc): ?>
                    <?php
                    // Build URL based on sub-location type
                    $subLocUrl = '/' . $servizio['slug'] . '/';
                    if ($subLocationType === 'provincia') {
                        $subLocUrl .= 'provincia-' . $subLoc['slug'];
                    } else {
                        $subLocUrl .= $subLoc['slug'];
                    }
                    ?>
                    <a href="<?= htmlspecialchars($subLocUrl) ?>" class="location-card">
                        <h3><?= htmlspecialchars($subLoc['nome']) ?></h3>
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
            <?php include __DIR__ . '/components/lead-form-wizard.php'; ?>
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

<?php include __DIR__ . '/../includes/footer.php'; ?>