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

<!-- Recommended Professionals / Premium Banner -->
<section class="recommended-professionals">
    <div class="container">
        <div class="section-overlay-text">PREMIUM</div>
        <div class="section-header">
            <h2 class="premium-title">Professionisti Consigliati</h2>
            <p class="premium-subtitle">L'eccellenza veterinaria selezionata per garantirti il meglio</p>
        </div>

        <?php
        $pdo = db()->getConnection();
        $locationId = $location['id'];
        $isProvincia = ($locationType === 'provincia');
        $isRegione = ($locationType === 'regione');

        $partnersList = [];
        try {
            if ($isProvincia) {
                $sql = "SELECT DISTINCT p.* FROM partners p
                        JOIN partner_province pp ON p.id = pp.partner_id
                        WHERE pp.provincia_id = ? AND p.stato = 'attivo'
                        LIMIT 3";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$locationId]);
                $partnersList = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } elseif ($isRegione) {
                $sql = "SELECT DISTINCT p.* FROM partners p
                        JOIN partner_regioni pr ON p.id = pr.partner_id
                        WHERE pr.regione_id = ? AND p.stato = 'attivo'
                        LIMIT 3";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$locationId]);
                $partnersList = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            logError("Error fetching partners: " . $e->getMessage());
        }
        ?>

        <div class="partners-premium-wrapper">
            <?php if (!empty($partnersList)): ?>
                <div class="partners-grid-premium">
                    <?php foreach ($partnersList as $partner): ?>
                        <div class="partner-card-new">
                            <div class="card-accent"></div>
                            <div class="card-logo-box">
                                <?php
                                $displayImg = $partner['immagine_vetrina'] ?: $partner['logo'];
                                if ($displayImg): ?>
                                    <img src="/assets/img/partners/<?= htmlspecialchars($displayImg) ?>"
                                        alt="<?= htmlspecialchars($partner['nome_azienda']) ?>">
                                <?php else: ?>
                                    <div class="no-logo-placeholder"><i class="fas fa-hand-holding-heart"></i></div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body-new">
                                <h4><?= htmlspecialchars($partner['nome_azienda']) ?></h4>
                                <?php if ($partner['descrizione_breve']): ?>
                                    <p class="partner-intro"><?= htmlspecialchars($partner['descrizione_breve']) ?></p>
                                <?php endif; ?>

                                <div class="card-actions-new">
                                    <?php if ($partner['telefono']): ?>
                                        <a href="tel:<?= $partner['telefono'] ?>" class="partner-link call"><i
                                                class="fas fa-phone-alt"></i> Chiama</a>
                                    <?php endif; ?>
                                    <?php if ($partner['website_url']): ?>
                                        <a href="<?= htmlspecialchars($partner['website_url']) ?>" target="_blank"
                                            class="partner-link site"><i class="fas fa-globe"></i> Sito</a>
                                    <?php endif; ?>
                                    <?php if ($partner['google_maps_url']): ?>
                                        <a href="<?= htmlspecialchars($partner['google_maps_url']) ?>" target="_blank"
                                            class="partner-link maps" title="Vedi Recensioni">
                                            <i class="fas fa-star" style="color: #fbbf24;"></i> Recensioni
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Light Theme Premium Banner Redesign -->
            <div class="premium-invite-banner-new light-theme">
                <div class="banner-content-info">
                    <div class="banner-icon"><i class="fas fa-certificate"></i></div>
                    <div class="banner-text">
                        <h3>Fai crescere la tua Clinica</h3>
                        <p>Diventa un partner certificato e posizionati davanti a migliaia di utenti che cercano
                            specialisti nella tua zona.</p>
                    </div>
                </div>
                <div class="banner-cta">
                    <a href="/pubblicita" class="btn-premium-action-light">
                        Ottieni Visibilità Premium <i class="fas fa-rocket"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .recommended-professionals {
        padding: 80px 0;
        background: #fbfcfe;
        position: relative;
        overflow: hidden;
    }

    .section-overlay-text {
        position: absolute;
        top: 5px;
        left: 50%;
        transform: translateX(-50%);
        font-size: clamp(60px, 15vw, 150px);
        font-weight: 900;
        color: rgba(37, 99, 235, 0.03);
        z-index: 0;
        letter-spacing: 20px;
        pointer-events: none;
        white-space: nowrap;
    }

    .recommended-professionals .container {
        position: relative;
        z-index: 1;
    }

    .premium-title {
        font-size: 2.8rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 12px;
        text-align: center;
        letter-spacing: -0.5px;
    }

    .premium-subtitle {
        font-size: 1.2rem;
        color: #64748b;
        margin-bottom: 60px;
        text-align: center;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .partners-grid-premium {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-bottom: 60px;
    }

    .partner-card-new {
        background: #ffffff;
        border-radius: 24px;
        padding: 35px 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
    }

    .partner-card-new:hover {
        transform: translateY(-15px);
        box-shadow: 0 25px 50px -12px rgba(37, 99, 235, 0.15);
        border-color: #2563eb;
    }

    .card-accent {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: #2563eb;
        border-radius: 24px 24px 0 0;
        opacity: 0.8;
    }

    .card-logo-box {
        width: 110px;
        height: 110px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        border-radius: 50%;
        padding: 15px;
        border: 1px solid #f1f5f9;
    }

    .card-logo-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .no-logo-placeholder {
        color: #2563eb;
        font-size: 2.5rem;
        opacity: 0.2;
    }

    .partner-type-tag {
        font-size: 0.7rem;
        font-weight: 800;
        color: #2563eb;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 12px;
        display: block;
        background: #eff6ff;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .partner-card-new h4 {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        line-height: 1.3;
    }

    .partner-intro {
        font-size: 0.9rem;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 25px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-actions-new {
        display: flex;
        gap: 8px;
        width: 100%;
        flex-wrap: wrap;
    }

    .partner-link {
        flex: 1;
        min-width: 80px;
        padding: 10px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .partner-link.call {
        background: #2563eb;
        color: white;
        border: none;
    }

    .partner-link.site {
        background: #ffffff;
        color: #475569;
        border: 2px solid #e2e8f0;
    }

    .partner-link.maps {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .partner-link:hover {
        opacity: 0.9;
        transform: scale(1.02);
    }

    .premium-invite-banner-new.light-theme {
        background: #ffffff;
        border-radius: 30px;
        padding: 50px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 40px;
        color: #1e293b;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.08);
        border: 2px dashed #e2e8f0;
        position: relative;
    }

    .banner-content-info {
        display: flex;
        align-items: center;
        gap: 30px;
        flex: 1;
    }

    .banner-icon {
        width: 70px;
        height: 70px;
        background: #eff6ff;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #2563eb;
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.1);
    }

    .banner-text h3 {
        font-size: 1.8rem;
        font-weight: 800;
        margin: 0 0 8px 0;
        color: #0f172a;
    }

    .banner-text p {
        margin: 0;
        color: #64748b;
        line-height: 1.6;
        font-size: 1.1rem;
    }

    .btn-premium-action-light {
        background: #2563eb;
        color: white;
        padding: 18px 36px;
        border-radius: 16px;
        text-decoration: none;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s;
        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4);
        font-size: 1.1rem;
    }

    .btn-premium-action-light:hover {
        background: #1d4ed8;
        transform: translateY(-5px);
        box-shadow: 0 15px 30px -5px rgba(37, 99, 235, 0.5);
    }

    @media (max-width: 991px) {
        .premium-invite-banner-new.light-theme {
            flex-direction: column;
            text-align: center;
            padding: 40px 30px;
        }

        .banner-content-info {
            flex-direction: column;
        }

        .btn-premium-action-light {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Google Maps & Places Results -->
<?php if (!empty(GOOGLE_MAPS_API_KEY)): ?>
    <section class="map-section">
        <div class="container">
            <div class="section-header text-center">
                <h2>Veterinari e Cliniche Disponibili per <?= htmlspecialchars($servizio['nome']) ?>
                    <?= htmlspecialchars($location['nome']) ?>
                </h2>
                <p>Ecco i professionisti più vicini a <?= htmlspecialchars($location['nome']) ?></p>
            </div>

            <div class="map-container-wrapper">
                <div id="map" data-query="<?= htmlspecialchars($servizio['nome']) ?>"
                    data-location="<?= htmlspecialchars($location['nome']) ?>">
                    <div class="map-loading"
                        style="display: flex; align-items: center; justify-content: center; height: 100%; background: #eee;">
                        <span>Caricamento mappa e risultati...</span>
                    </div>
                </div>

                <div class="places-list-container">
                    <div id="places-list">
                        <!-- Populated by maps.js -->
                        <div class="text-muted text-center p-4">Ricerca cliniche in corso...</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Google Maps JS SDK -->
    <script src="/assets/js/maps.js?v=<?= time() ?>"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY ?>& callback=initMap & libraries=places & v=beta & loading=async"
            async defe r></script>
<?php endif; ?>

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